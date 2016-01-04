<?php
/* @var $this UseCaseEventController */
/* @var $data UseCaseEvent */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_event')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_event), array('view', 'id'=>$data->id_event)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('category')); ?>:</b>
	<?php echo CHtml::encode($data->category); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('use_case')); ?>:</b>
	<?php echo CHtml::encode($data->use_case); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('refers_to')); ?>:</b>
	<?php echo CHtml::encode($data->refers_to); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('primary_actor')); ?>:</b>
	<?php echo CHtml::encode($data->primary_actor); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('order')); ?>:</b>
	<?php echo CHtml::encode($data->order); ?>
	<br />


</div>