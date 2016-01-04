<?php
/* @var $this ExternalSourceController */
/* @var $model ExternalSource */

$this->breadcrumbs=array(
    'External Sources'=>array('index'),
    'Create',
);

$this->menu=array(
    array('label'=>'List Sources', 'url'=>array('source/index')),
    array('label'=>'Create Use Case', 'url'=>array('useCase/create')),
);
?>

<h1>Create ExternalSource</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?> 