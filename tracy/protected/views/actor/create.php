<?php
/* @var $this ActorController */
/* @var $model Actor */

$this->breadcrumbs=array(
	'Actors'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Actor', 'url'=>array('index')),
	array('label'=>'Manage Actor', 'url'=>array('admin')),
);
?>

<h1>Create Actor</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>