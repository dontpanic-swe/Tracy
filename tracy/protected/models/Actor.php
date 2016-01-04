<?php

/**
 * This is the model class for table "actor".
 *
 * The followings are the available columns in table 'actor':
 * @property integer $id_actor
 * @property string $description
 * @property integer $parent
 *
 * The followings are the available model relations:
 * @property Actor[] $actors
 * @property UseCaseEvent[] $useCaseEvents
 * @property UseCaseEvent[] $useCaseEvents1
 * @property Actor $parent0
 */
class Actor extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Actor the static model class
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
		return 'actor';
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
			array('parent', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_actor, description, parent', 'safe', 'on'=>'search'),
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
			'actors' => array(self::HAS_MANY, 'Actor', 'parent'),
			'useCaseEvents' => array(self::MANY_MANY, 'UseCaseEvent', 'secondary_actors(id_actor, id_event)'),
			'useCaseEvents1' => array(self::HAS_MANY, 'UseCaseEvent', 'primary_actor'),
			'parent0' => array(self::BELONGS_TO, 'Actor', 'parent'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_actor' => 'Id Actor',
			'description' => 'Description',
			'parent' => 'Parent',
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

		$criteria->compare('id_actor',$this->id_actor);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('parent',$this->parent);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}