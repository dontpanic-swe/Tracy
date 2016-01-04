<?php

$this->breadcrumbs = array(
    'Spell check' => array('index'),
    'Packages' => array('package'),
    $package->full_name()
);

$this->menu=array(
    array('label'=>'View Package', 'url'=>array('package/view',
                                              'id'=>$package->id_package)),
    array('label'=>'Edit Package', 'url'=>array('package/update',
                                              'id'=>$package->id_package)),
);


echo "<h2>".CHtml::link($package->full_name(),
             array('package/view','id'=>$package->id_package))
    ."</h2>";
$desc_errors = SpellCheckController::check($package->description);

if ( !empty($desc_errors) )
{
   $this->error_list($desc_errors,$package,"Description");
}
else
    echo 'No errors';
    
$package->with('classes');
foreach($package->classes as $class)
{
    $this->renderPartial('class',array('class'=>$class));
}

$package->with('packages');
foreach($package->packages as $package)
{
    $this->renderPartial('package',array('package'=>$package));
}