<?php
/* @var $this ActorController */
/* @var $data Actor */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_actor')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_actor), array('view', 'id'=>$data->id_actor)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parent')); ?>:</b>
	<?php echo CHtml::encode($data->parent); ?>
	<br />


</div>