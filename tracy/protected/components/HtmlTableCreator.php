<?php

class HtmlTableCreator extends TableCreator
{
    function id() { return 'html'; }
    function begin_table($ncols) { return '<table>'; }
    function end_table() { return '</table>'; }
    function begin_heading_row() { return '<tr>'; }
    function end_heading_row() { return '</tr>'; }
    function heading($title,$spanning=null)
    {
        return "<th". ( $spanning != null ? " colspan='$spanning'" : "" ) . ">".
                    htmlspecialchars($title)."</th>";
    }
    function caption($text)
    {
        return "<caption>".htmlspecialchars($text)."</caption>";
    }
    
    function row(array $cells)
    {
        $row = "";
        foreach ( $cells as $cell )
        {
            $row .= "<td>".htmlspecialchars($cell)."</td>";
        }
        return "<tr>$row</tr>";
    }
    
    function title($level,$text,$title=null)
    {
        return "<h$level>".htmlspecialchars($text)."</h$level>";
    }
    function begin_list()
    {
        return "<ul>";
    }
    function end_list()
    {
        return "</ul>";
    }
    function list_item($text)
    {
        return "<li>".htmlspecialchars($text)."</li>";
    }
    
    function begin_desc_list()
    {
        return "<dl>";
    }
    function end_desc_list()
    {
        return "</dl>";
    }
    function desc_list_item($def,$text)
    {
        return "<dt>".htmlspecialchars($def)."</dt>\n<dd>$text</dd>\n";
    }
}