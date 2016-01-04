<?php
/* @var $this TestController */
/* @var $model Test */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'test-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">-
        <?php echo $form->labelEx($model,'status'); 
              echo $form->dropDownList ($model,'status',
                                        $model->status_drop() );
              echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'jenkins_id'); ?>
		<?php echo $form->textField($model,'jenkins_id',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'jenkins_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>
    
    <?php
        if (isset($model->id_test) )
        {
            $lname = strtolower($model->test_type());
            $this->renderPartial($lname."_form",array('model'=>$model->$lname,
                                                      'form'=>$form));
        }
        else
        {
            $selected_type = ' ';
            if ( isset($special) )
                $selected_type = $special->test_type();
            $types = array('System','Unit','Integration','Validation');
            $list = array_combine($types,$types);
            $list[' ']=' ';
            echo CHtml::dropDownList('test_type',$selected_type,$list,
                                     array('id'=>'test_type')
                                );
            foreach($types as $type)
            {
                echo "<div id='type_$type' ";
                if ( $selected_type != $type )
                {
                    $classname = $type."Test";
                    $sub_model = new $classname;
                    echo "style='display:none;' ";
                }
                else if ( isset($special) )
                {
                    $sub_model = $special;
                }
                echo ">";
                $lname = strtolower($sub_model->test_type());
                $this->renderPartial($lname."_form",array('model'=>$sub_model,
                                                              'form'=>$form));
                echo "</div>";
            }
            
            Yii::app()->clientScript->registerScript('test_type', "
$('#test_type').bind('change',update_test_type);

function update_test_type()
{
    
    if ($('#test_type').val()=='System')
    {
        $('#type_Unit').hide('slow');
        $('#type_Validation').hide('slow');
        $('#type_Integration').hide('slow');
        $('#type_System').show('slow');
    }
    else if ($('#test_type').val()=='Integration')
    {
        $('#type_Unit').hide('slow');
        $('#type_Validation').hide('slow');
        $('#type_Integration').show('slow');
        $('#type_System').hide('slow');
    }
    else if ($('#test_type').val()=='Validation')
    {
        $('#type_Unit').hide('slow');
        $('#type_Validation').show('slow');
        $('#type_Integration').hide('slow');
        $('#type_System').hide('slow');
    }
    else if ($('#test_type').val()=='Unit')
    {
        $('#type_Unit').show('slow');
        $('#type_Validation').hide('slow');
        $('#type_Integration').hide('slow');
        $('#type_System').hide('slow');
    }
    
}");
        }

$this->endWidget(); ?>

</div><!-- form -->