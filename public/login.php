<?php
require_once "../app/controllers/AuthController.php";

$controller = new AuthController($conn);
$controller->login();
?>