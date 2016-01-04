<?php
/** @var Table_Creator $table_creator */

$this->breadcrumbs=array(
    'LaTeX'=>array('index'),
    "Classes",
);

$this->menu=array(
    array('label'=>'Create Class', 'url'=>array('class/create')),
    array('label'=>'List Classes', 'url'=>array('class')),
    );

if ( !$raw )
    echo "<h1>View Generated LaTeX for Classes</h1>";

    
function display_class(Class_Prog $class, TableCreator $table_creator)
{
    $txt = "";
    
    if ( !$class->library )
    {
        $id = $class->full_name();
        $name = $class->full_name(" :: ");
        $txt .= $table_creator->anchor($id,'')."\n";
        
        $txt .= $table_creator->title(3,$name,$class->name);
        
        
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

function display_package(Package $pack,$indentation,TableCreator $table_creator)
{
    $txt = "";
    
    /*$name = $pack->full_name("::");
    if ( !$pack->virtual )
    {
        $txt .= $table_creator->anchor($name,'')."\n";
        $txt .= $table_creator->title(2,$name);
        $txt .= $pack->description."\n\n";
    }*/
    if ( $indentation == 1 )
        $txt .= $table_creator->title(2,$pack->name);
    //else if ( $indentation == 2 )
    //    $txt .= $table_creator->title(3,$pack->name);
    
    
    $pack->with('classes','packages');
    
    foreach ( $pack->classes as $c )
    {
        $txt .= display_class($c,$table_creator);
    }
    
    foreach ( $pack->packages as $p )
    {
        $txt .= display_package($p,$indentation+1,$table_creator);
    }
    
    return $txt;
}
    
$sourceArray = array();

$toplevel = Package::model()->findAll('parent is null and name != "Qt"');
$txt = "";
foreach ( $toplevel as $uberpackage)
{
    $txt .= display_package($uberpackage,0,$table_creator);
}

if ( $table_creator->id() != 'html' && !$raw )
    echo CodeGen::generic_render_code($txt,'latex',true);
else echo $txt;