<?php

class GeneratorController extends Controller
{
    public $layout='//generator/layout';
    public $lang;
    
    public function filters()
	{
		return array(
			'accessControl',
		);
	}
    
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users
				'users'=>array('*'),
			),
		);
	}
    
    function render_code($view,$raw,$xml,$params=array())
    {
        $params['raw']=$raw;
        
        
        if ( !$raw )
        {
            if ( ! Yii::app()->user->isGuest )
                $this->render($view,$params);
            else
                $this->redirect(array('site/login'));
        }
        else
        {
            if ( $xml )
                header("Content-Type: text/xml; charset=utf-8");
            else
                header("Content-Type: text/plain; charset=utf-8");
            $this->renderPartial($view,$params);
        }
    }
    
    
    function actionXmi($raw=0)
    {
        $this->render_code('xmi_formatted',$raw,true);
    }
    
    
    
    function render_normal_page($view,$params=array())
    {
        if ( ! Yii::app()->user->isGuest )
        {
            $this->layout = '//layouts/column2';
            $this->render($view,$params);
        }
        else
            $this->redirect(array('site/login'));
    }
    
    function actionIndex()
    {
        $this->render_normal_page('index');
    }
    
    function actionDecl($id=null,$raw=0)
    {
        $class = Class_Prog::model()->findByPk($id);
        $this->render_code('cpp_class',$raw,false,array('class'=>$class,
                                                             'view'=>'decl'));
    }
    
    function actionImpl($id=null,$raw=0)
    {
        $class = Class_Prog::model()->findByPk($id);
        $this->render_code('cpp_class',$raw,false,array('class'=>$class,
                                                             'view'=>'impl'));
    }
    
    function internalFile($name,$ext,$raw)
    {
        $ns = explode('/',$name);
        
        $name = $ns[count($ns)-1];
        
        $camel_name = str_replace(' ','',ucwords(str_replace('_',' ',$name)));
        
        $classes = Class_Prog::model()->findAll('name = :n1 OR name = :n2',
                               array('n1'=>$name, 'n2'=>$camel_name) );
        
        
        for ( $i = count($ns)-2, $l=1; $i >= 0 && count($classes) > 1; $i--,$l++ )
        {
            $new_classes = array();
            foreach($classes as $c)
            {
                $t = $c->ancestor_level($l);
                if ( $t != null && $t->name == $ns[$i] )
                    $new_classes []= $c;
            }
            $classes = $new_classes;
        }
        
        if ( count($classes) == 0 )
            throw new CHttpException(404,'The requested page does not exist.');
        
        $class = $classes[0];
        
        $view = $ext == 'cpp' ? 'impl' : 'decl';
        $this->render_code('cpp_class',$raw,false,array('class'=>$class,
                                                            'view'=>$view));
    }
    
    function actionFile($name,$ext,$raw=0)
    {
        $this->internalFile($name,$ext,$raw);
    }
    
    function actionRawFile($name,$ext,$raw=1)
    {
        $this->internalFile($name,$ext,$raw);
    }
    
    function actionDirList($sources=1)
    {
        $this->render_code('dir_view',true,false,array('sources'=>$sources));
    }
    
    function actionUnitTest($id,$raw=0)
    {
        $test = UnitTest::model()->findByPk($id);
        if ( $test == null )
            throw new CHttpException(404,'Test not found');
        $this->render_code('unit_test',$raw,false,array('test'=>$test));
    }
    
    function actionTestList()
    {
       $this->render_code('test_list',true,false);
    }
}