<?php


$project_name = Yii::app()->db_redmine->createCommand()
            ->select('name')
            ->from('projects')
            ->where("id=:id",array(":id"=>$project))
            ->queryScalar();

echo "<h1>$project_name</h1>";

$interval = new DateInterval('P1D');
$date_start_object = DateTime::createFromFormat('Y-m-d', $date_start);
$date_end_object = DateTime::createFromFormat('Y-m-d', $date_end);
$date_end_unix = $date_end_object->getTimestamp();

echo "<p>".$date_start_object->format('l F jS Y') .' - '
            . $date_end_object->format('l F jS Y');

echo '<img src="'.
        CHtml::normalizeUrl(array('latex/pdca_svg',
                'project' => $project,
                'date_start' => $date_start,
                'date_end' => $date_end,
                'w' => 970,
                'h' => 512
            )
        ).'" alt="Grafico PDCA" />';

echo '<table><tr><th>Date</th><th>P</th><th>D</th><th>C</th><th>A</th></tr>';

$statuses=array('Plan', 'Do', 'Check', 'Act');
for($date_step = $date_start_object; $date_step->getTimestamp() <= $date_end_unix;
        $date_step->add($interval) )
{
    echo '<tr><td>'.$date_step->format("Y-m-d").'</td>';
    foreach($statuses as $status)
    {
        echo '<td>'.
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
    }
    echo '</tr>';
}

echo '</table>';