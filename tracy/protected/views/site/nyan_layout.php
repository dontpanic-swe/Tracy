<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/column1'); ?>
<div id="content">
	<?php
        echo $content;
        $this->renderPartial('//site/nyan',array(
            'css'=>'
                top:0;
                right:0;
                width:400px;
                height:400px;
                ',
            'music' => false
        ));
    ?>
</div><!-- content -->
<?php $this->endContent(); ?>