<?php
/**

file
    /* ...          -> file_comment_block
    # ...
    //(tracy){      -> file_include
    // ...
    namespace ...   -> namespace_decl
    class ...       -> class_decl
    (empty)
    ...             -> error
    
file_comment_block
    @class ...      -> class_doc
    ...
    * /             -> file

file_include
    //(tracy)}      -> file
    ...             +
    
error

namespace_decl      +> file

class_doc
    @description    +
    @sec usage      +
    ...

class_decl
    class name      +
    : inherit       +
    {               +
    Q_OBJECT        +
    public:         +
    //(tracy){      -> extra_decl
    //              -> attribute_doc
    (empty)
    /*              -> method_doc
    ... ( ... )     -> method_decl
    ...             -> attribute_decl
    
extra_decl
    //(tracy)}      -> class_decl
    ...             +
    
attribute_doc       +]attribute_decl

attribute_decl      +> class

method_doc
    @pre            +
    @post           +
    @param          +
    @description    +
    ...
    * /             -]method_decl

method_decl         +
*/

class CppLoader
{
    private $s; ///< stream
    public $class; ///< model
    public $namespace; ///< model array
    public $parents; ///< model array
    public $attributes;///< model array
    public $methods;///< model array
    private $current_access='private';
    
    function CppLoader($code)
    {
        global $regex_id, $regex_qual_id,$regex_type_name;
        $regex_id = "[[:alnum:]_]+";
        $regex_qual_id = "(?:$regex_id::)*$regex_id";
        $regex_type_name = "(?:const)?\s*$regex_qual_id". 
                        "(?:<(?:[[:alnum:]_() ]|(?:<(?:[[:alnum:]_() ]|(?:<[[:alnum:]_() ]+>)?)+>)?)+>)?".
                        "\**&?";

        $this->s = fopen('data://text/plain,' . $code,'r');
        $this->namespace = array();
        $this->parents = array();
        $this->class = Class_Prog::model();
        $this->attributes = array();
        $this->methods = array();
    }
    
    function append_namespace($name)
    {
        if ( count($this->namespace) == 0 )
            $ns = Package::model()->find('parent is null and name = :name',
                                         array('name'=>$name) );
        else
        {
            $prev = $this->namespace[count($this->namespace)-1];
            if ( isset($prev->id_package) )
                $ns = Package::model()->find('parent = :pid and name = :name',
                                             array('pid'=>$prev->id_package,
                                                   'name'=>$name));
        }
        
        if ( $ns == null )
        {
            $ns = new Package;
            $ns->name = $name;
        }
        
        $this->namespace []= $ns;
    }
    
    function append_parent($name)
    {
        $this->parents []= $p = Class_Prog::find_by_qualified_name($name,
                                $this->namespace[count($this->namespace)-1] );
    }
    
    function scan()
    {
        return $this->file();
    }
    
    private function eof()
    {
        return feof($this->s);
    }
    
    private function get_line()
    {
        return trim(stream_get_line($this->s,256,"\n"));
    }
    
    private function get_line_verbatim()
    {
        return stream_get_line($this->s,256,"\n");
    }

    /// toplevel declarations
    private function file()
    {
        global $regex_id, $regex_qual_id;
        while(!$this->eof())
        {
            $l = $this->get_line();
            if ( preg_match("|^/\*|",$l) )
                $this->file_comment_block();
            else if ( preg_match("/namespace ($regex_id)/",$l,$match) )
            {
                $this->append_namespace($match[1]);
            }
            else if ( $l == "//begin include section" )
            {
                $this->file_include();
            }
            else if ( preg_match("/^class\s+($regex_id)".
                                 "(\s*:\s*($regex_qual_id(\s*,\s*$regex_qual_id)*))?/",
                                 $l,$match) )
            {
                $this->class_decl($match[1],trim($match[3]));
            }
        }
    }
    
    /// comment block at file scope
    private function file_comment_block()
    {
        global $regex_id;
        while(!$this->eof())
        {
            $l = $this->get_line();
            if ( preg_match("|\*/|",$l) )
                return;
            else if ( preg_match("/@class/",$l) )
                return $this->class_doc();
        }
    }
    
    /// verbatim code
    private function file_include()
    {
        $lines = array();
        while(!$this->eof())
        {
            $l = $this->get_line_verbatim();
            if ( trim($l) == '//end include section' )
                break;
            $lines []= $l;
        }
        $this->class->include = implode("\n",$lines);
    }
    
    private function class_doc()
    {
        while(!$this->eof())
        {
            $l = $this->get_line();
            if ( preg_match("|\*/|",$l) )
                return;
            else if ( preg_match("/\s*\*?\s*@details\s+(.*)/",$l,$match) )
            {
                $this->class->description .= $match[1]." ";
                if ( ! $this->class_doc_desc() )
                    return;
            }
            else if ( preg_match("/\s*\*?\s*@par\s+Usage/",$l) )
            {
                if ( ! $this->class_doc_usage() )
                    return;
            }
        }
    }
    
    private function class_doc_desc()
    {
        while(!$this->eof())
        {
            $l = $this->get_line();
            if ( preg_match("|\*/|",$l) )
                return false;
            else if ( preg_match("/\s*\*?\s*@par\s+Usage/",$l) )
            {
                return $this->class_doc_usage();
            }
            preg_match("/\s*\*?\s*(.*)/",$l,$match);
            $l = trim($match[1]);
            if ( strlen($l) == 0 )
                return true;
            $this->class->description .= "$l ";
        }
        return false;
    }
    
    private function class_doc_usage()
    {
        while(!$this->eof())
        {
            $l = $this->get_line();
            if ( preg_match("|\*/|",$l) )
                return false;
            else if ( preg_match("/\s*\*?\s*@details\s+(.*)/",$l,$match) )
            {
                $this->class->description .= $match[1]." ";
                return $this->class_doc_desc();
            }
            preg_match("/\s*\*?\s*(.*)/",$l,$match);
            $l = trim($match[1]);
            if ( strlen($l) == 0 )
                return true;
            $this->class->usage .= "$l ";
        }
        return false;
    }
    
    private function class_decl($name,$inherit_list)
    {
        $this->class->name = $name;
        if ( strlen($inherit_list) > 0 )
            $parents = preg_split('/\s*,\s*/',$inherit_list);
        // todo else search as next line
        
        foreach ( $parents as $p )
            $this->append_parent($p);
        
        while(!$this->eof())
        {
            $l = $this->get_line();
            if ( preg_match("/Q_OBJECT/",$l) )
                $this->class->qobject = 1;
            else if ( $l == "//begin extra declarations" )
            {
                $this->class_extra_decl();
            }
            else if ( preg_match("#//[/!]\s*(.*)#",$l,$match) )
            {
                $this->attribute_decl($match[1]);
            }
            else if ( preg_match("#/*[*!]#",$l) )
            {
                $this->method_doc();
            }
            else if ( preg_match("/(private|protecter|public|signal)\s*:/",$l,$match) )
            {
                $this->current_access = $match[1];
            }
        }
    }
    
    private function attribute_decl($desc)
    {
        global $regex_id,$regex_type_name;
        
        while(!$this->eof())
        {
            $l = $this->get_line();
            if ( strlen($l) > 0 )
            {
                preg_match("/(\\bstatic\\b)?\s*(\\bconst\\b)?\s*".
                           "($regex_type_name)\s+($regex_id)\s*;/",
                           $l, $match);
                $att = new Attribute; // todo locate existing attributes
                $att->access = $this->current_access;
                $att->static = $match[1] == 'static';
                $att->const = $match[2] == 'const';
                $att->type = $match[3];
                $att->name = $match[4];
                $att->description = $desc;
                $this->attributes []= $att;
                return;
            }
        }
    }
    
    private function method_doc()
    {
        // todo
        while(!$this->eof())
        {
            $l = $this->get_line();
            if ( preg_match("|\*/|",$l) )
                return;
        }
    }
    
    
    
    /// verbatim code
    private function class_extra_decl()
    {
        $lines = array();
        while(!$this->eof())
        {
            $l = $this->get_line();
            if ( $l == '//end extra declarations' )
                break;
            $lines []= $l;
        }
        $this->class->extra_declaration = implode("\n",$lines);
    }
}