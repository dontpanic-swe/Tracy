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

<!-- DropDown to be copied -->
<div style='display:none'>
<?php echo $form->dropDownList(Actor::model(),'id_actor', CHtml::listData(Actor::model()->findAll(), 'id_actor', 'description')); ?>
</div>
<!-- end DropDown to be copied-->

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
        <?php echo $form->labelEx($model,'description'); ?>
        <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
        <?php echo $form->error($model,'description'); ?>
    </div>
    
    <!-- Events here -->
        
    <div class="row">
        <div id='events'>
	   <p style='margin-bottom:10px'><a href='#' id='newEvent'>Create new Event</a></p>
        </div>
        
        <?php
        
        $model->with('useCaseEvents');
        
        //Main Event Flow Here
        
        echo "<div id='event0' name='0' style='margin-bottom:10px;'>
        <div style='border: 1px solid lightgrey;' name='0'><p>Event Flow 0</p>
        <select name='baseEvent[0]'>
		<option value='1'>Principale</option>
		<option value='2'>Alternativo</option>
	</select>
	<button class='newEventButton'>New Action in Event</button>";
	
	$tCount1 = 0;
        foreach ($model->useCaseEvents as $obj) {
	  if ($obj->category == 1) {
	    $obj->with('primaryActor');
	    echo "<div id='[0][{$obj->order}]' style='border: 1px solid lightgrey;'>
	    <label>Order n.</label><input type='text' value='{$obj->order}' name='event[0][{$obj->order}][order]'>
	    <label>Description</label><input type='text' name='event[0][0][description]' value='{$obj->description}'>
	    <label>Refers to</label><input type='text' name='event[0][{$obj->order}][refers_to]' value='{$obj->refers_to}'>
	    <label>Actor</label>";
	    
	    //TODO Get this working
	    //echo $form->dropDownList($obj,'primaryActor.id_actor', CHtml::listData(Actor::model()->findAll(), 'id_actor', 'description'));
	    
	    echo "<button class='deleteAction'>Delete</button>
	    </div>";
	    
	    $tCount1++;
	  }
        }
        
        echo "</div></div>";
        
        //Alternative Event Flows Here
        
        echo "<div id='event1' name='1' style='margin-bottom:10px;'>
        <div style='border: 1px solid lightgrey;' name='1'><p>Event Flow 1</p>
        <select name='baseEvent[1]'>
		<option value='1'>Principale</option>
		<option value='2' selected='selected'>Alternativo</option>
	</select>
	<button class='newEventButton'>New Action in Event</button>";
	
	$tCount2 = 0;
        foreach ($model->useCaseEvents as $obj) {
	  if ($obj->category == 2) {
	    $obj->with('primaryActor');
	    echo "<div id='[0][{$obj->order}]' style='border: 1px solid lightgrey;'>
	    <label>Order n.</label><input type='text' value='{$obj->order}' name='event[0][{$obj->order}][order]'>
	    <label>Description</label><input type='text' name='event[0][0][description]' value='{$obj->description}'>
	    <label>Refers to</label><input type='text' name='event[0][{$obj->order}][refers_to]' value='{$obj->refers_to}'>
	    <label>Actor</label>";
	    
	    //TODO Get this working
	    //echo $form->dropDownList($obj,'primaryActor.id_actor', CHtml::listData(Actor::model()->findAll(), 'id_actor', 'description'));
	    
	    echo "<button class='deleteAction'>Delete</button>
	    </div>";
	    
	    $tCount2++;
	  }
        }
        
        echo "</div></div>";
  	
  	?>
        
    </div>
    
    
    <!-- End Events -->

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

<?php
Yii::app()->clientScript->registerScript('add_event_actions', "

eventNumber = 1;
eventActions = new Array('$tCount1','$tCount2');

//TODO = issues when adding actions - spawns actor_select quite everywhere
//	 'delete' button
//	 'baseEvent' selector is hardcoded -- noob
//	 

$('a#newEvent').click(function(event) {
  event.preventDefault();
  var form = '<div id=\"event' + eventNumber.toString() + '\" name=\"' + eventNumber.toString() + '\" style=\"margin-bottom:10px;\">' +
	'<div style=\"border: 1px solid lightgrey;\" name=\"' + eventNumber.toString() + '\"><p>Event Flow ' + eventNumber.toString() + '</p>' +
  	'<select name=\"baseEvent[' + eventNumber.toString() + ']\">' +
  		'<option value=\"1\">Principale</option>' +
  		'<option value=\"2\">Alternativo</option>' +
  	'</select>' +
  	'<button class=\"newEventButton\">New Action in Event</button>' +
  	'</div>' +
  	'</div>';
  $('div#events').append(form);
  eventActions[eventNumber] = -1;
  newEventButtonBinder(eventNumber);
  eventNumber++;
});

function newEventButtonBinder(eventNum) {
	$('.newEventButton').click(function(event) {
		event.preventDefault();
		var thisNum = eventActions[eventNum]+1;
		eventActions[eventNum]++;
		var actorDropdown = $('#Actor_id_actor').clone();
  		actorDropdown.attr('id','actor[' + eventNum + '][' + thisNum + ']');
  		actorDropdown.attr('name','event[' + eventNum + '][' + thisNum + '][actor]');
  		var topNum = $(this).parent('div').attr('name');
  		var form = '<div style=\"border: 1px solid lightgrey;\" id=\"[' + eventNum + '][' + thisNum + ']\">' +
		    '<label>Order n.</label><input type=\"text\" name=\"event[' + eventNum.toString() + '][' + thisNum.toString() + '][order]\" value=\"' + thisNum.toString() + '\"/>' +
		    '<label>Description</label><input type=\"text\" name=\"event[' + eventNum.toString() + '][' + thisNum.toString() + '][description]\" />' +
		    '<label>Refers to</label><input type=\"text\" name=\"event[' + eventNum.toString() + '][' + thisNum.toString() + '][refers_to]\" class=\"related_uc_autocomplete\"/>' +
		  '</div>';
  		$(this).parent('div').append(form);
  		var newForm = $(this).parent('div').children(\"div#[' + eventNum + '][' + thisNum + ']\");
  		newForm.append('<label>Actor</label>');
		newForm.append(actorDropdown);
		newForm.append('<button class=\"deleteAction\">Delete</button>');
		bindActorAutocomplete();
  	});
}

function bindActorAutocomplete() {
$('.related_uc_autocomplete').autocomplete({'minLength':'2','select':function( event, ui ) {
$(this).val(ui.item.id);
return false;
},'source':'/requisiti/useCase/parentcompletion'});
}

$('.newEventButton').each(function() {
  var num = $(this).parent('div').attr('name').;
  newEventButtonBinder(num);
});

",
CClientScript::POS_READY );

?>

</div><!-- form -->

