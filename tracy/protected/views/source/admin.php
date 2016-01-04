<?php
/* @var $this SourceController */
/* @var $model Source */

$this->breadcrumbs=array(
    'Sources'=>array('index'),
    'Manage',
);

$this->menu=array(
    array('label'=>'List Source', 'url'=>array('index')),
    array('label'=>'List External Sources', 'url'=>array('externalSource/index')),
    array('label'=>'List Use Cases', 'url'=>array('useCase/index')),
    array('label'=>'Create External Source', 'url'=>array('externalSource/create')),
    array('label'=>'Create Use Case', 'url'=>array('useCase/create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('source-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<h1>Manage Sources</h1>

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

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'source-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        'id_source',
        array(
            'class'=>'CDataColumn',
            'header'=>'Type',
            'type'=>'html',
            'value'=>'isset($data->useCase) ? "Use case" :
                    (
                        isset($data->externalSource) ? "External Source" :
                            "<i>(Orphaned)</i>"
                    )',
        ),
        'description',
        array(
            'class'=>'CButtonColumn',
        ),
    ),
)); ?> 
