<?php

echo '<ul>';
echo '<li>'.CHtml::link('Packages',array('package')).'</li>';
echo '<li>'.CHtml::link('Classes',array('class')).'</li>';
echo '<li>'.CHtml::link('Attributes',array('attribute')).'</li>';
echo '<li>'.CHtml::link('Methods',array('method')).'</li>';
echo '</ul>';