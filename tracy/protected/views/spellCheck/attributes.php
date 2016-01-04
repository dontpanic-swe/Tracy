<?php

$this->breadcrumbs = array(
    'Spell check' => array('index'),
    'Attributes'
);


if ( $attributes != null )
    foreach ( $attributes as $model )
    {
        $desc_errors = SpellCheckController::check($model->description);
        
        if ( !empty($desc_errors)  )
        {
            echo "<h3>".CHtml::link($model->full_name(),
                         array('class/attributeView','id'=>$model->id_attribute))
                ."</h3>";
            $this->error_list($desc_errors,$model,"Description");
        }
    
    }


?>
