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
    $validation = $req->with('validation0')->validation0;
    
    $ret = "";
    
    $rv = '';
    if ( isset($test) )
        $rv = $test->public_id();
    else if ( isset($validation) )
        $rv = $validation->name;
    
    $ret.= $table_creator->row(array(
            $table_creator->child_indentation($indentation),
            $req->public_id(),
            $rv
        ));

    $nested = $req->with('requirements')->requirements;
    foreach($nested as $nreq )
        $ret .= test_row($nreq,$indentation+1,$table_creator,$filter_cat,$filter_pri);
    return $ret;
}
    
$table =
    $table_creator->begin_table("|r l|l|") .
    $table_creator->heading_row(array("Requisito"=>2,"Test"));
    

$toplevel = Requirement::model()->with('sources')
                    ->findAll("t.parent is null");
    
foreach($toplevel as $req )
{
    $table .= test_row($req,0,$table_creator,$filter_cat,$filter_pri);
}
    
$table .= $table_creator->caption("Tabella requisiti / test di sistema").
          $table_creator->end_table();

if ( $table_creator->id() != 'html' && !$raw )
    echo CodeGen::generic_render_code($table,'latex',true);
else
    echo $table;
    
    

