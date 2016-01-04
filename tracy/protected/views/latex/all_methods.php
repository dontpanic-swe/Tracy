<?php

/// @warning HARD CODED DB PK!!!!!!!!!!!
$methods = Method::model()->findAll();

$table =
    $table_creator->begin_table("|m{3cm}|m{6cm}|m{2cm}|m{2cm}|") .
    $table_creator->heading_row(array("Classe",
                                      "Metodo","Righe","Test"));

foreach($methods as $m)
{
    $class = Class_Prog::model()->findByPK($m->id_class)->name;
    $table .= $table_creator->row(array($class,$m->name,'',''));
}

$table .= $table_creator->caption("Tabella metodi").
          $table_creator->end_table();


if ( $table_creator->id() != 'html' && !$raw )
    echo CodeGen::generic_render_code($table,'latex',true);
else
    echo $table;