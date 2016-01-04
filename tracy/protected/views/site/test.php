<?php

$classes = Class_Prog::model()->findAll();

foreach ( $classes as $class )
{
    $errors = SpellCheck::check($class->description);
    
    if ( !empty($errors) )
    {
        echo CHtml::link($class->name,
                         array('class/view','id'=>$class->id_class));
        echo " - Description";
        echo '<ul>';
        foreach ( $errors as $err )
            echo "<li>$err</li>";
        echo '</ul>';
    }
    
    $errors = SpellCheck::check($class->usage);
    
    if ( !empty($errors) )
    {
        echo CHtml::link($class->name,
                         array('class/view','id'=>$class->id_class));
        echo " - Usage";
        echo '<ul>';
        foreach ( $errors as $err )
            echo "<li>$err</li>";
        echo '</ul>';
    }
}


?>
