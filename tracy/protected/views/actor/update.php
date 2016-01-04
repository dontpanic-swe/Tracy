<?php
/* @var $this ActorController */
/* @var $model Actor */

$this->breadcrumbs=array(
	'Actors'=>array('index'),
	$model->id_actor=>array('view','id'=>$model->id_actor),
	'Update',
);

$this->menu=array(
	array('label'=>'List Actor', 'url'=>array('index')),
	array('label'=>'Create Actor', 'url'=>array('create')),
	array('label'=>'View Actor', 'url'=>array('view', 'id'=>$model->id_actor)),
	array('label'=>'Manage Actor', 'url'=>array('admin')),
);
?>

<h1>Update Actor <?php echo $model->id_actor; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>