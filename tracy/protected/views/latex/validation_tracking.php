<?php

function test_row(ValidationTest $test,$indentation,TableCreator $table_creator)
{
    $test->with('test','requirement','children');
    
    $ret = "";
    
    $ret .= $table_creator->row(array(
            $table_creator->child_indentation($indentation),
            $test->public_id(),
            $test->test->description,
            $test->requirement == null ? '' : $test->requirement->public_id(),
            'success', // $test->test->status == 'unimplemented' ? 'N.I.' : $test->test->status
        ));

    $nested = $test->children;
    foreach($nested as $c )
        $ret .= test_row($c,$indentation+1,$table_creator);
    return $ret;
}

$toplevel = ValidationTest::model()->with('test')
                    ->findAll("t.parent is null");

$table =
    $table_creator->begin_table("|r l|p{6cm}|l|l|") .
    $table_creator->heading_row(array("Validazione"=>2,"Descrizione",
                                      "Requisito","Stato"));
    
foreach($toplevel as $test )
{
    $table .= test_row($test,0,$table_creator);
}

$table .= $table_creator->caption("Tabella test validazione / requisiti").
          $table_creator->end_table();


if ( $table_creator->id() != 'html' && !$raw )
    echo CodeGen::generic_render_code($table,'latex',true);
else
    echo $table;