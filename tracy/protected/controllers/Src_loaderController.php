<?php

class Src_loaderController extends Controller
{
    
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform all actions
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
    
    public function actionIndex()
    {
        $this->render('index');
    }
    
    public function actionView()
    {
        if ( isset($_POST['code']) )
        {
            Yii::app()->session['code'] = $_POST['code'];
        }
        
        $this->render('view',array('code'=>Yii::app()->session['code']));
    }
    
    public function actionDiff($id,$mode)
    {
        if ( !isset(Yii::app()->session['code']) )
            $this->redirect(array('index'));
        
        $class = Class_Prog::model()->findByPk($id);
        if ( $class == null )
            throw new CHttpException(404,'Unable to find the requested class.');
        
        $original = $this->renderPartial('/generator/cpp_class_decl',
                                         array('raw'=>1,'class'=>$class),
                                         true
                                    );
        $new = Yii::app()->session['code'];
        $diff=TextDiff::compare($original,$new,$mode);


        $this->render('diff',array(
            'class'=>$class,
            'diff'=>$diff,
            'new'=>$new,
            'mode'=>$mode,
        ));
    }
    
    public function actionScan()
    {
        if ( !isset(Yii::app()->session['code']) )
            $this->redirect(array('index'));
            
        $loader = new CppLoader(Yii::app()->session['code']);
        $loader->scan();
        Yii::app()->session['namespace'] = $loader->namespace;
        Yii::app()->session['class'] = $loader->class;
        Yii::app()->session['attributes'] = $loader->attributes;
        Yii::app()->session['methods'] = $loader->methods;
        $this->render('scan_preview',array('loader'=>$loader));
    }
    
    /// shows namespaces, creates new ones
    function actionUpdate_namespace()
    {
        if ( !isset(Yii::app()->session['namespace']) )
            $this->redirect(array('index'));
            
        $ns = Yii::app()->session['namespace'];
        if ( count($ns) == 0 )
            $this->redirect(array('update_bind'));
        

        if(isset($_POST['Package']))
        {
            $model=new Package;
            $model->attributes=$_POST['Package'];
            if($model->save())
            {
                foreach ( $ns as &$namespace )
                    if ( !isset($namespace->parent) )
                    {
                        $namespace->parent = $model->id_package;
                        break;
                    }
                Yii::app()->session['namespace'] = $ns;
            }
        }
    
        $this->render('upate/namespace');
    }
    
    /// Ask whether the class should be updated or autodetect from namespaces
    function actionUpdate_bind()
    {
        if ( !isset(Yii::app()->session['class']) )
            $this->redirect(array('index'));
        
        $class = Yii::app()->session['class'];
        if ( isset($_REQUEST['bind_class']) )
        {
            $oldclass = Class_Prog::findByPk($_REQUEST['bind_class']);
            if ( $oldclass != null )
            {
                $class->id_class = $oldclass->id_class;
                $class->isNewRecord = false;
                Yii::app()->session['class'] = $class;
            }
        }
        
        
        if ( $class->isNewRecord )
            $this->render('upate/bind');
        else
            $this->redirect(array('update_class'));
    }
    
    function actionUpdate_class()
    {
        if ( !isset(Yii::app()->session['class']) )
            $this->redirect(array('index'));
            
        $ns = Yii::app()->session['namespace'];
        $class = Yii::app()->session['class'];
        if ( count($ns) > 0 )
        {
            $last_ns = $ns[count($ns)-1];
            $class->id_package = $last_ns->id_package;
            Yii::app()->session['class'] = $class;
        }
        
        if(isset($_POST['Class_Prog']))
        {
            $class->attributes=$_POST['Class_Prog'];
            if($class->save())
            {
                Yii::app()->session['class'] = $class;
                $this->redirect(array('update_members'));
            }
        }
        
        $this->render('upate/classs');
    }
    
    /// shows attribute/methods to be created + gridview of those that can be removed
    function actionUpdate_members()
    {
        if ( !isset(Yii::app()->session['attributes']) ||
             !isset(Yii::app()->session['methods'])    ||
             !isset(Yii::app()->session['class'])       )
            $this->redirect(array('index'));
        if ( Yii::app()->session['class']->isNewRecord )
            $this->redirect(array('update_class'));
        $this->render('upate/members');
    }
    
    function actionUpdate_complete()
    {
        $id = Yii::app()->session['class']->id_class;
        Yii::app()->session->clear();
        $this->redirect(array('class/view','id'=>$id));
    }
    
}