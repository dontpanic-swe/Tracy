<?php
/* @var $this RequirementController */
/* @var $model Requirement */

$this->breadcrumbs=array(
	'Requirements'=>array('index'),
	$model->id_requirement,
);

$this->menu=array(
	array('label'=>'List Requirement', 'url'=>array('index')),
	array('label'=>'Manage Sources', 'url'=>array('source/index')),
	array('label'=>'Manage Categories', 'url'=>array('requirement/categories')),
	array('label'=>'Manage Priorities', 'url'=>array('requirement/priorities')),
	array('label'=>'Manage Validation Methods', 'url'=>array('requirement/validations')),
	array('label'=>'Create Requirement', 'url'=>array('create')),
	array('label'=>'Update Requirement',
          'url'=>array('update', 'id'=>$model->id_requirement)),
	array('label'=>'Delete Requirement', 'url'=>'#',
          'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_requirement),
                               'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Create test','url'=>array('test/create','req'=>$model->id_requirement)),
);

?>

<h1>View Requirement #<?php echo $model->id_requirement." (".$model->public_id().") "; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_requirement',
        'apported',
        array(
            'label'=>'Category',
            'type'=>'html',
            'value'=>CHtml::link($model->category0->name,
                        array('index','Requirement[id_category]'=>
                              $model->category0->id_category) ),
        ),
        array(
            'label'=>'Priority',
            'type'=>'html',
            'value'=>CHtml::link($model->priority0->name,
                        array('index','Requirement[id_priority]'=>
                              $model->priority0->id_priority) ),
        ),
		'description',
        array(
            'label'=>'Parent',
            'type'=>'html',
            'value'=>isset($model->parent0) ?
                        CHtml::link($model->parent0->description,
                                    array('view','id'=>$model->parent0->id_requirement) ) :
                        null,
        ),
        array(
            'label' => 'Test',
            'type' => 'html',
            'value'=>isset($model->system_test) ?
                        CHtml::link($model->system_test->with('test')
                                    ->test->description,
                            array('test/view',
                                  'id'=>$model->system_test->test->id_test) ) :
                        null,
        ),
        array(
            'label' => 'Validation',
            'type' => 'html',
            'value'=>isset($model->validation0) ?
                        CHtml::link($model->validation0->name,
                            array('index','Requirement[id_validation]'=>
                                  $model->validation0->id_validation) ) :
                        null,
        ),
            
	),
));

echo "<h2>Sources</h2>";
$model->with('sources');
if ( isset($model->sources) )
{

    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=> new CActiveDataProvider(
                'Source',
                array (
                    'criteria'=> array (
                        'condition' => 'sr.id_requirement = :id',
                        'params'=>array(':id'=>$model->id_requirement),
                        'join'=>'JOIN source_requirement sr ON t.id_source=sr.id_source',
                        'with' => array('useCase','externalSource'),
                    ),
                )
            ),
        'columns'=>array(
            'id_source',
            array(
                'class'=>'CDataColumn',
                'header'=>'ID LaTeX',
                'value'=>'isset($data->useCase) ? $data->useCase->public_id() : null',
            ),
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
            array(
                'class'=>'CDataColumn',
                'header'=>'Description',
                'type'=>'html',
                'value'=>'CHtml::link($data->description,array("source/view","id"=>$data->id_source))',
            ),
        ),
    ));
}

$model->with('requirements');
if ( isset($model->requirements) )
{
    echo '<h2>Children</h2>';
    if ( count($model->requirements) == 0 )
        echo '<p>No children</p>';
    else
    {
        echo '<ul>';
        foreach($model->requirements as $c)
            echo "<li>".CHtml::link($c->public_id()." - ".$c->description,
                                    array('view','id'=>$c->id_requirement))."</li>";
        echo '</ul>';
    }
}


echo '<h2>Packages</h2>';
$model->with('packages');
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=> new CArrayDataProvider($model->packages,
                                            array('keyField'=>'id_package')
                                        ),
    'columns'=>array(
        array(
            'class'=>'CDataColumn',
            'header'=>'Package',
            'type'=>'html',
            'value'=>'CHtml::link($data->full_name(),
                        array("package/view","id"=>$data->id_package)
                    )',
        ),
        array(
            'class'=>'CButtonColumn',
            'deleteButtonUrl'=>'array("remove_tracking",
                                    "requirement"=>'.$model->id_requirement.',
                                    "package"=>$data->id_package,
                                )',
            'viewButtonUrl'=>'array("package/view",
                                    "id"=>$data->id_package,
                                )',
            'template'=>'{view}{delete}',
        ),
    ),
));



echo CHtml::beginForm(array('view','id'=>$model->id_requirement));
echo CHtml::label('Select package to be added ','add_class');
$this->autoComplete("add_package","package/parentCompletion");
echo CHtml::submitButton('Add');
echo CHtml::endForm();






echo '<h2>Classes</h2>';
$model->with('classes');
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=> new CArrayDataProvider($model->classes,
                                            array('keyField'=>'id_class')
                                        ),
    'columns'=>array(
        array(
            'class'=>'CDataColumn',
            'header'=>'Package',
            'type'=>'html',
            'value'=>'CHtml::link($data->full_name(),
                        array("class/view","id"=>$data->id_class)
                    )',
        ),
        array(
            'class'=>'CButtonColumn',
            'deleteButtonUrl'=>'array("remove_tracking_class",
                                    "requirement"=>'.$model->id_requirement.',
                                    "class"=>$data->id_class,
                                )',
            'viewButtonUrl'=>'array("class/view",
                                    "id"=>$data->id_class,
                                )',
            'template'=>'{view}{delete}',
        ),
    ),
));
echo CHtml::beginForm(array('view','id'=>$model->id_requirement));
echo CHtml::label('Select class to be added ','add_class_real');
$this->autoComplete("add_class","class/parentCompletion");
echo CHtml::submitButton('Add');
echo CHtml::endForm();
