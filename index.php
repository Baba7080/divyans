<?php

define("BASE_PATH", "/naksh/");

require_once 'controllers/BaseController.php';
require_once 'models/BaseModel.php';

$controller = new BaseController();
$controller->handleRequest();
?>
