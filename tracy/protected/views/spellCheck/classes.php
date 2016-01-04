<?php

$this->breadcrumbs = array(
    'Spell check' => array('index'),
    'Classes'
);



if ( $classes != null )
    foreach ( $classes as $class )
    {
        $desc_errors = SpellCheckController::check($class->description);
        $use_errors = SpellCheckController::check($class->usage);
        
        if ( !empty($desc_errors) || !empty($pre_errors) || !empty($post_errors) )
        {
            echo "<h2>".CHtml::link($class->full_name(),
                         array('class/view','id'=>$class->id_class))
                ."</h2>";
           $this->error_list($desc_errors,$class,"Description");
           $this->error_list($use_errors,$class,"Usage");
        }
    
    }

?>
