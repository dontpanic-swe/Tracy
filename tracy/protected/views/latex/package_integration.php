<?php 

function component_row(Package $package,$indentation,TableCreator $table_creator)
{
    
    $package->with('integration');
    
    
    $ret = $table_creator->row(array(
        $package->full_name(),
        $package->integration == null ? 'Architettura del sistema' :
            $package->integration->public_id(),
    ));
    
    $nested = $package->with('packages')->packages;
    
    foreach($nested as $nuc )
    {
        $ret .= component_row($nuc,$indentation+1,$table_creator);
    }
    return $ret;
}

$comps = Package::model()->findAll("parent is null and name != 'Qt'");

$table =
    $table_creator->begin_table("|l|l|") .
    $table_creator->heading_row(array("Componente","Test"));
    
foreach($comps as $package )
{
    $package->with('integration');
    $table .= component_row($package,0,$table_creator);
}

$table .= $table_creator->caption("Tabella componente / test di integrazione").
          $table_creator->end_table();


if ( $table_creator->id() != 'html' && !$raw )
    echo CodeGen::generic_render_code($table,'latex',true);
else
    echo $table;