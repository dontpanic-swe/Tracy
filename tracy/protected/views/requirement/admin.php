<?php
/* @var $this RequirementController */
/* @var $model Requirement */

$this->breadcrumbs=array(
	'Requirements'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Requirement', 'url'=>array('index')),
	array('label'=>'Create Requirement', 'url'=>array('create')),
	array('label'=>'Manage Sources', 'url'=>array('source/index')),
	array('label'=>'Manage Categories', 'url'=>array('requirement/categories')),
	array('label'=>'Manage Priorities', 'url'=>array('requirement/priorities')),
	array('label'=>'Manage Validation Methods', 'url'=>array('requirement/validations')),
	array('label'=>'Req.Source Table', 'url'=>array('latex/RequirementSource')),
	array('label'=>'UC Req. Table', 'url'=>array('latex/SourceRequirement')),
	array('label'=>'Summary Table', 'url'=>array('latex/RequirementSummary')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('requirement-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Requirements</h1>

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
	'id'=>'requirement-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'enablePagination'=>false,
	'columns'=>array(
		'id_requirement:text:Id',
        array(
            'class'=>'CDataColumn',
            'header'=>'Id LaTeX',
            'type'=>'html',
            'value'=>'CHtml::link($data->public_id(),
                array("view","id"=>$data->id_requirement))',
        ),
        'apported',
        array(
            'type'=>'html',
            'name' => 'category',
            'filter' => CHtml::listData(RequirementCategory::model()->findAll(),
                                        'id_category', 'name'), 
            'value' => 'CHtml::link($data->category0->name,
                        array("index","Requirement[id_category]"=>$data->category0->id_category) )',
        ),
        
        array(
            'name'=>'priority',
            'type'=>'html',
            'filter' => CHtml::listData(RequirementPriority::model()->findAll(),
                                        'id_priority', 'name'), 
            'value'=>'CHtml::link($data->priority0->name,
                        array("index","Requirement[id_priority]"=>$data->priority0->id_priority) )',
        ),
        array(
            'class'=>'CDataColumn',
            'header'=>'Parent description',
            'type'=>'html',
            'value'=>'isset($data->parent0) ?
                        CHtml::link($data->parent0->description,
                                    array("view","id"=>$data->parent0->id_requirement) ) :
                        null',
        ),
		'parent',
		'description',
        
        
        array(
            'name'=>'validation',
            'type'=>'html',
            'filter' => CHtml::listData(RequirementValidation::model()->findAll(),
                                        'id_validation', 'name'), 
            'value'=>'isset($data->validation0) ?
                    CHtml::link($data->validation0->name,
                        array("index","Requirement[id_validation]"=>
                            $data->validation0->id_validation) )    :
                    null',
        ),
        
        array(
            'class'=>'CDataColumn',
            'header'=>'Test',
            'type'=>'html',
            'value'=>'isset($data->system_test) ?
                        CHtml::link($data->system_test->with("test")->test->description,
                            array("test/view",
                                  "id"=>$data->system_test->test->id_test) ) :
                        null',
        ),
        
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
