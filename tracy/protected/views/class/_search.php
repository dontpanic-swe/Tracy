<?php
/* @var $this ClassController */
/* @var $model Class_Prog */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

    <div class="row">
        <?php echo $form->label($model,'id_class'); ?>
        <?php echo $form->textField($model,'id_class'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>64)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'id_package'); ?>
        <?php echo $form->textField($model,'id_package'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'description'); ?>
        <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'usage'); ?>
        <?php echo $form->textArea($model,'usage',array('rows'=>6, 'cols'=>50)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'qobject'); ?>
        <?php echo $form->checkBox($model,'qobject'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'include'); ?>
        <?php echo $form->textArea($model,'include',array('rows'=>6, 'cols'=>50)); ?>
    </div>
    
    <div class="row">
        <?php
            echo $form->labelEx($model,'type'); 
            echo $form->dropDownList ($model,'type',
                                      array('class','abstract','interface') );
            echo $form->error($model,'type');
        ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->