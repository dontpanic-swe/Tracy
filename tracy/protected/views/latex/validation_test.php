<?php
/* @var $table_creator TableCreator */


function test_row(ValidationTest $test,TableCreator $table_creator)
{
    $test->with('children');
    if ( !isset($test->children) || count($test->children) == 0 )
        return "";
    
    $str = "\nAll'utente è richiesto di:\n";
    $str .= $table_creator->begin_list();
    
    foreach ( $test->children as $child )
    {
        $child->with('test');
        $id = $child->public_id();
        $str .= $table_creator->list_item(
                    $table_creator->anchor($id, $child->test->description." ($id)"));
        $str .= test_row($child,$table_creator);
    }
    
    $str .= $table_creator->end_list();
    
    return $str;
}

$toplevel = ValidationTest::model()->with('test')
                    ->findAll("t.parent is null");
$table = '';
foreach($toplevel as $test )
{
    $id = $test->public_id();
    $table .= $table_creator->anchor($id,'')."\n";
    $table .= $table_creator->title(3,"Test $id");
    $table .= $test->test->description."\n";
    $table .= test_row($test,$table_creator);
}


if ( $table_creator->id() != 'html' && !$raw )
    echo CodeGen::generic_render_code($table,'latex',true);
else
    echo $table;
    
    

