<?php

function class_row(Class_Prog $class,TableCreator $table_creator)
{
    $ret = "";
    
    $pkg_name = array();
    foreach(  $class->requirements as $c )
    {
        array_push($pkg_name,$c->public_id());
    }
    array_unique($pkg_name);
    sort($pkg_name);
    
    $cells = array( 
                   $table_creator->anchor($class->full_name(),
                                          $class->name),
                   implode("\n\n",$pkg_name) );
    
    $ret .= $table_creator->row($cells);
        
    return $ret;
}


    
$table =
    $table_creator->begin_table("|l|p{3cm}|") .
    $table_creator->heading_row(array("Classe","Requisiti"));
    
$toplevel = Class_Prog::model()->findAll("t.library != 1");
foreach($toplevel as $req )
{
    $table .= class_row($req,$table_creator);
}
    
$table .= $table_creator->caption("Tabella classi / requisiti") .
            $table_creator->end_table();

if ( $table_creator->id() != 'html' && !$raw )
    echo CodeGen::generic_render_code($table,'latex',true);
else
    echo $table;