<?php

    $this->breadcrumbs = array(
        'Upload source'=>array('index'),
        'View'=>array('view'),
        'Update'=>array('scan'),
        'Namespace',
    );
    
    echo '<h1>Check Namespace</h1>';
    
    $to_be_created = null;
    $display = array();
    foreach ( Yii::app()->session['namespace'] as $namespace )
    {
        if ( $namespace->isNewRecord )
        {
            if ( $to_be_created == null )
                $to_be_created = $namespace;
            $display []= $namespace->name;
        }
        else
            $display []= CHtml::link($namespace->name,
                            array('package/view','id'=>$namespace->id_package));
    }
    
    echo '<p>The uploaded class is located in the namespace</p>';
    echo '<p>'.implode("::",$display).'</p>';
    
    if ( $to_be_created != null )
    {
        echo '<h2>Create Package</h2>';
        echo $this->renderPartial('/package/_form', array('model'=>$to_be_created));
    }
    else
        echo CHtml::link('Continue',array('update_bind'));