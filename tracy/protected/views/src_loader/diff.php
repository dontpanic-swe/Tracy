<?php

    
    $this->breadcrumbs = array(
        'Upload source'=>array('index'),
        'Preview'=>array('view'),
        'Diff from '.$class->cpp_header_file(),
    );
    
    $this->menu=array(
        array('label'=>'Edit', 'url'=>array('index','edit'=>1)),
        array('label'=>'View Uploaded File', 'url'=>array('view')),
        array('label'=>'View Generated File', 'url'=>array('generator/decl',
                                                   'id'=>$class->id_class)),
        array('label'=>'Scan', 'url'=>array('scan')),
    );

    if(empty($diff))
    {
        echo '<div class="error">No changes.</div>';
    }
    else
    {
        echo "<p>Changes from the ".
                CHtml::link('generated file',array('generator/decl',
                                                   'id'=>$class->id_class)).
                "</p>";
        if ( $mode == 'inline' )
        {
            $this->menu []= array('label'=>'Full Diff',
                                  'url'=>array('diff',
                                                'id'=>$class->id_class,
                                                'mode'=>'diff'
                                        )
                                );
            Yii::app()->clientscript->registerCss('diff',
                'del {color:red;}
                ins{color:green;}');
            echo "<pre class='diff'>$diff</pre>";
        }
        else
        {
            $this->menu []= array('label'=>'Inline Diff',
                                  'url'=>array('diff',
                                                'id'=>$class->id_class,
                                                'mode'=>'inline'
                                        )
                                );
            echo CodeGen::generic_render_code($diff,'diff',true);
        }
    }

?>