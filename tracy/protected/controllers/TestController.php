<?php

class TestController extends Controller
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

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
    
    function actionAddChild($parent)
    {
		$model=new Test;
        $special = new ValidationTest;
        $special->parent = $parent;

		if(isset($_POST['Test']) && isset($_POST['test_type']) )
		{
			$model->attributes=$_POST['Test'];
            $special->attributes = $_POST['ValidationTest'];
            
            $special->id_test = 0;
            if ( $special->validate() )
            {
                if($model->save())
                {
                    $special->id_test = $model->id_test;
                    $special->save(false);
                    $this->redirect(array('view','id'=>$model->id_test));
                }
            }
		}
        

		$this->render('create',array(
			'model'=>$model,
            'special'=>$special,
		));
    }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Test;
        $special = null;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Test']) && isset($_POST['test_type']) )
		{
			$model->attributes=$_POST['Test'];
            
            switch ( $_POST['test_type'] )
            {
                case 'System':
                    $special = new SystemTest;
                    $special->attributes = $_POST['SystemTest'];
                    break;
                case 'Unit':
                    $special = new UnitTest;
                    $special->attributes = $_POST['UnitTest'];
                    break;
                case 'Integration':
                    $special = new IntegrationTest;
                    $special->attributes = $_POST['IntegrationTest'];
                    break;
                case 'Validation':
                    $special = new ValidationTest;
                    $special->attributes = $_POST['Vali tionTest'];
                    break;
            }
            
            if ( $special != null )
            {
                $special->id_test = 0;
                if ( $special->validate() )
                {
                    if($model->save())
                    {
                        $special->id_test = $model->id_test;
                        $special->save(false);
                        $this->redirect(array('view','id'=>$model->id_test));
                    }
                }
            }
		}
        

		$this->render('create',array(
			'model'=>$model,
            'special'=>$special,
		));
	}
    
    

    
    function actionUnitMethod($method)
    {
        $meth = Method::model()->findByPk($method);
        if ( $meth == null )
            throw new CHttpException(404,'Method not found :-(');
        $t = UnitTest::model()->findByAttributes(array('id_method'=>$method));
        if ( $t != null )
            $this->redirect(array('view','id'=>$t->id_test) );
        else
        {
            $model=new Test;
            $special = new UnitTest;
            $special->id_method = $id_method;
    
            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);
    
            if(isset($_POST['Test']) )
            {
                $model->attributes=$_POST['Test'];
                
                $special->id_test = 0;
                if($model->save())
                {
                    $special->id_test = $model->id_test;
                    $special->save(false);
                    $this->redirect(array('view','id'=>$model->id_test));
                }
            }
            
    
            $this->render('create',array(
                'model'=>$model,
                'special'=>$special,
            ));
        }
    }

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

        $specific = $model->test_type()."Test";
        if ( isset($_POST[$specific]) )
        {
            $model->specific()->attributes=$_POST[$specific];
            if(isset($_POST['Test']))
            {
                $model->attributes=$_POST['Test'];
                if($model->save() && $model->specific()->save())
                    $this->redirect(array('view','id'=>$model->id_test));
            }
        }

		$this->render('update',array(
			'model'=>$model,
            'special'=>$model->specific()
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$mod = $this->loadModel($id);
        $mod->specific()->delete();
        $mod->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Test('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Test']))
			$model->attributes=$_GET['Test'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Test::model()->with('integration','system', 'unit','validation')
                                ->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
        
        /*if ( $model->integration != null )
            $model = $model->integration;
        else if ( $model->system != null )
            $model = $model->system;
        else if ( $model->unit != null )
            $model = $model->unit;
        else if ( $model->validation != null )
            $model = $model->validation;
        else
			throw new CHttpException(404,'The requested page does not exist.');*/
        
        
		return $model;
	}


	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='test-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    
    function actionSystem()
    {
        $this->render('list_type',array('type'=>'System'));
    }
    function actionUnit()
    {
        $this->render('list_type',array('type'=>'Unit'));
    }
    function actionValidation()
    {
        $this->render('list_type',array('type'=>'Validation'));
    }
    function actionIntegration()
    {
        $this->render('list_type',array('type'=>'Integration'));
    }
    
    
}
