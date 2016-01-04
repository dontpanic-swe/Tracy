<?php

/**
 * This is the model class for table "package".
 *
 * The followings are the available columns in table 'package':
 * @property integer $id_package
 * @property string $name
 * @property integer $parent
 * @property string $description
 * @property integer $virtual
 *
 * The followings are the available model relations:
 * @property Class_Prog[] $classes
 * @property Package $parent0
 * @property Package[] $packages
 * @property IntegrationTest $integrationTests
 * @property Requirement[] $requirements
 */
class Package extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Package the static model class
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
        return 'package';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, description', 'required'),
            array('parent, virtual', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>32),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_package, name, parent, description', 'safe', 'on'=>'search'),
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
            'classes' => array(self::HAS_MANY, 'Class_Prog', 'id_package'),
            'parent0' => array(self::BELONGS_TO, 'Package', 'parent'),
            'packages' => array(self::HAS_MANY, 'Package', 'parent'),
            'integration' => array(self::HAS_ONE, 'IntegrationTest', 'id_package'),
            'requirements' => array(self::MANY_MANY, 'Requirement', 'package_requirement(id_package, id_requirement)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_package' => 'Id Package',
            'name' => 'Name',
            'parent' => 'Parent',
            'description' => 'Description',
            'virtual' => 'Virtual',
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

        $criteria->compare('id_package',$this->id_package);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('parent',$this->parent);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('virtual',$this->virtual,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    /// @return array of all parents
    public function parent_array($include_self=false)
    {
        $this->with('parent0');
        
        if ( $this->parent0 != null )
        {
            $a = $this->parent0->parent_array();
            array_push($a,$this->parent0);
            if ( $include_self )
                array_push($a,$this);
            return $a;
        }
        
        return $include_self ? array($this) : array();
    }
    
    public function full_name($separator="::",$virtual=true)
    {
        $this->with('parent0');
        if ( $this->parent0 != null )
        {
            $name = $this->virtual && !$virtual ? '' :  $separator . $this->name;    
            return $this->parent0->full_name($separator) . $name;
        }
        else if ( $this->virtual && !$virtual )
            return '';
        else
            return $this->name;
    }
    
    function all_descendant()
    {
        $this->with('packages');
        $packages = array();
        $packages []= $this;
        $packages = array_merge($packages,$this->packages);
        
        foreach($this->packages as $p)
        {
            $packages = array_merge($packages,$p->all_descendant());
        }
        return $packages;
    }
    
    function all_descendant_id()
    {
        $this->with('packages');
        $packages = array();
        $packages []= $this->id_package;
        
        foreach($this->packages as $p)
        {
            $packages []= $p->id_package;
            $packages = array_merge($packages,$p->all_descendant_id());
        }
        return $packages;
    }
    
    
    function instability()
    {
        $ce = $this->efferente();
        $ca = $this->afferente();
        if ( $ca + $ce == 0 )
            return 0;
        return $ce / ( $ca+$ce);
    }
    
    
    function xerrente($id_this,$id_that,$skip_id=null)
    {
        $dependencies = $this->get_dependencies();
        
        
        if ( !is_array($skip_id) )
        {
            $skip_id = $this->all_descendant_id();
            $lib = Package::model()->find('name="Qt"');
            if ( $lib != null )
                $skip_id []= $lib->id_package;
        }
        
        $val = 0;
        
        foreach($dependencies as $d)
        {
            if ( $d[$id_this] == $this->id_package &&
                !in_array($d[$id_that],$skip_id) )
                $val++;
        }
        
        $this->with('packages');
        foreach($this->packages as $p)
        {
            $val += $p->xerrente($id_this,$id_that,$skip_id);
        }
        
        return $val;
    }
    
    function efferente()
    {
        return $this->xerrente("id_from","id_to");
    }
    
    function afferente()
    {
        return $this->xerrente("id_to","id_from");
    }
    
    /**
     * returns array with dependencies to other packages
     * @param bool $type if false all dependencies will be the same,
     *                   otherwise "attribute" or "inheritance"
     */
    function get_dependencies($type=true)
    {
        if ( $type )
        {
            $attribute   = "attribute";
            $inheritance = "inheritance";
        }
        else
        {
            $attribute   = "dependency";
            $inheritance = "dependency";
        }
        $cmd = yii::app()->db->createCommand(
<<<SQL
select distinct pf.name as name_from, pf.id_package as id_from,
                pt.name as name_to, pt.id_package as id_to,
                "$attribute" as type
from association a 
join class cf on a.class_from = cf.id_class
join class ct on a.class_to = ct.id_class
join package pf on cf.id_package = pf.id_package
join package pt on ct.id_package = pt.id_package
where pt.id_package!=pf.id_package and (pt.id_package=:id or pf.id_package=:id)
union(

SELECT DISTINCT pf.name AS name_from, pf.id_package AS id_from,
                pt.name AS name_to, pt.id_package AS id_to,
                "$inheritance" as type
FROM inherit a
JOIN class cf ON a.child = cf.id_class
JOIN class ct ON a.parent = ct.id_class
JOIN package pf ON cf.id_package = pf.id_package
JOIN package pt ON ct.id_package = pt.id_package
WHERE pt.id_package != pf.id_package and (pt.id_package=:id or pf.id_package=:id)

)
SQL
        );
        $cmd->bindValue(':id',$this->id_package);
        return $cmd->queryAll();
    }
} 