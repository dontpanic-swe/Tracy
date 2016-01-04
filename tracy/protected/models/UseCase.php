<?php

/**
 * This is the model class for table "use_case".
 *
 * The followings are the available columns in table 'use_case':
 * @property integer $id_use_case
 * @property string $title
 * @property integer $parent
 * @property string $description
 * @property string $pre
 * @property string $post
 *
 * The followings are the available model relations:
 * @property Source $idUseCase
 * @property UseCase $parent0
 * @property UseCase[] $useCases
 * @property UseCaseEvent[] $useCaseEvents
 * @property UseCaseEvent[] $useCaseEvents1
 */
class UseCase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UseCase the static model class
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
		return 'use_case';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, description, pre, post', 'required'),
			array('parent', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_use_case, title, parent, description, pre, post', 'safe', 'on'=>'search'),
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
			'idUseCase' => array(self::BELONGS_TO, 'Source', 'id_use_case'),
			'parent0' => array(self::BELONGS_TO, 'UseCase', 'parent'),
			'useCases' => array(self::HAS_MANY, 'UseCase', 'parent'),
			'useCaseEvents' => array(self::HAS_MANY, 'UseCaseEvent', 'use_case'),
			'useCaseEvents1' => array(self::HAS_MANY, 'UseCaseEvent', 'refers_to'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_use_case' => 'Id Use Case',
			'title' => 'Title',
			'parent' => 'Parent',
			'description' => 'Description',
			'pre' => 'Pre',
			'post' => 'Post',
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

		$criteria->compare('id_use_case',$this->id_use_case);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('parent',$this->parent);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('pre',$this->pre,true);
		$criteria->compare('post',$this->post,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination'=>false,
		));
	}
    
    
    function public_id()
    {
        return "UC".$this->id_numer();
    }
    
    function id_numer()
    {
        $this->with('parent0');
        if ( isset($this->parent0) )
        {
            return  $this->parent0->id_numer() . "." .
                    ( $this->count('t.parent=:p and t.id_use_case < :id',
                       array('p'=>$this->parent0->id_use_case,
                             'id'=>$this->id_use_case) ) + 1 );
        }
        return  $this->count('t.parent is null and t.id_use_case < :id',
                   array('id'=>$this->id_use_case) ) + 1;
    }
    
    function actors()
    {
        $actors = array();
        $this->with('useCaseEvents');
        foreach($this->useCaseEvents as $event)
        {
            $event->with('primaryActor');
            array_push($actors,$event->primaryActor->description);
        }
        return array_unique($actors);
    }
}