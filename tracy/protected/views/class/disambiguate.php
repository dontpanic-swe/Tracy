<?php
/* @var $this ClassController */
/* @var $classes array[Class]*/
/* @var $name string*/

$this->breadcrumbs=array(
    'Class'=>array('index'),
    "Disambiguate $name",
);

$this->menu=array(
    array('label'=>'List Class', 'url'=>array('index')),
    array('label'=>'Create Class', 'url'=>array('create')),
);


$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'class-par-grid',
    'dataProvider'=> new CArrayDataProvider ($classes,
                                             array('keyField'=>'id_class') ),
    //'filter'=>$model,
    'columns'=>Class_Prog::grid_columns(array() )
));