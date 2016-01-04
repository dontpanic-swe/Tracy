<?php
$this->breadcrumbs=array(
    'LaTeX'
);
$this->pageTitle="LaTeX";
$this->bg_image = "http://{$_SERVER['HTTP_HOST']}/logo/lolbot.png";
//$this->bg_image = "http://{$_SERVER['HTTP_HOST']}/logo/logo_right.png";

echo '<h1>LaTeX</h1>';
//echo '<div style="display:inline-block">';



echo '<h2>Qualifica</h2>';
echo '<ul>';
echo '<li>'.CHtml::link('CAC',array('latex/cac')).'</li>';
echo '<li>'.CHtml::link('DdP',array('latex/ddp')).'</li>';
echo '<li>'.CHtml::link('Requisiti / Classi',array('latex/reClass')).'</li>';
echo '<li>'.CHtml::link('Classi / Requisiti',array('latex/classReq')).'</li>';
echo '</ul>';

echo '<h2>Test</h2>';
echo '<h3>Validation</h3>';
echo '<ul>';
echo '<li>'.CHtml::link('Validation - Description',array('latex/validationTest')).'</li>';
echo '<li>'.CHtml::link('Validation - Requirement',array('latex/validationTracking')).'</li>';
echo '<li>'.CHtml::link('Requirement - Validation',array('latex/requirementValidation')).'</li>';
echo '</ul>';
echo '<h3>Integration</h3>';
echo '<ul>';
echo '<li>'.CHtml::link('Integration - Component',array('latex/integration')).'</li>';
echo '<li>'.CHtml::link('Component - Integration',array('latex/componentIntegration')).'</li>';
echo '</ul>';
echo '<h3>System</h3>';
echo '<ul>';
echo '<li>'.CHtml::link('System Test- Requirement',array('latex/SystemTest')).'</li>';
echo '<li>'.CHtml::link('Requirement - System Test',array('latex/RequirementTest')).'</li>';
echo '</ul>';
echo '<h3>Unit</h3>';
echo '<ul>';
echo '<li>'.CHtml::link('Daniele Test',array('latex/danieleTest')).'</li>';
echo '<li>'.CHtml::link('Daniele Tracking',array('latex/danieleTracking')).'</li>';
//echo '<li>'.CHtml::link('Unit Test- Method',array('latex/unitTracking')).'</li>';
echo '</ul>';

echo '<h2>Progettazione</h2>';
echo '<ul>';
echo '<li>'.CHtml::link('PDCA',array('latex/pdca','project'=>7,
                                     'date_start'=>'2013-01-16',
                                     'date_end'=>'2013-01-30')).'</li>';
echo '<li>'.CHtml::link('Requirement -> Component',array('latex/RequirementComponent')).'</li>';
echo '<li>'.CHtml::link('Requirement -> Component (Compact)',array('latex/RequirementComponentCompact')).'</li>';
echo '<li>'.CHtml::link('Component -> Requirement',array('latex/ComponentRequirement')).'</li>';
echo '<li>'.CHtml::link('Components',array('latex/Components')).'</li>';
echo '<li>'.CHtml::link('Component Coupling',array('ComponentCoupling')).'</li>';
echo '<li>'.CHtml::link('Classes',array('latex/class')).'</li>';
echo '<li>'.CHtml::link('Requirement - System Test - Validation',array('latex/rsysva')).'</li>';
echo '</ul>';


echo '<h2>Analisi Requisiti</h2>';
echo '<ul >';
echo '<li>'.CHtml::link('Requirement -> Source',array('latex/RequirementSource')).'</li>';
echo '<li>'.CHtml::link('Requirement -> Source (Compact)',array('latex/RequirementSourceCompact')).'</li>';
echo '<li>'.CHtml::link('Source -> Requirement',array('latex/SourceRequirement')).'</li>';
echo '<li>'.CHtml::link('Requirement Summary',array('latex/RequirementSummary')).'</li>';
echo '<li>'.CHtml::link('Use Cases',array('latex/UseCases')).'</li>';
echo '<li>'.CHtml::link('Requirement Apportion',array('latex/RequirementApportion')).'</li>';

echo '<li>'.CHtml::link('"Graficone" Use Case',array('latex/Graficone')).'</li>';
echo '<li>'.CHtml::link('PDCA',array('latex/pdca','project'=>1,
                                     'date_start'=>'2012-11-20',
                                     'date_end'=>'2012-12-20')).'</li>';
echo '</ul>';

//echo '</div>';
//echo "<img width='400' style='float:right' src='http://{$_SERVER['HTTP_HOST']}/logo/lolbot.png'/>";
