<?php
    
    echo shell_exec('echo ' .
           escapeshellarg($this->renderPartial('graficone_dot',array('name'=>$name),true)).
           ' | dot -Tsvg'
           );