<?php
/* @var $this ClassController */
/* @var $model Method*/

$this->breadcrumbs=array(
    'Classes'=>array('class/index'),
    $class->name=>array('class/view','id'=>$class->id_class),
    'Create Method',
);

$this->menu=array(
    array('label'=>'View Class', 'url'=>array('view','id'=>$class->id_class)),
);
?>

<h1>Create Method</h1>

<?php echo $this->renderPartial('method/_form', array('model'=>$model)); ?> 