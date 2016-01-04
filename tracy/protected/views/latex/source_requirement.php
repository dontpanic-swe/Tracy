<?php
    
function src_row($uc,$indentation,TableCreator $table_creator)
{
    
    $req_id = array();
    $uc->with('idUseCase');
    $uc->idUseCase->with('requirements');
    foreach( $uc->idUseCase->requirements as $rq )
    {
        array_push($req_id, $table_creator->link($rq->public_id(),$rq->public_id()));
    }
    
    $cells = array( $table_creator->child_indentation($indentation),
                   $table_creator->link($uc->public_id(),$uc->public_id()),
                   $table_creator->link($uc->public_id(),$uc->title),
                   implode("\n\n",$req_id)  );
    
    $ret = $table_creator->row($cells);
    
    
    $nested = UseCase::model()->findAll("t.parent = :parent",
                        array(':parent'=>$uc->id_use_case) );
    
    foreach($nested as $nuc )
    {
        $ret .= src_row($nuc,$indentation+1,$table_creator);
    }
    return $ret;
}
    
$table =
    $table_creator->begin_table("|r l p{5cm}|p{3cm}|") .
    $table_creator->heading_row(array("Fonte"=>3,"Requisito"));
    
    
$external = ExternalSource::model()->findAll();
foreach($external as $x )
{
    $req_id = array();
    $x->with('idSource');
    $x->idSource->with('requirements');
    foreach( $x->idSource->requirements as $rq )
    {
        array_push($req_id, $table_creator->link($rq->public_id(),$rq->public_id()));
    }
    
    $cells = array( $table_creator->child_indentation(0),
                   "", $x->description, implode("\n\n",$req_id)  );
    
    $table .= $table_creator->row($cells);
}

$toplevel = UseCase::model()->findAll("t.parent is null");
foreach($toplevel as $req )
{
    $table .= src_row($req,0,$table_creator);
}
    
$table .= $table_creator->caption("Tabella fonti/requisiti") .
            $table_creator->end_table();

if ( $table_creator->id() != 'html' && !$raw )
    echo CodeGen::generic_render_code($table,'latex',true);
else
    echo $table;