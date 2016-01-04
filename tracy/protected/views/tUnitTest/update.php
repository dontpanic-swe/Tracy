<?php
/* @var $this TUnitTestController */
/* @var $model TUnitTest */

$this->breadcrumbs=array(
    'Tunit Tests'=>array('index'),
    $model->id_test=>array('view','id'=>$model->id_test),
    'Update',
);

$this->menu=array(
    array('label'=>'List TUnitTest', 'url'=>array('index')),
    array('label'=>'Create TUnitTest', 'url'=>array('create')),
    array('label'=>'View TUnitTest', 'url'=>array('view', 'id'=>$model->id_test)),
    array('label'=>'Manage TUnitTest', 'url'=>array('admin')),
);
?>

<h1>Update TUnitTest <?php echo $model->id_test; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>  
