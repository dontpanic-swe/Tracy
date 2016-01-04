<?php

/**
 * This is the model class for table "unit_test".
 *
 * The followings are the available columns in table 'unit_test':
 * @property integer $id_test
 * @property integer $id_method
 *
 * The followings are the available model relations:
 * @property Test $test
 * @property Method $method
 */
class UnitTest extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UnitTest the static model class
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
		return 'unit_test';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_test, id_method', 'required'),
			array('id_test, id_method', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_test, id_method', 'safe', 'on'=>'search'),
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
			'method' => array(self::BELONGS_TO, 'Method', 'id_method'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_test' => 'Id Test',
			'id_method' => 'Id Method',
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
		$criteria->compare('id_method',$this->id_method);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    function public_id()
    {
        return "TU".$this->id_numer();
    }
    
    function id_numer()
    {
        return $this->id_test;
    }
    
    function test_type()
    {
        return 'Unit';
    }
    
    function relation_link()
    {
        $this->with('method');
        return CHtml::link($this->method->full_name(),array(
            'class/methodView', 'id'=>$this->method->id_method
        ) );
    }

}