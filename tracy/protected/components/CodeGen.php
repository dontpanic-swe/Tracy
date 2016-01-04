<?php

class CodeGen
{
    private static $geshi_file = "protected/components/geshi/geshi.php";
    /**
     * @param string $code          code to be indented
     * @param int    $width         wrap width
     * @param int    $base_indent   indentation
     * @param int    $next_indent   indentation
     * @param string $between       text between $base_indent and $next_indent
     */
    static function indent_code($code,$width,$base_indent, $next_indent, $between='*')
    {
        $arr = explode("\n",
                       wordwrap($code,$width-
                                ($base_indent+$next_indent+strlen($between))));
        
        for ($i = 1; $i < count($arr); $i++ )
        {
            $arr[$i] =  str_repeat(' ',$base_indent)
                             .$between
                             .str_repeat(' ',$next_indent)
                             .$arr[$i];
        }
        return implode("\n",$arr);
    }
    
    
    // todo: per-class members
    static function render_code($source,$line_numbers=false,$lang='cpp-qt',$class=null)
    {
        require_once self::$geshi_file;
        $geshi = new GeSHi($source, $lang);
        $geshi->add_keyword_group(42,'font-weight:bold',false,Yii::app()->db->createCommand()
                                  ->select('name')->from('class')->queryColumn()
                                 );
        $geshi->set_url_for_keyword_group(42,
                CHtml::normalizeUrl( array('class/view') ).'/{FNAME}'
                );
        if ( $line_numbers)
            $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
        return $geshi->parse_code();
    }
    
    static function generic_render_code($source,$lang,$line_numbers=false)
    {
        require_once self::$geshi_file;
        $geshi = new GeSHi($source, $lang);
        if ( $line_numbers)
            $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
        $geshi->enable_keyword_links(false);
        return $geshi->parse_code();
    }
}