<?php

$action = $this->action->id;
$this->breadcrumbs=array(
    $this->pageTitle,
);


$raw = array($action);
foreach($_REQUEST as $k=>$v)
    $raw[$k]=$v;
$raw['raw'] = true;
    
if ( !is_array($this->menu) )
    $this->menu = array();
$this->menu []= array('label'=>'View Raw', 'url'=>$raw);

$this->beginContent('//layouts/column2');

echo $content;
$this->endContent();