<?php

define("BASE_PATH", "/simplemvc/");

require_once 'controllers/BaseController.php';
require_once 'models/BaseModel.php';

$controller = new BaseController();
$controller->handleRequest();
?>
