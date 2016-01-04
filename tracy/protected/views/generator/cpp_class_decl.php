<?php

$this->pageTitle = $class->cpp_header_file();


$this->menu []= array('label'=>'Source', 'url'=>array('impl','raw'=>$raw,
                                                      'id'=>$class->id_class) );

$guard = strtoupper($class->cpp_file_name())."_H";
echo "/*!
 * @file      ".$class->cpp_header_file()."
 * @date      ".date('Y-m-d')."
 * @brief     Declaration for $class->name
 * @author    Tracy
 * @copyright ".date('Y'). " Don't Panic
 */

#ifndef $guard
#define $guard

//begin include section
".$class->include."
//end include section

".
    $class->cpp_namespace_open()."\n".
    $class->cpp_doc()."\n". $class->cpp_decl()."\n\n".
    $class->cpp_namespace_close()."

#endif // $guard
";