<?php
/* @var $this AssociationController */
/* @var $model Association */


$attribute->with('class');
$this->breadcrumbs=array(
    'Classes'=>array('class/index'),
    $attribute->class->name=>array('class/view','id'=>$attribute->id_class),
    $attribute->name=>array('class/attributeview'=>$attribute->id_attribute),
    'Make Association'
);


$this->menu=array(
    array('label'=>'View Class', 'url'=>array('view','id'=>$attribute->id_class)),
    array('label'=>'View Attribute', 'url'=>array('view',
                                                  'id'=>$attribute->id_attribute)),
);

?>

<h1>Update Association <?php echo $model->id_association; ?></h1>

<?php echo $this->renderPartial('attribute/association/_form',
                                array('model'=>$model)); ?> 