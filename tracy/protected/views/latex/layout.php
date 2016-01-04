<?php

$action = $this->action->id;
$this->breadcrumbs=array(
    'LaTeX'=>array('index'),
    $action,
);


$raw = array($action);
foreach($_REQUEST as $k=>$v)
    $raw[$k]=$v;
$raw['type'] = 'latex';
$raw['raw'] = true;
    
$this->menu=array(
    array('label'=>'HTML', 'url'=>array($action,'type'=>'html')),
    array('label'=>'LaTeX', 'url'=>array($action,'type'=>'latex')),
    array('label'=>'Raw LaTeX', 'url'=>$raw),
);

$this->beginContent('//layouts/column2');
echo $content;
$this->endContent();