<?php echo $form->errorSummary($model); ?>

<div class="row">
<?php
    echo $form->labelEx($model,'id_method');
    $val = '';
    if ( isset($model->id_method) )
    {
        $val = Method::model()->findByPk($model->id_method)->signature_name();
    }
    echo $this->autoComplete('UnitTest[id_method]','class/methodComplete',
                             $model->id_method, $val );
    echo $form->error($model,'id_method');
   ?>
</div>