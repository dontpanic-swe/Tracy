<?php

if ( !$raw )
{
    $cats = CHtml::listData(RequirementCategory::model()->findAll(),
                            "id_category","name");
    $cats[""] = "Tutti";
    
    $pris = CHtml::listData(RequirementPriority::model()->findAll(),
                            "id_priority","name");
    $pris[""] = "Tutti";
    
    echo "<div><form>";
    
    echo CHtml::label("Category","category");
    echo CHtml::dropDownList("category",$filter_cat,$cats);
    echo "<br/>";
    
    echo CHtml::label("Priority","priority");
    echo CHtml::dropDownList("priority",$filter_pri,$pris);
    echo "<br/>";
    
    echo CHtml::submitButton("Filter");
    
    echo "</form></div>";
    echo "<hr/>";
}


function test_row(Requirement $req,$indentation,TableCreator $table_creator,$filter_cat,$filter_pri)
{
    $test = $req->with('system_test')->system_test;
    $ret = "";
    
    
    if (   isset($req->system_test) &&
           ( !is_numeric($filter_cat) || $req->category == $filter_cat ) &&
           ( !is_numeric($filter_pri) || $req->priority == $filter_pri )  )
    {
        $test->with('test');
        $ret .= $table_creator->row (
                array( $test->public_id(),
                       $test->test->description,
                       'success',
                       $table_creator->anchor($req->public_id(),$req->public_id()),
                    )
            );
    }

    $nested = $req->with('requirements')->requirements;
    foreach($nested as $nreq )
        $ret .= test_row($nreq,$indentation+1,$table_creator,$filter_cat,$filter_pri);
    return $ret;
}
    
$table =
    $table_creator->begin_table("|l|p{6cm}|l|l|") .
    $table_creator->heading_row(array("Test","Descrizione","Stato","Requisito"));
    

$toplevel = Requirement::model()->with('sources')
                    ->findAll("t.parent is null");
    
foreach($toplevel as $req )
{
    $table .= test_row($req,0,$table_creator,$filter_cat,$filter_pri);
}
    
$table .= $table_creator->caption("Tabella di tracciamento test di sistema / requisiti").
          $table_creator->end_table();

if ( $table_creator->id() != 'html' && !$raw )
    echo CodeGen::generic_render_code($table,'latex',true);
else
    echo $table;
    
    

