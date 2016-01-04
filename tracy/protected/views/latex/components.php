<?php
/* @var $this UseCaseController */
/* @var $model UseCase */

$this->breadcrumbs=array(
    'LaTeX'=>array('index'),
    'Component'
);


if ( !$raw )
    echo "<h1>View Generated LaTeX for Components</h1>";

function display_comp($component, TableCreator $table_creator)
{
    
    $string = '';
    $id = $component->full_name();
    $name = $component->full_name("::\\-");
    //$actors = implode(', ',$useCase->actors());
    $string .= $table_creator->anchor($id,'')."\n";
    $string .= $table_creator->title(2, $name);
    
      
    $string .="
    \\begin{figure}[H]
        \\centering
        \\scalegraphics{img/package/$component->name}
        \\caption{Componente $name}\\label{fig:$id} 
    \\end{figure}
    ";
        
    $string .= $table_creator->title(4,'Descrizione');
    $string .= $component->description. ". \n";
    
    
    $component->with('classes');
    if ( count($component->classes) > 0 )
    {
        $string .= $table_creator->title(4,'Classi');
        $string .= $table_creator->begin_list();
        foreach($component->classes as $class)
        {
            $name = $class->full_name();
            $string .= $table_creator->list_item(
                            $table_creator->link($name,$name)
                        );
        }
        $string .= $table_creator->end_list();
    }
      
    $component->with('packages');
    $nested = $component->packages;
    if ( count($nested) > 0 )
    {
        $string .= $table_creator->title(4,'Figli');
        $string .= $table_creator->begin_list();
        foreach($nested as $nuc )
        {
            $name = $nuc->full_name();
            $string .= $table_creator->list_item(
                            $table_creator->link($name,$name)
                    );
        }
        $string .= $table_creator->end_list();
        
    }
    
    
    $allass = $component->get_dependencies(false);
    if ( count($allass) > 0 )
    {
        $skip = $component->all_descendant_id();
        $alldep = array();
        foreach($allass as $dep)
        {
            if ( !in_array($dep['id_from'],$skip) )
                $alldep []= $dep['id_from'];
            else if ( !in_array($dep['id_to'],$skip) )
                $alldep []= $dep['id_to'];
        }
        
        if ( count($alldep) > 0 )
        {
            
            $string .= $table_creator->title(4,'Interazioni con altri componenti');
            $string .= $table_creator->begin_list();
            foreach($alldep as $d )
            {
                $nuc = Package::model()->findByPk($d);
                $name = $nuc->full_name();
                $string .= $table_creator->list_item(
                                $table_creator->link($name,$name)
                        );
            }
            $string .= $table_creator->end_list();
        }
        
    }
    
        
    foreach($nested as $nuc )
    {
        $string .= display_comp($nuc,$table_creator);
    }

    
    
    return $string;
}

$sourceArray = array();

$toplevel = Package::model()->findAll('parent is null and name != "Qt"');
$latex =  "";
foreach ( $toplevel as $component)
{
    $latex .= display_comp($component,$table_creator);
}

if ( !$raw && $table_creator->id() == 'latex' ) 
    echo CodeGen::generic_render_code($latex,'latex',true);
else
    echo $latex;
  
?> 