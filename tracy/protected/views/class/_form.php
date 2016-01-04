<?php
/* @var $this ClassController */
/* @var $model Class_Prog */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'class-prog-form',
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
         <?php
        
            $pid = '';
            $pdesc = '';
            $model->with('package');
            if ( isset($model->package) )
            {
                $pid = $model->package->id_package;
                $pdesc = $model->package->name;
            }
            
            echo $form->labelEx($model,'id_package');
            echo $form->hiddenField($model,'id_package',array('id'=>'actual_parent',
                                                          'value'=>$pid));
            $this->widget('zii.widgets.jui.CJuiAutoComplete',
                array(
                    'name'=>'id_package_autocomplete',
                    'sourceUrl'=>array('package/parentcompletion'),
                    'options'=> array (
                        'select'=>"js:function(event, ui) {
                                    $('#actual_parent').val(ui.item.id);
                                }",
                    ),
                    'value'=>$pdesc,
                ) );
            echo $form->error($model,'id_package');
        ?>
    </div>
    
    
    <div class="row">
        <?php echo $form->labelEx($model,'library'); ?>
        <?php echo $form->checkBox ($model,'library'); ?>
        <?php echo $form->error($model,'library'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'qobject'); ?>
        <?php echo $form->checkBox ($model,'qobject'); ?>
        <?php echo $form->error($model,'qobject'); ?>
    </div>
    
    
    <div class="row">
        <?php
            echo $form->labelEx($model,'type'); 
            echo $form->dropDownList ($model,'type',
                                      $model->type_drop() );
            echo $form->error($model,'type');
        ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'description'); ?>
        <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
        <?php echo $form->error($model,'description'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'usage'); ?>
        <?php echo $form->textArea($model,'usage',array('rows'=>6, 'cols'=>50)); ?>
        <?php echo $form->error($model,'usage'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'include'); ?>
        <?php echo $form->textArea($model,'include',array('rows'=>6, 'cols'=>50)); ?>
        <?php echo $form->error($model,'include'); ?>
    </div>
    
    
    <div class="row">
        <?php echo $form->labelEx($model,'extra_declaration'); ?>
        <?php echo $form->textArea($model,'extra_declaration',array('rows'=>6, 'cols'=>50)); ?>
        <?php echo $form->error($model,'extra_declaration'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->