<?php

function test_row(UnitTest $test,$indentation,TableCreator $table_creator)
{
    return$table_creator->row(array(
            $table_creator->child_indentation($indentation),
            $test->public_id(),
            $test->test->description,
            $test->method->name."()",
            'success', // $test->test->status == 'unimplemented' ? 'N.I.' : $test->test->status
        ));
}

$toplevel = UnitTest::model()->findAll();

$table =
    $table_creator->begin_table("|r l|p{6cm}|l|l|") .
    $table_creator->heading_row(array("Test di unità"=>2,"Descrizione",
                                      "Metodo", "Stato"));
    
foreach($toplevel as $test )
{
    $table .= test_row($test,0,$table_creator);
}

$table .= $table_creator->caption("Tabella test unità / metodi").
          $table_creator->end_table();


if ( $table_creator->id() != 'html' && !$raw )
    echo CodeGen::generic_render_code($table,'latex',true);
else
    echo $table;