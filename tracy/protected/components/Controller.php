<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
    public $bg_image = '';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
    
    function activeAutoComplete($form,$model,$attribute,$relation,
                                $id_attribute,$value_attribute,
                                $complete_view)
    {
        $pid = '';
        $pdesc = '';
        $model->with($relation);
        if ( isset($model->$relation) )
        {
            $pid = $model->$relation->$id_attribute;
            $pdesc = $model->$relation->$value_attribute;
        }
        
        echo $form->labelEx($model,$attribute);
        echo $form->hiddenField($model,$attribute,array('id'=>"actual_$attribute",
                                                      'value'=>$pid));
        $this->widget('zii.widgets.jui.CJuiAutoComplete',
            array(
                'name'=>$attribute."_autocomplete",
                'sourceUrl'=>array($complete_view),
                'options'=> array (
                    'select'=>"js:function(event, ui) {
                                $('#actual_$attribute').val(ui.item.id);
                            }",
                ),
                'value'=>$pdesc,
            ) );
        echo $form->error($model,$attribute);
    }
    
    function autoComplete($name,$complete_view,$id='',$value='')
    {
        $url = is_array($complete_view) ? $complete_view : array($complete_view);
        $dom_id = CHtml::getIdByName($name);
        echo CHtml::hiddenField($name,$id,array('id'=>$dom_id));
        $this->widget('zii.widgets.jui.CJuiAutoComplete',
            array(
                'name'=>$dom_id.'_autocomplete',
                'sourceUrl'=>$url,
                'options'=> array (
                    'select'=>"js:function(event, ui) {
                                $('#$dom_id').val(ui.item.id);
                            }",
                ),
                'value'=>$value,
            ) );
    }
}