<?php
/**
 * Created by PhpStorm.
 * User: pol
 * Date: 2019-01-21
 * Time: 08:59
 */

include_once 'classes/DB.php';
include_once 'classes/Mail.php';

$conn = DB::get_connection();

$date = date("Y-m-d", time());
$sql = "SELECT * FROM slots WHERE date ='$date' AND confirmed IS NOT NULL";
$results = $conn->query($sql);
$times = array();
foreach ($results as $row) {
    Mail::send_reminder($row);
    print('Sent reminder for appointment <br>');
    $obj = array();
    $obj['id'] = $row['id'];
    $obj['start'] = $row['start'];
    $obj['end'] = $row['end'];
    array_push($times, $obj);
}
if (!empty($times)) {
    Mail::send_schedule($times);
    print('Sent schedule <br>');
}


$sql = "SELECT * FROM slots WHERE date < DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND confirmed IS NOT NULL AND completed_survey IS NOT NULL";
foreach ($results as $row) {
    Mail::send_reminder_survey($row);
    print('Sent reminder to fill out survey <br>');
}

print('Done <br>');

$conn->close();