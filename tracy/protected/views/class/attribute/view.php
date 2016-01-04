<?php
/* @var $this AttributeController */
/* @var $model Attribute */

$this->breadcrumbs=array(
    'Classes'=>array('class/index'),
    $class->name=>array('class/view','id'=>$class->id_class),
    $model->name,
);

$this->menu=array(
    array('label'=>'View Class', 'url'=>array('view','id'=>$class->id_class)),
    array('label'=>'Associations', 'url'=>array('associations')),
    array('label'=>'Create Attribute', 'url'=>array('attributeCreate',
                                                    'class'=>$class->id_class)),
    array('label'=>'Update Attribute', 'url'=>array('attributeUpdate',
                                                    'id'=>$model->id_attribute)),
    array('label'=>'Delete Attribute', 'url'=>'#',
          'linkOptions'=>array('submit'=>array('attributeDelete',
                                               'id'=>$model->id_attribute),
                        'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Create Method', 'url'=>array('methodCreate',
                                                    'class'=>$class->id_class)),
    array('label'=>'Check spelling', 'url'=>array('spellceck/attribute',
                                                  'id'=>$model->id_attribute)),
);


echo '<h1>View Attribute ';

$class->with('package');
foreach ( $class->package->parent_array(true) as $n )
    echo CHtml::link($n->name,array('package/view','id'=>$n->id_package))." :: ";
    
echo CHtml::link( $class->name, array('class/view','id'=>$class->id_class) );
echo " :: ".$model->name."</h1>";


$this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        //'id_attribute',
        'name',
        'type',
        'const',
        'static',
        'access',
        //'id_class',
        'description',
        'getter','setter'
    ),
));


echo '<h2>Association</h2>';
$model->with('association');
if ( isset($model->association) )
{
    $ass = $model->association->with('classFrom','classTo');
    $this->widget('zii.widgets.CDetailView', array(
        'data'=>$ass,
        'attributes'=>array(
            array(
                'label'=>'Class From',
                'type'=>'html',
                'value'=>CHtml::link(CHtml::encode($ass->classFrom->name),
                                 array('class/view',
                                       'id'=>$ass->classFrom->id_class)),
            ),
            'aggregation_from',
            array(
                'label'=>'Class To',
                'type'=>'html',
                'value'=>CHtml::link(CHtml::encode($ass->classTo->name),
                                 array('class/view',
                                       'id'=>$ass->classTo->id_class)),
            ),
            'aggregation_to',
        'multiplicity',
        ),
    ));
    
    echo CHtml::link('Remove association',array('assocdelete',
                                        'id'=>$ass->id_association));
    echo ' - ';
    echo CHtml::link('Edit association',array('assocupdate',
                                        'id'=>$ass->id_association));
}
else
{
    echo '<p>This attribute is not an association</p>';
    
    echo CHtml::link('Create association',array('assoccreate',
                                        'attribute'=>$model->id_attribute),
                        array(
                            'style'=>'font-size:10em; background-color:yellow;
                            color:red; text-decoration:blink;',
                    )
                );
    
}

echo CodeGen::render_code(
"class $class->name {
    $model->access:
        ".$model->cpp_doc()."
        ".$model->cpp_decl()."
};
");