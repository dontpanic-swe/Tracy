<?php
/* @var $this ActorController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Actors',
);

$this->menu=array(
	array('label'=>'Create Actor', 'url'=>array('create')),
	array('label'=>'Manage Actor', 'url'=>array('admin')),
);
?>

<h1>Actors</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
