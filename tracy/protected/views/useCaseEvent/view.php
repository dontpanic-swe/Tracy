<?php
/* @var $this UseCaseEventController */
/* @var $model UseCaseEvent */

$this->breadcrumbs=array(
	'Use Case Events'=>array('index'),
	$model->id_event,
);

$this->menu=array(
	array('label'=>'List UseCaseEvent', 'url'=>array('index')),
	array('label'=>'Create UseCaseEvent', 'url'=>array('create')),
	array('label'=>'Update UseCaseEvent', 'url'=>array('update', 'id'=>$model->id_event)),
	array('label'=>'Delete UseCaseEvent', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_event),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UseCaseEvent', 'url'=>array('admin')),
);
?>

<h1>View UseCaseEvent #<?php echo $model->id_event; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_event',
		'category',
		'use_case',
		'description',
		'refers_to',
		'primary_actor',
		'order',
	),
)); ?>
