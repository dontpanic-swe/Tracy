<?php
/* @var $this UseCaseController */
/* @var $model UseCase */

$this->breadcrumbs=array(
    'Use Cases'=>array('index'),
    'Manage',
);

$this->menu=array(
    array('label'=>'List Sources', 'url'=>array('source/index')),
    array('label'=>'Create ExternalSource', 'url'=>array('externalSource/create')),
    array('label'=>'Create Use Case', 'url'=>array('useCase/create')),
    array('label'=>'Use Case LaTeX', 'url'=>array('latex/UseCases')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('use-case-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<h1>Manage Use Cases</h1>

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
    'id'=>'use-case-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'enablePagination'=>false,
    'columns'=>array(
        'id_use_case',
        array(
            'class'=>'CDataColumn',
            'header'=>'ID LaTeX',
            'value'=>'$data->public_id()',
        ),
        'parent',
        'title',
        'pre',
        'post',
        array(
            'class'=>'CButtonColumn',
        ),
    ),
)); ?>