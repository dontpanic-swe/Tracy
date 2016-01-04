<?php

$dirname =
    Yii::app()->getRequest()->getHostInfo('').
    Yii::app()->getUrlManager()->getBaseUrl() ;
    
    Yii::app()->clientscript->registerCssFile($dirname."/css/nyan.css");
    
    Yii::app()->clientscript->registerCss("NyanStar",
    ".star{background: url($dirname/media/card.png);}
    #nyanContainer { $css }
    ");
?>


<div id="nyanContainer">
    <div id="nyanCat">
        <div id="wholeHead">
            <div class="ear"></div>
            <div class="ear rightEar"></div>
            <div id="mainHead" class="skin">
                <div class="eye"></div>
                <div class="eye rightEye"></div>
                <div class="skin mouth"></div>
            </div>
        </div>
        <div id="toastBody" class="skin">
            <div class="gearBox"></div>
            <div class="gear large_gear">
                <div number="1"></div>
                <div number="2"></div>
                <div number="3"></div>
            </div>
            
            <div class="gear tiny_gear">
                <div number="1"></div>
                <div number="2"></div>
                <div number="3"></div>
            </div>
        </div>
        <div id="wholeArm">
            <div class="arm skin"></div>
            <div class="arm middleArm skin"></div>
            <div class="arm backArm skin"></div>
            
            <div class="arm leftArm skin"></div>
            <div class="arm middleArm leftArm skin"></div>
            <div class="arm backArm leftArm skin"></div>
        </div>
        <div id="allYourLegAreBelongToUs">
            <div class="skin leg back"></div>
            <div class="skin leg front"></div>
        </div>
        <div class="rainbow"></div>
        <div class="rainbow r2"></div>
        <div class="rainbow r3"></div>
        <div class="rainbow r4"></div>
    </div>
    <div class="star starMovement1">
        <div number="1"></div>
        <div number="2"></div>
        <div number="3"></div>
        <div number="4"></div>
        <div number="5"></div>
        <div number="6"></div>
        <div number="7"></div>
        <div number="8"></div>
    </div>
    <div class="star starMovement2 backwards">
        <div number="1"></div>
        <div number="2"></div>
        <div number="3"></div>
        <div number="4"></div>
        <div number="5"></div>
        <div number="6"></div>
        <div number="7"></div>
        <div number="8"></div>
    </div>
    <div class="star starMovement3">
        <div number="1"></div>
        <div number="2"></div>
        <div number="3"></div>
        <div number="4"></div>
        <div number="5"></div>
        <div number="6"></div>
        <div number="7"></div>
        <div number="8"></div>
    </div>
   <div class="star starMovement4">
        <div number="1"></div>
        <div number="2"></div>
        <div number="3"></div>
        <div number="4"></div>
        <div number="5"></div>
        <div number="6"></div>
        <div number="7"></div>
        <div number="8"></div>
    </div>
    <div class="star starMovement5">
        <div number="1"></div>
        <div number="2"></div>
        <div number="3"></div>
        <div number="4"></div>
        <div number="5"></div>
        <div number="6"></div>
        <div number="7"></div>
        <div number="8"></div>
    </div>
    <div class="star starMovement6">
        <div number="1"></div>
        <div number="2"></div>
        <div number="3"></div>
        <div number="4"></div>
        <div number="5"></div>
        <div number="6"></div>
        <div number="7"></div>
        <div number="8"></div>
    </div>
</div>

<?php
    if ( $music )
    {
        echo '<audio loop="loop" src="'.
                $dirname."/media/nyan_cat.ogg".
                '" autoplay="true" />';
    }
