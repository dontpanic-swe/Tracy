<?php
/* @var $this TestController */
/* @var $model Test */

$this->breadcrumbs=array(
	'Tests',
);

$this->menu=array(
	array('label'=>'Create Test', 'url'=>array('create')),
	array('label'=>'Validation Tests', 'url'=>array('validation')),
	array('label'=>'System Tests', 'url'=>array('system')),
	array('label'=>'Integration Tests', 'url'=>array('integration')),
	array('label'=>'Unit Tests', 'url'=>array('unit')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('test-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Tests</h1>

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


$model->with('integration','system', 'unit','validation');

$page_size = isset($_GET['num'])?$_GET['num']:100;
$dp = $model->search();
if ( $page_size == 0 || !is_numeric($page_size) )
{
    $page_size = 'infinity';
    $dp->pagination = false;
}
else
    $dp->pagination->pageSize = $page_size;
$page_sizes = array_unique(array(20,50,100,'infinity',$page_size));
natsort($page_sizes);
echo '<p>View: ';
foreach($page_sizes as $ps )
{
    if ( $ps == $page_size )
        echo "<strong>$ps</strong>";
    else
        echo CHtml::link($ps,array('index','num'=>$ps));
    echo " ";
}
echo ' tests per page</p>';



$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'test-grid',
	'dataProvider'=>$dp,
	'filter'=>$model,
	'columns'=>array(
        
        array(
            'class'=>'CDataColumn',
            'header'=>'Id LaTeX',
            'type'=>'text',
            'value'=>'$data->public_id()',
        ),
        
		'id_test',
		'status',
		'description',
		'jenkins_id',
        
        array(
            'class'=>'CDataColumn',
            'header'=>'Type',
            'type'=>'html',
            'filter'=>array('System'=>'System', 'Unit'=>'Unit',
                            'Integration'=>'Integration',
                            'Validation'=>'Validation'),
            'value'=>'$data->test_type()',
        ),
        
        array(
            'class'=>'CDataColumn',
            'header'=>'Refers to',
            'type'=>'html',
            'value'=>'$data->relation_link()',
        ),
        
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
