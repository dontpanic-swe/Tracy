<?php
$fc = new SimpleXMLElement( file_get_contents(
        'http://weather.yahooapis.com/forecastrss?w=719751&u=c' ) );
$desc = $fc->channel->item->description;
$tp = strpos($desc,'<a');
echo '<h2>'.$fc->channel->item->title.'</h2>';
echo substr($desc,0,-$tp);