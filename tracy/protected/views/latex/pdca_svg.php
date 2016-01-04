<?php
header("Content-type: image/svg+xml");

require_once ( "protected/components/graph_lib.php" );



$img_width = $w;
$img_height = $h;
$font_size = $font;

$interval = new DateInterval('P1D');
$date_start = DateTime::createFromFormat('Y-m-d', $date_start);
$date_end = DateTime::createFromFormat('Y-m-d', $date_end);
$date_end_unix = $date_end->getTimestamp();

$statuses=array('Act', 'Check', 'Do', 'Plan' );
$status_colors=array('Act'=>'#579d1c',
                     'Check' =>'#ffd320',
                     'Do' => '#ff950e',
                     'Plan'=>'#c5000b' );
    
        
        echo "<?xml version='1.0' encoding='UTF-8'?>
<!DOCTYPE svg PUBLIC '-//W3C//DTD SVG 1.1//EN'
                     'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd'>
<svg xmlns='http://www.w3.org/2000/svg'
     width='$img_width'
     height='$img_height'
     >";
?>
    <style type="text/css">
        text
        {
            font-size: <?php echo $font_size; ?>px;
        }
        .grid
        {
            stroke:gray;
            stroke-opacity:0.5;
        }
        .plot, .legend_marker
        {
            stroke-width:2;
        }
        .grid_label
        {
            fill:gray;
            text-anchor: end;
        }
        .legend_label
        {
            fill:black;
        }
    </style>
<?php

$label_x = array(); // text on x axis (day)
$raw_data = array();// status => day => number

//initialize
foreach($statuses as $status)
    $raw_data[$status] = array();

// retrieve data
for($date_step = $date_start;
        $date_step->getTimestamp() <= $date_end_unix;
        $date_step->add($interval) )
{
    $raw = 0;
    foreach($statuses as $status)
    {
        $raw +=
            Yii::app()->db_pdca->createCommand()
            ->select('COUNT(*)')
            ->from('dati_pdca')
            ->where("
                `category_name`='PDCA' AND
                `data_importazione`= :date AND
                `status_name` =  :status AND
                `project_parent_id`=  :project",
                array(":date"=>$date_step->format("Y-m-d"),
                      ":status"=>$status,
                      ":project"=>$project )
            )
            ->queryScalar();
            
        if ( $raw == 0 && count($raw_data[$status]) > 0 ) // fix holes in db
            $raw_data[$status] []= $raw_data[$status][count($raw_data[$status])-1];
        else
            $raw_data[$status] []= $raw; // plot real data
    }
    $label_x []= $date_step->format("Y-m-d");
}

$plotted_lines = array();
foreach ( $raw_data as $status => $data )
{
    $plotted_lines []= new data_set($status,$status_colors[$status],$data);
}

$plotted_lines = array_reverse($plotted_lines);

$g = new graph($label_x,$plotted_lines);

$padding = $font_size*2;
$legend_width = $font_size*5;
$graph_width = $img_width - 3*$padding - $legend_width*2;
$graph_height = $img_height - 2*$padding;

$g->legend($padding,
           $padding*2,
           $legend_width,
           min($graph_height,$font_size*5));

$g->render(2*$padding+$legend_width,
           $padding,
           $graph_width,
           $graph_height,
           true,
           grid_style::BEHIND);

echo '</svg>';
        