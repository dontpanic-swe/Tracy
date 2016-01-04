<?php


$this->breadcrumbs=array(
	'Tests'=>array('index'),
	$type,
);

$this->menu=array(
	array('label'=>'Create Test', 'url'=>array('create')),
);

echo "<h1>Manage $type Tests</h1>";

$class = $type."Test";
$model = $class::model()->with('test');

if ( $type == 'Validation' )
{
    $model->with('parent0');
    $extra = array(
                'class'=>'CDataColumn',
                'header'=>'Parent',
                'type'=>'html',
                'value'=>'$data->parent0 == null ? null :
                            CHtml::link($data->parent0->public_id(),
                                array("view","id"=>$data->parent))',
            );
}
else
{
    $extra = array(
                'class'=>'CDataColumn',
                'header'=>'',
                'type'=>'raw',
                'value'=>'',
            );
}

$page_size = isset($_GET['num'])?$_GET['num']:'lol';
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
	'columns'=>array(
        
        array(
            'class'=>'CDataColumn',
            'header'=>'Id LaTeX',
            'type'=>'text',
            'value'=>'$data->public_id()',
        ),
        
        
		'test.status',
		'test.description',
		'test.jenkins_id',
        
        array(
            'class'=>'CDataColumn',
            'header'=>'Refers to',
            'type'=>'html',
            'value'=>'$data->relation_link()',
        ),
        
        $extra,
        
		array(
			'class'=>'CButtonColumn',
		),
	),
));



?>
