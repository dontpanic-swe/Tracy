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




function requirement_row($req,$indentation,TableCreator $table_creator,$filter_cat,$filter_pri)
{
    
    $ret = "";
    
    if (  ( !is_numeric($filter_cat) || $req->category == $filter_cat ) &&
          ( !is_numeric($filter_pri) || $req->priority == $filter_pri ) &&
          isset($req->packages) && count($req->packages) > 0
        )
    {
        $pkg_name = array();
        foreach(  $req->packages as $c )
        {
            $c->with('package');
            array_push($pkg_name,$table_creator->link($c->full_name(),
                                                      $c->full_name("::\\-")));
        }
        array_unique($pkg_name);
        sort($pkg_name);
        
        $cells = array( $table_creator->child_indentation($indentation),
                       $table_creator->link($req->public_id(),$req->public_id()),
                       implode("\n\n",$pkg_name) );
        
        $ret .= $table_creator->row($cells);
        
        
    }
    $nested = Requirement::model()->with('packages')
                        ->findAll("t.parent=:parent",
                                  array(':parent'=>$req->id_requirement));
    foreach($nested as $nreq )
        $ret .= requirement_row($nreq,$indentation+1,$table_creator,$filter_cat,$filter_pri);
    return $ret;
}


$toplevel = Requirement::model()->with('packages')
                    ->findAll("t.parent is null");

    
$table =
    $table_creator->begin_table("|r l|p{10cm}|") .
    $table_creator->heading_row(array("Requisito"=>2,"Componenti"));
    
foreach($toplevel as $req )
{
    $table .= requirement_row($req,0,$table_creator,$filter_cat,$filter_pri);
}
$fc = is_numeric($filter_cat) ?
        strtolower( RequirementCategory::model()->findByPk($filter_cat)->latex_name )." "
        : "";
$table .= $table_creator->caption("Tabella requisiti $fc/ componenti").
          $table_creator->end_table();

if ( $table_creator->id() != 'html' && !$raw )
    echo CodeGen::generic_render_code($table,'latex',true);
else
    echo $table;