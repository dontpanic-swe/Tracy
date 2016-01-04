<?php
/* @var $this ClassController */
/* @var $model Method */

$this->breadcrumbs=array(
    'Classes'=>array('class/index'),
    $class->name=>array('class/view','id'=>$class->id_class),
    $model->name,
);

$this->menu=array(
    array('label'=>'View Class', 'url'=>array('view','id'=>$class->id_class)),
    array('label'=>'Create Method', 'url'=>array('methodCreate',
                                                    'class'=>$class->id_class)),
    array('label'=>'Create Attribute', 'url'=>array('attributeCreate',
                                                    'class'=>$class->id_class)),
    array('label'=>'Update Method', 'url'=>array('methodUpdate',
                                                    'id'=>$model->id_method)),
    array('label'=>'Delete Method', 'url'=>'#',
          'linkOptions'=>array('submit'=>array('methodDelete',
                                               'id'=>$model->id_method),
                        'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>"Add Argument", 'url'=>array("argumentCreate",
                                                 "method"=>$model->id_method)),
    array('label'=>'Check spelling', 'url'=>array('spellceck/method',
                                                  'id'=>$model->id_method)),
    array('label'=>'Unit Test', 'url'=>array('test/unitMethod',
                                                  'method'=>$model->id_method)),
);


echo '<h1>View Method ';

$class->with('package');
foreach ( $class->package->parent_array(true) as $n )
    echo CHtml::link($n->name,array('package/view','id'=>$n->id_package))." :: ";
    
echo CHtml::link( $class->name, array('class/view','id'=>$class->id_class) );
echo " :: ".$model->name."</h1>";

$this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'id_method',
        'name',
        'pre',
        'post',
        'description',
        'return',
        'access',
        'virtual',
        'abstract',
        'override',
        'final',
        'static',
        'const',
        'nothrow',
    ),
));
echo "<h2>Test</h2>";
echo CHtml::link( isset($model->test) ? 'View' : 'Create',
                 array('test/unitMethod','method'=>$model->id_method) );

echo "<h2>Arguments</h2>";

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=> new CActiveDataProvider(
            'Argument',
            array (
                'criteria'=> array (
                    'condition' => 'id_method = :id',
                    'params'=>array(':id'=>$model->id_method),
                    'order'=>'`order`'
                ),
            )
        ),
    'columns'=>array(
        'name',
        'description',
        'type',
        'direction',
        array(
            'class'=>'CButtonColumn',
            'updateButtonUrl'=>'array("argumentupdate", "id"=>$data->id_argument )',
            'deleteButtonUrl'=>'array("argumentremove", "id"=>$data->id_argument )',
            'template'=>'{update}{delete}',
        ),
    ),
));

echo '<div class="append">';
echo CHtml::link("Add Argument", array("argumentCreate","method"=>$model->id_method));
echo '</div>';


$model->with('slots','signals');

$do_slots = $model->access == 'signal';
if ( count($model->slots) > 0 && $model->access != 'signal' )
{
    echo '<div class="errorSummary">This method is not a signal but
        has slots attached to it</div>';
    $do_slots = true;
}
if ( $do_slots )
{
    echo '<h2>Slots</h2>';
    
    
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=> new CArrayDataProvider($model->slots,
                                                array('keyField'=>'id_method')),
        'columns'=>array(
            array(
                'class'=>'CDataColumn',
                'header'=>'Class',
                'type'=>'html',
                'value'=>'CHtml::link($data->with("class")->class->name,
                            array("view","id"=>$data->class->id_class))',
            ),
            array(
                'class'=>'CDataColumn',
                'header'=>'Name',
                'type'=>'html',
                'value'=>'CHtml::link($data->name,array("methodView","id"=>$data->id_method))',
            ),
            'description',
            'access',
            'return',
            'virtual',
            'override',
            'final',
            'const',
            'nothrow',
            'static',
            array(
                'class'=>'CButtonColumn',
                'deleteButtonUrl'=>'array("disconnect",
                        "to" => '.$model->id_method.',
                        "signal" => '.$model->id_method.',
                        "slot" => $data->id_method )',
                'template'=>'{delete}'
            ),
        ),
    ));
    
    echo '<div class="append">';
    echo CHtml::beginForm(array('connect','signal'=>$model->id_method,
                                'to'=>$model->id_method),'get');
    echo CHtml::label('Slot','slot');
    $this->autoComplete('slot','class/methodComplete');
    echo CHtml::submitButton('Connect');
    echo CHtml::endForm();
    echo '</div>';   
}


    echo '<h2>Signals</h2>';
    
    
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=> new CArrayDataProvider($model->signals,
                                                array('keyField'=>'id_method')),
        'columns'=>array(
            array(
                'class'=>'CDataColumn',
                'header'=>'Class',
                'type'=>'html',
                'value'=>'CHtml::link($data->with("class")->class->name,
                            array("view","id"=>$data->class->id_class))',
            ),
            array(
                'class'=>'CDataColumn',
                'header'=>'Name',
                'type'=>'html',
                'value'=>'CHtml::link($data->name,array("methodView","id"=>$data->id_method))',
            ),
            'description',
            'access',
            'return',
            'virtual',
            'override',
            'final',
            'const',
            'nothrow',
            'static',
            array(
                'class'=>'CButtonColumn',
                'deleteButtonUrl'=>'array("disconnect",
                        "to" => '.$model->id_method.',
                        "slot" => '.$model->id_method.',
                        "signal" => $data->id_method )',
                'template'=>'{delete}'
            ),
        ),
    ));
    echo '<div class="append">';
    echo CHtml::beginForm(array('connect','slot'=>$model->id_method,
                                'to'=>$model->id_method),'get');
    echo CHtml::label('Signal','signal');
    $this->autoComplete('signal',array('class/methodComplete','filter'=>'signal'));
    echo CHtml::submitButton('Connect');
    echo CHtml::endForm();
    echo '</div>';        
        
echo CodeGen::render_code(
"class $class->name {
    $model->access:
        ".$model->cpp_doc()."
        ".$model->cpp_decl()."
};
");
