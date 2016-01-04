<?php
/* @var $this ClassController */
/* @var $model Method */

$this->breadcrumbs=array(
    'Classes'=>array('class/index'),
    $class->name=>array('class/view','id'=>$class->id_class),
    $method->name=>array('class/methodview','id'=>$method->id_method),
    $model->name,
);

$this->menu=array(
    array('label'=>'View Class', 'url'=>array('view','id'=>$class->id_class)),
    array('label'=>'View Method', 'url'=>array('methodView',
                                                'id'=>$method->id_method)),
    array('label'=>'Update Method', 'url'=>array('methodUpdate',
                                                    'id'=>$method->id_method)),
    array('label'=>"Add Argument", 'url'=>array("argumentCreate",
                                                 "method"=>$method->id_method)),
    array('label'=>"Delete Argument", 'url'=>array("argumentDelete",
                                                 "id"=>$model->id_argument)),
);
?>

<h1>Update Argument <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('method/argument/_form', array('model'=>$model)); ?> 