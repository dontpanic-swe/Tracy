<?php
/* @var $this ClassController */
/* @var $data Class_Prog */
?>

<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('id_class')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->id_class), array('view', 'id'=>$data->id_class)); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
    <?php echo CHtml::encode($data->name); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('id_package')); ?>:</b>
    <?php echo CHtml::encode($data->id_package); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
    <?php echo CHtml::encode($data->description); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('usage')); ?>:</b>
    <?php echo CHtml::encode($data->usage); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('qobject')); ?>:</b>
    <?php echo CHtml::encode($data->qobject); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('include')); ?>:</b>
    <?php echo CHtml::encode($data->include); ?>
    <br />


</div>