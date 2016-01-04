<?php
/* @var $this UseCaseController */
/* @var $model UseCase */

function display_uc($useCase, $raw)
{

    echo "\\item \\hyperlink{".$useCase->public_id()."}{".$useCase->public_id()
        ." - ".$useCase->title."} \n";

    $useCase->with('useCases');
    if ( isset($useCase->useCases) && count($useCase->useCases) > 0 )
    {
        echo "\\begin{itemize}\n";
        foreach ( $useCase->useCases as $child)
        {
            display_uc($child,$raw);
        }
        echo "\\end{itemize}\n";
    }

}

$toplevel = UseCase::model()->findAll('parent is null');
foreach ( $toplevel as $useCase)
{
    foreach ( $useCase->useCases as $child)
    {
        echo "\\paragraph{\\hyperlink{".$useCase->public_id()."}{".
            $useCase->public_id()." - ".$useCase->title."}} \n";

        $child->with('useCases');
        if ( isset($child->useCases) && count($child->useCases) > 0 )
        {
            echo "\\begin{itemize}\n";
            foreach ( $child->useCases as $grand)
            {
                display_uc($grand,$raw);
            }
            echo "\\end{itemize}\n";
        }
    }
}



  
?> 