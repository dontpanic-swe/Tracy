<?php

    $this->breadcrumbs = array(
        'Upload source'=>array('index'),
        'Preview',
    );
    
    
    $this->menu=array(
        array('label'=>'Edit', 'url'=>array('index','edit'=>1)),
        array('label'=>'Scan', 'url'=>array('scan')),
    );
    
    echo CHtml::beginForm(array('diff'),'get');
    echo CHtml::label('Compare with generated header for ','class');
    echo CHtml::hiddenField('id','',array('id'=>'actual_class'));
    $this->widget('zii.widgets.jui.CJuiAutoComplete',
        array(
            'name'=>'class_autocomplete',
            'sourceUrl'=>array('class/parentcompletion'),
            'options'=> array (
                'select'=>"js:function(event, ui) {
                            $('#actual_class').val(ui.item.id);
                        }",
            ),
            'value'=>'',
        ) );
    echo CHtml::dropDownList('mode','diff',array('diff'=>'diff',
                                                 'inline'=>'inline'));
    echo CHtml::submitButton('Compare');
    echo CHtml::endForm();

    echo CodeGen::render_code($code,true,'cpp-qt');