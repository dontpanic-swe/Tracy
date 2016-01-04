<?php

/**
 * This is the model class for table "inherit".
 *
 * The followings are the available columns in table 'inherit':
 * @property integer $child
 * @property integer $parent
 *
 * The followings are the available model relations:
 * @property Class_Prog $child0
 * @property Class_Prog $parent0
 */
class Inherit extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Inherit the static model class
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
        return 'inherit';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('child, parent', 'required'),
            array('child, parent', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('child, parent', 'safe', 'on'=>'search'),
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
            'child0' => array(self::BELONGS_TO, 'Class_Prog', 'child'),
            'parent0' => array(self::BELONGS_TO, 'Class_Prog', 'parent'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'child' => 'Child',
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

        $criteria->compare('child',$this->child);
        $criteria->compare('parent',$this->parent);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
} 