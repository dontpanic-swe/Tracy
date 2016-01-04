<?php

/**
 * This is the model class for table "argument".
 *
 * The followings are the available columns in table 'argument':
 * @property integer $id_argument
 * @property string $name
 * @property string $type
 * @property string $direction
 * @property string $description
 * @property integer $id_method
 * @property integer $order
 *
 * The followings are the available model relations:
 * @property Method $idMethod
 */
class Argument extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Argument the static model class
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
        return 'argument';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, type, id_method', 'required'),
            array('order, id_method', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>64),
            array('type', 'length', 'max'=>128),
            array('direction', 'length', 'max'=>3),
            array('description','safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_argument, name, type, direction, const, id_method', 'safe', 'on'=>'search'),
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
            'method' => array(self::BELONGS_TO, 'Method', 'id_method'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_argument' => 'Id Argument',
            'name' => 'Name',
            'type' => 'Type',
            'direction' => 'Direction',
            'description' => 'Description',
            'id_method' => 'Id Method',
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

        $criteria->compare('id_argument',$this->id_argument);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('type',$this->type,true);
        $criteria->compare('direction',$this->direction,true);
        $criteria->compare('description',$this->description);
        $criteria->compare('id_method',$this->id_method);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    static function direction_drop()
    {
        return array('in'=>'in','out'=>'out');
    }
    
    function cpp_decl()
    {
        return  "$this->type $this->name";
    }
    
} 