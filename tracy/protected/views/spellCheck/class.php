<?php

$this->breadcrumbs = array(
    'Spell check' => array('index'),
    'Classes' => array('class'),
    $class->full_name()
);

$this->menu=array(
    array('label'=>'View Class', 'url'=>array('class/view','id'=>$class->id_class)),
    array('label'=>'Edit Class', 'url'=>array('class/update','id'=>$class->id_class)),
    array('label'=>'View Source', 'url'=>array('generator/decl','id'=>$class->id_class)),
);


echo "<h2>".CHtml::link($class->full_name(),
             array('class/view','id'=>$class->id_class))
    ."</h2>";
$desc_errors = SpellCheckController::check($class->description);
$use_errors = SpellCheckController::check($class->usage);

if ( !empty($desc_errors) || !empty($pre_errors) || !empty($post_errors) )
{
   $this->error_list($desc_errors,$class,"Description");
   $this->error_list($use_errors,$class,"Usage");
}
else
    echo 'No errors';
    
echo "<h2>Methods</h2>";

$this->renderPartial('methods',
              array('methods'=>$class->with('methods')->methods));

echo "<h2>Attributes</h2>";
$this->renderPartial('attributes',
              array('attributes'=>$class->with('attributes')->attributes));