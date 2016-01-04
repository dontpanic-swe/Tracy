digraph EveryUseCase {
rankdir="LR";

<?php

    function print_uc($useCase)
    {
        $id = 'uc_'.$useCase->id_use_case;
        echo $id.' [label="'.$useCase->title." (".$useCase->public_id().")\"];\n";
        if ( is_numeric($useCase->parent) )
        {
            echo 'uc_'.$useCase->parent." -> $id;\n";
        }
        echo "\n";
        $useCase->with('useCases');
        if(isset($useCase->useCases))
        {
            //echo "subgraph cluster_$id {\n";
            foreach($useCase->useCases as $ch)
                print_uc($ch);
            //echo "}\n";
        }
    }


    foreach ( UseCase::model()->findAll('t.parent is null') as $useCase)
    {
        print_uc($useCase);
    }

?>


}