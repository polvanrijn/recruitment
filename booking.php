<?php
/**
 * Created by PhpStorm.
 * User: pol
 * Date: 2019-01-20
 * Time: 21:43
 */

include_once('inc/header.php');
include_once('inc/classes/DB.php');

echo "<h1 class='mt-5'>Bedankt voor uw interesse</h1>";
echo '<p class="lead">Het onderzoek is inmiddels afgerond.</p>';


//if (isset($_POST['date_id'], $_POST['email'])) {
//    if (DB::date_is_free($_POST['date_id'])) {
//        echo "<h1 class='mt-5'>Bedankt voor uw aanmelding</h1>";
//        echo '<p class="lead">Check uw email (' . $_POST['email'] . '). Kijk eventueel ook in de spam-map. Klik dan op de bevestigingslink om de afspraak te bevestigen.</p>';
//        DB::create_confirmation($_POST['email'], $_POST['date_id']);
//    } else {
//        echo "<h1 class='mt-5'>Iemand was helaas sneller!</h1>";
//        echo '<p class="lead">Ga terug naar <a href="booking.php">de vorige pagina</a> en maak opnieuw een afspraak</p>';
//    }
//} else {
//    echo "<h1 class='mt-5'>Maak een afspraak</h1>";
//    echo '<p class="lead">Kies een geschikt tijdsstip voor het experiment. Het zou erg fijn zijn als u de afspraak zo legt dat meerdere afspraken op elkaar volgen.</p>';
//
//    $available_slots = DB::get_available_slots();
//    echo '<form method="post">';
//    echo '<div class="row">';
//    echo '<div class="col">';
//    $last_date = '';
//    setlocale(LC_ALL, 'nl_NL');
//    $count = 0;
//    echo '<div class="card-deck">';
//    foreach ($available_slots as $slot) {
//        $count++;
//        $id = $slot['id'];
//        $date = $slot['date'];
//        $start = substr($slot['start'], 0, 5);
//        $end = substr($slot['end'], 0, 5);
//        $time = strtotime("$date $start");
//        $disabled = '';
//        if (time() > $time or !is_null($slot['confirmed'])) {
//            $disabled = 'disabled';
//        }
//        if ($date != $last_date and $count > 1) {
//            echo '</ul>';
//            echo '</div>';
//        }
//        if ($date != $last_date) {
//            echo '<div class="card mb-3" style="max-width: 30rem">';
//            echo '<div class="card-header">' . strftime('%A<br>%d %B', strtotime($date)) . '</div>';
//            echo '<ul class="list-group list-group-flush">';
//        }
//
//        echo '<li class="form-check list-group-item ml-2">';
//
//        echo "<input class='form-check-input' type='radio' name='date_id' id='i$id' value='$id' $disabled required>";
//        $class = '';
//        if (!is_null($slot['confirmed'])) {
//            $class = 'text-danger';
//        }
//        echo "<label class='$class form-check-label' for='i$id'>";
//        echo "$start-$end";
//        echo '</label>';
//
//        echo '</li>';
//
//
//        $last_date = $date;
//
//    }
//    echo '</ul>';
//    echo '</div>';
//    echo '</div>';
//
//    echo '</div>';
//    echo '</div>';
//    echo '<div class="form-group mt-3">';
//    echo '<label for="email">Emailadres</label>';
//    echo '<input type="email" class="form-control" id="email" name="email" placeholder="Emailadres" required>';
//    echo '</div>';
//    echo '<button type="submit" class="btn btn-lg btn-success mt-4 mb-5">Bevestig mijn afspraak</button>';
//    echo '</form>';
//}

include_once('inc/footer.php');