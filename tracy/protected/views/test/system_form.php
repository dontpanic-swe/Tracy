<?php echo $form->errorSummary($model); ?>

<?php

    $model->with('requirement');

    $pid = '';
    $pdesc = '';
    if ( isset($model->requirement) )
    {
        $pid = $model->requirement->id_requirement;
        $pdesc = $model->requirement->description;
    }
    
    echo $form->labelEx($model,'id_requirement');
    echo $form->hiddenField($model,'id_requirement',array('id'=>'system_actual_requirement',
                                                  'value'=>$pid));
    $this->widget('zii.widgets.jui.CJuiAutoComplete',
        array(
            'name'=>'system_requirement_autocomplete',
            'sourceUrl'=>array('requirement/parentcompletion'),
            'options'=> array (
                'select'=>"js:function(event, ui) {
                            $('#system_actual_requirement').val(ui.item.id);
                        }",
            ),
            'value'=>$pdesc,
        ) );
    echo $form->error($model,'id_requirement');
?> 