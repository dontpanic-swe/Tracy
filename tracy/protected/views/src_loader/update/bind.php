<?php

    $this->breadcrumbs = array(
        'Upload source'=>array('index'),
        'View'=>array('view'),
        'Update'=>array('scan'),
        'Namespace'=>array('update_namespace'),
        'Bind class'
    );
    
    $class = Yii::app()->session['class'];

echo '<p>The class '.$class->full_name.' has not been recognised</p>';


echo CHtml::beginForm(array('update_bind'));
echo CHtml::label('Select existing class to be updated ','bind_class');
echo CHtml::hiddenField('bind_class','',array('id'=>'bind_class'));
$this->widget('zii.widgets.jui.CJuiAutoComplete',
    array(
        'name'=>'bind_autocomplete',
        'sourceUrl'=>array('class/parentcompletion'),
        'options'=> array (
            'select'=>"js:function(event, ui) {
                        $('#bind_class').val(ui.item.id);
                    }",
        ),
        'value'=>'',
    ) );
echo CHtml::submitButton('Update');
echo CHtml::endForm();


echo CHtml::beginForm(array('update_class'));
echo CHtml::submitButton('Create new class');
echo CHtml::endForm();
