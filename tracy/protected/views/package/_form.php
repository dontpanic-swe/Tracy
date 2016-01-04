<?php
/* @var $this PackageController */
/* @var $model Package */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'package-form',
    'enableAjaxValidation'=>false,
)); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>


    <div class="row">
        <?php
        
            $pid = '';
            $pdesc = '';
            if ( isset($model->parent0) )
            {
                $pid = $model->parent0->id_package;
                $pdesc = $model->parent0->name;
            }
            
            echo $form->labelEx($model,'parent');
            echo $form->hiddenField($model,'parent',array('id'=>'actual_parent',
                                                          'value'=>$pid));
            $this->widget('zii.widgets.jui.CJuiAutoComplete',
                array(
                    'name'=>'parent_autocomplete',
                    'sourceUrl'=>array('package/parentcompletion'),
                    'options'=> array (
                        'select'=>"js:function(event, ui) {
                                    $('#actual_parent').val(ui.item.id);
                                }",
                    ),
                    'value'=>$pdesc,
                ) );
            echo $form->error($model,'parent');
        ?> 
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('size'=>32,'maxlength'=>32)); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>
    
    
	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
    
    
	<div class="row">
		<?php echo $form->labelEx($model,'virtual'); ?>
		<?php echo $form->checkBox($model,'virtual'); ?>
		<?php echo $form->error($model,'virtual'); ?>
	</div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->