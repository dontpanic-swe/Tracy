<?php 


$comps = IntegrationTest::model()->findAll();

$table =
    $table_creator->begin_table("|l|p{6cm}|l|l|") .
    $table_creator->heading_row(array("Test","Descrizione","Componente","Stato"));
    
foreach($comps as $test )
{
    $test->with('test','package');
    $table .= $table_creator->row(array(
        $test->public_id(),
        $test->test->description,
        $test->package->name,
        'success', // $test->test->status == 'unimplemented' ? 'N.I.' : $test->test->status
    ));
}

$table .= $table_creator->caption("Tabella test di integrazione").
          $table_creator->end_table();


if ( $table_creator->id() != 'html' && !$raw )
    echo CodeGen::generic_render_code($table,'latex',true);
else
    echo $table;