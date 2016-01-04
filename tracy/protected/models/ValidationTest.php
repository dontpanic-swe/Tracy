<?php

/**
 * This is the model class for table "validation_test".
 *
 * The followings are the available columns in table 'validation_test':
 * @property integer $id_test
 * @property integer $parent
 * @property integer $id_requirement
 *
 * The followings are the available model relations:
 * @property Test $test
 * @property ValidationTest $parent0
 * @property ValidationTest[] $children
 * @property Requirement $requirement
 */
class ValidationTest extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ValidationTest the static model class
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
		return 'validation_test';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_test', 'required'),
			array('id_test, parent, id_requirement', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_test, parent, id_requirement', 'safe', 'on'=>'search'),
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
			'test' => array(self::BELONGS_TO, 'Test', 'id_test'),
			'parent0' => array(self::BELONGS_TO, 'ValidationTest', 'parent'),
			'children' => array(self::HAS_MANY, 'ValidationTest', 'parent'),
			'requirement' => array(self::BELONGS_TO, 'Requirement', 'id_requirement'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_test' => 'Id Test',
			'parent' => 'Parent',
			'id_requirement' => 'Id Requirement',
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

		$criteria->compare('id_test',$this->id_test);
		$criteria->compare('parent',$this->parent);
		$criteria->compare('id_requirement',$this->id_requirement);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function public_id()
    {
        return "TV".$this->id_numer();
    }
    
    function id_numer()
    {
        $this->with('parent0');
        if ( isset($this->parent0) )
        {
            return  $this->parent0->id_numer() . "." .
                    ( $this->count('t.parent=:p and t.id_test < :id',
                       array('p'=>$this->parent0->id_test,
                             'id'=>$this->id_test) ) + 1 );
        }
        return  $this->count('t.parent is null and t.id_test < :id',
                   array('id'=>$this->id_test) ) + 1;
    }
    
    /*function public_id()
    {
        $this->with('requirement');
        if ( isset($this->requirement) )
            return "TV".$this->requirement->id_numer();
        return "TV-Not-Implemented";
    }*/
    
    function test_type()
    {
        return 'Validation';
    }
    
    function relation_link()
    {
        $this->with('requirement');
        return $this->requirement == null ? null :
            CHtml::link($this->requirement->public_id(),array(
            'requirement/view', 'id'=>$this->requirement->id_requirement
        ) );
    }

}