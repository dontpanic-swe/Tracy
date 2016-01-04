<?php
/* @var $this AttributeController */
/* @var $model Attribute */

$this->breadcrumbs=array(
    'Classes'=>array('class/index'),
    $class->name=>array('class/view','id'=>$class->id_class),
    'Create Attribute',
);

$this->menu=array(
    array('label'=>'View Class', 'url'=>array('view','id'=>$class->id_class)),
);
?>

<h1>Create Attribute</h1>

<?php echo $this->renderPartial('attribute/_form', array('model'=>$model)); ?> 