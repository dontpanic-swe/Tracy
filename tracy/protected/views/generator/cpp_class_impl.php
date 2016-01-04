<?php

$this->pageTitle = $class->cpp_source_file();


$this->menu []= array('label'=>'Header', 'url'=>array('decl','raw'=>$raw,
                                                      'id'=>$class->id_class));

echo "/*!
 * @file      ".$class->cpp_source_file()."
 * @date      ".date('Y-m-d')."
 * @brief     Implementation file for $class->name
 * @author    Tracy
 * @copyright ".date('Y'). " Don't Panic
 */
";

echo '#include "'.$class->cpp_header_file()."\"\n\n".
     $class->cpp_namespace_open()."\n";

$class->with('methods,members');

foreach($class->members as $att )
    if ( $att->static )
        echo "$att->type $class->name::$att->name;\n";
echo "\n";

foreach($class->methods as $meth)
    if ( !$meth->abstract )
    {
        echo "$meth->return $class->name::$meth->name (".$meth->cpp_args().") ".
        ($meth->const?'const ':'') ."{
    //! @todo Implement $class->name::$meth->name
}\n\n";
    }
    
echo $class->cpp_namespace_close();