<?php
require "common.php";
require "services.php";

$serv = new DBService();

$login = $_POST['login'];
$password = $_POST['password'];

if ($serv->isValidAccount($login, $password)){
    $_SESSION['user'] = $login;
    $_SESSION['role'] = $serv->getRole($login);
}

redirect('index.php');
?>
