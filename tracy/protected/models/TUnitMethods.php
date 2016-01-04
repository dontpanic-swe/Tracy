<?php

/**
 * This is the model class for table "t_unit_methods".
 *
 * The followings are the available columns in table 't_unit_methods':
 * @property integer $id_method
 * @property integer $id_t_unit_test
 *
 * The followings are the available model relations:
 * @property TUnitTest $unitTest
 * @property Method $method
 */
class TUnitMethods extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TUnitMethods the static model class
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
        return 't_unit_methods';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_method, id_t_unit_test', 'required'),
            array('id_method, id_t_unit_test', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_method, id_t_unit_test', 'safe', 'on'=>'search'),
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
            'unitTest' => array(self::BELONGS_TO, 'TUnitTest', 'id_t_unit_test'),
            'method' => array(self::BELONGS_TO, 'Method', 'id_method'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_method' => 'Id Method',
            'id_t_unit_test' => 'Id T Unit Test',
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

        $criteria->compare('id_method',$this->id_method);
        $criteria->compare('id_t_unit_test',$this->id_t_unit_test);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}  
