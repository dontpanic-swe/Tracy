<?php
/* @var $this TUnitTestController */
/* @var $model TUnitTest */

$this->breadcrumbs=array(
    'Tunit Tests'=>array('index'),
    $model->id_test,
);

$this->menu=array(
    array('label'=>'List TUnitTest', 'url'=>array('index')),
    array('label'=>'Create TUnitTest', 'url'=>array('create')),
    array('label'=>'Update TUnitTest', 'url'=>array('update', 'id'=>$model->id_test)),
    array('label'=>'Delete TUnitTest', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_test),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage TUnitTest', 'url'=>array('admin')),
);
?>

<h1>View TUnitTest #<?php echo $model->id_test; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'id_test',
        'description',
    ),
)); ?>

<h3>Tested Methods</h3>

<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>new CArrayDataProvider($model->tUnitMethods,
                                       array('keyField'=>false)),
    'columns'=>array(
        array(
              'class'=>'CDataColumn',
              'type'=>'html',
              'header'=>'Method',
              'value'=>'CHtml::link($data->method->signature_name(),
                    array("class/methodView","id"=>$data->id_method))',
        ),
    ),
));

echo CHtml::beginForm(array('connectMethod','id'=>$model->id_test));

$this->autoComplete("id_method","Class/methodComplete"); 
echo "<input type='submit' />";
echo CHtml::endForm();