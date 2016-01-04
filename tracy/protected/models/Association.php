<?php

/**
 * This is the model class for table "association".
 *
 * The followings are the available columns in table 'association':
 * @property integer $id_association
 * @property string $aggregation_from
 * @property integer $class_from
 * @property string $aggregation_to
 * @property integer $class_to
 * @property integer $id_attribute
 * @property string $multiplicity
 *
 * The followings are the available model relations:
 * @property Class_Prog $classFrom
 * @property Class_Prog $classTo
 * @property Attribute $attribute
 */
class Association extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Association the static model class
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
        return 'association';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('aggregation_from, class_from, aggregation_to, class_to, id_attribute', 'required'),
            array('class_from, class_to, id_attribute', 'numerical', 'integerOnly'=>true),
            array('aggregation_from, aggregation_to', 'length', 'max'=>9),
            array('multiplicity','safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_association, aggregation_from, class_from, aggregation_to, class_to, id_attribute', 'safe', 'on'=>'search'),
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
            'classFrom' => array(self::BELONGS_TO, 'Class_Prog', 'class_from'),
            'classTo' => array(self::BELONGS_TO, 'Class_Prog', 'class_to'),
            'attribute' => array(self::BELONGS_TO, 'Attribute', 'id_attribute'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_association' => 'Id Association',
            'aggregation_from' => 'Aggregation From',
            'class_from' => 'Class From',
            'aggregation_to' => 'Aggregation To',
            'class_to' => 'Class To',
            'id_attribute' => 'Id Attribute',
            'multiplicity' => 'Multiplicity',
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

        $criteria->compare('id_association',$this->id_association);
        $criteria->compare('aggregation_from',$this->aggregation_from,true);
        $criteria->compare('class_from',$this->class_from);
        $criteria->compare('aggregation_to',$this->aggregation_to,true);
        $criteria->compare('class_to',$this->class_to);
        $criteria->compare('id_attribute',$this->id_attribute);
        $criteria->compare('multiplicity',$this->multiplicity);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    static function aggreg_drop()
    {
        return array('none'=>'none',
                     'aggregate'=>'aggregate',
                     'composite'=>'composite');
    }
    
    static function multiplicity_drop()
    {
        return array(
                '*'=>'*',
                '1' => '1',
                '0..1' => '0..1',
                '0..*' => '0..*',
                '1..*' => '1..*'
        );
    }
} 