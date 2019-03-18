<?php
/**
 * Created by PhpStorm.
 * User: pol
 * Date: 2019-01-21
 * Time: 08:59
 */

include_once 'classes/DB.php';

$conn = DB::get_connection();

// sql to create table
$sql = "DROP TABLE IF EXISTS slots";
if ($conn->query($sql) === TRUE) {
    echo "Old table removed successfully <br>";
} else {
    echo "Error removing table: " . $conn->error;
}

$sql = "CREATE TABLE slots (
id VARCHAR(13) PRIMARY KEY, 
date DATE NOT NULL,
start TIME NOT NULL,
end TIME NOT NULL,
email VARCHAR(50),
confirmed TINYINT(1),
completed_survey TINYINT(1),
confirmation_code VARCHAR(33)
                      )";

if ($conn->query($sql) === TRUE) {
    echo "Table slots created successfully <br>";
} else {
    echo "Error creating table: " . $conn->error;
}

function getServiceScheduleSlots($duration, $start, $end)
{
    $start = new DateTime($start);
    $end = new DateTime($end);
    $start_time = $start->format('H:i');
    $end_time = $end->format('H:i');
    $i = 0;
    while (strtotime($start_time) <= strtotime($end_time)) {
        $start = $start_time;
        $end = date('H:i', strtotime('+' . $duration . ' minutes', strtotime($start_time)));
        $start_time = date('H:i', strtotime('+' . $duration . ' minutes', strtotime($start_time)));
        $i++;
        if (strtotime($start_time) <= strtotime($end_time)) {
            $time[$i]['start'] = $start;
            $time[$i]['end'] = $end;
        }
    }
    return $time;
}

$slot = getServiceScheduleSlots(30, '09:00', '22:00');

$start_date = strtotime("4 February 2019");
for ($i = 0; $i < 7; $i++) {
    $date = date("Y-m-d", $start_date + $i * 60 * 60 * 24);
    foreach ($slot as $time) {
        $id = uniqid();
        $start = date('H:i:s', strtotime($time['start']));
        $end = date('H:i:s', strtotime($time['end']));
        $sql = "INSERT INTO slots (id, date, start, end)
VALUES ('$id', '$date', '$start', '$end')";

        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully <br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}


$conn->close();