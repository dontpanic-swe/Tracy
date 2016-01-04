<?php

$ass = isset($_GET['ass']) && $_GET['ass'];
$xmi = new XmiRenderer;

$xmi->begin();
$toplevel = Package::model()->findAll('parent is null');
foreach ( $toplevel as $pack )
    $xmi->package($pack,$ass);
    
$xmi->end();