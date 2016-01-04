<?php /* @var $this Controller */ ?>
<!DOCTYPE html >
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

    <?php
        if ( strlen($this->bg_image) > 0 )
        {
            echo "
            <style type='text/css'>
                #content {
                    background-image: url('{$this->bg_image}');
                    background-position: right top;
                    background-repeat: no-repeat;
                    background-size: auto 400px;
                    min-height: 400px;
                    width: 900px;
                }
            </style>";
        }
    ?>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; 2012-<?php echo date('Y'); ?> by Don't Panic!.<br/>
		All Rights Reserved.<br/>
        
        
            <script type="text/javascript">
                function destroy()
                {
                    var KICKASSVERSION='2.0';
                    var s = document.createElement('script');
                    s.type='text/javascript';
                    document.body.appendChild(s);
                    s.src='//hi.kickassapp.com/kickass.js';
                    void(0);
                }
            </script>
        <input type="button" value="Destroy" onclick="destroy()" />
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
