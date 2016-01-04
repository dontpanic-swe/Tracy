<?php
/* @var $this UseCaseEventController */
/* @var $model UseCaseEvent */

$this->breadcrumbs=array(
	'Use Case Events'=>array('index'),
	$model->id_event=>array('view','id'=>$model->id_event),
	'Update',
);

$this->menu=array(
	array('label'=>'List UseCaseEvent', 'url'=>array('index')),
	array('label'=>'Create UseCaseEvent', 'url'=>array('create')),
	array('label'=>'View UseCaseEvent', 'url'=>array('view', 'id'=>$model->id_event)),
	array('label'=>'Manage UseCaseEvent', 'url'=>array('admin')),
);
?>

<h1>Update UseCaseEvent <?php echo $model->id_event; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>