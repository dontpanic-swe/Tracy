<?php
/* @var $this ClassController */
/* @var $model Class_Prog */

$this->breadcrumbs=array(
    'Class'=>array('index'),
    'Create',
);

$this->menu=array(
    array('label'=>'List Class', 'url'=>array('index')),
    array('label'=>'Manage Class', 'url'=>array('admin')),
);
?>

<h1>Create Class</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?> 