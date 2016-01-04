<?php
/* @var $this TUnitTestController */
/* @var $model TUnitTest */

$this->breadcrumbs=array(
    'Tunit Tests'=>array('index'),
    'Create',
);

$this->menu=array(
    array('label'=>'List TUnitTest', 'url'=>array('index')),
    array('label'=>'Manage TUnitTest', 'url'=>array('admin')),
);
?>

<h1>Create TUnitTest</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?> 