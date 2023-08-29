<?php namespace MyApp\Core\Helpers;

use MyApp\Core\Helpers\Encriptar;
use MyApp\config\vars_form;

class Form{
    use vars_form;
    private $cripto;

    public function __construct(){
        $this->cripto= new Encriptar();
    }
    #Permite validar Recaptcha V3 Google
    public function valited_google_form_v3(){
        
        if(isset($_POST[$this->nametoken3])){
            $google_token=$_POST[$this->nametoken3];
            $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$this->secret_key3."&response=$google_token");
            $response=json_decode($response);
            $response=(array)$response;
        
            if($response['success'] && ($response['score'] > 0.7)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    #Permite validar Recaptcha V2 Google
    public function valited_google_form_v2(){
        
        if(isset($_POST[$this->nametoken2])){
            $google_token=$_POST[$this->nametoken2];
            $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$this->secret_key2."&response=$google_token");
            $response=json_decode($response);
            $response=(array)$response;
        
            if($response['success']){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    #Retorna el identificador del formulario
    public function get_name_form(){
        return $this->name_form;
    }
     
    #Genera una Key aleatoria para el form
    public function get_key_form(){
        return $this->cripto->generated_randon(rand(10,30));
    }

    #Funcion que permite que la key de seguridad del Formulario sea GLOBAL
    function generate_key_form($name_key,$key){
        $_SESSION[$name_key]=$key;
    }
    //Solo se activa la creacion de la key en la pagina del formulario de login
    function generate_key_formlogin($method,$key,$actual_pag){
        if($method==$actual_pag && !isset($_POST[$this->name_form])){
            self::generate_key_form($this->name_keylogin,$key);
        }
    }

    //Solo se activa la creacion de la key en la pagina del formulario de registro
    function generate_key_formregister($method,$key,$actual_pag){
        if($method==$actual_pag && !isset($_POST[$this->name_form])){
            self::generate_key_form($this->name_keyregister,$key);
        }
    }

    /* METODOS DE CONFIRMACION DEL ENVIO DE DATOS DEL FORMULARIO */

    #Funcion exclusiva del login - Confirma el envio de informacion
    function confirm_form_login(){
        return self::confirm_form_key($this->name_keylogin,$this->name_keylogin);
    }

    #Funcion exclusiva del register - Confirma el envio de informacion
    function confirm_form_register(){
        return self::confirm_form_key($this->name_keyregister,$this->name_keyregister);
    }

    #Confirma el envio de informacion de un formulario con key
    function confirm_form_key($name_key,$name_form_key,$name_form=null){
        // $name_form_key=($name_form_key!=null)?$name_form_key:$this->name_form;
        $name_form=$name_form==null?$this->name_form:$name_form;
        
        $res=isset($_POST[$name_form]);
        if(!$res)
            return false;    
        $val= isset($_POST[$name_form_key]) && isset($_SESSION[$name_key]);

        if($val){   
           
            $val=$_POST[$name_form_key]==$_SESSION[$name_key];

            unset($_SESSION[$name_key]);//Elimina el name form
            unset($_POST[$name_form_key]);//Elimina la key unica
            return $val;
        }
            
        return false;
    }

    #Confirma el envio de informacion de un formulario sin key $_POST
    function confirm_form($name_form=null){
        $name_form=($name_form!=null)?$name_form:$this->name_form;
        $val=(isset($_POST[$name_form]))?true:false;
        if($val) unset($_POST[$name_form]);//Elimina el name form
        return $val;
    }

    #Confirma el envio de informacion $_GET
    function confirm_simple_form($name_form=null){
        $name_form=($name_form!=null)?$name_form:$this->name_form;
        $val=(isset($_GET[$name_form]))?true:false;
        if($val) unset($_GET[$name_form]);//Elimina el name form
        return $val;
    }

    /* METODOS PARA TRATAMIENTO DE LA INFORMACION */

    #Condensa/Limpia los datos (METHOD POST)
    public function limpiar_data($args){
        $data=array();
        for($i=0;$i<count($args);$i++){
            $data[$args[$i]]=addslashes(htmlentities($_POST[$args[$i]]));
            unset($_POST[$args[$i]]);    
        }
        return $data;
    }

    
}
?>