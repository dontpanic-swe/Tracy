<?php
/* @var $this RequirementController */
/* @var $model Requirement */

$this->breadcrumbs=array(
	'Requirements'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Requirement', 'url'=>array('admin')),
	array('label'=>'Manage Sources', 'url'=>array('source/index')),
	array('label'=>'Manage Categories', 'url'=>array('requirement/categories')),
	array('label'=>'Manage Priorities', 'url'=>array('requirement/priorities')),
	array('label'=>'Manage Validation Methods', 'url'=>array('requirement/validations')),
);
?>

<h1>Create Requirement</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>