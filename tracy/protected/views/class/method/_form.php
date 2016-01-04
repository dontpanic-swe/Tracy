<?php
/* @var $this MethodController */
/* @var $model Method */
/* @var $form CActiveForm */
?>

<div class="form">

<?php

//todo script static<->virtual<->final/override
Yii::app()->clientScript->registerScript('toggle', "

$('#Method_access').bind('change',update_signal);

function update_signal()
{
    
    if ($('#Method_access').val()=='signal')
    {
        $('#no_signal').hide('slow');
        
        $('#Method_pre').val('');
        $('#Method_post').val('');
        $('#Method_return').val('void');
        $('#Method_virtual').prop('checked', false);
        $('#Method_override').prop('checked', false);
        $('#Method_final').prop('checked', false);
        $('#Method_abstract').prop('checked', false);
        $('#Method_static').prop('checked', false);
        $('#Method_const').prop('checked', false);
        $('#Method_nothrow').prop('checked', false);
        
    }
    else
        $('#no_signal').show('slow');
}

$('#Method_static').click(function() {
    if($(this).is(':checked')) 
    {                      
        $('#no_static').hide('slow');
        
        $('#Method_virtual').prop('checked', false);
        $('#Method_abstract').prop('checked', false);
        $('#Method_override').prop('checked', false);
        $('#Method_final').prop('checked', false);
        $('#Method_const').prop('checked', false);
    }
    else
        $('#no_static').show('slow');
});

$('#Method_virtual').click(function() {
    if($(this).is(':checked')) 
    {    
        $('#static').hide('slow');
        $('#Method_static').prop('checked', false);
        $('#abstract').show('slow');
    }
    else
    {
        if( !$('#Method_const').is(':checked') &&
            !$('#Method_override').is(':checked') &&
            !$('#Method_final').is(':checked') ) 
            $('#static').show('slow');
        $('#abstract').hide('slow');
        $('#Method_abstract').prop('checked', false);
    }
});

$('#Method_const').click(function() {
    if($(this).is(':checked')) 
    {                      
        $('#static').hide('slow');
        $('#Method_static').prop('checked', false);
    }
    else
    {
        if( !$('#Method_virtual').is(':checked') &&
            !$('#Method_override').is(':checked') &&
            !$('#Method_final').is(':checked') ) 
            $('#static').show('slow');
    }
});


$('#Method_final').click(function() {
    if($(this).is(':checked')) 
    {                      
        $('#static').hide('slow');
        $('#Method_static').prop('checked', false);
    }
    else
    {
        if( !$('#Method_virtual').is(':checked') &&
            !$('#Method_const').is(':checked') &&
            !$('#Method_override').is(':checked') ) 
            $('#static').show('slow');
    }
});


$('#Method_override').click(function() {
    if($(this).is(':checked')) 
    {                      
        $('#static').hide('slow');
        $('#Method_static').prop('checked', false);
    }
    else
    {
        if( !$('#Method_virtual').is(':checked') &&
            !$('#Method_final').is(':checked') &&
            !$('#Method_const').is(':checked') ) 
            $('#static').show('slow');
    }
});

");

$form=$this->beginWidget('CActiveForm', array(
    'id'=>'method-form',
    'enableAjaxValidation'=>false,
)); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>64)); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>
    
    
    <div class="row">
        <?php echo $form->labelEx($model,'description'); ?>
        <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
        <?php echo $form->error($model,'description'); ?>
    </div>
    
    
    <div class="row">
        <?php echo $form->labelEx($model,'access'); 
            echo $form->dropDownList ($model,'access',
                                      $model->access_drop() );
            echo $form->error($model,'access'); ?>
    </div>

    <div id="no_signal">
        <div class="row">
            <?php echo $form->labelEx($model,'pre'); ?>
            <?php echo $form->textArea($model,'pre',array('rows'=>6, 'cols'=>50)); ?>
            <?php echo $form->error($model,'pre'); ?>
        </div>
    
        <div class="row">
            <?php echo $form->labelEx($model,'post'); ?>
            <?php echo $form->textArea($model,'post',array('rows'=>6, 'cols'=>50)); ?>
            <?php echo $form->error($model,'post'); ?>
        </div>
    
    
        <div class="row">
            <?php echo $form->labelEx($model,'return'); ?>
            <?php echo $form->textField($model,'return',array('size'=>60,'maxlength'=>128)); ?>
            <?php echo $form->error($model,'return'); ?>
        </div>
        
        
    
        <div class="row" id='static' >
            <?php echo $form->labelEx($model,'static'); ?>
            <?php echo $form->checkBox($model,'static'); ?>
            <?php echo $form->error($model,'static'); ?>
        </div>
        
        <div id="no_static">
    
            <div class="row">
                <?php echo $form->labelEx($model,'virtual'); ?>
                <?php echo $form->checkBox($model,'virtual'); ?>
                <?php echo $form->error($model,'virtual'); ?>
            </div>
            
            <div class="row" id='abstract' style='display:none'>
                <?php echo $form->labelEx($model,'abstract'); ?>
                <?php echo $form->checkBox($model,'abstract'); ?>
                <?php echo $form->error($model,'abstract'); ?>
            </div>
        
            <div class="row">
                <?php echo $form->labelEx($model,'override'); ?>
                <?php echo $form->checkBox($model,'override'); ?>
                <?php echo $form->error($model,'override'); ?>
            </div>
        
            <div class="row">
                <?php echo $form->labelEx($model,'final'); ?>
                <?php echo $form->checkBox($model,'final'); ?>
                <?php echo $form->error($model,'final'); ?>
            </div>
        
            <div class="row">
                <?php echo $form->labelEx($model,'const'); ?>
                <?php echo $form->checkBox($model,'const'); ?>
                <?php echo $form->error($model,'const'); ?>
            </div>
            
        </div>
    
        <div class="row">
            <?php echo $form->labelEx($model,'nothrow'); ?>
            <?php echo $form->checkBox($model,'nothrow'); ?>
            <?php echo $form->error($model,'nothrow'); ?>
        </div>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->