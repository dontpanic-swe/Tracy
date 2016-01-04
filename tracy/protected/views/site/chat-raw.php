<?php

$date = date('Y-m-d H:i:s',strtotime("-30  minutes"));
$all = Chat::model()->findAll("id > :last and time > :lt",
                              array('last'=>$last,'lt'=>$date));
$cont = "";
$id = 0;
foreach ( $all as $msg )
{
    $cont .= "<div class='chat-msg' id='$msg->id' >\n";
    $cont .= "<div class='chat-user'>$msg->user</div>\n";
    $cont .= "<div class='chat-time'>$msg->time</div>\n";
    $cont .= "<div class='chat-content'>$msg->content</div>\n";
    $cont .= "</div>\n";
    $id = $msg->id;
}

if ( $id == 0 )
{
    $id = Yii::app()->db->createCommand()
                            ->select('max(id)')
                            ->from('chat')
                            ->queryScalar();
}

echo json_encode(array('content'=>$cont,'last_id'=>$id));