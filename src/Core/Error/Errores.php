<?php namespace MyApp\Core\Error;

use MyApp\config\vars_session;
use MyApp\config\vars_controller;

class Errores{
    private $directorio="/Views/Components/Errores/";
    use vars_session;
    use vars_controller;

    function __construct(){

        if(isset($_COOKIE[$this->name_languagecookie])){
            $this->main_language=$_COOKIE[$this->name_languagecookie];
        }

        $archivo_language="Core\..\..\Language\\".$this->main_language."_sub.php";
        
        if(file_exists($archivo_language))
            require($archivo_language);
    }
    //Ruta
    function generar(){
        echo "error mandarin";
    }
    function no_resource(){
        echo "No disponible";
    }
    function no_found(){
        echo "No found";
    }
    function no_permission(){
        echo "No tienes Permiso";
    }

    //Controller

    function falla_archivo_model(){
        print "<h2>Hay archivo para el modelo pero no hay clase.</h2>";
    }
    function falla_control($control){
        print "<h2>Tienes un archivo pero No tienes ninguna clase para el controlador Principal ".$control."</h2>";
    }
    function falla_control_principal($control){
        print("<h2>Tienes un archivo pero No tienes ninguna clase para el controlador ".$control."</h2>");
    }
   
    //View
    function falla_vista(){
        print "Aun no tienes vista para tu Controlador ʕ•́ᴥ•̀ʔっ♡";
    }
    function falla_template(){
        print("Ops no cuentas con el template indicado");
    }
    function falla_template_privado(){
        print "Esta seccion es privada requieres de mas permisos";
    }
    function falla_template_estatico(){
        print "No se encontro el template especificado";
    }
    function falla_permisos(){
        print "Esta seccion es privada requieres de mas permisos";
    }
    function falla_template_login(){
        print "No se encontro tu template para el Login ʕ•́ᴥ•̀ʔっ♡";
    }
    function falla_template_register(){
        print "No se encontro tu template para el Register ʕ•́ᴥ•̀ʔっ♡";
    }
}
?>