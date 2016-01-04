<?php

/**
 * This is the model class for table "t_unit_test".
 *
 * The followings are the available columns in table 't_unit_test':
 * @property integer $id_test
 * @property string $description
 *
 * The followings are the available model relations:
 * @property TUnitMethods[] $tUnitMethods
 */
class TUnitTest extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TUnitTest the static model class
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
        return 't_unit_test';
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
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_test, description', 'safe', 'on'=>'search'),
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
            'tUnitMethods' => array(self::HAS_MANY, 'TUnitMethods', 'id_t_unit_test'),
            'methods' => array(self::MANY_MANY, 'Method', 't_unit_methods(id_method,id_t_unit_test)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_test' => 'Id Test',
            'description' => 'Description',
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
        $criteria->compare('description',$this->description,true);

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
}  
