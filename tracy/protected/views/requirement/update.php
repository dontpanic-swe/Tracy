<?php
/* @var $this RequirementController */
/* @var $model Requirement */

$this->breadcrumbs=array(
	'Requirements'=>array('index'),
	$model->id_requirement=>array('view','id'=>$model->id_requirement),
	'Update',
);

$this->menu=array(
	array('label'=>'List Requirement', 'url'=>array('index')),
	array('label'=>'Manage Sources', 'url'=>array('source/index')),
	array('label'=>'Create Requirement', 'url'=>array('create')),
	array('label'=>'View Requirement', 'url'=>array('view', 'id'=>$model->id_requirement)),
	array('label'=>'Manage Categories', 'url'=>array('requirement/categories')),
	array('label'=>'Manage Priorities', 'url'=>array('requirement/priorities')),
	array('label'=>'Manage Validation Methods', 'url'=>array('requirement/validations')),
);
?>

<h1>Update Requirement <?php echo $model->id_requirement; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>