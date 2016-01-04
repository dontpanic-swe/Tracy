<?php

/**
 * This is the model class for table "test".
 *
 * The followings are the available columns in table 'test':
 * @property integer $id_test
 * @property string $status
 * @property string $description
 * @property string $jenkins_id
 *
 * The followings are the available model relations:
 * @property IntegrationTest $integration
 * @property SystemTest $system
 * @property UnitTest $unit
 * @property ValidationTest $validation
 */
class Test extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Test the static model class
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
		return 'test';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('description', 'required'),
			array('status', 'length', 'max'=>13),
			array('jenkins_id', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_test, status, description, jenkins_id', 'safe', 'on'=>'search'),
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
			'integration' => array(self::HAS_ONE, 'IntegrationTest', 'id_test'),
			'system' => array(self::HAS_ONE, 'SystemTest', 'id_test'),
			'unit' => array(self::HAS_ONE, 'UnitTest', 'id_test'),
			'validation' => array(self::HAS_ONE, 'ValidationTest', 'id_test'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_test' => 'Id Test',
			'status' => 'Status',
			'description' => 'Description',
			'jenkins_id' => 'Jenkins',
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
		$criteria->compare('status',$this->status,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('jenkins_id',$this->jenkins_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    function public_id()
    {
        $s = $this->specific();
        if ( $s == null )
            return "Bad-Test";
        else
            return $s->public_id();
    }
    function test_type()
    {
        $s = $this->specific();
        if ( $s == null )
            return "Test";
        else
            return $s->test_type();
    }
    
    
    function relation_link()
    {
        $s = $this->specific();
        if ( $s == null )
            return null;
        else
            return $s->relation_link();
    }
    
    function specific()
    {
        $this->with('integration','system', 'unit','validation');
        if ( isset($this->integration) )
            return $this->integration;
        else if ( isset($this->system) )
            return $this->system;
        else if ( isset($this->unit) )
            return $this->unit;
        else if ( isset($this->validation) )
            return $this->validation;
        else
            return null;
    }
    
    static function status_drop()
    {
        return array('unimplemented'=>'unimplemented',
                     'failed'=>'failed',
                     'success'=>'success'
                );
    }
}