<?php

require_once realpath("Composer/autoload.php");
use MyApp\App;

require_once "src/config/config.php";

$miapp=new App();
$miapp->prepare_app(); //Preparo la app
$miapp->cookie_policy(false);
$miapp->run(); //Ejecuto la app

?>