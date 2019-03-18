<?php
/**
 * Created by PhpStorm.
 * User: pol
 * Date: 2019-01-20
 * Time: 21:43
 */

include_once('inc/header.php');

if (isset($_SESSION['suitable']) and $_SESSION['suitable'] == 0){
    echo "<h1 class='mt-5'>Helaas kunt u niet aan het experiment meedoen</h1>";
    echo "<p class='lead'>Hartelijk bedankt voor uw interesse!</p>";
    include_once 'inc/footer.php';
    die();
}

?>

            <h1 class="display-4 mt-5">Name of your experiment</h1>
            <a class="btn btn-primary btn-lg my-5" href="pre-test.php">Klik hier om verder te gaan</a>

        </div>
    </div>
<?php
include_once('inc/footer.php');