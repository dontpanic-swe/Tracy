<?php echo $form->errorSummary($model); ?>

<?php

    $model->with('package');

    $pid = '';
    $pdesc = '';
    if ( isset($model->package) )
    {
        $pid = $model->package->id_requirement;
        $pdesc = $model->package->description;
    }
    
    echo $form->labelEx($model,'id_package');
    echo $form->hiddenField($model,'id_package',array('id'=>'actual_package',
                                                  'value'=>$pid));
    $this->widget('zii.widgets.jui.CJuiAutoComplete',
        array(
            'name'=>'package_autocomplete',
            'sourceUrl'=>array('package/parentcompletion'),
            'options'=> array (
                'select'=>"js:function(event, ui) {
                            $('#actual_package').val(ui.item.id);
                        }",
            ),
            'value'=>$pdesc,
        ) );
    echo $form->error($model,'id_package');
?> 