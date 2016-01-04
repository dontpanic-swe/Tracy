<?php
/* @var $this UseCaseController */
/* @var $model UseCase */

$this->breadcrumbs=array(
    'Use Cases'=>array('index'),
    $model->id_use_case,
);

$this->menu=array(
    array('label'=>'List Sources', 'url'=>array('source/index')),
    array('label'=>'Create ExternalSource', 'url'=>array('externalSource/create')),
    array('label'=>'Create Use Case', 'url'=>array('useCase/create')),
    array('label'=>'Update UseCase', 'url'=>array('update', 'id'=>$model->id_use_case)),
    array('label'=>'Delete UseCase', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_use_case),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'View Image', 'url'=>array('image','id'=>$model->id_use_case) ),
);
?>

<h1>View UseCase #<?php echo $model->id_use_case; ?> (<?php echo $model->public_id(); ?>)</h1>

<?php

$model->with('parent0');

$this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'id_use_case',
        'title',
        array(
            'label'=>'Parent',
            'type'=>'html',
            'value'=>isset($model->parent0) ?
                        CHtml::link($model->parent0->public_id()." - ".
                                    $model->parent0->title,
                                    array('view','id'=>$model->parent0->id_use_case) ) :
                        null,
        ),
        'description',
        'pre',
        'post',
    ),
)); 

echo "<br /><h1>Event Flow</h1>";

echo "<h3>Main Scenario</h3>";

echo CHtml::link("Add new Event to Main Scenario", array("useCaseEvent/create","uc"=>$model->id_use_case,"cat"=>"1"));

$criteria = new CDbCriteria();
$criteria->condition = "use_case = {$model->id_use_case} AND category = 1";
$criteria->order = "`order`";
$events = UseCaseEvent::model()->findAll($criteria);

$dataProvider=new CActiveDataProvider('UseCaseEvent', array(
    'pagination'=>array(
        'pageSize'=>20,
    ),
));
$dataProvider->setCriteria($criteria);

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
	'id_event',
        array(
            'class'=>'CDataColumn',
            'name'=>'category',
            'type'=>'html',
            'value'=>'$data->category0->name',
        ),
	'description',
        array(
            'class'=>'CDataColumn',
            'name'=>'Refers To',
            'type'=>'html',
            'value'=>'isset($data->refersTo) ?
                            CHtml::link($data->refersTo->public_id(),
                                    array("useCase/view","id"=>$data->refers_to))
                        : null',
        ),
        array(
            'class'=>'CDataColumn',
            'name'=>'Primary Actor',
            'type'=>'html',
            'value'=>'$data->primaryActor->description',
        ),
	'order',
	array(
            'class'=>'CButtonColumn',
            'buttons'=>array
	     (
		'view' => array
		(
		'url'=>'$this->grid->controller->createUrl("/useCaseEvent/view/$data->primaryKey")',
		),
		'update' => array
		(
		'url'=>'$this->grid->controller->createUrl("/useCaseEvent/update/$data->primaryKey")',
		),
		'delete' => array
		(
		'url'=>'$this->grid->controller->createUrl("/useCaseEvent/delete/$data->primaryKey")',
		),
	    ),

        ),
    ),
));


echo "<h3>Extend</h3>";

echo CHtml::link("Add new Event to Extend Scenario", array("useCaseEvent/create","uc"=>$model->id_use_case,"cat"=>"3"));

$criteria = new CDbCriteria();
$criteria->condition = "use_case = {$model->id_use_case} AND category = 3";
$criteria->order = "`order`";
$events = UseCaseEvent::model()->with('category0','useCase','primaryActor')->findAll($criteria);

$dataProvider=new CActiveDataProvider('UseCaseEvent', array(
    'pagination'=>array(
        'pageSize'=>20,
    ),
));
$dataProvider->setCriteria($criteria);

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
	'id_event',
        array(
            'class'=>'CDataColumn',
            'name'=>'category',
            'type'=>'html',
            'value'=>'$data->category0->name',
        ),
	'description',
        array(
            'class'=>'CDataColumn',
            'name'=>'Refers To',
            'type'=>'html',
            'value'=>'isset($data->refersTo) ?
                            CHtml::link($data->refersTo->public_id(),
                                    array("useCase/view","id"=>$data->refers_to))
                        : null',
        ),
        array(
            'class'=>'CDataColumn',
            'name'=>'Primary Actor',
            'type'=>'html',
            'value'=>'$data->primaryActor->description',
        ),
	'order',
	array(
            'class'=>'CButtonColumn',
            'buttons'=>array
	     (
		'view' => array
		(
		'url'=>'$this->grid->controller->createUrl("/useCaseEvent/view/$data->primaryKey")',
		),
		'update' => array
		(
		'url'=>'$this->grid->controller->createUrl("/useCaseEvent/update/$data->primaryKey")',
		),
		'delete' => array
		(
		'url'=>'$this->grid->controller->createUrl("/useCaseEvent/delete/$data->primaryKey")',
		),
	    ),
        ),
    ),
));


echo "<h3>Inlude</h3>";

echo CHtml::link("Add new Event to Include Scenario", array("useCaseEvent/create","uc"=>$model->id_use_case,"cat"=>"4"));

$criteria = new CDbCriteria();
$criteria->condition = "use_case = {$model->id_use_case} AND category = 4";
$criteria->order = "`order`";
$events = UseCaseEvent::model()->with('category0','useCase','primaryActor')->findAll($criteria);

$dataProvider=new CActiveDataProvider('UseCaseEvent', array(
    'pagination'=>array(
        'pageSize'=>20,
    ),
));
$dataProvider->setCriteria($criteria);

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
	'id_event',
        array(
            'class'=>'CDataColumn',
            'name'=>'category',
            'type'=>'html',
            'value'=>'$data->category0->name',
        ),
	'description',
        array(
            'class'=>'CDataColumn',
            'name'=>'Refers To',
            'type'=>'html',
            'value'=>'isset($data->refersTo) ?
                            CHtml::link($data->refersTo->public_id(),
                                    array("useCase/view","id"=>$data->refers_to))
                        : null',
        ),
        array(
            'class'=>'CDataColumn',
            'name'=>'Primary Actor',
            'type'=>'html',
            'value'=>'$data->primaryActor->description',
        ),
	'order',
	array(
            'class'=>'CButtonColumn',
            'buttons'=>array
	     (
		'view' => array
		(
		'url'=>'$this->grid->controller->createUrl("/useCaseEvent/view/$data->primaryKey")',
		),
		'update' => array
		(
		'url'=>'$this->grid->controller->createUrl("/useCaseEvent/update/$data->primaryKey")',
		),
		'delete' => array
		(
		'url'=>'$this->grid->controller->createUrl("/useCaseEvent/delete/$data->primaryKey")',
		),
	    ),
        ),
    ),
));



echo "<h3>Alternate</h3>";

echo CHtml::link("Add new Event to Alternate Scenario", array("useCaseEvent/create","uc"=>$model->id_use_case,"cat"=>"2"));

$criteria = new CDbCriteria();
$criteria->condition = "use_case = {$model->id_use_case} AND category = 2";
$criteria->order = "`order`";
$events = UseCaseEvent::model()->with('category0','useCase','primaryActor')->findAll($criteria);

$dataProvider=new CActiveDataProvider('UseCaseEvent', array(
    'pagination'=>array(
        'pageSize'=>20,
    ),
));
$dataProvider->setCriteria($criteria);

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
	'id_event',
        array(
            'class'=>'CDataColumn',
            'name'=>'category',
            'type'=>'html',
            'value'=>'$data->category0->name',
        ),
	'description',
        array(
            'class'=>'CDataColumn',
            'name'=>'Refers To',
            'type'=>'html',
            'value'=>'isset($data->refersTo) ?
                            CHtml::link($data->refersTo->public_id(),
                                    array("useCase/view","id"=>$data->refers_to))
                        : null',
        ),
        array(
            'class'=>'CDataColumn',
            'name'=>'Primary Actor',
            'type'=>'html',
            'value'=>'$data->primaryActor->description',
        ),
	'order',
	array(
            'class'=>'CButtonColumn',
            'buttons'=>array
	     (
		'view' => array
		(
		'url'=>'$this->grid->controller->createUrl("/useCaseEvent/view/$data->primaryKey")',
		),
		'update' => array
		(
		'url'=>'$this->grid->controller->createUrl("/useCaseEvent/update/$data->primaryKey")',
		),
		'delete' => array
		(
		'url'=>'$this->grid->controller->createUrl("/useCaseEvent/delete/$data->primaryKey")',
		),
	    ),
        ),
    ),
));

echo CHtml::image(CHtml::normalizeUrl(array('imagesvg', 'id'=>$model->id_use_case)),'');

?> 
