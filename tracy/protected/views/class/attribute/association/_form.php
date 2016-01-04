<?php
/* @var $this AssociationController */
/* @var $model Association */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'association-form',
    'enableAjaxValidation'=>false,
)); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'aggregation_from'); 
            echo $form->dropDownList ($model,'aggregation_from',
                                      $model->aggreg_drop() );
            echo $form->error($model,'aggregation_from'); ?>
    </div>

    
    <div class="row">
    <?php
        $model->with('classFrom');
        $from_id = $model->class_from;
        $from_desc = $model->classFrom->name;
        
        echo $form->labelEx($model,'class_from');
        echo $form->hiddenField($model,'class_from',array('id'=>'actual_from',
                                                          'value'=>$from_id));
        $this->widget('zii.widgets.jui.CJuiAutoComplete',
            array(
                'name'=>'from_autocomplete',
                'sourceUrl'=>array('class/parentcompletion'),
                'options'=> array (
                    'select'=>"js:function(event, ui) {
                                $('#actual_from').val(ui.item.id);
                            }",
                ),
                'value'=>$from_desc,
            ) );

        echo $form->error($model,'class_from'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'aggregation_to'); 
            echo $form->dropDownList ($model,'aggregation_to',
                                      $model->aggreg_drop() );
            echo $form->error($model,'aggregation_to'); ?>
    </div>

    <div class="row">
    <?php
        $model->with('classTo');
        $to_id = $model->class_to;
        $to_desc = isset($model->classTo) ? $model->classTo->name : '';
        
        echo $form->labelEx($model,'class_to');
        echo $form->hiddenField($model,'class_to',array('id'=>'actual_to',
                                                          'value'=>$to_id));
        $this->widget('zii.widgets.jui.CJuiAutoComplete',
            array(
                'name'=>'to_autocomplete',
                'sourceUrl'=>array('class/parentcompletion'),
                'options'=> array (
                    'select'=>"js:function(event, ui) {
                                $('#actual_to').val(ui.item.id);
                            }",
                ),
                'value'=>$to_desc,
            ) );

        echo $form->error($model,'class_to'); ?>
    </div>
    
    
    <div class="row">
        <?php echo $form->labelEx($model,'multiplicity'); 
            echo $form->dropDownList ($model,'multiplicity',
                                      $model->multiplicity_drop() );
            echo $form->error($model,'multiplicity'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->