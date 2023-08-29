<?php namespace MyApp\config;

trait vars_manejador{
    private $themes_styles=1;
    private $dir_controller="Controllers/";
    private $dir_model="Models/";
    private $dir_view="Views/";
    private $dir_public="public/";
    /** Ninguno de ellos puede ser un controlador
     *  General
     *  Components
     *  Register
     *  Login
     * 
     */
    private $controllers=array(
        ['Prueba',true],
        ['About',true],
        ['Project',true],
        ['Contact',true]
        
    );


}
?>