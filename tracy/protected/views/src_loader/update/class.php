<?php

    $this->breadcrumbs = array(
        'Upload source'=>array('index'),
        'View'=>array('view'),
        'Update'=>array('scan'),
        'Namespace'=>array('update_namespace'),
        'Class'
    );
    
    $class = Yii::app()->session['class'];
    
    echo '<h1>'.($class->isNewRecord?'Create':'Update')." class</h1>";
    
    echo $this->renderPartial('/class/_form',
            array('model'=>$class));