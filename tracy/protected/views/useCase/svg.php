<?php
    
    echo shell_exec('echo '.
           escapeshellarg($this->renderPartial('dot',array('id'=>$id),true)) .
           ' | protected/components/ucase_script/render_dot.sh'
           );
