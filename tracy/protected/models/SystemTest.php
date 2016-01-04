<?php

/**
 * This is the model class for table "system_test".
 *
 * The followings are the available columns in table 'system_test':
 * @property integer $id_test
 * @property integer $id_requirement
 *
 * The followings are the available model relations:
 * @property Test $test
 * @property Requirement $requirement
 */
class SystemTest extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SystemTest the static model class
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
		return 'system_test';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_test, id_requirement', 'required'),
			array('id_test, id_requirement', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_test, id_requirement', 'safe', 'on'=>'search'),
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
			'id_requirement' => 'Id Requirement',
		);
	}
    
    function public_id()
    {
        $this->with('requirement');
        if ( isset($this->requirement) )
            return "TS".$this->requirement->id_numer();
        return "TS-Not-Implemented";
    }
    
    function test_type()
    {
        return 'System';
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
        $criteria->compare('id_requirement',$this->id_requirement);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    function relation_link()
    {
        $this->with('requirement');
        return CHtml::link($this->requirement->public_id(),array(
            'requirement/view', 'id'=>$this->requirement->id_requirement
        ) );
    }

}