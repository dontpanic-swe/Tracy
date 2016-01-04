<?php
/* @var $this PackageController */
/* @var $model Package */

$this->breadcrumbs=array(
    'Packages'=>array('index'),
    $model->name=>array('view','id'=>$model->id_package),
    'Update',
);

$this->menu=array(
    array('label'=>'Create Package', 'url'=>array('create')),
    array('label'=>'View Package', 'url'=>array('view', 'id'=>$model->id_package)),
    array('label'=>'Manage Package', 'url'=>array('index')),
);
?>

<h1>Update Package <?php echo $model->id_package; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?> 