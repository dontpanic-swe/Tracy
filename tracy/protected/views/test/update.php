<?php
/* @var $this TestController */
/* @var $model Test */

$this->breadcrumbs=array(
	'Tests'=>array('index'),
	$model->id_test=>array('view','id'=>$model->id_test),
	'Update',
);

$this->menu=array(
	array('label'=>'List Test', 'url'=>array('index')),
	array('label'=>'Create Test', 'url'=>array('create')),
	array('label'=>'View Test', 'url'=>array('view', 'id'=>$model->id_test)),
	array('label'=>'Manage Test', 'url'=>array('admin')),
);
?>

<h1>Update Test <?php echo $model->id_test; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'special'=>$special)); ?>