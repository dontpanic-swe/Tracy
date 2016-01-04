<?php

/**
 * This is the model class for table "connect".
 *
 * The followings are the available columns in table 'connect':
 * @property integer $signal
 * @property integer $slot
 *
 * The followings are the available model relations:
 * @property Method $signal0
 * @property Method $slot0
 */
class Connect extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Connect the static model class
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
        return 'connect';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('signal, slot', 'required'),
            array('signal, slot', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('signal, slot', 'safe', 'on'=>'search'),
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
            'signal0' => array(self::BELONGS_TO, 'Method', 'signal'),
            'slot0' => array(self::BELONGS_TO, 'Method', 'slot'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'signal' => 'Signal',
            'slot' => 'Slot',
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

        $criteria->compare('signal',$this->signal);
        $criteria->compare('slot',$this->slot);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
} 