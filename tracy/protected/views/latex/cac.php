<?php
/* @var $this UseCaseController */
/* @var $model UseCase */

$this->breadcrumbs=array(
    'LaTeX'=>array('index'),
    'CAC'
);


if ( !$raw )
    echo "<h1>Components, Associations, Classes</h1>";

function display_comp($component, TableCreator $table_creator)
{
    
    $string = '';
    $id = $component->full_name();
    $name = $component->full_name("::\\-");
    //$actors = implode(', ',$useCase->actors());
    $string .= $table_creator->anchor($id,'')."\n";
    $string .= $table_creator->title(2, $name);
    
    $string .= $table_creator->title(3, "Informazioni sul package");
    
      
    $string .="
    \\begin{figure}[H]
        \\centering
        \\scalegraphics{img/package/$component->name}
        \\caption{Componente $name}\\label{fig:$id} 
    \\end{figure}
    ";
        
    $string .= $table_creator->title(4,'Descrizione');
    $string .= $component->description. ". \n";
    
    
      
    $component->with('packages');
    $nested = $component->packages;
    if ( count($nested) > 0 )
    {
        $string .= $table_creator->title(4,'Package contenuti');
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
    
    
    $component->with('classes');
    if ( count($component->classes) > 0 )
    {
        $string .= $table_creator->title(3,'Classi');
        //$string .= $table_creator->begin_list();
        foreach($component->classes as $class)
        {
            /*$name = $class->full_name();
            $string .= $table_creator->list_item(
                            $table_creator->link($name,$name)
                        );*/
            $string .= display_class($class,$table_creator);
        }
        //$string .= $table_creator->end_list();
    }
    
        
    foreach($nested as $nuc )
    {
        $string .= display_comp($nuc,$table_creator);
    }

    
    
    return $string;
}


function display_class(Class_Prog $class, TableCreator $table_creator)
{
    $txt = "";
    
    if ( !$class->library )
    {
        $id = $class->full_name();
        $name = $class->full_name(" :: ");
        $txt .= $table_creator->anchor($id,'')."\n";
        
        $txt .= $table_creator->title(4,$name,$class->name);
        
        
        $txt .= $table_creator->begin_desc_list();
        
        
        $txt .= $table_creator->desc_list_item('Descrizione',
                                               $class->description);
        
        if ( strlen(trim($class->usage)) > 0 )
        {
            $txt .= $table_creator->desc_list_item('Utilizzo',$class->usage);
        }
        
        $class->with('children','parents');
        
        if ( count($class->parents) )
        {
            $inh_txt = '';
            $inh_txt .= "\\vspace{-7mm}\n";
            $inh_txt .= $table_creator->begin_list();
            foreach($class->parents as $par)
            {
                if ( !$par->library )
                    $inh_txt .= $table_creator->list_item(
                                $table_creator->link($par->full_name(),
                                                 $par->full_name(" :: "))
                            )
                    ;
                else
                    $inh_txt .= $table_creator->list_item($par->full_name());
            }
            $inh_txt .= $table_creator->end_list();
            $txt .= $table_creator->desc_list_item('Classi ereditate',$inh_txt);
        }
        
        if ( count($class->children) )
        {
            $inh_txt = '';
            
            $inh_txt .= "\\vspace{-7mm}\n";
            $inh_txt .= $table_creator->begin_list();
            foreach($class->children as $par)
            {
                $inh_txt .= $table_creator->list_item(
                            $table_creator->link($par->full_name(),
                                             $par->full_name(" :: "))
                        );
            }
            $inh_txt .= $table_creator->end_list();
            
            $txt .= $table_creator->desc_list_item('Classi figlie',$inh_txt);
            
        }
        
        
        $ass = Association::model()->findAll(
                            'class_from = :idc or class_to = :idc',
                            array(':idc'=>$class->id_class) );
        if ( count($ass) )
        {
            $rel = '';
            
            $rel .= "\\vspace{-7mm}\n";
            $rel .= $table_creator->begin_desc_list();
            foreach($ass as $ss)
            {
                
                if ( $ss->class_to != $class->id_class )
                {
                    $par = $ss->with('classTo')->classTo;
                    $ass_dir = "uscente";
                }
                else if ( $ss->class_from != $class->id_class )
                {
                    $par = $ss->with('classFrom')->classFrom;
                    $ass_dir = "entrante";
                }
                else
                    continue;
                
                $ss->with('attribute');
                
                if ( !$par->library )
                {
                    $acn = $par->full_name();
                    if ( strlen($acn) > 60 )
                    {
                        $acn = substr($acn,strlen($acn)-60);
                        $acn = "...".substr($acn,strpos($acn,"::")+2);
                    }
                    $ass_name = $table_creator->link($par->full_name(),$acn);
                }
                else
                    $ass_name = $par->full_name();
                
                $ass_txt = "Relazione $ass_dir, ".
                                lcfirst($ss->attribute->description);
                $rel .= $table_creator->desc_list_item($ass_name,$ass_txt);
            }
            $rel .= $table_creator->end_desc_list();
            
            $txt .= $table_creator->desc_list_item(
                                'Relazioni con altre classi', $rel
                                );
            
        }
        
        
    }
    
    
    $txt .= $table_creator->end_desc_list();
    
    $txt .= "\n\\vspace{0.5cm}\n";
    
    return $txt;
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