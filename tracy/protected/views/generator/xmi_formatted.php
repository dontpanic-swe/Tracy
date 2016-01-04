<?php

    $cnt = $this->renderPartial("xmi", array(),  true);
    if ( $raw )
        echo $cnt;
    else
        echo CodeGen::render_code( $cnt, true,'xml');