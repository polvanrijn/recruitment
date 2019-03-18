<?php
session_start();
#unset($_SESSION['suitable']);
include_once 'functions.php';

?>
    <!doctype html>
    <html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
              integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
              crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet">
        <link href="/src/style.css" rel="stylesheet">
        <title>Experiment</title>
    </head>
    <body>
    <div class="container">
<?php


if (isset($_GET['msg'])){
    echo '<div class="alert alert-success" role="alert">' . $_GET['msg'] . '</div>';
}