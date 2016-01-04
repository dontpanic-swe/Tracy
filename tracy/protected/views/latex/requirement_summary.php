<?php
    
$cats = RequirementCategory::model()->findAll();
$pris = RequirementPriority::model()->findAll();

$titles = array("Categoria");
foreach($pris as $p)
    array_push($titles,$p->name);


$table =
    $table_creator->begin_table(count($pris)+1) .
    $table_creator->heading_row($titles);
    
    
foreach($cats as $c)
{
    $row = array ( $c->name );
    foreach ( $pris as $p )
        array_push($row,Requirement::model()->count("priority=:p and category=:c",
                                                    array("p"=>$p->id_priority,
                                                          "c"=>$c->id_category) ) );
    $table .= $table_creator->row($row);
}
    
$table .= $table_creator->caption("Riepilogo requisiti") .
            $table_creator->end_table();

if ( $table_creator->id() != 'html' && !$raw )
    echo CodeGen::generic_render_code($table,'latex',true);
else
    echo $table;