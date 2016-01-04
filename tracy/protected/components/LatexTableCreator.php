<?php

class LatexTableCreator extends TableCreator
{
    private $heading_before = false;
    public $hline = "\\hline";
    
    function id() { return 'latex'; }
    function begin_table($ncols)
    {
        $cols = "|";
        if ( is_numeric($ncols) )
        {
            for ( $i = 0; $i < $ncols; $i++ )
                $cols .= "l|";
        }
        else if ( is_array($ncols) )
        {
            foreach($ncols as $c)
            {
                if ( $c <= 0 )
                    $cols .= "l|";
                else
                    $cols .= "p{".$c."cm}|";
            }
        }
        else if ( is_string($ncols) )
        {
            $cols = $ncols;
        }
        return "\\begin{longtable}{{$cols}}\n";
    }
    function end_table()
    {
        return "\\end{longtable}\n";
    }
    function begin_heading_row()
    {
        $this->heading_before = false ;
        return "$this->hline\n";
    }
    function end_heading_row()
    {
        return "\\tabularnewline\n$this->hline\n";
    }
    function heading($title,$spanning=null)
    {
        $str = "";
        
        if ( $this->heading_before )
            $str .= " & ";
            
        if ( $spanning != null )
            $str .= "\\multicolumn{{$spanning}}{|c|}{";
            
        $str .= $title;
        
        if ( $spanning != null )
            $str .= "}";
            
        $this->heading_before = true;
        
        return $str;
    }
    function caption($text)
    {
        return "\\caption{{$text}} \\tabularnewline\n";
    }
    
    function row(array $cells)
    {
        $str = "";
        for ( $i = 0; $i < count($cells)-1; $i++ )
        {
            $str .= $cells[$i]." & ";
        }
        $str .= $cells[count($cells)-1];
        return $str . $this->end_heading_row();
    }
    
    function child_indentation($level)
    {
        if ( $level == 0 )
            return "";
        
        $top = 0.2;
        $middle = 0.1;
        $bottom = 0;
        
        $left = 0;
        $right = 1;
        $step = 0.2;
        
        //$code = "\draw ($left,$top) -- ($left,$bottom);";
        
        $x = $left + $level * $step;
        $code = "\\draw [->, thick] ($x,$top) -- ($x,$middle) -- ($right,$middle);";
        
        return "\\begin{tikzpicture}\n$code\n\\end{tikzpicture}";
    
        //\cline{2-6} ??
    }
    function link($target,$text)
    {
        return "\\hyperlink{{$target}}{{$text}}";
    }
    function anchor($target,$text)
    {
        return "\\hypertarget{{$target}}{{$text}}";
    }
    
    
    function title($level,$text,$title=null)
    {
        if ( $title != null )
            $title = "[$title]";
        switch ( $level )
        {
            case 1: return "\\section$title{{$text}}\n";
            case 2: return "\\subsection$title{{$text}}\n";
            case 3: return "\\subsubsection$title{{$text}}\n";
            case 4: return "\\paragraph$title{{$text}}\n";
            case 5: return "\\subparagraph$title{{$text}}\n";
            case 6: return "\\subsubparagraph$title{{$text}}\n";
            default: return "$text\n";
        }
    }
    function begin_list()
    {
        return "\\begin{itemize}\n";
    }
    function end_list()
    {
        return "\\end{itemize}\n";
    }
    function list_item($text)
    {
        return "\\item $text\n";
    }
    
    
    function begin_desc_list()
    {
        return "\\begin{description}\n";
    }
    function end_desc_list()
    {
        return "\\end{description}\n";
    }
    function desc_list_item($def,$text)
    {
        return "\\item[$def] \\hfill \\\\\n $text\n";
    }
}