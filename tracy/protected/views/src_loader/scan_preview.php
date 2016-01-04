<?php

    $this->breadcrumbs = array(
        'Upload source'=>array('index'),
        'Preview'=>array('view'),
        'Scan'
    );
    
    $this->menu=array(
        array('label'=>'Edit', 'url'=>array('index','edit'=>1)),
        array('label'=>'View', 'url'=>array('view')),
    );
    
echo "<h1>Scanning results</h1>";
echo "<h2>Namespace</h2><dl>";
foreach($loader->namespace as $ns)
{
    $n = $ns->isNewRecord ? ' (new)':'';
    echo "<dt>$ns->name$n</dt><dd>$ns->description</dd>";
}
echo "</dl><h2>Inherits</h2><dl>";
foreach($loader->parents as $p)
{
    $n = $p->isNewRecord ? ' (new)':'';
    echo "<dt>$p->name$n</dt><dd><pre>";
    print_r($p->attributes);
    echo "</pre></dd>";
}
echo "</dl>";
echo "<h2>Class</h2><pre>";
print_r($loader->class->attributes);
echo "<h2>Attributes</h2><dl>";
foreach($loader->attributes as $p)
{
    $n = $p->isNewRecord ? ' (new)':'';
    echo "<dt>$p->name$n</dt><dd><pre>";
    print_r($p->attributes);
    echo "</pre></dd>";
}
echo "</dl>";
echo "<h2>Methods</h2>";
echo "todo";
print_r($loader->methods);