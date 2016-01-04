<?php
/* @var $this RequirementController */
/* @var $model Requirement */
/* @var $form CActiveForm */
?>

<div class="form">

<?php

Yii::app()->clientScript->registerScript('remove_source', "

function rmitem(element)
{
    element = document.getElementById(element);
    
    var list_input = document.getElementById('sourcelist');
    
    var oldlist = JSON.parse(list_input.value);
    
    var numid = element.id.match(/[0-9]+/);
    
    for ( var i = 0; i < oldlist.length; i++ )
    {
        if ( oldlist[i] == numid )
        {
            oldlist.splice(i,1);
            i--;
        }
    }
    list_input.value = JSON.stringify(oldlist);
    
    element.parentNode.removeChild(element);
}",
CClientScript::POS_BEGIN );

Yii::app()->clientScript->registerScript('insert_source', "

var currentid = -1;

$('#addsource').click(function(){

    if ( currentid != -1 )
    {
        var list_input = $('#sourcelist');
        var desc_container = $('#source_desc_list');
        var autocomplete = $('#source_autocomplete');
        
        var oldlist = JSON.parse(list_input.val());
        oldlist.push(currentid);
        list_input.val ( JSON.stringify(oldlist) );
        
        var itemid = 'source_item_'+currentid;
        desc_container.append(
            '<li id=\"'+itemid+'\">'+
                autocomplete.val()+' ('+currentid+')'+
                '<input type=\"button\" value=\"Remove\" onClick=\"rmitem('+itemid+');\" />'+
            '</li>');
        
        autocomplete.val('');
        
        currentid = -1;
    }
    return false;
});


");


$form=$this->beginWidget('CActiveForm', array(
	'id'=>'requirement-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
        <?php
            echo $form->labelEx($model,'category'); 
            echo $form->dropDownList($model,'category',
                    CHtml::listData(RequirementCategory::model()->findAll(),
                                    'id_category','name'));
            echo $form->error($model,'category');
        ?>
	</div>

	<div class="row">
        <?php
            echo $form->labelEx($model,'priority'); 
            echo $form->dropDownList($model,'priority',
                    CHtml::listData(RequirementPriority::model()->findAll(),
                                    'id_priority','name'));
            echo $form->error($model,'priority');
        ?> 
	</div>

	<div class="row">
        <?php
            echo $form->labelEx($model,'apported'); 
            echo $form->checkBox($model,'apported');
            echo $form->error($model,'apported');
        ?> 
	</div>

	<div class="row">
        <?php
        
            $pid = '';
            $pdesc = '';
            if ( isset($model->parent0) )
            {
                $pid = $model->parent0->id_requirement;
                $pdesc = $model->parent0->description;
            }
            
            echo $form->labelEx($model,'parent');
            echo $form->hiddenField($model,'parent',array('id'=>'actual_parent',
                                                          'value'=>$pid));
            $this->widget('zii.widgets.jui.CJuiAutoComplete',
                array(
                    'name'=>'parent_autocomplete',
                    'sourceUrl'=>array('requirement/parentcompletion'),
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
        <?php
            echo $form->labelEx($model,'sources');
            
            $js = '[]';
            $model->with('sources');
            if ( $model->sources_json != "" )
                $js = $model->sources_json;
            else if ( isset($model->sources) )
            {
                $arr = array();
                foreach($model->sources as $r )
                    array_push($arr,$r->id_source);
                $js = json_encode($arr);
            }
            
            echo $form->hiddenField($model,'sources_json',array('id'=>'sourcelist','value'=>$js));
            echo '<ul id="source_desc_list" style="white-space:pre;">';
            if ( isset($model->sources) )
            {
                foreach ( $model->sources as $source )
                {
                    $source = $source->with('useCase','externalSource');
                    $sid = $source->id_source;
                    $itemid = "source_item_$sid";
                    echo "<li id='$itemid'>".
                            $source->description() . "($sid)".
                            "<input type='button' value='Remove' onClick='rmitem(\"$itemid\");' />" .
                        "</li>";
                }
            }
            echo '</ul>';
            $this->widget('zii.widgets.jui.CJuiAutoComplete',
                array(
                    'name'=>'source_autocomplete',
                    'sourceUrl'=>array('requirement/sourcecompletion'),
                    'id'=>'source_autocomplete',
                    'options'=> array (
                        'select'=>"js:function(event, ui) {
                                    $('#source_autocomplete').val(ui.item.value);
                                    currentid = ui.item.id;
                                }",
                    )
                ) );
            echo $form->error($model,'sources');
            echo CHtml::button('Add',array('id'=>'addsource'));
        ?> 
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
    
    
	<div class="row">
        <?php
            echo $form->labelEx($model,'validation'); 
            echo $form->dropDownList($model,'validation',
                    CHtml::listData(RequirementValidation::model()->findAll(),
                                    'id_validation','name'));
            echo $form->error($model,'validation');
        ?> 
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->