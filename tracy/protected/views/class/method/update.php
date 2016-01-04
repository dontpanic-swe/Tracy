<?php
/* @var $this MethodController */
/* @var $model Method */

$this->breadcrumbs=array(
    'Classes'=>array('class/index'),
    $class->name=>array('class/view','id'=>$class->id_class),
    $model->name=>array('class/methodview','id'=>$model->id_method),
    'Update',
);


$this->menu=array(
    array('label'=>'View Class', 'url'=>array('view','id'=>$class->id_class)),
    array('label'=>'Create Method', 'url'=>array('methodCreate',
                                                    'class'=>$class->id_class)),
    array('label'=>'View Method', 'url'=>array('view', 'id'=>$model->id_method)),
    array('label'=>"Add Argument", 'url'=>array("argumentCreate",
                                                 "method"=>$model->id_method)),
);



echo '<h1>Update Method ';

$class->with('package');
foreach ( $class->package->parent_array(true) as $n )
    echo CHtml::link($n->name,array('package/view','id'=>$n->id_package))."::";
    
echo CHtml::link( $class->name, array('class/view','id'=>$class->id_class) );
echo "::".$model->name."</h1>";

echo $this->renderPartial('method/_form', array('model'=>$model)); ?> 