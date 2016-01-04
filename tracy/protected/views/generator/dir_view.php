<?php

$classes = Class_Prog::model()->with('package')->findAll('library = 0');

foreach($classes as $class)
{
    echo $class->package->full_name("/")."/".$class->cpp_header_file()."\n";
    if ( $sources )
        echo $class->package->full_name("/")."/".$class->cpp_source_file()."\n";
}


?>