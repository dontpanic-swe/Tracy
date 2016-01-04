<?php

class RequirementController extends Controller
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

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
        $model = $this->loadModel($id);
        
        if ( isset($_REQUEST['add_package']) )
        {
            $class = Package::model()->findByPk($_REQUEST['add_package']);
            if ( $class != null )
            {
                $nm = new ClassRequirement;
                $nm->id_requirement = $model->id_requirement;
                $nm->id_package = $class->id_package;
                $nm->save(false);
            }
        }
        
        if ( isset($_REQUEST['add_class']) )
        {
            $class = Class_Prog::model()->findByPk($_REQUEST['add_class']);
            if ( $class != null )
            {
                $nm = new ClassRequirementReal;
                $nm->id_requirement = $model->id_requirement;
                $nm->id_class = $class->id_class;
                $nm->save(false);
            }
        }
        
		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Requirement;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Requirement']))
		{
			$model->attributes=$_POST['Requirement'];
            
			if($model->save())
            {
                $model->save_sources();
				$this->redirect(array('view','id'=>$model->id_requirement));
            }
		}

		$this->render('create',array(
			'model'=>$model,
		));
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

		if(isset($_POST['Requirement']))
		{
			$model->attributes=$_POST['Requirement'];
			if($model->save())
            {
                $model->save_sources();
				$this->redirect(array('view','id'=>$model->id_requirement));
            }
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$m = $this->loadModel($id);
        if ( isset($m->sources) )
        {
            foreach($m->sources as $s)
            {
                $s->delete();
            }
        }
        
        $m->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Requirement('search');
        $model->with('category0','priority0','parent0');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Requirement']))
			$model->attributes=$_GET['Requirement'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
    
    function actionCategories()
    {
        $model=new RequirementCategory;
        
        if ( isset($_GET['edit']) )
        {
            $model_edit = RequirementCategory::model()->findByPk($_GET['edit']);
            if($model_edit!==null)
                $model = $model_edit;
                
        }
        
        if(isset($_POST['RequirementCategory']))
		{
			$model->attributes=$_POST['RequirementCategory'];
            $model->save();
		}
        
        
        $this->render('categories',array('model'=>$model));
        
    }
    
    function actionPriorities()
    {
        $model=new RequirementPriority;
        
        if ( isset($_GET['edit']) )
        {
            $model_edit = RequirementPriority::model()->findByPk($_GET['edit']);
            if($model_edit!==null)
                $model = $model_edit;
                
        }
        
        if(isset($_POST['RequirementPriority']))
		{
			$model->attributes=$_POST['RequirementPriority'];
            $model->save();
		}
        
        
        $this->render('priorities',array('model'=>$model));
        
    }
    
    function actionValidations()
    {
        $model=new RequirementValidation;
        
        if ( isset($_GET['edit']) )
        {
            $model_edit = RequirementValidation::model()->findByPk($_GET['edit']);
            if($model_edit!==null)
                $model = $model_edit;
                
        }
        
        if(isset($_POST['RequirementValidation']))
		{
			$model->attributes=$_POST['RequirementValidation'];
            $model->save();
		}
        
        
        $this->render('validations',array('model'=>$model));
        
    }
    

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Requirement::model()->findByPk($id);
        $model->with('category0','priority0','parent0','sources',
                     'validation0','system_test');
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='requirement-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    
    
    function actionParentCompletion($term)
    {
        /*$matches = Requirement::model()->findAll(
            'description like :txt',
            array(':txt'=>"%$term%")
        );*/
        
        $sql_command = Yii::app()->db->createCommand()
            ->select('description as value, id_requirement as id' )
            ->from ( 'requirement' )
            ->where ( 'description like :txt', 
                        array('txt'=>"%$term%") )
            ->order ( 'description' )
            ->limit ( 10 );
        $matches = $sql_command->queryAll();
        
        echo json_encode ( $matches );
    }
    
    
    function actionSourceCompletion($term)
    {
        $sql_command = Yii::app()->db->createCommand()
            ->select('s.id_source as id,
                     if(uc.title is null, xs.description,uc.title) as value' )
            ->from ( 'source s' )
            ->leftJoin ( 'external_source xs', 'xs.id_source=s.id_source' )
            ->leftJoin ( 'use_case uc', 'uc.id_use_case=s.id_source' )
            ->where ( 'xs.description like :txt or uc.title like :txt', 
                        array('txt'=>"%$term%") )
            ->order ( 'value' )
            ->limit ( 10 );
        $matches = $sql_command->queryAll();
        
        echo json_encode ( $matches );
    }
    
    function actionRemove_tracking($requirement,$package)
    {
        ClassRequirement::model()->findByAttributes(array(
                                'id_requirement'=>$requirement,
                                'id_package' => $package
                            )
                        )->delete();
    }
    
    function actionRemove_tracking_class($requirement,$class)
    {
        ClassRequirementReal::model()->findByAttributes(array(
                                'id_requirement'=>$requirement,
                                'id_class' => $class
                            )
                        )->delete();
    }
}
