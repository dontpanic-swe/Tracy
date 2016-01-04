<?php

/**
 * This is the model class for table "attribute".
 *
 * The followings are the available columns in table 'attribute':
 * @property integer $id_attribute
 * @property string $name
 * @property string $type
 * @property integer $const
 * @property integer $static
 * @property string $access
 * @property integer $id_class
 * @property string $description
 * @property integer $getter
 * @property integer $setter
 *
 * The followings are the available model relations:
 * @property Class_Prog $class
 * @property Association $association
 */
class Attribute extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Attribute the static model class
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
        return 'attribute';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, type, id_class, description', 'required'),
            array('getter, setter, const, static, id_class', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>64),
            array('type', 'length', 'max'=>128),
            array('access', 'length', 'max'=>9),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('description, id_attribute, name, type, const, static, access, id_class, signal', 'safe', 'on'=>'search'),
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
            'class' => array(self::BELONGS_TO, 'Class_Prog', 'id_class'),
            'association' => array(self::HAS_ONE, 'Association', 'id_attribute'),
        );
    }

    function full_name($separator="::")
    {
        return $this->with('class')->class->full_name($separator).
                $separator.$this->name;
    }
    
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_attribute' => 'Id Attribute',
            'name' => 'Name',
            'type' => 'Type',
            'const' => 'Const',
            'static' => 'Static',
            'access' => 'Access',
            'id_class' => 'Id Class',
            'description'=>'Description',
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

        $criteria->compare('id_attribute',$this->id_attribute);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('type',$this->type,true);
        $criteria->compare('const',$this->const);
        $criteria->compare('static',$this->static);
        $criteria->compare('access',$this->access,true);
        $criteria->compare('id_class',$this->id_class);
        $criteria->compare('description',$this->id_class);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    
    static function access_drop()
    {
        return array('private'=>'private',
                     'protected'=>'protected',
                     'public'=>'public');
    }
    
    function cpp_doc()
    {
        return "//!".preg_replace( '/\s+/', ' ', $this->description );
    }
    function cpp_decl()
    {
        return  ($this->static?'static ':'')
                .($this->const?'const ':'')
                ."$this->type $this->name;"
                ;
    }
    
    
    function accessors($indent=4)
    {
        $baseind = str_repeat(' ',$indent);
        $codeind = str_repeat(' ',$indent+4);
        $string = '';
        if ( $this->getter )
            $string .= "$baseind//! Getter per $this->name\n".
                $baseind.$this->type." get".ucfirst($this->name)."() const {\n".
                    $codeind.'return '.$this->name.";\n".
                $baseind."}\n";
        if ( $this->setter )
        {
            $param_type = $this->type;
            if ( !preg_match('/.+[*&]+$/',$param_type) )
            {
                $param_type = "const $param_type &";
            }
            $string .= "$baseind//! Setter per $this->name\n".
                $baseind."void set".ucfirst($this->name)."(".
                        $param_type." ".$this->name.") {\n".
                    $codeind.'this->'.$this->name." = ".$this->name.";\n".
                $baseind."}\n";
        }
        return $string;
    }
} 