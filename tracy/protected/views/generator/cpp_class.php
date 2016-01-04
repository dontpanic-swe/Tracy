<?php


if ( $class!= null )
    $cont = $this->renderPartial("cpp_class_$view",
                                 array('class'=>$class,'raw'=>$raw),  true);
else
    $cont  = "";
    
if ( $raw )
    echo $cont;
else
{
    $cid = null;
    $cname = "";
    if ( $class != null )
    {
        $cid = $class->id_class;
        $cname = $class->name;
        $this->menu []= array('label'=>'Manage', 'url'=>array('class/view',
                                                    'id'=>$class->id_class));
    }
    else
        $this->pageTitle = "Select class";
    
    echo CHtml::beginForm(array($this->action->id,'raw'=>0),'get');
    echo CHtml::label('Class','class');
    echo CHtml::hiddenField('id',$cid,array('id'=>'actual_class'));
    $this->widget('zii.widgets.jui.CJuiAutoComplete',
        array(
            'name'=>'class_autocomplete',
            'sourceUrl'=>array('class/parentcompletion'),
            'options'=> array (
                'select'=>"js:function(event, ui) {
                            $('#actual_class').val(ui.item.id);
                        }",
            ),
            'value'=>$cname,
        ) );
    echo CHtml::submitButton('View');
    echo CHtml::endForm();

    if ( $class != null )
        echo CodeGen::render_code($cont,true,'cpp-qt');
}