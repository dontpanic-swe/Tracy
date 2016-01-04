<?php
    
function component_row(Package $component,$indentation,TableCreator $table_creator)
{
    
    $req_id = array();
    $component->with('requirements');
    foreach( $component->requirements as $rq )
    {
        array_push($req_id, $table_creator->link($rq->public_id(),$rq->public_id()));
    }
    array_unique($req_id);
    
    $cells = array( $table_creator->child_indentation($indentation),
                   $table_creator->link($component->full_name(),
                                        $component->full_name("::\\-")),
                   implode("\n\n",$req_id)  );
    
    $ret = $table_creator->row($cells);
    
    
    $nested = $component->with('packages')->packages;
    
    foreach($nested as $nuc )
    {
        $ret .= component_row($nuc,$indentation+1,$table_creator);
    }
    return $ret;
}
    
$table =
    $table_creator->begin_table("|r l|p{3cm}|") .
    $table_creator->heading_row(array("Componenti"=>2,"Requisiti"));
    
    

$toplevel = Package::model()->findAll("t.parent is null and t.name!='Qt'");
foreach($toplevel as $req )
{
    $table .= component_row($req,0,$table_creator);
}
    
$table .= $table_creator->caption("Tabella componenti / requisiti") .
            $table_creator->end_table();

if ( $table_creator->id() != 'html' && !$raw )
    echo CodeGen::generic_render_code($table,'latex',true);
else
    echo $table;