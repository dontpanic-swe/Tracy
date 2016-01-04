<?php

/**
 * This is the model class for table "requirement".
 *
 * The followings are the available columns in table 'requirement':
 * @property integer $id_requirement
 * @property integer $category
 * @property integer $priority
 * @property integer $parent
 * @property string $description
 * @property integer $validation0
 *
 * The followings are the available model relations:
 * @property RequirementCategory $category0
 * @property Requirement $parent0
 * @property Requirement[] $requirements
 * @property RequirementPriority $priority0
 * @property Source[] $sources
 * @property SystemTest $system_test
 * @property Package[] $packages
 * @property RequirementValidation $validation0
 * @property SystemTest[] $systemTests
 * @property ValidationTest[] $validationTests
 */
class Requirement extends CActiveRecord
{
    
    public $sources_json;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Requirement the static model class
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
		return 'requirement';
	}
    
    function sourcesValidatorRule($attribute)
    {
        $srcarr = json_decode ( $this->$attribute );
        if ( !is_array($srcarr) || count($srcarr) < 1 )
        {
            $this->addError($attribute,'Must have at least a source');
            return false;
        }
        foreach ( $srcarr as $src )
        {
            if ( Source::model()->findByPk($src) == null )
            {
                $this->addError($attribute,'Invalid source detected');
                return false;
            }
            
        }
        
        return true;
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('sources_json','sourcesValidatorRule'),
            array('apported','boolean'),
			array('category, priority, description, sources_json, apported', 'required'),
			array('category, priority, parent, validation', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_requirement, category, priority, parent, description', 'safe', 'on'=>'search'),
		);
	}

    function save_sources() 
	{
        echo 'saved~'.$this->sources_json."~";
        
        SourceRequirement::model()->deleteAll('id_requirement=:idreq',array('idreq'=>$this->id_requirement));
        $sarr = array_unique(json_decode($this->sources_json));
        
        foreach ( $sarr as $s )
        {
            $sr = new SourceRequirement();
            $sr->id_requirement = $this->id_requirement;
            $sr->id_source = $s;
            $sr->save(false);
        }
    }
    
    /**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'category0' => array(self::BELONGS_TO, 'RequirementCategory', 'category'),
			'parent0' => array(self::BELONGS_TO, 'Requirement', 'parent'),
			'requirements' => array(self::HAS_MANY, 'Requirement', 'parent'),
			'priority0' => array(self::BELONGS_TO, 'RequirementPriority', 'priority'),
			'sources' => array(self::MANY_MANY, 'Source', 'source_requirement(id_requirement, id_source)'),
            'system_test' => array(self::HAS_ONE, 'SystemTest', 'id_requirement'),
            'packages' => array(self::MANY_MANY, 'Package', 'package_requirement(id_requirement, id_package)'),
            'validation0' => array(self::BELONGS_TO, 'RequirementValidation', 'validation'),
            'systemTest' => array(self::HAS_ONE, 'SystemTest', 'id_requirement'),
            'validationTest' => array(self::HAS_ONE, 'ValidationTest', 'id_requirement'),
            'classes' => array(self::MANY_MANY, 'Class_Prog',
                            'class_requirement(id_requirement, id_class)'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_requirement' => 'Id Requirement',
			'category' => 'Category',
			'priority' => 'Priority',
			'parent' => 'Parent',
			'description' => 'Description',
            'apported'=>'Apported',
            'validation'=>'Validation',
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

		$criteria->compare('t.id_requirement',$this->id_requirement);
        

        $criteria->compare('t.category',$this->category);
        $criteria->compare('t.priority',$this->priority);
        
		$criteria->compare('t.parent',$this->parent);
		$criteria->compare('t.description',$this->description,true);
		$criteria->compare('t.validation',$this->validation,true);
        
        //$criteria->order = 't.parent, t.id_requirement';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination'=>false,
		));
	}
    
    /// @warning HARD CODED DB PK!!!!!!!!!!!
    public function public_id()
    {
        $this->with('category0');
        
        $cat = $this->category0->name[0];
        if ( $cat == 'D' )
            $cat = 'Q';
        return "R".($this->priority-4).$cat.$this->id_numer();
    }
    
    function id_numer()
    {
        $this->with('parent0');
        if ( isset($this->parent0) )
        {
            return  $this->parent0->id_numer() . "." .
                    ( $this->count('t.parent=:p and t.id_requirement < :id',
                       array('p'=>$this->parent0->id_requirement,
                             'id'=>$this->id_requirement) ) + 1 );
        }
        return  $this->count('t.parent is null and t.id_requirement < :id',
                   array('id'=>$this->id_requirement) ) + 1;
    }
    
}