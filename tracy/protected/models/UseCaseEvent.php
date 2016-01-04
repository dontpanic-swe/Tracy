<?php

/**
 * This is the model class for table "use_case_event".
 *
 * The followings are the available columns in table 'use_case_event':
 * @property integer $id_event
 * @property integer $category
 * @property integer $use_case
 * @property string $description
 * @property integer $refers_to
 * @property integer $primary_actor
 * @property integer $order
 *
 * The followings are the available model relations:
 * @property Actor[] $actors
 * @property Actor $primaryActor
 * @property UseCase $useCase
 * @property EventCategory $category0
 * @property UseCase $refersTo
 */
class UseCaseEvent extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UseCaseEvent the static model class
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
		return 'use_case_event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('category, use_case, description, order', 'required'),
			array('category, use_case, refers_to, primary_actor', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_event, category, use_case, description, refers_to, primary_actor, order', 'safe', 'on'=>'search'),
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
			'actors' => array(self::MANY_MANY, 'Actor', 'secondary_actors(id_event, id_actor)'),
			'primaryActor' => array(self::BELONGS_TO, 'Actor', 'primary_actor'),
			'useCase' => array(self::BELONGS_TO, 'UseCase', 'use_case'),
			'category0' => array(self::BELONGS_TO, 'EventCategory', 'category'),
			'refersTo' => array(self::BELONGS_TO, 'UseCase', 'refers_to'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_event' => 'Id Event',
			'category' => 'Category',
			'use_case' => 'Use Case',
			'description' => 'Description',
			'refers_to' => 'Refers To',
			'primary_actor' => 'Primary Actor',
			'order' => 'Order',
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

		$criteria->compare('id_event',$this->id_event);
		$criteria->compare('category',$this->category);
		$criteria->compare('use_case',$this->use_case);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('refers_to',$this->refers_to);
		$criteria->compare('primary_actor',$this->primary_actor);
		$criteria->compare('order',$this->order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}