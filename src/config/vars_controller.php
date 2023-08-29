<?php namespace MyApp\config;

    trait vars_controller{

        private $directory_cookie_policy="Views/Components/Cookie_Policy/";

        private $control_principal="Prueba";//el nombre del controlador de la ruta main
        private $control_login="Login";
        private $control_register="Register";

        private $main_language="en"; #Idioma por defecto
        private $main_theme=1; # 1 a 10
        private $main_mode=1; # 0 / 1
    
    }
?>