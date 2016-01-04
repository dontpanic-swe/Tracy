<?php
/* @var $this ClassController */

$this->breadcrumbs=array(
    'Class'=>array('index'),
    'Associations',
);

$this->menu=array(
    array('label'=>'List Class', 'url'=>array('index')),
);

echo '<h1>Manage Associations</h1>';

/*

SELECT id_association AS id, c_f.name AS class_from, c_t.name AS class_to, att.name AS attribute
FROM association a
JOIN class c_f ON a.class_from = c_f.id_class
JOIN class c_t ON a.class_to = c_f.id_class
JOIN attribute att ON a.id_attribute = att.id_attribute

*/
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
        ->order('class_from, class_to');

        

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'class-prog-grid',
    'dataProvider'=>new CArrayDataProvider($arr->queryAll(),
                                           array('pagination'=>false) ),
    'columns'=>array(
            array(
                'class'=>'CDataColumn',
                'header'=>'From',
                'type'=>'html',
                'value'=>'CHtml::link($data["class_from"],
                            array("class/view","id"=>$data["id_from"]))',
            ),
            'aggregation_from',
            
            
            array(
                'class'=>'CDataColumn',
                'header'=>'Attribute',
                'type'=>'html',
                'value'=>'CHtml::link($data["attribute"],
                            array("class/methodView","id"=>$data["id_att"]))',
            ),
            
            'aggregation_to',
            array(
                'class'=>'CDataColumn',
                'header'=>'To',
                'type'=>'html',
                'value'=>'CHtml::link($data["class_to"],
                            array("class/view","id"=>$data["id_to"]))',
            ),
            
            array(
                'class'=>'CButtonColumn',
                'template'=>'{delete}',
                'deleteButtonUrl'=>'array("assocdelete", "id"=>$data["id"] )',
            ),
        )
    )
);