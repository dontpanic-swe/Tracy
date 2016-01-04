<?php
/* @var $this ExternalSourceController */
/* @var $model ExternalSource */

$this->breadcrumbs=array(
    'External Sources'=>array('index'),
    $model->id_source,
);

$this->menu=array(
    array('label'=>'List Sources', 'url'=>array('source/index')),
    array('label'=>'Create ExternalSource', 'url'=>array('create')),
    array('label'=>'Create Use Case', 'url'=>array('useCase/create')),
    array('label'=>'Update ExternalSource', 'url'=>array('update', 'id'=>$model->id_source)),
    array('label'=>'Delete ExternalSource', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_source),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View ExternalSource #<?php echo $model->id_source; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'id_source',
        'description',
    ),
)); ?> 
