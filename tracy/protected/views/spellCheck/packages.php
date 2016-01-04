<?php

$this->breadcrumbs = array(
    'Spell check' => array('index'),
    'Packages'
);


if ( $packages != null )
    foreach ( $packages as $model )
    {
        $desc_errors = SpellCheckController::check($model->description);
        
        if ( !empty($desc_errors)  )
        {
            echo "<h3>".CHtml::link($model->full_name(),
                         array('package/view','id'=>$model->id_package))
                ."</h3>";
            $this->error_list($desc_errors,$model,"Description");
        }
    
    }


?>
