<?php
$this->breadcrumbs=array(
    'Generator'
);
$this->pageTitle="Generator";
$this->bg_image = "http://{$_SERVER['HTTP_HOST']}/logo/lolbot_code.png";


echo '<h1>Generator</h1>';


echo '<ul>';
echo '<li>'.CHtml::link('C++',array('decl','raw'=>0,)).'</li>';
echo '<li>'.CHtml::link('XMI',array('xmi','raw'=>0,)).'</li>';
echo '</ul>';
