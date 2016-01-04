<?php
/* @var $this UseCaseEventController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Use Case Events',
);

$this->menu=array(
	array('label'=>'Create UseCaseEvent', 'url'=>array('create')),
	array('label'=>'Manage UseCaseEvent', 'url'=>array('admin')),
);
?>

<h1>Use Case Events</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
