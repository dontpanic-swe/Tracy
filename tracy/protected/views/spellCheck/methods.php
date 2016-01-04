<?php

$this->breadcrumbs = array(
    'Spell check' => array('index'),
    'Methods'
);


if ( $methods != null )
    foreach ( $methods as $model )
    {
        $desc_errors = SpellCheckController::check($model->description);
        $pre_errors = SpellCheckController::check($model->pre);
        $post_errors = SpellCheckController::check($model->post);
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
            echo "<h3>".CHtml::link($model->full_name(),
                         array('class/methodView','id'=>$model->id_method))
                ."</h3>";
            $this->error_list($desc_errors,$model,"Description");
            $this->error_list($pre_errors,$model,"Pre");
            $this->error_list($post_errors,$model,"Post");
            foreach( $args as $n => $ae )
                 $this->error_list($ae,$method,$n);
        }
    
    }


?>
