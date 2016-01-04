<?php

$tests = UnitTest::model()->findAll();

foreach($tests as $t)
    echo strtolower($t->public_id()).".cpp";