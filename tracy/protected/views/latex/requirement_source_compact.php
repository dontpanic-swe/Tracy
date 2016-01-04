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
          ( !is_numeric($filter_pri) || $req->priority == $filter_pri )
        )
    {
        $source_desc = array();
        foreach(  $req->sources as $rc )
        {
            $rc->with('useCase','externalSource');
            if ( isset($rc->useCase) )
            {
                array_push($source_desc,$table_creator->link($rc->useCase->public_id(),
                                                             $rc->useCase->public_id()));
            }
            else
                array_push($source_desc,$rc->externalSource->description);
        }
        sort($source_desc);
        
        $cells = array( $table_creator->child_indentation($indentation),
                       $req->public_id(),
                       implode("\n\n",$source_desc) );
        
        $ret .= $table_creator->row($cells);
        
        
    }
    $nested = $req->with('sources')
                        ->findAll("t.parent=:parent",
                                  array(':parent'=>$req->id_requirement));
    foreach($nested as $nreq )
        $ret .= requirement_row($nreq,$indentation+1,$table_creator,$filter_cat,$filter_pri);
    return $ret;
}


$toplevel = Requirement::model()->with('sources')
                    ->findAll("t.parent is null");

    
$table =
    $table_creator->begin_table("|r l|p{6cm}|") .
    $table_creator->heading_row(array("Requisito"=>2,"Fonti"));
    
foreach($toplevel as $req )
{
    $table .= requirement_row($req,0,$table_creator,$filter_cat,$filter_pri);
}
    
$table .= $table_creator->caption("Tabella requisiti/fonti").
          $table_creator->end_table();

if ( $table_creator->id() != 'html' && !$raw )
    echo CodeGen::generic_render_code($table,'latex',true);
else
    echo $table;