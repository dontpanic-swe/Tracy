<?php
/* @var $this ClassController */
/* @var $model Class_Prog */

$this->breadcrumbs=array(
    'Class'=>array('index'),
    $model->name,
);

$this->menu=array(
    array('label'=>'List Classes', 'url'=>array('index')),
    array('label'=>'Associations', 'url'=>array('associations')),
    array('label'=>'View Source', 'url'=>array('generator/decl','id'=>$model->id_class)),
    array('label'=>'Create Class', 'url'=>array('create')),
    array('label'=>'Update Class', 'url'=>array('update', 'id'=>$model->id_class)),
    array('label'=>'Delete Class', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_class),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Create Method', 'url'=>array('methodCreate',
                                                    'class'=>$model->id_class)),
    array('label'=>'Create Attribute', 'url'=>array('attributeCreate',
                                                    'class'=>$model->id_class)),
    array('label'=>'Check spelling', 'url'=>array('spellceck/class',
                                                  'id'=>$model->id_class)),
);


echo "<h1>View Class ";

$model->with('package');
foreach ( $model->package->parent_array(true) as $n )
    echo CHtml::link($n->name,array('package/view','id'=>$n->id_package))." :: ";
    
echo $model->name."</h1>";



$this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'id_class',
        'name',
        'type',
        'description',
        'usage',
        'qobject',
        'library',
        'include',
        'extra_declaration',
    ),
)); 


echo "<h2>Parents</h2>";

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'class-par-grid',
    'dataProvider'=> new CArrayDataProvider ( $model->with('parents')->parents,
                                             array('keyField'=>'id_class') ),
    //'filter'=>$model,
    'columns'=>Class_Prog::grid_columns(array(
        array(
            'class'=>'CButtonColumn',
            'deleteButtonUrl'=>'array("removeInheritance",
                                    "parent"=>$data->id_class,
                                    "child"=>'.$model->id_class.',
                                    "to"=>'.$model->id_class.')',
        ),
    ) )
));

 
$pid = '';
$pdesc = '';

echo '<div class="append">';
echo CHtml::beginForm(array('addParent','id'=>$model->id_class));
echo CHtml::label('Add as parent','parent');
echo CHtml::hiddenField('parent',$pid,array('id'=>'actual_parent'));
$this->widget('zii.widgets.jui.CJuiAutoComplete',
    array(
        'name'=>'id_parent_autocomplete',
        'sourceUrl'=>array('class/parentcompletion'),
        'options'=> array (
            'select'=>"js:function(event, ui) {
                        $('#actual_parent').val(ui.item.id);
                    }",
        ),
        'value'=>$pdesc,
    ) );
echo CHtml::submitButton('Add');
echo CHtml::endForm();
echo '</div>';


echo "<h2>Children</h2>";

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'class-child-grid',
    'dataProvider'=> new CArrayDataProvider ( $model->with('children')->children,
                                             array('keyField'=>'id_class') ),
    //'filter'=>$model,
    'columns'=>Class_Prog::grid_columns(array(
        array(
            'class'=>'CButtonColumn',
            'deleteButtonUrl'=>'array("removeInheritance",
                                    "parent"=>'.$model->id_class.',
                                    "child"=>$data->id_class,
                                    "to"=>'.$model->id_class.' )',
        ),
    ) )
));

 
$cid = '';
$cdesc = '';

echo '<div class="append">';
echo CHtml::beginForm(array('addChild','id'=>$model->id_class));
echo CHtml::label('Add as child','child');
echo CHtml::hiddenField('child',$cid,array('id'=>'actual_child'));
$this->widget('zii.widgets.jui.CJuiAutoComplete',
    array(
        'name'=>'id_child_autocomplete',
        'sourceUrl'=>array('class/parentcompletion'),
        'options'=> array (
            'select'=>"js:function(event, ui) {
                        $('#actual_child').val(ui.item.id);
                    }",
        ),
        'value'=>$cdesc,
    ) );
echo CHtml::submitButton('Add');
echo CHtml::endForm();
echo '</div>';

echo "<h2>Attributes</h2>";

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=> new CActiveDataProvider(
            'Attribute',
            array (
                'criteria'=> array (
                    'condition' => 'id_class = :id',
                    'params'=>array(':id'=>$model->id_class),
                    'order'=>'static, access, name'
                ),
                'pagination'=>false
            )
        ),
    'columns'=>array(
        array(
            'class'=>'CDataColumn',
            'header'=>'Name',
            'type'=>'html',
            'value'=>'CHtml::link($data->name,array("attributeView","id"=>$data->id_attribute))',
        ),
        'type',
        'access',
        'const',
        'static',
        'description',
        array(
            'class'=>'CButtonColumn',
            'updateButtonUrl'=>'array("attributeupdate", "id"=>$data->id_attribute )',
            'viewButtonUrl'=>'array("attributeview", "id"=>$data->id_attribute )',
            'deleteButtonUrl'=>'array("attributedelete", "id"=>$data->id_attribute )',
        ),
    ),
));

echo '<div class="append">';
echo CHtml::link("Add attribute", array("attributeCreate","class"=>$model->id_class));
echo '</div>';


echo "<h2>Associations</h2>";
$arr = Yii::app()->db->createCommand()
        ->select('id_association AS id,
                 aggregation_from, aggregation_to,
                 c_f.name AS class_from, c_f.id_class AS id_from,
                 c_t.name AS class_to, c_t.id_class AS id_to,
                 att.name AS attribute, att.id_attribute as id_att')
        ->from('association a')
        ->join('class c_f','a.class_from = c_f.id_class')
        ->join('class c_t','a.class_to = c_t.id_class')
        ->join('attribute att','a.id_attribute = att.id_attribute')
        ->order('class_from, class_to')
        ->where('a.class_from = :idc or a.class_to = :idc',
                array(':idc'=>$model->id_class));
$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'class-prog-grid',
    'dataProvider'=>new CArrayDataProvider($arr->queryAll(),
                                           array('pagination'=>false) ),
    'columns'=>array(
            array(
                'class'=>'CDataColumn',
                'header'=>'From',
                'type'=>'html',
                'value'=>'$data["class_from"] == '.$model->id_class.' ?
                        $data["class_from"] :
                        CHtml::link($data["class_from"],
                            array("class/view","id"=>$data["id_from"]))',
            ),
            'aggregation_from',
            
            
            array(
                'class'=>'CDataColumn',
                'header'=>'Attribute',
                'type'=>'html',
                'value'=>'CHtml::link($data["attribute"],
                            array("class/attributeView","id"=>$data["id_att"]))',
            ),
            
            'aggregation_to',
            array(
                'class'=>'CDataColumn',
                'header'=>'To',
                'type'=>'html',
                'value'=>'$data["class_to"] == '.$model->id_class.' ?
                        $data["class_to"] :
                        CHtml::link($data["class_to"],
                            array("class/view","id"=>$data["id_to"]))',
            ),
            
            array(
                'class'=>'CButtonColumn',
                'template'=>'{update} {delete}',
                'deleteButtonUrl'=>'array("assocDelete", "id"=>$data["id"] )',
                'updateButtonUrl'=>'array("assocUpdate", "id"=>$data["id"] )',
            ),
        )
    )
);

echo "<h2>Methods</h2>";

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=> new CActiveDataProvider(
            'Method',
            array (
                'criteria'=> array (
                    'condition' => 'id_class = :id',
                    'params'=>array(':id'=>$model->id_class),
                    'order'=>'static, access, name, const, id_method'
                ),
                'pagination'=>false
            )
        ),
    'columns'=>array(
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
            'updateButtonUrl'=>'array("methodupdate", "id"=>$data->id_method )',
            'viewButtonUrl'=>'array("methodview", "id"=>$data->id_method )',
            'deleteButtonUrl'=>'array("methoddelete", "id"=>$data->id_method )',
        ),
    ),
));
echo '<div class="append">';
echo CHtml::link("Add method", array("methodCreate","class"=>$model->id_class));
echo '</div>';

$slots = array();
$signals = array();
foreach($model->methods as $meth)
{
    $slots = array_merge($slots,$meth->slots);
    $signals = array_merge($signals,$meth->signals);
}

echo '<h2>Slots</h2>';
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=> new CArrayDataProvider($slots,
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
    ),
));


echo '<h2>Signals</h2>';
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=> new CArrayDataProvider($signals,
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
    ),
));


echo '<h2>Inherited Methods</h2>';
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=> new CArrayDataProvider($model->inh_methods(),
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
            'class'=>'CDataColumn',
            'header'=>'Override',
            'type'=>'html',
            'value'=>'!$data->virtual && !$data->override?"":CHtml::link("Override",
                array("class/override", "id_class"=>'.$model->id_class.',
                    "id_method"=>$data->id_method) )',
        ),
    ),
));


echo CodeGen::render_code($model->include."\n\n".
                          $model->cpp_namespace_open()."\n".
                          $model->cpp_doc()."\n". $model->cpp_decl()."\n\n".
                          $model->cpp_namespace_close(),
                          true);
