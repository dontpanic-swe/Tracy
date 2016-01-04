<?php
/* @var $this PackageController */
/* @var $model Package */

$this->breadcrumbs=array(
    'Packages'=>array('index'),
    'Manage',
);

$this->menu=array(
    array('label'=>'List Package', 'url'=>array('index')),
    array('label'=>'Create Package', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('package-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<h1>Manage Packages</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
    'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php

$dp = $model->with('parent0')->search();
$dp->pagination = false;

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'package-grid',
    'dataProvider'=>$dp,
    'filter'=>$model,
    'columns'=>array(
        'id_package',
        'name',
        array(
            'class'=>'CDataColumn',
            'header'=>'Full name',
            'value'=>'$data->full_name()',
        ),
        array(
            'class'=>'CDataColumn',
            'header'=>'Parent',
            'type'=>'html',
            'value'=>'isset($data->parent0) ?
                        CHtml::link($data->parent0->name,
                                    array("view","id"=>$data->parent0->id_package) ) :
                        null',
        ),
        'description',
        'virtual',
        array(
            'class'=>'CButtonColumn',
        ),
    ),
)); ?>