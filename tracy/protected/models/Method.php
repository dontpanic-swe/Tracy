<?php

/**
 * This is the model class for table "method".
 *
 * The followings are the available columns in table 'method':
 * @property integer $id_method
 * @property string $name
 * @property string $pre
 * @property string $post
 * @property string $description
 * @property string $return
 * @property string $access
 * @property integer $virtual
 * @property integer $override
 * @property integer $final
 * @property integer $static
 * @property integer $const
 * @property integer $nothrow
 * @property integer $abstract
 * @property integer $id_class
 *
 * The followings are the available model relations:
 * @property Argument[] $arguments
 * @property Class_Prog $class
 * @property Method[]   $slots
 * @property Method[]   $signals
 * @property UnitTest   $test
 * @property TUnitTest   $ttest
 */
class Method extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Method the static model class
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
        return 'method';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, description, id_class', 'required'),
            array('abstract, virtual, override, final, static, const, nothrow, id_class', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>64),
            array('return', 'length', 'max'=>128),
            array('access', 'length', 'max'=>9),
            array('pre, post', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_method, name, pre, post, description, return, access, virtual, override, final, static, const, nothrow, id_class', 'safe', 'on'=>'search'),
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
            'arguments' => array(self::HAS_MANY, 'Argument', 'id_method',
                                 'order'=>'`order`'),
            'class' => array(self::BELONGS_TO, 'Class_Prog', 'id_class'),
            'slots' => array(self::MANY_MANY, 'Method', 'connect(signal,slot)'),
            'signals' => array(self::MANY_MANY, 'Method', 'connect(slot,signal)'),
            'test' => array(self::HAS_ONE, 'UnitTest', 'id_method'),
            'ttest'=>array(self::HAS_ONE,'TUnitMethods','id_method'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_method' => 'Id Method',
            'name' => 'Name',
            'pre' => 'Pre',
            'post' => 'Post',
            'description' => 'Description',
            'return' => 'Return',
            'access' => 'Access',
            'virtual' => 'Virtual',
            'override' => 'Override',
            'final' => 'Final',
            'static' => 'Static',
            'const' => 'Const',
            'nothrow' => 'Nothrow',
            'id_class' => 'Id Class',
            'abstract' => 'Abstract',
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
        $criteria->compare('name',$this->name,true);
        $criteria->compare('pre',$this->pre,true);
        $criteria->compare('post',$this->post,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('return',$this->return,true);
        $criteria->compare('access',$this->access,true);
        $criteria->compare('virtual',$this->virtual);
        $criteria->compare('override',$this->override);
        $criteria->compare('final',$this->final);
        $criteria->compare('static',$this->static);
        $criteria->compare('const',$this->const);
        $criteria->compare('nothrow',$this->nothrow);
        $criteria->compare('abstract',$this->abstract);
        $criteria->compare('id_class',$this->id_class);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    static function access_drop()
    {
        return array('private'=>'private',
                     'protected'=>'protected',
                     'public'=>'public',
                     'signal'=>'signal');
    }
    
    function signature_name()
    {
        $this->with('class','arguments');
        $args = array();
        foreach($this->arguments as $arg)
        {
            $args []= $arg->type;
        }
        return $this->class->name."::".$this->name."(".implode(",",$args).")";
    }
    
    function full_name($separator="::")
    {
        return $this->with('class')->class->full_name($separator).
                $separator.$this->name;
    }
    
    
    function cpp_doc($indent=8)
    {
        
        $indent+=1; // make room for *
        $ind = "\n".str_repeat(' ',$indent);
        
        $longest_name = 0;
        foreach($this->arguments as $arg)
        {
            if ( strlen($arg->name) > $longest_name )
            {
                $longest_name = strlen($arg->name);
            }
        }
        
        $params = "";
        $this->with('arguments');
        foreach($this->arguments as $arg)
        {
            $params .= "* @param[$arg->direction]"
                    .($arg->direction=='in'?'  ':' ')
                    ."$arg->name "
                    . str_repeat(' ',$longest_name-strlen($arg->name))
                    . CodeGen::indent_code($arg->description,80,$indent,
                                           $longest_name+15)
                    . $ind;
        }
        
        return "/*!$ind"
        ."* @details ".CodeGen::indent_code($this->description,80,$indent,10).$ind
        .(strlen($this->pre)>0?
           "* @pre     ".CodeGen::indent_code($this->pre,80,$indent,10).$ind
           : '' )
        .(strlen($this->post)>0?
           "* @post    ".CodeGen::indent_code($this->post,80,$indent,10).$ind
           : '' )
        ."$params*/";
    }
    
    
    function cpp_decl()
    {
        
        return  ($this->static?'static ':'')
                .($this->virtual?'virtual ':'')
                ."$this->return $this->name (".$this->cpp_args().") "
                .($this->const?'const ':'')
                .($this->nothrow?'nothrow ':'')
                .($this->override?'override ':'')
                .($this->final?'final ':'')
                .($this->abstract?'=0':'')
                .";"
                ;
    }
    
    function cpp_args()
    {
        $parr = array();
        
        $this->with('arguments');
        foreach($this->arguments as $arg)
        {
            $parr []= $arg->cpp_decl();
        }
        $args = implode(", ",$parr);
        if ( strlen($args) > 0 )
            $args = " $args ";
        return $args;
    }
} 