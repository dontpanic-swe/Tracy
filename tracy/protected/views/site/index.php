<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;


echo '<h1>'.CHtml::encode(Yii::app()->name).'</h1>';



echo '<h2>Progettazione</h2>';
echo '<ul>';
echo '<li>'.CHtml::link('Class',array('class/index')).'</li>';
echo '<li>'.CHtml::link('Package',array('package/index')).'</li>';
echo '<li>'.CHtml::link('Test',array('test/index'));
echo '<ul>';
echo '<li>'.CHtml::link('System Test',array('test/system')).'</li>';
echo '<li>'.CHtml::link('Integration Test',array('test/integration')).'</li>';
echo '<li>'.CHtml::link('Validation Test',array('test/validation')).'</li>';
echo '<li>'.CHtml::link('Unit Test',array('test/unit')).'</li>';
echo '<li>'.CHtml::link('Real Unit Test',array('tUnitTest/admin')).'</li>';
echo '</ul></li>';
echo '</ul>';

echo '<h2>Analisi Requisiti</h2>';
echo '<ul >';
echo '<li>'.CHtml::link('Requirements',array('requirement/index')).'</li>';
echo '<li>'.CHtml::link('Sources',array('source/index')).'</li>';
echo '<li>'.CHtml::link('Use Cases',array('useCase/index')).'</li>';
echo '</ul>';


echo '<h2>Generators</h2>';
echo '<ul>';
echo '<li>'.CHtml::link('LaTeX &amp; Graphs',array('latex/index')).'</li>';
echo '<li>'.CHtml::link('C++',array('generator/decl','raw'=>0,)).'</li>';
echo '<li>'.CHtml::link('XMI ASS',array('generator/xmi','raw'=>0,'ass'=>1)).'</li>';
echo '<li>'.CHtml::link('XMI NO ASS',array('generator/xmi','raw'=>0,'ass'=>0)).'</li>';
echo '<li>'.CHtml::link('Reverse Engineer',array('src_loader/index','raw'=>0,)).'</li>';
echo '</ul>';


echo '<h2>'.CHtml::link('Spell Check',array('spellCheck/index')).'</h2>';
echo '<ul>';
echo '<li>'.CHtml::link('Packages',array('spellCheck/package')).'</li>';
echo '<li>'.CHtml::link('Classes',array('spellCheck/class')).'</li>';
echo '<li>'.CHtml::link('Attributes',array('spellCheck/attribute')).'</li>';
echo '<li>'.CHtml::link('Methods',array('spellCheck/method')).'</li>';
echo '</ul>';

echo '<h2>Extra</h2>';
echo '<ul>';
echo '<li>'.CHtml::link('Chat',array('chat')).'</li>';
echo '<li>'.CHtml::link('Weather Forecast',array('forecast')).'</li>';
echo '<li>'.CHtml::link('Tetris',array('tetris')).'</li>';
echo '<li>'.CHtml::link('Snake',array('snake')).'</li>';
echo '<li>'.CHtml::link('Nyan Tracy',array('nyan')).'</li>';
echo '</ul>';

?>