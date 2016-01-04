<?php

class PackageController extends Controller
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

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($parent=null)
    {
        $model=new Package;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        
        if ( isset($parent) )
        {
            $model->parent = $parent;
        }

        if(isset($_POST['Package']))
        {
            $model->attributes=$_POST['Package'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id_package));
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

        if(isset($_POST['Package']))
        {
            $model->attributes=$_POST['Package'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id_package));
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
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model=new Package('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Package']))
            $model->attributes=$_GET['Package'];

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
        $model=Package::model()->findByPk($id);
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
        if(isset($_POST['ajax']) && $_POST['ajax']==='package-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
    
    function actionParentCompletion($term)
    {
        $limit = 10;
        $sql_command = Yii::app()->db->createCommand()
            ->select('name as value, id_package as id' )
            ->from ( 'package' )
            ->where ( 'name like :txt', 
                        array('txt'=>"%$term%") )
            ->order ( 'name' )
            ->limit ( $limit );
        $matches = $sql_command->queryAll();
        
        if ( count($matches) == 1 )
        {
            $p = Package::model()->findByPk($matches[0]['id']);
            $p->with('packages');
            if ( isset($p->packages) && is_array($p->packages) )
            {
                foreach($p->packages as $c )
                {
                    if ( count($matches) >= $limit )
                        break;
                    array_push($matches,array('value'=>$p->name."::".$c->name,
                                              'id'=>$c->id_package) );
                }
            }
        }
        
        echo json_encode ( $matches );
    }
}