<?php

$this->breadcrumbs = array(
    'Spell check' => array('index'),
    'Methods' => array('method'),
    $method->full_name()
);

$this->menu=array(
    array('label'=>'View Class', 'url'=>array('class/view',
                                              'id'=>$method->id_class)),
    array('label'=>'View Method', 'url'=>array('class/methodView',
                                              'id'=>$method->id_method)),
    array('label'=>'Edit Method', 'url'=>array('class/methodUpdate',
                                              'id'=>$method->id_method)),
);


echo "<h2>".CHtml::link($method->full_name(),
             array('class/methodView','id'=>$method->id_method))
    ."</h2>";
$desc_errors = SpellCheckController::check($method->description);
$pre_errors = SpellCheckController::check($method->pre);
$post_errors = SpellCheckController::check($method->post);
$args = array();
foreach($method->arguments as $arg)
{
    $err = SpellCheckController::check($arg->description);
    if ( !empty($err) )
        $args [$arg->name] = $err;
}

if ( !empty($desc_errors) || !empty($pre_errors) || !empty($post_errors)
    || !empty($args) )
{
   $this->error_list($desc_errors,$method,"Description");
   $this->error_list($pre_errors,$method,"Pre");
   $this->error_list($post_errors,$method,"Post");
   foreach( $args as $n => $ae )
        $this->error_list($ae,$method,$n);
}
else
    echo 'No errors';