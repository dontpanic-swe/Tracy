<?php
/* @var $this ClassController */
/* @var $model Class_Prog */

$this->breadcrumbs=array(
    'Class'=>array('index'),
    $model->name=>array('view','id'=>$model->id_class),
    'Update',
);

$this->menu=array(
    array('label'=>'List Class', 'url'=>array('index')),
    array('label'=>'Create Class', 'url'=>array('create')),
    array('label'=>'View Class', 'url'=>array('view', 'id'=>$model->id_class)),
    array('label'=>'Create Method', 'url'=>array('methodCreate',
                                                    'class'=>$model->id_class)),
    array('label'=>'Create Attribute', 'url'=>array('attributeCreate',
                                                    'class'=>$model->id_class)),
);
?>

<h1>Update Class <?php echo $model->id_class; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?> 