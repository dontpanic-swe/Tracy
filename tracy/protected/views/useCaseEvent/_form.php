<?php
/* @var $this UseCaseEventController */
/* @var $model UseCaseEvent */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'use-case-event-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'category'); ?>
		<?php echo $form->textField($model,'category'); ?>
		<?php echo $form->error($model,'category'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'use_case'); ?>
		<?php echo $form->textField($model,'use_case'); ?>
		<?php echo $form->error($model,'use_case'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

    
	<div class="row">
        <?php
        
            $rtid = '';
            $rtdesc = '';
            $model->with('refersTo');
            if ( isset($model->refersTo) )
            {
                $rtid = $model->refersTo->id_use_case;
                $rtdesc = $model->refersTo->description;
            }
            
            echo $form->labelEx($model,'refers_to');
            echo $form->hiddenField($model,'refers_to',
                                    array('id'=>'actual_refers_to',
                                          'value'=>$rtid));
            $this->widget('zii.widgets.jui.CJuiAutoComplete',
                array(
                    'name'=>'refers_to_autocomplete',
                    'sourceUrl'=>array('useCase/parentcompletion'),
                    'options'=> array (
                        'select'=>"js:function(event, ui) {
                                    $('#actual_refers_to').val(ui.item.id);
                                }",
                    ),
                    'value'=>$rtdesc,
                ) );
            echo $form->error($model,'refers_to');
        ?> 
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'primary_actor'); ?>
		<?php echo $form->dropDownList($model,'primary_actor', CHtml::listData(Actor::model()->findAll(), 'id_actor', 'description')); ?>
		<?php echo $form->error($model,'primary_actor'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'order'); ?>
		<?php echo $form->textField($model,'order'); ?>
		<?php echo $form->error($model,'order'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->