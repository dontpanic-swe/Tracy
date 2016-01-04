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
    echo $form->hiddenField($model,'id_requirement',array('id'=>'validation_actual_requirement',
                                                  'value'=>$pid));
    $this->widget('zii.widgets.jui.CJuiAutoComplete',
        array(
            'name'=>'validation_requirement_autocomplete',
            'sourceUrl'=>array('requirement/parentcompletion'),
            'options'=> array (
                'select'=>"js:function(event, ui) {
                            $('#validation_actual_requirement').val(ui.item.id);
                        }",
            ),
            'value'=>$pdesc,
        ) );
    echo $form->error($model,'id_requirement');
?>

    <div class="row">
        <?php echo $form->labelEx($model,'parent'); ?>
        <?php echo $form->textField($model,'parent'); ?>
        <?php echo $form->error($model,'parent'); ?>
    </div>
