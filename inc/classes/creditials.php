<?php
/**
 * Created by PhpStorm.
 * User: pol
 * Date: 2019-02-04
 * Time: 16:59
 */

function get_mqsl_creditials()
{
    return array(
        'user' => 'root',
        'password' => 'root',
        'db' => '', // DB name
        'host' => 'localhost'
    );
}

function get_email_creditials()
{
    return array(
        'Host' => '',                           // Specify main and backup SMTP servers
        'SMTPAuth' => true,                     // Enable SMTP authentication
        'Username' => '',                       // SMTP username
        'Password' => '',                       // SMTP password
        'SMTPSecure' => 'tls',                  // Enable TLS encryption, `ssl` also accepted
        'Port' => 587                           // TCP port to connect to
    );
}