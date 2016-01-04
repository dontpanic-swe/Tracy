<?php

function req_row(Requirement $req,$indentation,TableCreator $table_creator)
{
    $req->with('validationTest','requirements');
    
    $ret = "";
    
    $ret .= $table_creator->row(array(
            $table_creator->child_indentation($indentation),
            $req->public_id(),
            $req->validationTest == null ? '' : $req->validationTest->public_id(),
        ));

    $nested = $req->requirements;
    foreach($nested as $c )
        $ret .= req_row($c,$indentation+1,$table_creator);
    return $ret;
}

$toplevel = Requirement::model()->findAll("t.parent is null");

$table =
    $table_creator->begin_table("|r l|l|") .
    $table_creator->heading_row(array("Requisito"=>2,"Validazione"));
    
foreach($toplevel as $req )
{
    $table .= req_row($req,0,$table_creator);
}

$table .= $table_creator->caption("Tabella requisiti / test validazione").
          $table_creator->end_table();


if ( $table_creator->id() != 'html' && !$raw )
    echo CodeGen::generic_render_code($table,'latex',true);
else
    echo $table;