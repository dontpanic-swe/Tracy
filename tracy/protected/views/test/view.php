<?php
/* @var $this TestController */
/* @var $model Test */

$this->breadcrumbs=array(
	'Tests'=>array('index'),
	$model->id_test,
);

$this->menu=array(
	array('label'=>'List Test', 'url'=>array('index')),
	array('label'=>'Create Test', 'url'=>array('create')),
	array('label'=>'Update Test', 'url'=>array('update', 'id'=>$model->id_test)),
	array('label'=>'Delete Test', 'url'=>'#',
          'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_test),
                    'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View <?php echo $model->test_type()." Test ".$model->public_id(); ?></h1>

<?php


if ( isset($model->id_parent) )
{
    $model->with('parent');
    $extra = array(
                'label'=>'Parent',
                'type'=>'html',
                'value'=>$model->parent == null ? null :
                            CHtml::link($model->parent->public_id(),
                                array("view","id"=>$model->parent)),
            );
}
else
    $extra = null;


$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_test',
		'status',
		'description',
		'jenkins_id',
        array(
            'label'=>'Refers to',
            'type'=>'html',
            'value'=>$model->relation_link(),
        ),
        
	),
));


if ( $model->test_type() == 'Validation' )
{
    $model->validation->with('children');
    
    
    echo '<h2>Children</h2>';
    
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'test-grid',
        'dataProvider'=>new CArrayDataProvider($model->validation->children,
                                           array('keyField'=>'id_test')),
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
            
            array(
                'class'=>'CButtonColumn',
            ),
        ),
    ));
    
    echo '<div class="append">';
    echo CHtml::link('Add child',array('addChild','parent'=>$model->id_test));
    echo '</div>';
}
else if ( $model->test_type() == 'Unit' )
{
	$this->menu []= array('label'=>'Code stub',
                        'url'=>array('generator/unitTest','id'=>$model->id_test));
}

?>
