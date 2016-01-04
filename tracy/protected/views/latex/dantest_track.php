<?php
/* @var $table_creator TableCreator */

function test_row(Method $meth,TableCreator $table_creator)
{
    $text = $meth->ttest != null ? $meth->ttest->unitTest->public_id() : '';
    return $table_creator->row(array(
            Class_Prog::model()->findByPK($meth->id_class)->name."::".$meth->name."()",
            $text,
        ));
}

function display_class(Class_Prog $class, TableCreator $table_creator)
{
    $string = "";
    foreach($class->methods as $meth)
    {
        $string .= test_row($meth,$table_creator);
    }
    return $string;
}

function display_comp(Package $component, TableCreator $table_creator)
{
    $string = "";
    $component->with('classes');
    foreach($component->classes as $class)
    {
        $string .= display_class($class,$table_creator);
    }
    
    $component->with('packages');
    $nested = $component->packages;
    foreach($nested as $nuc )
    {
        $string .= display_comp($nuc,$table_creator);
    }
    
    return $string;
}

$sourceArray = array();

$toplevel = Package::model()->findAll('parent is null and name != "Qt"');

$table =
    $table_creator->begin_table("|p{11cm}|p{2cm}|") .
    $table_creator->heading_row(array("Metodo","Test"));
    
foreach ( $toplevel as $component)
{
    $table .= display_comp($component,$table_creator);
}

$table .= $table_creator->caption("Tabella metodi / test unità").
          $table_creator->end_table();

if ( !$raw && $table_creator->id() == 'latex' ) 
    echo CodeGen::generic_render_code($table,'latex',true);
else
    echo $table;