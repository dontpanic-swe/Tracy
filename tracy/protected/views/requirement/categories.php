<?php
/* @var $this RequirementController */
/* @var $model Requirement */

$this->breadcrumbs=array(
	'Requirements'=>array('index'),
	'Categories',
);

$this->menu=array(
	array('label'=>'List Requirements', 'url'=>array('index')),
	array('label'=>'Create Requirement', 'url'=>array('create')),
	array('label'=>'Manage Sources', 'url'=>array('source/index')),
	array('label'=>'Manage Priorities', 'url'=>array('requirement/priorities')),
	array('label'=>'Manage Validation Methods', 'url'=>array('requirement/validations')),
);

echo '<h1>Manage Categories</h1>';

$gridview = new IDGridView;
$gridview->dataProvider = new CActiveDataProvider('RequirementCategory');
$gridview->columns=array(
		'id_category',
        'name',
        array(
            'class' => 'CLinkColumn',
            'label' => 'edit',
            'urlExpression' => 'array("requirement/categories","edit"=>"$data->id_category")',
        ),
    );
$gridview->id_field='id_category';
$gridview->init();
$gridview->run();



echo '<h2>Create/edit</h2>';

$form=$this->beginWidget('CActiveForm', array(
	'id'=>'category-form',
	'enableAjaxValidation'=>false,
));



echo $form->errorSummary($model);
?>

	<div class="row">
        <?php
            echo $form->labelEx($model,'id_category');
            echo "<span>{$model->id_category}<span>";
            echo $form->hiddenField ($model,'id_category');
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
