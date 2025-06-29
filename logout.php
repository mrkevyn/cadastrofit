<?php
require_once 'Classes/Database.php';
require_once 'Classes/User.php';

$database = new Database();
$user = new User($database);

$user->logout();
?>
