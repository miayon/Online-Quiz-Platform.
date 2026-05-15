<?php

require_once "../app/controllers/RegisterController.php";

$controller = new RegisterController($conn);

$controller->register();
?>