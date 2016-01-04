
<?php
/* @var $this AttributeController */
/* @var $model Attribute */

$this->breadcrumbs=array(
    'Classes'=>array('class/index'),
    $class->name=>array('class/view','id'=>$class->id_class),
    $model->name=>array('class/attributeview','id'=>$model->id_attribute),
    'Update',
);


$this->menu=array(
    array('label'=>'View Class', 'url'=>array('view','id'=>$class->id_class)),
    array('label'=>'Create Attribute', 'url'=>array('attributeCreate',
                                                    'class'=>$class->id_class)),
    array('label'=>'View Attribute', 'url'=>array('view', 'id'=>$model->id_attribute)),
);



echo '<h1>Update Attribute ';

$class->with('package');
foreach ( $class->package->parent_array(true) as $n )
    echo CHtml::link($n->name,array('package/view','id'=>$n->id_package))."::";
    
echo CHtml::link( $class->name, array('class/view','id'=>$class->id_class) );
echo "::".$model->name."</h1>";

echo $this->renderPartial('attribute/_form', array('model'=>$model)); ?> 