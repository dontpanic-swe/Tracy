<?php
/* @var $this ClassController */
/* @var $model Class_Prog */

$this->breadcrumbs=array(
    'Class'=>array('index'),
    'Manage',
);

$this->menu=array(
    array('label'=>'List Class', 'url'=>array('index')),
    array('label'=>'Create Class', 'url'=>array('create')),
    array('label'=>'Associations', 'url'=>array('associations')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('class--prog-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<h1>Manage Classes</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo '<p>'.CHtml::link('Advanced Search','#',array('class'=>'search-button')).'<p>'; ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
    'model'=>$model,
)); ?>
</div><!-- search-form -->


<?php

$page_size = isset($_GET['num'])?$_GET['num']:'lol';
$dp = $model->with('package')->search();
if ( $page_size == 0 || !is_numeric($page_size) )
{
    $page_size = 'infinity';
    $dp->pagination = false;
}
else
    $dp->pagination->pageSize = $page_size;

$page_sizes = array_unique(array(20,50,100,'infinity',$page_size));
natsort($page_sizes);
echo '<p>View: ';
foreach($page_sizes as $ps )
{
    if ( $ps == $page_size )
        echo "<strong>$ps</strong>";
    else
        echo CHtml::link($ps,array('index','num'=>$ps));
    echo " ";
}
echo ' classes per page</p>';

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'class-prog-grid',
    'dataProvider'=>$dp,
    'filter'=>$model,
    'columns'=> Class_Prog::grid_columns(array(array('class'=>'CButtonColumn',))),
)); ?>