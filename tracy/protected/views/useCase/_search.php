<?php
/* @var $this UseCaseController */
/* @var $model UseCase */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

    <div class="row">
        <?php echo $form->label($model,'id_use_case'); ?>
        <?php echo $form->textField($model,'id_use_case'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'parent'); ?>
        <?php echo $form->textField($model,'parent'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'description'); ?>
        <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'pre'); ?>
        <?php echo $form->textArea($model,'pre',array('rows'=>6, 'cols'=>50)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'post'); ?>
        <?php echo $form->textArea($model,'post',array('rows'=>6, 'cols'=>50)); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->