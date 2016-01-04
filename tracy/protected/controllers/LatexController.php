<?php

class LatexController extends Controller
{
    public $layout='//latex/layout';
    
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
			/*array('allow', // allow authenticated user to perform all actions
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),*/
		);
	}
    
    
    function render_latex_table($action,$type='html',$raw=false,$params=array())
    {
        if ( $type == 'latex' )
            $params['table_creator'] = new LatexTableCreator;
        else
            $params['table_creator'] = new HtmlTableCreator;

        $params['raw']=$raw;
        
        if ( !$raw )
        {
            if ( ! Yii::app()->user->isGuest )
                $this->render($action,$params);
            else
                $this->redirect(array('site/login'));
        }
        else
        {
            header("Content-Type: text/plain; charset=utf-8");
            $this->renderPartial($action,$params);
        }
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
    
    function render_plain_text($view,$params=array())
    {
        if ( ! Yii::app()->user->isGuest )
        {
            header('Content-type: image/svg+xml; charset=utf-8');
            $this->renderPartial($view,$params);
        }
        else
            $this->redirect(array('site/login'));
    }
    
    function actionIndex()
    {
        $this->render_normal_page('index');
    }
    
    function actionRequirementSource($type='html',$raw=false,$category="",$priority="")
    {
            
        $this->render_latex_table('requirement_source',$type,$raw,array(
                        'filter_cat'=>$category,
                        'filter_pri'=>$priority
                        ) );
    }
    function actionRequirementSourceCompact($type='html',$raw=false,$category="",$priority="")
    {
        
        $this->render_latex_table('requirement_source_compact',$type,$raw,array(
                        'filter_cat'=>$category,
                        'filter_pri'=>$priority
                        ) );
    }
    
    function actionSourceRequirement($type='html',$raw=false)
    {
        $this->render_latex_table('source_requirement',$type,$raw);
    }
    
    function actionRequirementSummary($type='html',$raw=false)
    {
        $this->render_latex_table('requirement_summary',$type,$raw);
    }
    
    function actionUseCases($raw=false)
    {
        $this->render_latex_table('use_cases','latex',$raw);
    }
    
    function actionRequirementApportion($raw=false)
    {
        $this->render_latex_table('requirement_apported','latex',$raw);
    }
    
    function actionGraficone($name=true)
    {
        $this->render_normal_page('graficone',array('name'=>$name));
    }
    
    function actionGraficoneSvg($name=true)
    {
        $this->render_plain_text('graficone_svg',array('name'=>$name));
    }
    
    function actionGraficoneDot($name=true)
    {
        $this->render_plain_text('graficone_dot',array('name'=>$name));
    }
    
    function actionUCTree($raw=true)
    {
        $this->render_latex_table('uc_tree','latex',$raw);
    }
    
    function actionPDCA($date_start,$date_end,$project)
    {
        $this->render_normal_page('pdca',array('date_start'=>$date_start,
                                               'date_end'=>$date_end,
                                               'project'=>$project ) );
    }
    
    
    function actionPDCA_SVG($date_start,$date_end,$project,$w,$h,$font=10)
    {
        $this->renderPartial('pdca_svg',array('date_start'=>$date_start,
                                               'date_end'=>$date_end,
                                               'project'=>$project,
                                               'w'=>$w,
                                               'h'=>$h,
                                               'font'=>$font) );
    }
    
    function actionRequirementComponent($type='html',$raw=false,$category="",$priority="")
    {
            
        $this->render_latex_table('requirement_component',$type,$raw,array(
                        'filter_cat'=>$category,
                        'filter_pri'=>$priority
                        ) );
    }
    
    function actionRequirementComponentCompact($type='html',$raw=false,$category="",$priority="")
    {
            
        $this->render_latex_table('requirement_component_compact',$type,$raw,array(
                        'filter_cat'=>$category,
                        'filter_pri'=>$priority
                        ) );
    }    
    
    function actionComponentRequirement($type='html',$raw=false)
    {
        $this->render_latex_table('component_requirement',$type,$raw);
    }
    
    function actionComponents($type='html',$raw=false)
    {
        $this->render_latex_table('components',$type,$raw);
    }
    
    function actionSystemTest($type='html',$raw=false,$category="",$priority="")
    {
            
        $this->render_latex_table('system_test',$type,$raw,array(
                        'filter_cat'=>$category,
                        'filter_pri'=>$priority
                        ) );
    }
    
    function actionRequirementTest($type='html',$raw=false,$category="",$priority="")
    {
            
        $this->render_latex_table('requirement_test',$type,$raw,array(
                        'filter_cat'=>$category,
                        'filter_pri'=>$priority
                        ) );
    }
    
    function actionClass($type='html',$raw=false)
    {
        $this->render_latex_table('classes',$type,$raw);
    }
    
    function actionValidationTest($type='html',$raw=false)
    {
            
        $this->render_latex_table('validation_test',$type,$raw );
    }
    
    function actionValidationTracking($type='html',$raw=false)
    {
            
        $this->render_latex_table('validation_tracking',$type,$raw );
    }
    
    function actionRequirementValidation($type='html',$raw=false)
    {
            
        $this->render_latex_table('requirement_validation',$type,$raw );
    }
    
    function actionComponentIntegration($type='html',$raw=false)
    {
            
        $this->render_latex_table('package_integration',$type,$raw );
    }
    
    function actionIntegration($type='html',$raw=false)
    {
            
        $this->render_latex_table('integration',$type,$raw );
    }
    
    function actionRsysva($type='html',$raw=false,$category="",$priority="")
    {
            
        $this->render_latex_table('req_system_validation',$type,$raw,array(
                        'filter_cat'=>$category,
                        'filter_pri'=>$priority
                        ) );
    }
    
    function actionComponentCoupling($type='html',$raw=false)
    {
            
        $this->render_latex_table('package_coupling',$type,$raw );
    }
    
    function actionUnitTracking($type='html',$raw=false)
    {
            
        $this->render_latex_table('unit_tracking',$type,$raw );
    }
    function actionCac($type='html',$raw=false)
    {
        $this->render_latex_table('cac',$type,$raw );
    }
    function actionAllMethods($type='html',$raw=false)
    {
            
        $this->render_latex_table('all_methods',$type,$raw );
    }
    
    function actionDanieleTest($type='html',$raw=false)
    {
        $this->render_latex_table('unit_test',$type,$raw );
    }
    
    function actionDanieleTracking($type='html',$raw=false)
    {
        $this->render_latex_table('dantest_track',$type,$raw );
    }
    
    function actionDdp($type='html',$raw=false)
    {
        $this->render_latex_table('ddp',$type,$raw );
    }
    
    function escape_latex($txt)
    {
        return preg_replace("[_&]","\\0",$att->name);
    }
    
    function actionReClass($type='html',$raw=false,$category="",$priority="")
    {
        $this->render_latex_table('requirement_class',$type,$raw,array(
                        'filter_cat'=>$category,
                        'filter_pri'=>$priority
                        ) );
    }
    
    function actionClassReq($type='html',$raw=false)
    {
        $this->render_latex_table('class_requirement',$type,$raw );
    }
}