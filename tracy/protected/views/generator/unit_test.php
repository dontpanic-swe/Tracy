<?php
/** @var $test UnitTest*/


$txt = "/*!
Test di unità ".$test->public_id()."

{$test->test->description}
*/
#include \"".$test->method->class->cpp_header_file()."\"

int main() {

    {$test->method->class->name} obj;

    auto result = obj.{$test->method->name}();

    if ( result == expected_result )
        return 0;
    else
        return 1;
}";

if ( $raw )
    echo $txt;
else
{
    $this->menu []= array('label'=>'View Test', 'url'=>array('test/view',
                                                'id'=>$test->id_test));
    $this->menu []= array('label'=>'View Method', 'url'=>array('class/methodView',
                                                'id'=>$test->method->id_method));
    echo CodeGen::render_code($txt,true,'cpp-qt');
}