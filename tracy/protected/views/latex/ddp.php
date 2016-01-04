<?php
/* @var $this UseCaseController */
/* @var $model UseCase */

$this->breadcrumbs=array(
    'LaTeX'=>array('index'),
    'DdP'
);


if ( !$raw )
    echo "<h1>View Generated LaTeX for DDP</h1>";
    
    
    
function escape_latex($txt)
{
    return str_replace(array('_','&'),array('\_','\&'),$txt);
}

function display_method(Method $meth, TableCreator $table_creator)
{
    $string = "
    \\item \\color{blue}\\code{";
    
    if ($meth->access == "public") 
      $string .= '+ ';
    else if ($meth->access == "private")
      $string .= '- ';
    else
      $string .= '\# ';
    
    if ( strlen($meth->return) > 0 )
        $string .= escape_latex($meth->return)." ";
    
    $string .= escape_latex($meth->name)."(";
  
    
    if ( !empty($meth->arguments) )
    {
        foreach($meth->arguments as $arg)
        {
            $string .= escape_latex($arg->name)." : ".escape_latex($arg->type).", ";
        }
    }
    
    
    $string .= ")} \\color{black} \\hfill \\\ ";

    $string .= $meth->description."\\\ ";
    
    if ( !empty($meth->arguments) )
    {
      $string .= "\\textbf{Argomenti}
	\\begin{itemize}
	";
        foreach($meth->arguments as $arg)
        {
            $string .= "\item" /* \$[\${$arg->direction}\$]\$ */ . " ".escape_latex($arg->name)." : ".escape_latex($arg->type)." \\\ ";
            $string .= escape_latex($arg->description);
        }
      $string .= "\\end{itemize}
      ";
    }
        
    $note = array();
    if ( $meth->abstract )
        $note []= "Deve essere astratto";
    else if ( $meth->virtual )
        $note []= "Deve essere virtuale";
    if ( $meth->static )
        $note []= "Deve essere un metodo statico";
    if ( $meth->const )
        $note []= "Deve essere esplicitamente marcato come costante";
    if ( $meth->override )
        $note []= "Questo metodo è stato ridefinito";
    if ( !empty($note) )
    {
	$string .= "\\textbf{Note}
	  \\begin{itemize}
	  ";
        foreach($note as $nn)
        {
            $string .= "\\item ".$nn."
	    ";
        }
        $string .= "\\end{itemize}
        ";
    }
    
//     if ( !empty($meth->arguments) )
//     {
//         $n = $table_creator->begin_desc_list();
//         foreach($meth->arguments as $arg)
//         {
//             $n .= $table_creator->desc_list_item(
//                 "[$arg->direction] $arg->name : $arg->type",
//                 $arg->description
//             );
//         }
//         $n .= $table_creator->end_desc_list();
//     }
//     
//     $string .= $table_creator->end_desc_list();
    
    return $string;
}


function display_attribute(Attribute $att, TableCreator $table_creator)
{
    $string = "\\item \\color{dkgreen}\\code{";
    
    if ($att->access == "public") 
      $string .= '+ ';
    else if ($att->access == "private")
      $string .= '- ';
    else
      $string .= '\# ';
    
    $string .= escape_latex($att->type)." ";
    
    $string .= escape_latex($att->name)."} \\color{black} \\hfill \\\
    ";
    
    $string .= escape_latex($att->description)."
    ";
    
    return $string;//$table_creator->desc_list_item($att->name,$txt);
}

function display_class(Class_Prog $class, TableCreator $table_creator)
{
    $string = "";
    $id = $class->full_name();
    
    $string .= $table_creator->anchor($id,'')."\n";
    $string .= $table_creator->title(3,$class->name." ($class->type)");
    
    $string .="
    \\begin{figure}[H]
        \\centering
        \\scalegraphics{img/classi/$class->name}
        \\caption{Classe $class->name}\\label{fig:$id} 
    \\end{figure}
    ";
    
    $string .= "\\begin{description}
    \\item[Descrizione]  \\hfill \\\
      ";
    $string .= $class->description;
    $string .= "
    \\item[Utilizzo]  \\hfill \\\
      ";
    $string .= $class->usage;
    
    
    
    $class->with('children','parents');
    if ( count($class->parents) )
    {
        $string .= "
	  \\item[Classi ereditate]  \\hfill
      ";
    
        $string .= $table_creator->begin_list();
        foreach($class->parents as $par)
        {
            if ( !$par->library )
                $string .= $table_creator->list_item(
                            $table_creator->link($par->full_name(),
                                             $par->full_name(" :: "))
                        )
                ;
            else
                $string .= $table_creator->list_item($par->full_name());
        }
        $string .= $table_creator->end_list();
    }
        
    if ( count($class->children) )
    {
        $string .= "
	  \\item[Ereditata da]  \\hfill
      ";
        
        $string .= $table_creator->begin_list();
        foreach($class->children as $par)
        {
            $string .= $table_creator->list_item(
                        $table_creator->link($par->full_name(),
                                         $par->full_name(" :: "))
                    );
        }
        $string .= $table_creator->end_list();
        
    }
    
    
    if ( !empty($class->attributes0) )
    {
        $string .= "
	  \\item[Attributi]  \\hfill
	  \\begin{description}
	  ";
        //$string .= $table_creator->begin_desc_list();
        
        foreach($class->attributes0 as $attribute)
        {
            $string .= display_attribute($attribute,$table_creator);
        }
        //$string .= $table_creator->end_desc_list();
        $string .= "
	  \\end{description}
      ";
    }
        
    if ( !empty($class->methods) )
    {
        
        $string .= "
	  \\item[Metodi]  \\hfill
	  \\begin{description}
      ";
        //$string .= $table_creator->begin_desc_list();
        foreach($class->methods as $meth)
        {
            $string .= display_method($meth,$table_creator);
        }
        //$string .= $table_creator->end_desc_list();
        $string .= "
	  \\end{description}
      ";
    }
    
    $string .= "
    \\end{description}
    ";       
        
    return $string;
}

function display_comp($component, TableCreator $table_creator)
{
    
    $string = '';
    $id = $component->full_name();
    $name = $component->full_name("::\\-");
    $component->with('classes');
    //$actors = implode(', ',$useCase->actors());
    /*if ( !empty($component->classes) )
    {*/
    
    $string .= $table_creator->anchor($id,'')."\n";
    $string .= $table_creator->title(2, $name);
    
    $string .="
    \\begin{figure}[H]
        \\centering
        \\scalegraphics{img/package/$component->name}
        \\caption{Componente $name}\\label{fig:$id} 
    \\end{figure}
    ";
    //}
    
    $string .= $component->description."
    ";
    
    foreach($component->classes as $class)
    {
        $string .= display_class($class,$table_creator);
    }
      
    $component->with('packages');
    $nested = $component->packages;
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