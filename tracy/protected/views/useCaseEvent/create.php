<?php
/* @var $this UseCaseEventController */
/* @var $model UseCaseEvent */

$this->breadcrumbs=array(
	'Use Case Events'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'View and Manage Related Use Case', 'url'=>array("useCase/view/{$model->use_case}")),
);
?>

<h1>Create UseCaseEvent</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>