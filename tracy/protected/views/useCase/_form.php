 <?php
/* @var $this UseCaseController */
/* @var $model UseCase */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'use-case-form',
    'enableAjaxValidation'=>false,
)); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'parent'); ?>
        <?php $this->widget('zii.widgets.jui.CJuiAutoComplete',
                array(
		    'name' => 'parent_autocomplete',
                    'model'=>$model,
                    'attribute'=>'parent',
                    'sourceUrl'=>array('useCase/parentcompletion'),
                    'options'=>array(
		      'minLength'=>'2',
		      'select'=>'js:function( event, ui ) {
			 $(this).val(ui.item.id);
			return false;
		      }',
		    ),
                ) ); ?>
        <?php echo $form->error($model,'parent'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'title'); ?>
        <?php echo $form->textField($model,'title'); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'description'); ?>
        <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
        <?php echo $form->error($model,'description'); ?>
    </div>

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

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->  