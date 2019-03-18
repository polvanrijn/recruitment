<?php
/**
 * Created by PhpStorm.
 * User: pol
 * Date: 2019-01-20
 * Time: 21:43
 */

include_once('inc/header.php');
include_once('inc/classes/DB.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_GET['id'], $_GET['code'])){
    echo "<h1 class='my-5'>". DB::confirm_email($_GET['id'], $_GET['code']) ."</h1>";
}
else {
    echo '<h1 class="my-5">Ongeldige aanvraag</h1>';
}
?>

<?php
include_once('inc/footer.php');