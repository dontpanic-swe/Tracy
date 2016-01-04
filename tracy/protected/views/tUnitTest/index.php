<?php
/* @var $this TUnitTestController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    'Tunit Tests',
);

$this->menu=array(
    array('label'=>'Create TUnitTest', 'url'=>array('create')),
    array('label'=>'Manage TUnitTest', 'url'=>array('admin')),
);
?>

<h1>Tunit Tests</h1>

<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_view',
)); ?> 
