<?php
/* @var $this TUnitTestController */
/* @var $data TUnitTest */
?>

<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('id_test')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->id_test), array('view', 'id'=>$data->id_test)); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
    <?php echo CHtml::encode($data->description); ?>
    <br />


</div> 
