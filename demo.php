<?php
include_once 'inc/header.php';
if (!isset($_SESSION['zaehler'])) {
    $_SESSION['zaehler'] = 0;
} else {
    $_SESSION['zaehler']++;
}

echo $_SESSION['zaehler'];

include_once 'inc/footer.php';