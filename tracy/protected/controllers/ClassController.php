<?php

class ClassController extends Controller
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
        if ( !is_numeric($id) )
        {
            $classes = Class_Prog::model()->findAllByAttributes(array('name'=>$id));
            if ( count($classes) == 0 )
                throw new CHttpException(404,'The requested page does not exist.');
            else if ( count($classes) == 1 )
                $id = $classes[0]->id_class;
            else
            {
                $this->render('disambiguate',array('name'=>$id,'classes'=>$classes));
                return;
            }
        }
        
        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($package=null)
    {
        $model=new Class_Prog;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if ( $package != null )
            $model->id_package = $package;

        if(isset($_POST['Class_Prog']))
        {
            $model->attributes=$_POST['Class_Prog'];
            //echo "~"; print_r($model->attributes); die;
            if($model->save())
                $this->redirect(array('view','id'=>$model->id_class));
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

        if(isset($_POST['Class_Prog']))
        {
            $model->attributes=$_POST['Class_Prog'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id_class));
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
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model=new Class_Prog('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Class_Prog']))
            $model->attributes=$_GET['Class_Prog'];

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
        $model=Class_Prog::model()->findByPk($id);
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
        if(isset($_POST['ajax']) && $_POST['ajax']==='class--prog-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

/////////// ATTRIBUTE ////////////////
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadAttribute($id)
    {
        $model=Attribute::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
    
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionAttributeView($id)
    {
        $model = $this->loadAttribute($id)->with('class');
        $this->render('attribute/view',array(
            'model'=>$model,
            'class'=>$model->class,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionAttributeCreate($class)
    {
        $model=new Attribute;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        
        $class = $this->loadModel($class);
        $model->id_class = $class->id_class;

        if(isset($_POST['Attribute']))
        {
            $model->attributes=$_POST['Attribute'];
            if($model->save())
                $this->redirect(array('attributeview','id'=>$model->id_attribute));
        }

        $this->render('attribute/create',array(
            'model'=>$model,
            'class'=>$class,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionAttributeUpdate($id)
    {
        $model=$this->loadAttribute($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Attribute']))
        {
            $model->attributes=$_POST['Attribute'];
            if($model->save())
                $this->redirect(array('attributeview','id'=>$model->id_attribute));
        }
        
        $model->with('class');

        $this->render('attribute/update',array(
            'model'=>$model,
            'class' => $model->class
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionAttributeDelete($id)
    {
        $model = $this->loadAttribute($id);
        
        $class = $model->id_class;
        
        $model->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] :
                            array('class/view','id'=>$class));
    }
    
/////////// ASSOCIATION ////////////////
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadAssoc($id)
    {
        $model=Association::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
    
    function actionAssociations()
    {
        $this->render('assoc');
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionAssocCreate($attribute)
    {
        $model=new Association;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        
        $attribute = $this->loadAttribute($attribute);
        $model->id_attribute = $attribute->id_attribute;
        $model->class_from = $attribute->id_class;

        if(isset($_POST['Association']))
        {
            $model->attributes=$_POST['Association'];
            if($model->save())
                $this->redirect(array('attributeview','id'=>$attribute->id_attribute));
        }

        $this->render('attribute/association/create',array(
            'model'=>$model,
            'attribute'=>$attribute,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionAssocUpdate($id)
    {
        $model=$this->loadAssoc($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Association']))
        {
            $model->attributes=$_POST['Association'];
            if($model->save())
                $this->redirect(array('attributeview','id'=>$model->id_attribute));
        }
        
        $model->with('attribute');

        $this->render('attribute/association/update',array(
            'model'=>$model,
            'attribute' => $model->attribute
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionAssocDelete($id)
    {
        $model = $this->loadAssoc($id);
        
        $attribute = $model->id_attribute;
        
        $model->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] :
                            array('attributeview','id'=>$attribute));
    }
    

/////////// METHOD ////////////////
    
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadMethod($id)
    {
        $model=Method::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
    
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionMethodView($id)
    {
        $model = $this->loadMethod($id)->with('class');
        $this->render('method/view',array(
            'model'=>$model,
            'class'=>$model->class,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionMethodCreate($class)
    {
        $model=new Method;
        

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        
        $class = $this->loadModel($class);
        $model->id_class = $class->id_class;
        

        if(isset($_POST['Method']))
        {
            $model->attributes=$_POST['Method'];
            if($model->save())
                $this->redirect(array('methodview','id'=>$model->id_method));
        }

        $this->render('method/create',array(
            'model'=>$model,
            'class'=>$class,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionMethodUpdate($id)
    {
        $model=$this->loadMethod($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Method']))
        {
            $model->attributes=$_POST['Method'];
            if($model->save())
                $this->redirect(array('methodview','id'=>$model->id_method));
        }
        
        $model->with('class');

        $this->render('method/update',array(
            'model'=>$model,
            'class' => $model->class
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionMethodDelete($id)
    {
        $model = $this->loadMethod($id);
        
        $class = $model->id_class;
        
        $model->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] :
                            array('class/view','id'=>$class));
    }
    
    public function actionMethodComplete($term,$filter="")
    {
        $extrawhere = '';
        if ( $filter == 'signal' )
            $extrawhere = "and t.access = 'signal'";
            
            
        $all = Method::model()->findAll(array(
            'select'=>'id_method, t.id_class, t.name',
            'condition'=>"concat( c.name, '::', t.name ) like :txt $extrawhere",
            'order'=>'c.name, t.name',
            'join' => 'join class c using(id_class)',
            'limit' => 10,
            'params' => array('txt'=>"%$term%")
        ));
        $matches = array();
        foreach($all as $m)
        {
            $matches []= array('value'=>$m->signature_name(),'id'=>$m->id_method);
        }
        
       /* $sql_command = Yii::app()->db->createCommand()
            ->select("concat( c.name, '::', m.name ) value, id_method as id" )
            ->from ( 'method m' )
            ->join ( 'class c','c.id_class = m.id_class' )
            ->where ( " concat( c.name, '::', m.name ) like :txt $extrawhere",
                        array('txt'=>"%$term%") )
            ->order ( 'c.name, m.name' )
            ->limit ( 10 );
        $matches = $sql_command->queryAll();*/
       
        
        echo json_encode ( $matches );
    }
    
    function actionConnect($signal,$slot,$to)
    {
        $model = new Connect;
        $model->signal = $signal;
        $model->slot = $slot;
        $model->save();
        $this->redirect(array('methodView','id'=>$to));
    }
    
    function actionDisconnect($signal,$slot,$to)
    {
        $model =Connect::model()->findByAttributes(array(
                'signal'=>$signal, 'slot'=>$slot ));
        $model->delete();
        $this->redirect(array('methodView','id'=>$to));
    }
    
/////////// ARGUMENT ////////////////


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadArgument($id)
    {
        $model=Argument::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionArgumentCreate($method)
    {
        $model=new Argument;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        
        $method = $this->loadMethod($method);
        $model->id_method = $method->id_method;
        $class = $method->with('class')->class;
        

        if(isset($_POST['Argument']))
        {
            $model->attributes=$_POST['Argument'];
            if($model->save())
                $this->redirect(array('methodview','id'=>$method->id_method));
        }

        $this->render('method/argument/create',array(
            'model'=>$model,
            'method'=>$method,
            'class'=>$class,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionArgumentUpdate($id)
    {
        $model=$this->loadArgument($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Argument']))
        {
            $model->attributes=$_POST['Argument'];
            if($model->save())
                $this->redirect(array('methodview','id'=>$model->id_method));
        }
        
        $model->with('method');
        $model->method->with('class');

        $this->render('method/argument/update',array(
            'model'=>$model,
            'method'=>$model->method,
            'class' => $model->method->class
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionArgumentDelete($id)
    {
        $model = $this->loadArgument($id);
        
        $method = $model->id_method;
        
        $model->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] :
                            array('class/methodview','id'=>$method));
    }
    
/////////// RELATIVES ////////////////
    
    function actionParentCompletion($term)
    {        
        /*$sql_command = Yii::app()->db->createCommand()
            ->select('name as value, id_class as id' )
            ->from ( 'class' )
            ->where ( 'name like :txt', 
                        array('txt'=>"%$term%") )
            ->order ( 'name' )
            ->limit ( 10 );
        $matches = $sql_command->queryAll();*/
        
        $classes = Class_Prog::model()->findAll(array(
            'condition'=>'name like :txt',
            'order'=>'name',
            'limit' => 10,
            'params' => array('txt'=>"%$term%")
        ));
        
        $matches = array();
        foreach($classes as $c)
            $matches []= array(
                'value'=> $c->name ." (".$c->with('package')->package->full_name().")",
                'id' => $c->id_class
            );
        
        echo json_encode ( $matches );
    }
    
    function actionAddParent($id)
    {
        $inh = new Inherit;
        $inh->parent = $_REQUEST['parent'];
        $inh->child = $id;
        $inh->save();
        $this->redirect(array('view','id'=>$id));
    }
    
    function actionAddChild($id)
    {
        $inh = new Inherit;
        $inh->parent = $id;
        $inh->child = $_REQUEST['child'];
        $inh->save();
        $this->redirect(array('view','id'=>$id));
    }
    
    /// @param to id of destination view item
    function actionRemoveInheritance($parent,$child,$to)
    {
        $m = Inherit::model()->findByPk(array('parent'=>$parent,'child'=>$child));
        if ( $m != null )
            $m->delete();
        $this->redirect(array('view','id'=>$to)); 
    }
    
    function actionOverride($id_class,$id_method)
    {
        $meth = $this->loadMethod($id_method);
        $class = $this->loadModel($id_class);
        if ( !$meth->virtual && !$meth->override )
            throw new CHttpException(400,'Method is not virtual');
        
        $over = new Method();
        $over->attributes = $meth->attributes;
        $over->virtual = 0;
        $over->abstract = 0;
        $over->override = 1;
        $over->id_class = $class->id_class;
        $over->save(false);
        
        foreach($meth->arguments as $arg)
        {
            $n_arg = new Argument();
            $n_arg->attributes = $arg->attributes;
            $n_arg->id_method = $over->id_method;
            $n_arg->save(false);
        }
        
        $this->redirect(array('view','id'=>$id_class));
    }
}