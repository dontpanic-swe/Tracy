<?php
/* @var $this ActorController */
/* @var $model Actor */

$this->breadcrumbs=array(
	'Actors'=>array('index'),
	$model->id_actor,
);

$this->menu=array(
	array('label'=>'List Actor', 'url'=>array('index')),
	array('label'=>'Create Actor', 'url'=>array('create')),
	array('label'=>'Update Actor', 'url'=>array('update', 'id'=>$model->id_actor)),
	array('label'=>'Delete Actor', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_actor),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Actor', 'url'=>array('admin')),
);
?>

<h1>View Actor #<?php echo $model->id_actor; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_actor',
		'description',
		'parent',
	),
)); ?>
