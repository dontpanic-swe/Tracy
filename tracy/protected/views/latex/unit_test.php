<?php
/* @var $table_creator TableCreator */

function test_row(TUnitTest $test,TableCreator $table_creator)
{
    $methods=array();
    foreach ( $test->methods as $meth )
        $methods []= $meth->name."()";
    
    if ( !empty($methods) )
        return $table_creator->row(array(
                $test->public_id(),
                $test->description,
                implode($methods,"\n\n"),
                'Success'
            ));
    return '';
}

$toplevel = TUnitTest::model()->findAll();

$table =
    $table_creator->begin_table("|p{1cm}|p{5cm}|p{5cm}|p{1.5cm}|") .
    $table_creator->heading_row(array("Test","Descrizione",
                                      "Metodi", "Stato"));
    
foreach($toplevel as $test )
{
    $table .= test_row($test,$table_creator);
}

$table .= $table_creator->caption("Tabella descrizione test unità").
          $table_creator->end_table();


if ( $table_creator->id() != 'html' && !$raw )
    echo CodeGen::generic_render_code($table,'latex',true);
else
    echo $table;