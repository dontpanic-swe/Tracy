<?php

/**
 * This is the model class for table "class".
 *
 * The followings are the available columns in table 'class':
 * @property integer $id_class
 * @property string $name
 * @property integer $id_package
 * @property string $description
 * @property string $usage
 * @property integer $qobject
 * @property integer $library
 * @property string $include
 * @property string $type
 * @property string $extra_declaration
 *
 * The followings are the available model relations:
 * @property attributes0[] $attributes
 * @property Package $package
 * @property Inherit[] $inherits
 * @property Inherit[] $inherits1
 * @property Method[] $methods
 * @property Requirement[] $requirements
 * @property Class_Prog[] $children
 * @property Class_Prog[] $parents
 * @property Dependency[] $dependencies_from
 * @property Dependency[] $dependencies_to
 */
class Class_Prog extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Class_Prog the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'class';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, id_package, description, type', 'required'),
            array('id_package, qobject, library', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>64),
            array('extra_declaration, include, usage','safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_class, name, id_package, description,
                  usage, qobject, include', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'members' => array(self::HAS_MANY, 'Attribute', 'id_class'),
            'package' => array(self::BELONGS_TO, 'Package', 'id_package'),
            'children' => array(self::MANY_MANY, 'Class_Prog',
                                'inherit(parent,child)'),
            'parents' => array(self::MANY_MANY, 'Class_Prog',
                               'inherit(child,parent)'),
            'attributes0' => array(self::HAS_MANY, 'Attribute', 'id_class'),
            'methods' => array(self::HAS_MANY, 'Method', 'id_class'),
            'assoc_from' => array(self::HAS_MANY, 'Association', 'class_from'),
            'assoc_to' => array(self::HAS_MANY, 'Association', 'class_to'),
            'requirements' => array(self::MANY_MANY, 'Requirement',
                                    'package_requirement(id_package, id_requirement)'),
            'dependencies_from' => array(self::HAS_MANY, 'Dependency', 'id_from'),
            'dependencies_to' => array(self::HAS_MANY, 'Dependency', 'id_to'),
            'requirements' => array(self::MANY_MANY, 'Requirement',
                            'class_requirement(id_class, id_requirement)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_class' => 'Id Class_Prog',
            'name' => 'Name',
            'id_package' => 'Id Package',
            'description' => 'Description',
            'usage' => 'Usage',
            'qobject' => 'Qobject',
            'include' => 'Include',
            'type' => 'Type',
            'extra_declaration' => 'Extra Declaration',
            'library' => 'Library Class'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('t.id_class',$this->id_class);
        $criteria->compare('t.name',$this->name,true);
        $criteria->compare('t.id_package',$this->id_package);
        $criteria->compare('t.description',$this->description,true);
        $criteria->compare('t.usage',$this->usage,true);
        $criteria->compare('t.qobject',$this->qobject);
        $criteria->compare('t.include',$this->include,true);
        $criteria->compare('t.type',$this->type,true);
        $criteria->compare('t.library',$this->library,true);
        //$criteria->compare('package.name',$this->with('package')->package->name,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    static function type_drop()
    {
        return array('class'=>'class',
                     'abstract'=>'abstract',
                     'interface'=>'interface');
    }
    
    static function grid_columns($append=array())
    {
        $arr = array (
            //'id_class',
            array(
                'class'=>'CDataColumn',
                'type'=>'html',
                'name'=>'name',
                'value'=>'CHtml::link($data->name,
                            array("class/view","id"=>$data->id_class) )',
                
            ),
            array(
                'class'=>'CDataColumn',
                //'name'=>'package.name',
                'header'=>'Package',
                'type'=>'html',
                'value'=>'isset($data->package) ?
                            CHtml::link($data->package->full_name(" :: "),
                                        array("package/view","id"=>$data->package->id_package) ) :
                            null',
            ),
            //'id_package',
            
            array(
                'type'=>'html',
                'name' => 'type',
                'filter' => Class_Prog::type_drop(), 
                'value' => '$data->type',
            ),
            'description',
            'usage',
            'qobject:raw:Q',
            
            'library:raw:lib',
            
            //'include',
        );
        
        return array_merge($arr,$append);
    }
    
    function full_name($separator="::",$virtual_ns=false)
    {
        $pn = $this->with('package')->package->full_name($separator,$virtual_ns);
        if ( strlen($pn) > 0 )
            $pn .= $separator;
        return $pn.$this->name;
    }
    
    function ancestor_level($lvl)
    {
        if ( $lvl <= 0 )
            return null;
        $p = $this->with('package')->package;
        
        for ( $i = $lvl; $i > 1 && $p != null; $i-- )
        {
            $p = $p->with('parent0')->parent0;
        }
        
        return $p;
    }
    
    function cpp_doc($indent=0)
    {
        $indent+=1; // make room for *
        $ind = "\n".str_repeat(' ',$indent);
        return "/*!$ind"
        ."* @class   $this->name$ind"
        ."* @details ".CodeGen::indent_code( $this->description,
                                            80,$indent,10).$ind
        .( strlen(trim($this->usage)) == 0 ? '' : 
            "* @par Usage $ind"
            ."* ".CodeGen::indent_code( $this->usage,80,$indent,1).$ind
        )
        ."*/";
    }
    
    function cpp_decl($indent=0)
    {
        $indo = "\n".str_repeat(' ',$indent);
        $ind = "\n".str_repeat(' ',$indent+4);
        
        $r = "class $this->name";
        $this->with('parents');
        
        $parrarr = array();
        foreach($this->parents as $p)
            $parrarr []= $p->full_name();
        
        if ( count($parrarr) )
            $r .= " : " . implode(", ",$parrarr);
        
        $r .= "$indo{";
        
        if ( $this->qobject )
            $r .= "$ind Q_OBJECT";
            
        if ( strlen(trim($this->extra_declaration)) > 0 )
        {
            $decls = explode("\n",$this->extra_declaration);
            $r .= "$ind//begin extra declarations";
            foreach ( $decls as $d )
                $r .= $ind.$d;
            $r .= "$ind//end extra declarations";
        }
        
        // static data
        $access = array('private','protected','public','signal');
        foreach ( $access as $acc )
        {
            $attr = Attribute::model()->findAll(array(
                'condition'=>"id_class=:id and static=1 and access='$acc'",
                'params'=>array('id'=>$this->id_class),
                'order'=>'name'
            ));
            if ( count($attr) )
            {
                $r .= "$indo$acc:";
                foreach($attr as $a )
                    $r .= $ind.$a->cpp_doc($indent+4).$ind.$a->cpp_decl();
                $r .= "\n";
            }
        }
        // static functions
        foreach ( $access as $acc )
        {
            $attr = Method::model()->findAll(array(
                'condition'=>"id_class=:id and static=1 and access='$acc'",
                'params'=>array('id'=>$this->id_class),
                'order'=>'name'
            ));
            if ( count($attr) )
            {
                $r .= "$indo$acc:";
                foreach($attr as $a )
                    $r .= $ind.$a->cpp_doc($indent+4).$ind.$a->cpp_decl();
                $r .= "\n";
            }
        }
        
        // non-static data
        $accessors = '';
        foreach ( $access as $acc )
        {
            $attr = Attribute::model()->findAll(array(
                'condition'=>"id_class=:id and static=0 and access='$acc'",
                'params'=>array('id'=>$this->id_class),
                'order'=>'name'
            ));
            if ( count($attr) )
            {
                $r .= "$indo$acc:";
                foreach($attr as $a )
                {
                    $r .= $ind.$a->cpp_doc($indent+4).$ind.$a->cpp_decl();
                    $accessors .= $a->accessors($indent+4);
                }
                $r .= "\n";
            }
        }
        // accessors
        if ( strlen($accessors) > 0 )
            $r.= $indo."public:\n".$accessors;
        
        // non-static functions
        foreach ( $access as $acc )
        {
            $attr = Method::model()->findAll(array(
                'condition'=>"id_class=:id and static=0 and access='$acc'",
                'params'=>array('id'=>$this->id_class),
                'order'=>'name, const, id_method'
            ));
            if ( count($attr) )
            {
                $r .= "$indo$acc:";
                foreach($attr as $a )
                    $r .= $ind.$a->cpp_doc($indent+4).$ind.$a->cpp_decl();
                $r .= "\n";
            }
        }
        
        
        
        return "$r$indo};";
    }
    
    function cpp_namespace_open($indent=0)
    {
        $this->with('package');
        $p = $this->package;
        $ns = array();
        do
        {
            $ns []= $p->name;
            $p->with('parent0');
            $p = $p->parent0;
        }
        while ( isset($p) );
        
        $ns = array_reverse($ns);
        $r = "";
        foreach ( $ns as $n )
        {
            $r .= str_repeat(' ',$indent)."namespace $n {\n";
        }
        return $r;
    }
    function cpp_namespace_close($indent=0)
    {
        $this->with('package');
        $p = $this->package;
        $ns = array();
        do
        {
            $ns []= $p->name;
            $p->with('parent0');
            $p = $p->parent0;
        }
        while ( isset($p) );
        
        $r = "";
        foreach ( $ns as $n )
        {
            $r .= str_repeat(' ',$indent)."} // $n\n";
        }
        return $r;
    }
    
    function cpp_file_name()
    {
        return strtolower(preg_replace('/([[:alnum:]])([[:upper:]]+)/','\1_\2',$this->name));
    }
    
    function cpp_header_file()
    {
        return $this->cpp_file_name().".h";
    }
    
    function full_header_file()
    {
        return $this->package->full_name("/")."/".$this->cpp_header_file();
    }
    
    function cpp_source_file()
    {
        return $this->cpp_file_name().".cpp";
    }
    
    static function find_by_qualified_name($name,Package $namespace=null)
    {
        $name = trim($name);
        if ( strlen($name) == 0 )
            return null;
        
        $model = self::model();
        $name_parts = explode('::',$name);
        
        if ( count($name_parts) == 1 )
        {
            if ( $namespace != null && !$namespace->isNewRecord )
            {
                return $model->find('name = :name and id_package = :idp',
                                    array('name'=>$name,
                                          'idp'=>$namespace->id_package)
                                   );
            }
            return $model->find('name = :name', array('name'=>$name) );
        }
        
        $namespace = Package::model()->find('parent is null and name = :name',
                                            array('name'=>$name_parts[0]) );
        for ( $i = 1; $i < count($name_parts)-1; $i++ )
        {
            $namespace = Package::model()->find('parent = :idp and name = :name',
                                            array('name'=>$name_parts[$i],
                                                  'idp'=>$namespace->id_package)
                                            );
        }
        
        if ( $namespace == null )
            return null;
        
        return $model->find('name = :name and id_package = :idp',
                                    array('name'=>$name_parts[count($name_parts)-1],
                                          'idp'=>$namespace->id_package)
                                   );
    }
    
    function inh_methods($include_self=false)
    {
        $methods = $include_self ? $this->methods : array();
        if ( isset($this->with('parents')->parents) )
        {
            foreach ( $this->parents as $p )
                $methods = array_merge($methods,$p->inh_methods(true));
        }
        return $methods;
    }
} 