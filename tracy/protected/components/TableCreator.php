<?php

class TableCreator
{
    function id() { return null; }
    function begin_table($ncols){}
    function end_table(){}
    function begin_heading_row(){}
    function end_heading_row(){}
    function heading($title,$spanning=null){}
    function heading_row(array $headings)
    {
        $r = $this->begin_heading_row();
        foreach($headings as $k => $v )
        {
            if ( is_numeric($v) )
                $r .= $this->heading("$k",$v);
            else
                $r .= $this->heading("$v");
        }
        return $r.$this->end_heading_row();
    }
    function caption($text){}
    function row(array $cells){}
    function child_indentation($level)
    {
        $str = "";
        for ( $i = 0; $i < $level; $i++ )
        {
            $str .= ".";
        }
        return $str;
    }
    function link($target,$text)
    {
        return $text;
    }
    function anchor($target,$text)
    {
        return $text;
    }
    
    function title($level,$text,$title=null)
    {
        return $text;
    }
    function begin_list() {}
    function end_list() {}
    function list_item($text)
    {
        return $text;
    }
    function begin_desc_list() {}
    function end_desc_list() {}
    function desc_list_item($def,$text)
    {
        return "$def: $text";
    }
}