<?php
/* @var $this AttributeController */
/* @var $model Attribute */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'attribute-form',
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
        <?php echo $form->labelEx($model,'type'); ?>
        <?php echo $form->textField($model,'type',array('size'=>60,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'type'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'const'); ?>
        <?php echo $form->checkBox($model,'const'); ?>
        <?php echo $form->error($model,'const'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'static'); ?>
        <?php echo $form->checkBox($model,'static'); ?>
        <?php echo $form->error($model,'static'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'access'); 
            echo $form->dropDownList ($model,'access',
                                      $model->access_drop() );
            echo $form->error($model,'access'); ?>
    </div>
    
    
    <div class="row">
        <?php echo $form->labelEx($model,'description'); ?>
        <?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'description'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'getter'); ?>
        <?php echo $form->checkBox($model,'getter'); ?>
        <?php echo $form->error($model,'getter'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'setter'); ?>
        <?php echo $form->checkBox($model,'setter'); ?>
        <?php echo $form->error($model,'setter'); ?>
    </div>


    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->