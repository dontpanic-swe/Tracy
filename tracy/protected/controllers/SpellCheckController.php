<?php

class SpellCheckController  extends Controller
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
            'postOnly + delete', // we only allow deletion via POST request
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

    
    static function check($text)
    {
        return preg_split("/\\s+/", trim(
                    shell_exec('echo '.escapeshellarg($text).
                    " |  aspell -t -l it list | aspell -C -l en list ")
                ), NULL, PREG_SPLIT_NO_EMPTY );
    }
    
    function actionClass($id=null)
    {
        if ( $id == null )
            $this->render('classes',array(
                        'classes'=>Class_Prog::model()->findAll()
                    ));
        else
        {
            $c = Class_Prog::model()->findByPk($id);
            if ( $c == null )
                throw new CHttpException(404,'The requested page does not exist.');
            
            $this->render('class',array('class'=>$c));
        }
    }
    
    function actionIndex()
    {
        $this->render('index');
    }
    
    function actionMethod($id=null)
    {
        if ( $id == null )
            $this->render('methods',
                          array('methods'=>Method::model()->findAll()));
        else
        {
            $c = Method::model()->findByPk($id);
            if ( $c == null )
                throw new CHttpException(404,'The requested page does not exist.');
            
            $this->render('method',array('method'=>$c));
        }
    }
    
    
    function actionAttribute($id=null)
    {
        if ( $id == null )
            $this->render('attributes',
                          array('attributes'=>Attribute::model()->findAll()));
        else
        {
            $c = Attribute::model()->findByPk($id);
            if ( $c == null )
                throw new CHttpException(404,'The requested page does not exist.');
            
            $this->render('attribute',array('attribute'=>$c));
        }
    }
    
    
    function actionPackage($id=null)
    {
        if ( $id == null )
            $this->render('packages',
                          array('packages'=>Package::model()->findAll()));
        else
        {
            $c = Package::model()->findByPk($id);
            if ( $c == null )
                throw new CHttpException(404,'The requested page does not exist.');
            
            $this->render('package',array('package'=>$c));
        }
    }
    
    function error_list($errors,$model,$attribute)
    {
        if ( !empty($errors) )
        {
            echo "<h4>$attribute</h4>";
            echo '<ul>';
            foreach ( $errors as $err )
                echo "<li>$err</li>";
            echo '</ul>';
        }
    }
}