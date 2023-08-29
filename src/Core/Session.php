<?php namespace MyApp\Core;
use MyApp\Core\User;
use MyApp\Core\Helpers\Encriptar;
use MyApp\config\vars_session;

class Session {

    use vars_session;
    private $use_cookie;
    private $cript;

    public function __construct($validar_cokkie=true){       
        $this->use_cookie=($validar_cokkie)?true:false;
        $this->cript=new Encriptar();
        session_start();
    }
    
    
    #Crear una cokkie
    public function create_cokkie($name_cookie,$content,$time){
        setcookie($name_cookie,$content,time()+$time,"/");
    }
    #Crear una sesion
    public function create_sesion($name_sesion,$content){
        $_SESSION[$name_sesion]=$content;
    }
    #Crea una cokkie para los temas de estilo.
    public function create_cokkie_theme($content){
        self::create_cokkie($this->name_cookie_theme,$content,$this->duration_cookie_theme);
    }
    #Verifica si existe la cookie para los temas de estilo.
    public function verify_cookie_theme(){
        return isset($_COOKIE[$this->name_cookie_theme]);
    }

    #Crea una cokkie para el modo de estilo.
    public function create_cokkie_mode($content){
        self::create_cokkie($this->name_cookie_mode,$content,$this->duration_cookie_mode);
    }

    #Verifica si existe la cookie para el modo de estilo.
    public function verify_cookie_mode(){
        return isset($_COOKIE[$this->name_cookie_mode]);
    }

    #Crea una cookie para la configuracion de idiomas.
    public function create_cokkie_language($content){
        self::create_cokkie($this->name_languagecookie,$content,$this->duration_cookie_language);
    }
    public function verify_sesion_theme(){
        return isset($_SESSION[$this->name_sesion_theme]);
    }

    public function get_cookie_language(){
        return self::verify_cookie_language()?$_COOKIE[$this->name_languagecookie]:null;
    }
    public function get_cookie_theme(){
        return self::verify_cookie_theme()?$_COOKIE[$this->name_cookie_theme]:null;
    }
    public function get_sesion_theme(){
        return self::verify_sesion_theme()?$_SESSION[$this->name_sesion_theme]:null;
    }
    public function get_cookie_mode(){
        return self::verify_cookie_mode()?$_COOKIE[$this->name_cookie_mode]:null;
    }
    
    #Verifica si existe la cookie de configuracion de idiomas.
    public function verify_cookie_language(){
        return isset($_COOKIE[$this->name_languagecookie]);
    }

    
    #Inicia la sesion
    public function iniciar_sesion($user,$content=''){
        if($this->use_cookie){
            self::create_cokkie($this->name_maincookie,$this->cript->my_hash($content),$this->duration_cookie_sesion);#Duracion de 2horas
        }
        $_SESSION[$this->name_sesion]=$user;
    }
    #Cierra la sesion
    public function cerrar_sesion(){ //Mejorar
        if($this->use_cookie){
            self::create_cokkie($this->name_maincookie,'',-1);
        }
        session_destroy();
        self::redireccionar($this->url_redirecion);
    }


    #Crea la cokkie para el esapcio en donde se mostrara el mensaje de envio del email. Duracion de 5 minutos
    public function create_cookie_space_sms_verify_account($content){
        self::create_cokkie($this->name_msm_cookieverify,$content,$this->duration_sms_space_verify);
    }
    #Verifica la cookie anteriormente creada para el espcio del mensaje-email.
    public function validar_space_msm_cookie_verify(){
        return isset($_COOKIE[$this->name_msm_cookieverify]);
    }


    #Retorna el valor de la cookie de verificacion de la cuenta
    public function get_cookie_verify_account(){
        return $_COOKIE[$this->name_verifycookie_account];
    }

    #Crea la cookie para la verificacion de la cuenta 
    public function create_cookie_verify_account($content=null){
        // $content=$this->cript->my_hash($content);
        $content=($content==null)?$this->cript->generated_randon(rand(12,13)):$content;
        #En el cookie va una cadena de verificacion.
        self::create_cokkie($this->name_verifycookie_account,$content,$this->duration_cookie_verify); #Duracion de 1hr
    }
    #Valida la cookie para la verificacion de la cuenta
    public function validar_cookie_verify_account(){
        // $response=false;
        // if(isset($_COOKIE[$this->name_verifycookie_account])){
        //     if($this->cript->verify_my_hash($_COOKIE[$this->name_verifycookie_account],$value))
        //         $response=true;
        // }
        // return $response;
        $val=false;
        if(isset($_COOKIE[$this->name_verifycookie_account])){
               $val=true; 
        }
        return $val;
    }
    #Elimina la cookie para la verificacion de la cuenta
    public function close_cookie_verify_account($content=null){
        self::create_cokkie($this->name_verifycookie_account,$content,-1);
    }


    #Verifica si un usuario tiene activas sus credenciales SESION Y COOKIE
    public function validar_acceso(){
        if($this->use_cookie){
            return  (isset($_COOKIE[$this->name_maincookie]) || 
                isset($_SESSION[$this->name_sesion]))?true:false;
        }

        return self::validar();
    }
    #Verifica si la sesion esta activa sin cookies
    public function validar(){ 
        return isset($_SESSION[$this->name_sesion])?true:false;
    }

    #Redireciona a otra seccion de la aplicacion
    public function redireccionar($pag=null,$time=null){ 
        $url=URL_COMPLETA."/";
        if(!empty($pag)){
            $url.=$pag;
        }else{
            $url.=$this->url_redirecion;
        }
        header("refresh:".$time.";".$url);
    }

    public function name_sesion(){
        return $this->name_sesion;
    }
    public function name_cookie(){
        return $this->name_maincookie;
    }
    public function name_cookie_policy(){
        return $this->cookie_agree;
    }
   
    
 
}
?>