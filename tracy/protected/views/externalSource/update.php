 <?php
/* @var $this ExternalSourceController */
/* @var $model ExternalSource */

// if ( $model->getRelated('externalSource') !== null )
//     {
//       $this-redirect(array('/externalSource/update', 'id'=>$model->getRelated('externalSource')->id_source));
//     }
//     else if ( $model->getRelated('useCase') !== null )
//     {
//       $this-redirect(array('/useCase/update', 'id'=>$model->getRelated('useCase')->id_use_case));
//     }

$this->breadcrumbs=array(
    'External Sources'=>array('index'),
    $model->id_source=>array('view','id'=>$model->id_source),
    'Update',
);

$this->menu=array(
    array('label'=>'List Sources', 'url'=>array('source/index')),
    array('label'=>'View External Source', 'url'=>array('view', 'id'=>$model->id_source)),
    array('label'=>'Create ExternalSource', 'url'=>array('create')),
    array('label'=>'Create Use Case', 'url'=>array('useCase/create')),
);

?>

<h1>Update External Source <?php echo $model->id_source; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>  
