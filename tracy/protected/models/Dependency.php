<?php

/**
 * This is the model class for table "dependency".
 *
 * The followings are the available columns in table 'dependency':
 * @property integer $id_dependency
 * @property string $name
 * @property string $description
 * @property integer $id_from
 * @property integer $id_to
 *
 * The followings are the available model relations:
 * @property Class $from
 * @property Class $to
 */
class Dependency extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Dependency the static model class
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
        return 'dependency';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_from, id_to', 'required'),
            array('id_from, id_to', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>64),
            array('description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_dependency, name, description, id_from, id_to', 'safe', 'on'=>'search'),
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
            'from' => array(self::BELONGS_TO, 'Class', 'id_from'),
            'to' => array(self::BELONGS_TO, 'Class', 'id_to'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_dependency' => 'Id Dependency',
            'name' => 'Name',
            'description' => 'Description',
            'id_from' => 'Id From',
            'id_to' => 'Id To',
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

        $criteria->compare('id_dependency',$this->id_dependency);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('id_from',$this->id_from);
        $criteria->compare('id_to',$this->id_to);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
} 