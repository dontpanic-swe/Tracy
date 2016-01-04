<?php
/* @var $this RequirementController */
/* @var $model Requirement */

$this->breadcrumbs=array(
	'Requirements'=>array('index'),
	'Priorities',
);

$this->menu=array(
	array('label'=>'List Requirements', 'url'=>array('index')),
	array('label'=>'Create Requirement', 'url'=>array('create')),
	array('label'=>'Manage Sources', 'url'=>array('source/index')),
	array('label'=>'Manage Categories', 'url'=>array('requirement/categories')),
	array('label'=>'Manage Validation Methods', 'url'=>array('requirement/validations')),
);

echo '<h1>Manage Priorities</h1>';

$gridview = new IDGridView;
$gridview->dataProvider = new CActiveDataProvider('RequirementPriority');
$gridview->columns=array(
		'id_priority',
        'name',
        array(
            'class' => 'CLinkColumn',
            'label' => 'edit',
            'urlExpression' => 'array("requirement/priorities","edit"=>"$data->id_priority")',
        ),
    );
$gridview->id_field='id_priority';
$gridview->init();
$gridview->run();



echo '<h2>Create/edit</h2>';

$form=$this->beginWidget('CActiveForm', array(
	'id'=>'priority-form',
	'enableAjaxValidation'=>false,
));



echo $form->errorSummary($model);
?>

	<div class="row">
        <?php
            echo $form->labelEx($model,'id_priority');
            echo " <span> {$model->id_priority}<span>";
            echo $form->hiddenField ($model,'id_priority');
        ?>
	</div>
    
	<div class="row">
        <?php
            echo $form->labelEx($model,'name'); 
            echo $form->textField ($model,'name');
        ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>
