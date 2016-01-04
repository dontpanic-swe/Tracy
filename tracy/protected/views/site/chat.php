<?php
Yii::app()->clientscript->registerCss('chat',
<<<CSS
.chat-msg {
    background-color: rgba(255, 255, 255, 0.5);
    border: 1px solid black;
    border-radius: 0.5em 0.5em 0.5em 0.5em;
    padding: 0.25em;
    width: 50%;
}

.chat-user, .chat-time {
    display: inline-block;
    font-size: x-small;
    font-weight: bold;
}

.chat-time {
    float: right;
}

.chat-content {
    color: black;
}

#chat-container {
    overflow-y: auto;
    max-height: 600px;
}
CSS
);
echo '<div id="chat-container">';

//$this->renderPartial('chat-raw');

echo '</div>';

$url = CHtml::normalizeUrl(array('postChat'));
$raw_chat_url = CHtml::normalizeUrl(array('rawChat'));

?>

<form id='chat-form' action='<?php echo $url;?>'>
    <textarea name='content' id="chat-content"></textarea>
    <input type="button" value="Send" id='chat-btn' />
</form>

<script>
var waiting = false;
var last_id = 0;
function updateChat()
{
	if(!waiting)
    {
		waiting = true;
		jQuery.ajax({
			type: "GET",
			url: "<?php echo $raw_chat_url;?>?last="+last_id,
			data: "",
			dataType: "json",
			success: function(data)
            {
				if(data)
                {
                    jQuery('#chat-container').append(data.content);
                    last_id = data.last_id;
				}
				document.getElementById('chat-container').scrollTop = document.getElementById('chat-container').scrollHeight;
				waiting = false;
			}
		});
	}
	setTimeout(updateChat, 3000);
}

updateChat();
</script>
<?php
Yii::app()->clientscript->registerScript('chat',
<<<JS

$("#chat-btn").click( function()
{
    $.ajax({
        type: 'POST',
        url: '$url',
        data: $("#chat-form").serialize(),
        dataType:'html'
    });
    $("#chat-content").val("");
}
);


JS
);

