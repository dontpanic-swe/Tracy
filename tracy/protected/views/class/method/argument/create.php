<?php
/* @var $this ClassController */
/* @var $model Method */

$this->breadcrumbs=array(
    'Classes'=>array('class/index'),
    $class->name=>array('class/view','id'=>$class->id_class),
    $method->name=>array('class/methodview','id'=>$method->id_method),
    'Create Argument',
);

$this->menu=array(
    array('label'=>'View Class', 'url'=>array('view','id'=>$class->id_class)),
    array('label'=>'Create Method', 'url'=>array('methodCreate',
                                                    'class'=>$class->id_class)),
    array('label'=>'Update Method', 'url'=>array('methodUpdate',
                                                    'id'=>$method->id_method)),
    array('label'=>'Create Attribute', 'url'=>array('attributeCreate',
                                                    'class'=>$class->id_class)),
);

echo '<h1>Create Argument</h1>';

echo $this->renderPartial('method/argument/_form', array('model'=>$model));  