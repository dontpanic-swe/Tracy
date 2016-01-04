<?php
/* @var $this PackageController */
/* @var $model Package */

$this->breadcrumbs=array(
    'Packages'=>array('index'),
    'Create',
);

$this->menu=array(
    array('label'=>'Manage Package', 'url'=>array('index')),
);
?>

<h1>Create Package</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?> 