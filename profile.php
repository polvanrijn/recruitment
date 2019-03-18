<?php
/**
 * Created by PhpStorm.
 * User: pol
 * Date: 2019-01-20
 * Time: 21:43
 */

include_once('inc/header.php');
include_once('inc/classes/DB.php');


if (isset($_GET['id']) and !DB::date_is_free($_GET['id'])) {
    $result = DB::get_slot($_GET['id']);
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'cancel') {
            DB::cancel_appointment($_GET['id']);
            echo "<h1 class='my-5'>Uw afspraak is afgezegd</h1>";
            include_once('inc/footer.php');
            die();
        } elseif ($_POST['action'] == 'change' and isset($_POST['date_id'])) {
            DB::change_appointment($_GET['id'], $_POST['date_id']);
            $new_id = $_POST['date_id'];
            echo "<script>window.location.replace('/profile.php?id=$new_id&msg=Uw afspraak is gewijzigd!');</script>";
        }
    }

    echo "<h1 class='my-5'>Mijn profiel</h1>";
    $result = DB::extract_obj_information($result);
    echo '<div class="row">';
    echo '<div class="col-4">';
    echo "<h2>Mijn afspraak</h2>";
    echo '<table style="font-size:120%">';
    echo "<tr>";
    echo "<td><b>Datum</b></td>";
    echo "<td class='pl-3'>" . $result['date'] . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td><b>Tijdstip</b></td>";
    echo "<td class='pl-3'>" . $result['start'] . "-" . $result['end'] . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td><b>Code</b></td>";
    echo "<td class='pl-3'><code class='m-0'>" . $result['id'] . "</code></td>";
    echo "</tr>";
    echo '</table>';
    echo '</div>';

    echo '<div class="col-6">';
    echo '<h4 class="pb-2">Adresgegevens</h4>';
    echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d812.8384219352279!2d6.553284908514333!3d53.22665962347331!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c9cd472ac5d117%3A0x936368c7a6c9dd7c!2sTuinbouwstraat+124%2C+9717+JP+Groningen%2C+Netherlands!5e0!3m2!1sen!2sde!4v1549290320315" height="300" frameborder="0" style="border:0; width:100%" allowfullscreen></iframe>';
    echo '</div>';
    echo '<div class="col-2">';
    echo '<h4 class="pb-2">Afzeggen</h4>';
    echo '<form method="POST">';
    echo '<input type="hidden" name="old_id" value="' . $_GET['id'] . ' ">';
    echo '<input type="hidden" name="action" value="cancel">';
    echo '<button type="submit" class="btn btn-lg btn-danger">Afspraak afzeggen</button>';
    echo '</form>';
    echo '</div>';
    echo '</div>';
    echo "<h2>Andere afspraak maken</h2>";
    $available_slots = DB::get_available_slots();
    echo '<form method="post">';
    echo '<div class="row">';
    echo '<div class="col">';
    $last_date = '';
    setlocale(LC_ALL, 'nl_NL');
    $count = 0;
    echo '<div class="card-deck">';
    foreach ($available_slots as $slot) {
        $count++;
        $id = $slot['id'];
        $date = $slot['date'];
        $start = substr($slot['start'], 0, 5);
        $end = substr($slot['end'], 0, 5);
        $time = strtotime("$date $start");
        $disabled = '';
        if (time() > $time or !is_null($slot['confirmed'])) {
            $disabled = 'disabled';
        }
        if ($date != $last_date and $count > 1) {
            echo '</ul>';
            echo '</div>';
        }
        if ($date != $last_date) {
            echo '<div class="card mb-3" style="max-width: 30rem">';
            echo '<div class="card-header">' . strftime('%A<br>%d %B', strtotime($date)) . '</div>';
            echo '<ul class="list-group list-group-flush">';
        }

        echo '<li class="form-check list-group-item ml-2">';

        echo "<input class='form-check-input' type='radio' name='date_id' id='i$id' value='$id' $disabled required>";
        $class = '';
        if (!is_null($slot['confirmed'])) {
            $class = 'text-danger';
        }
        echo "<label class='$class form-check-label' for='i$id'>";
        echo "$start-$end";
        echo '</label>';

        echo '</li>';


        $last_date = $date;

    }
    echo '</ul>';
    echo '</div>';
    echo '</div>';
    echo '<input type="hidden" name="old_id" value="' . $_GET['id'] . ' ">';
    echo '<input type="hidden" name="action" value="change">';
    echo '</div>';
    echo '</div>';
    echo '<button type="submit" class="btn btn-lg btn-success mt-4 mb-5">Bevestig mijn nieuwe afspraak</button>';
    echo '</form>';

} else {
    echo '<h1 class="my-5">Ongeldige aanvraag</h1>';
}
?>

<?php
include_once('inc/footer.php');