<?php

$this->breadcrumbs = array(
    'Spell check' => array('index'),
    'Attributes' => array('attribute'),
    $attribute->full_name()
);

$this->menu=array(
    array('label'=>'View Class', 'url'=>array('class/view',
                                              'id'=>$attribute->id_class)),
    array('label'=>'View Attribute', 'url'=>array('class/attributeView',
                                              'id'=>$attribute->id_attribute)),
    array('label'=>'Edit Attribute', 'url'=>array('class/attributeUpdate',
                                              'id'=>$attribute->id_attribute)),
);


echo "<h2>".CHtml::link($attribute->full_name(),
             array('class/attributeView','id'=>$attribute->id_attribute))
    ."</h2>";
$desc_errors = SpellCheckController::check($attribute->description);

if ( !empty($desc_errors) )
{
   $this->error_list($desc_errors,$attribute,"Description");
}
else
    echo 'No errors';