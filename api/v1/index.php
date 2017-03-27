<?php
include 'LoginSystem.php';
$sys = new LoginSystem();
if($_GET['request'] == 'login')
    $sys->login($_GET['username'], $_GET['password']);