<?php

class UseCaseController extends Controller
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
//     public function actionCreate()
//     {
//         $model=new UseCase;
// 
//         // Uncomment the following line if AJAX validation is needed
//         // $this->performAjaxValidation($model);
// 
//         if(isset($_POST['UseCase']))
//         {
//         //Creates new Source, gets generated ID
// 	    $source=new Source;
// 	    $source->save();
// 
// 	    //Gives the new external source the generated ID
//             $model->attributes=$_POST['UseCase'];            
//             $model->id_use_case = $source->id_source;
// 
// 	    if($model->save()) {
// 	      //Then save related events
// 	      foreach ($_POST['baseEvent'] as $numEvent => $category) {
// 		foreach ($_POST['event'][$numEvent] as $actionNum => $rem) {
// 		  $event = new UseCaseEvent;
// 		  $event->category = $category;
// 		  $event->use_case = $model->id_use_case;
// 		  $event->description = $rem['description'];
// 		  $event->refers_to = $rem['refers_to'];
// 		  $event->primary_actor = $rem['actor'];
// 		  $event->order = $rem['order'];
// 		  $event->save();
// 		}
// 	      }
// 	      //And redirect the browser
//               $this->redirect(array('view','id'=>$model->id_use_case));
// 	    }
//             //if saving was not successful delete the created source
//             else $source->delete();
//         }
// 
//         $this->render('create',array(
//             'model'=>$model,
//         ));
//     }
    
    public function actionCreate()
    {
        $model=new UseCase;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['UseCase']))
        {
         //Creates new Source, gets generated ID
	    $source=new Source;
	    $source->save();

	    //Gives the new external source the generated ID
            $model->attributes=$_POST['UseCase'];            
            $model->id_use_case = $source->id_source;
            
            if($model->save())
                $this->redirect(array('view','id'=>$model->id_use_case));
            else $source->delete();
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

        if(isset($_POST['UseCase']))
        {
            $model->attributes=$_POST['UseCase'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id_use_case));
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
        //Should delete related source?
        //Source::model()->findByPk($id)->delete();
	
	//Surely should delete related event flow
	$criteria = new CDbCriteria();
	$criteria->condition = "use_case = $id";
	$events = UseCaseEvent::model()->findAll($criteria);
	foreach ($events as $obj) {
	  $obj->delete();
	}

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model=new UseCase('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['UseCase']))
            $model->attributes=$_GET['UseCase'];

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
        $model=UseCase::model()->findByPk($id);
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
        if(isset($_POST['ajax']) && $_POST['ajax']==='use-case-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
    /**
     * Cerca parent in
     * Restituisce i match trovati
     * @param string quanto inserito dall'utente
     */    
    function actionParentCompletion($term)
    {
        
        $sql_command = Yii::app()->db->createCommand()
            ->select('title as value, id_use_case as id' )
            ->from ( 'use_case' )
            ->where ( 'description like :txt or title like :txt', 
                        array('txt'=>"%$term%") )
            ->order ( 'description' )
            ->limit ( 10 );
        $matches = $sql_command->queryAll();
        
        echo json_encode ( $matches );
    }
    
    /*function actionLatex()
    {
      $this->render('latex');
    }*/
 
  function findRootUCs()
      {
      $criteria = new CDbCriteria();
	$criteria->condition = "parent IS NULL";
	$roots = UseCase::model()->findAll($criteria);
      return $roots;
      }
      
  function isParent($model)
      {
	$sql_command = Yii::app()->db->createCommand()
	      ->select('count(*)' )
	      ->from ( 'use_case' )
	      ->where ("parent = :p", array('p'=>$model->id_use_case));
	  $num = $sql_command->queryScalar();
	  return $num;
      }
   function getParent($model)
      {
	return UseCase::model()->findByPk($model->parent);
      }
	
  function getChildren($model)
      {
	$criteria = new CDbCriteria();
	$criteria->condition = "parent = {$model->id_use_case}";
	$children = UseCase::model()->findAll($criteria);
      return $children;
      }
  function generateNumber($model)
      {
	$parent = $this->getParent($model);
	if ($parent) {
	  $num = $this->generateNumber($parent);
	  $count = 0;
	  foreach($this->getChildren($parent) as $tch) {
	    $count++;
	    if ($tch->id_use_case == $model->id_use_case) break;
	  }
	  return $num.".".$count;
	}
	else {
	$count = 0;
	  foreach ($this->findRootUCs() as $trt) {
	    $count++;
	    if ($trt->id_use_case == $model->id_use_case) break;
	  }
	return $count;
	}
      }
  function getActors($model)
      {
	$actors = array();
	$string = '';
	foreach(UseCaseEvent::model()->findAll("use_case = {$model->id_use_case}") as $events) {
	    $actor = $events->primary_actor;
	    if (!isset($actors[$actor])) $actors[$actor] = 0;
	    else $actors[$actor]++;
	}
	foreach($actors as $idA => $t) {
	  $string .= Actor::model()->findByPk($idA)->description.", ";
	}
	return ($string != '') ? substr($string, 0, -2): $string;
      }
  function hasAlternate($model)
      {
	$sql_command = Yii::app()->db->createCommand()
	      ->select('count(*)' )
	      ->from ( 'use_case_event' )
	      ->where ("use_case = {$model->id_use_case} AND category = 2");
	  $num = $sql_command->queryScalar();
	  return $num;
      }
      
      
    function actionImage($id)
    {
        $this->render('image',array('id'=>$id));
    }
    
    function actionImageDot($id)
    {
        header('Content-Type: text/plain; charset=utf-8');
        $this->renderPartial('dot',array('id'=>$id));
    }
    
    function actionImageSvg($id)
    {
        header('Content-type: image/svg+xml; charset=utf-8');
        $this->renderPartial('svg',array('id'=>$id));
    }


} 
