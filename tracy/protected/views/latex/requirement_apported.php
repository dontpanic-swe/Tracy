<?php

/// @warning HARD CODED DB PK!!!!!!!!!!!
$reqs = Requirement::model()->findAll("category != 4 and priority != 4");

$table =
    $table_creator->begin_table("|m{3cm}|m{6cm}|m{4cm}|") .
    $table_creator->heading_row(array("Id",
                                      "Descrizione","Implementazione"));

foreach($reqs as $r)
{
    $id = $r->public_id();
    $app = $r->apported;
    $strapp = "";
    if ($app) {
    $strapp = "\color{dkgreen}{Implementato}";
    }
    else {
    $strapp = "\color{dkred}{Non implementato}";
    }
    $table .= $table_creator->row(array($id,$r->description,$strapp));
}

$table .= $table_creator->caption("Tabella implementazione requisiti opzionali e desiderabili").
          $table_creator->end_table();


if ( $table_creator->id() != 'html' && !$raw )
    echo CodeGen::generic_render_code($table,'latex',true);
else
    echo $table;