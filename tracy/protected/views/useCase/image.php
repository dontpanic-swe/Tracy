<?php

$this->breadcrumbs=array(
    'Use Cases'=>array('index'),
    $id=>array('view','id'=>$id),
    'Image',
);

$this->menu=array(
    array('label'=>'List Sources', 'url'=>array('source/index')),
    array('label'=>'Create ExternalSource', 'url'=>array('externalSource/create')),
    array('label'=>'Create Use Case', 'url'=>array('useCase/create')),
    array('label'=>'View UseCase', 'url'=>array('view', 'id'=>$id)),
    array('label'=>'Update UseCase', 'url'=>array('update', 'id'=>$id)),
    array('label'=>'Raw Image', 'url'=>array('imagesvg', 'id'=>$id)),
    array('label'=>'Raw Graphviz code', 'url'=>array('imagedot', 'id'=>$id)),
);


echo "<h1>View Image for UseCase #{$id}</h1>";
echo CHtml::image(CHtml::normalizeUrl(array('imagesvg', 'id'=>$id)),'');