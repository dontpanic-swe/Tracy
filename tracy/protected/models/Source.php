<?php

/**
 * This is the model class for table "source".
 *
 * The followings are the available columns in table 'source':
 * @property integer $id_source
 *
 * The followings are the available model relations:
 * @property ExternalSource $externalSource
 * @property Requirement[] $requirements
 * @property UseCase $useCase
 */
class Source extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Source the static model class
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
		return 'source';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_source', 'safe', 'on'=>'search'),
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
			'externalSource' => array(self::HAS_ONE, 'ExternalSource', 'id_source'),
			'requirements' => array(self::MANY_MANY, 'Requirement', 'source_requirement(id_source, id_requirement)'),
			'useCase' => array(self::HAS_ONE, 'UseCase', 'id_use_case'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_source' => 'Id Source',
			'description' => 'Description',
		);
	}
    
    function description()
    {
        if ( isset($this->useCase) )
            return $this->useCase->title;
        if ( isset($this->externalSource) )
            return $this->externalSource->description;
        return null;
    }
    
    function __get($name)
    {
        if ( $name == 'description' )
            return $this->description();
        return parent::__get($name);
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

		$criteria->compare('id_source',$this->id_source);
		$criteria->compare('description',$this->description());

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    
    
}