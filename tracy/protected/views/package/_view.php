<?php
/* @var $this PackageController */
/* @var $data Package */
?>

<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('id_package')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->id_package), array('view', 'id'=>$data->id_package)); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
    <?php echo CHtml::encode($data->name); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
    <p><? echo CHtml::encode($data->description); ?></p>
    


</div>