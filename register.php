<?php
require_once "config/db.php";
require_once "controllers/register_controller.php";

$controller = new RegisterController($conn);
$controller->register();
?>
