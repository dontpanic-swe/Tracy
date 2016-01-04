<?php
/* @var $this PackageController */
/* @var $model Package */

$this->breadcrumbs=array(
    'Packages'=>array('index'),
    $model->name,
);

$this->menu=array(
    array('label'=>'Create Package', 'url'=>array('create')),
    array('label'=>'Update Package', 'url'=>array('update', 'id'=>$model->id_package)),
    array('label'=>'Delete Package', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_package),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage Package', 'url'=>array('index')),
    array('label'=>'Check spelling', 'url'=>array('spellceck/package',
                                                  'id'=>$model->id_package)),
);


echo "<h1>View Package ";
//print_r($model->parent_array());
foreach ( $model->parent_array() as $n )
    echo CHtml::link($n->name,array('view','id'=>$n->id_package))."::";
echo $model->name . " </h1>";



$this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'id_package',
        'name',
        'parent',
        'description',
        'virtual',
         array(
            'label'=>'Accoppiamento Afferente',
            'type'=>'raw',
            'value'=>$model->afferente(),
        ),
         array(
            'label'=>'Accoppiamento Efferente',
            'type'=>'raw',
            'value'=>$model->efferente(),
        ),
    ),
));

echo "<h2>Children</h2>";
$model->with('packages');

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=> new CActiveDataProvider(
            'Package',
            array (
                'criteria'=> array (
                    'condition' => 'parent = :id',
                    'params'=>array(':id'=>$model->id_package),
                ),
                'pagination'=>false
            )
        ),
    'columns'=>array(
        array(
            'class'=>'CDataColumn',
            'header'=>'Name',
            'type'=>'html',
            'value'=>'CHtml::link($data->name,array("package/view","id"=>$data->id_package))',
        ),
        'description',
    ),
));
echo '<div class="append">';
echo CHtml::link("Add child", array("package/create","parent"=>$model->id_package));
echo '</div>';

echo "<h2>Classes</h2>";
$model->with('classes');

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=> new CActiveDataProvider(
            'Class_Prog',
            array (
                'criteria'=> array (
                    'condition' => 'id_package = :id',
                    'params'=>array(':id'=>$model->id_package),
                ),
                'pagination'=>false
            )
        ),
    'columns'=>Class_Prog::grid_columns(),
));

echo '<div class="append">';
echo CHtml::link("Add class", array("class/create","package"=>$model->id_package));
echo '</div>';


echo '<h2>Relations with other packages</h2><ul>';

$adp = new CArrayDataProvider($model->get_dependencies());
$adp->keyField = false;
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$adp,
    'columns'=>array(
        array(
            'class'=>'CDataColumn',
            'header'=>'From',
            'type'=>'html',
            'value'=>'$data["id_from"] == '.$model->id_package.'?
                     $data["name_from"] :
                     CHtml::link($data["name_from"],array("package/view",
                                    "id"=>$data["id_from"]))',
        ),
        array(
            'class'=>'CDataColumn',
            'header'=>'To',
            'type'=>'html',
            'value'=>'$data["id_to"] == '.$model->id_package.'?
                     $data["name_to"] :
                     CHtml::link($data["name_to"],array("package/view",
                                    "id"=>$data["id_to"]))',
        ),
        'type'
    )
));

