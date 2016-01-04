 <?php
/* @var $this UseCaseController */
/* @var $model UseCase */

$this->breadcrumbs=array(
    'Use Cases'=>array('index'),
    $model->id_use_case=>array('view','id'=>$model->id_use_case),
    'Update',
);

$this->menu=array(
    array('label'=>'List Sources', 'url'=>array('source/index')),
    array('label'=>'Create ExternalSource', 'url'=>array('externalSource/create')),
    array('label'=>'Create Use Case', 'url'=>array('useCase/create')),
    array('label'=>'View UseCase', 'url'=>array('view', 'id'=>$model->id_use_case)),
);
?>

<h1>Update UseCase <?php echo $model->id_use_case; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>  
