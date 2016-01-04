<?php

    
    $uc = UseCase::model()->with('useCaseEvents')->findByPk($id);
    
    if ( $uc == null )
        die;
        
    $actors = array();
    foreach ( $uc->useCaseEvents as $ev )
    {
        $ev->with('primaryActor', 'actors');
        foreach($ev->actors as $act )
            $actors[$act->id_actor] = $act;
        $actors[$ev->primaryActor->id_actor] = $ev->primaryActor;
    }
?>
digraph UseCase {
    rankdir=LR;
    compound=true;

    // Actors
    subgraph primaryActors {
        peripheries=0;
        labelloc="b";
        node [shape=plaintext];
        edge  [arrowhead="oarrow", minlen=3];

        // Sticky men 
        <?php
            foreach ( $actors as $act )
            {
                echo "subgraph clusterActor{$act->id_actor} {
                    label=\"{$act->description}\";
                    actor_{$act->id_actor}[image=\"protected/components/ucase_script/man.svg\" label=\"\"];
                };\n"; 
                
                $act->with('parent0');
                if ( $act->parent0 != null  && isset($actors[$act->parent0->id_actor]) )
                {
                    $idp = $act->parent0->id_actor;
                    echo "actor_{$act->id_actor}->actor_$idp [constraint=false lhead=clusterActor$idp];\n";
                }
            }
        ?>
    } // primaryActors


    // System
    subgraph clusterSystem {
        labelloc="t";
        label="Sistema";

        node [shape=ellipse, style=solid];
        <?php
            // edge [arrowhead="vee", style=dashed];
            foreach ( $uc->useCaseEvents as $ev )
            {
                if ($ev->category == 1)
                {
                    $ev->with('refersTo');
                    $d = $ev->description;
                    $u = "";
                    if ( isset($ev->refersTo) )
                    {
                        $d .= " [".$ev->refersTo->public_id()."]";
                        $u = ", URL=\"".$this->createAbsoluteUrl(
                                'useCase/view',
                                array('id'=>$ev->refersTo->id_use_case)
                            )."\"";
                    }
                    echo "event_{$ev->id_event} [label=\"$d\" $u];\n";
                }
                // Extends?
                // event_{$ev->id_event}->event_{$ev->extends_what}  [constraint=false, label="«extend»"];
            }
        ?>
    } // clusterSystem


    // Actor -> use case event
    edge [dir=none, style=solid];
    
    <?php
        foreach ( $uc->useCaseEvents as $ev )
        {
	  if ($ev->category == 1) {
            echo "/* event {$ev->description} */\n";
            
            $ev->with('primaryActor', 'actors');
            
            echo "actor_{$ev->primaryActor->id_actor} -> event_{$ev->id_event}; // (Primary) \n";
            
            foreach($ev->actors as $act )
                echo "actor_{$act->id_actor} -> event_{$ev->id_event};\n";
	   }
        }
    ?>
} 
