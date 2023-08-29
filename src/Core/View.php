<?php namespace MyApp\Core;  

use MyApp\Core\Render;
use MyApp\Core\Requirements;
use MyApp\Core\Error\Errores;
use MyApp\config\vars_form;

class View {
    private $view;
    private $method=null;
    private $param=null;

    private $archivo_view;
    private $archivo_aux;
    
    
    private $active_main;

    private $archivo_login;
    private $archivo_register;
    
    private $language;
    private $archivo_language;

    private $requirements;
    private $render;
    private $error;

    use vars_form;

    function __construct($view,$active,$language,$theme,$mode){
       
        //Se manda la vista principal por defecto index
        $this->view = $view;
        $this->active_main = $active;
        $this->archivo_view = "Views/".$view."/index.php";

        //Templates predeterminados
        $this->directorio="Views/Components/";
        $this->archivo_login = $this->directorio."Login/";
        $this->archivo_register = $this->directorio."Register/";
         
        //Settings
        $this->language=$language;
        $this->archivo_language="Language/".$language."_sub.php";

        $this->requirements=new Requirements($theme,$mode,$view);
        
        $this->render=new Render($this->archivo_language,$this->requirements->requirements());
        $this->error=new Errores();
    }

    #Permite ejecutar el constructor de un Contralador separado de sus metodos.Retorna true/false
    function get_space(){
        return $this->active_main;
    }
    function get_method(){
        return $this->method;
    }
    function set_method($method){
        $this->method = $method;
    }
    function get_param(){
        return $this->param;
    }
    function set_param($param){
        $this->param = $param;
    }

    #Puedes enviar un parametro con la data para la plantilla(opcional)
    function generar_vista_main($data=[]){
        
        if($this->active_main){
            if(file_exists($this->archivo_view)){
                $this->render->renderizar($this->archivo_view,$data); #PRIMER RENDER
                
            }else{
                $this->error->falla_vista();
            }
        }
    }
    #Recibe como parametro el nombre de la plantilla, segundo parametro es la data(opcional)
    function generar_view($template,$data=[]){
        $this->archivo_aux="Views/".$this->view."/".$template.".php";
        #SEGUNDO RENDER
        return file_exists($this->archivo_aux)?$this->render->renderizar($this->archivo_aux,$data):$this->error->falla_template();
    }
    #Similar a generar_view pero con la caracteristica de tener un modo incognito
    function generar_view_private($template,$private=true,$data=[]){
        if(!$private){
            $this->archivo_aux="Views/".$this->view."/".$template.".php";
            #TERCER RENDER
            return file_exists($this->archivo_aux)?$this->render->renderizar($this->archivo_aux,$data):$this->error->falla_template();
        }else{
            return $this->error->falla_template_privado();
        }
    }
    #Metodo avanzado-> Permite establecer un control para un metodo que utilice parametros
    function avanced_view($control, $template1, $template2, $data1, $data2){
        if($control==null){
            $archivo="Views/".$this->view."/".$template1.".php";
            $complete_temp=$this->view."/".$template1;
            (View::validated_view($complete_temp))?
                $this->render->renderizar($archivo,$data1): #CUARTO RENDER
                $this->error->falla_template_estatico();
        }else{
            $archivo="Views/".$this->view."/".$template2.".php";
            $complete_temp=$this->view."/".$template2;
            (View::validated_view($complete_temp))?
                $this->render->renderizar($archivo,$data2): #QUINTO RENDER
                $this->error->falla_template_estatico();
        }
    }
    #Similar a avanced_view pero con la caracteristica de tener un modo incognito
    function avanced_view_private($private,$control, $template1, $template2, $data1, $data2){
        if(!$private){
            if($control==null){
                $archivo="Views/".$this->view."/".$template1.".php";
                $complete_temp=$this->view."/".$template1;
                (View::validated_view($complete_temp))?
                    $this->render->renderizar($archivo,$data1): #SEXTO RENDER
                    $this->error->falla_template_estatico();
            }else{
                $archivo="Views/".$this->view."/".$template2.".php";
                $complete_temp=$this->view."/".$template2;
                (View::validated_view($complete_temp))?
                    $this->render->renderizar($archivo,$data2): #SEPTIMO RENDER
                    $this->error->falla_template_estatico();
            }
        }else{
            return $this->error->falla_permisos();
        }
    }
    
    #Retorna una vista, necesita de la ruta completa del directorio donde se encuentra el template
    static function validated_view($template,$data=[]){
        return file_exists("Views/".$template.".php")? true:false;
    }

    //SOLO PARA CLASES - LOGIN/REGISTER
    
    #Retorna el template para el login
    function template_login($key){
        if($this->active_main){
            if(file_exists($this->archivo_login)){
                $keys['key_form']=$key;
                $keys['key_google']=$this->site_key3;
                $keys['name_key_google']=$this->nametoken3;
                return $this->render->renderizar($this->archivo_login."index.php",$keys,$this->archivo_login.'formulario-login.php'); #OCTAVO RENDER
            }else{
                return $this->error->falla_template_login();
            }
        }
    }
    #Retorna el template para el register
    function template_register($key){
        if($this->active_main){
            if(file_exists($this->archivo_register)){
                $keys['key_form']=$key;
                $keys['key_google']=$this->site_key2;
                $keys['name_key_google']=$this->nametoken2;
                $this->render->renderizar($this->archivo_register."index.php",$keys,$this->archivo_register.'formulario-register.php'); #NOVENO RENDER
            }else{
                $this->error->falla_template_register();
            }
        }
    }
    
   

    #Renderiza el template para la vista del Login
    function run_login($name_form, $key){
        if(!isset($_POST[$name_form])&& $this->active_main){
            self::template_login($key);
        }
    }

    #Renderiza el template para la vista del Register
    function run_register($name_form, $key){
        if(!isset($_POST[$name_form]) && $this->active_main){
            self::template_register($key);
        }
    }
    
    
    #Retorna la ruta del archivo que contiene al template principal por defecto index
    function get_archivo_view(){
        return $this->archivo_view;
    }



    //Vista para los msm de errores de las vistas
    function error_generico(){
        return $this->error->generar();
    }
    function error_generico2(){
        return $this->error->no_found();
    }


     #Renderiza el template para la vista Resend-Email
     function template_resend_email($directory,$data){
        $archivo=$this->directorio.$directory."index.php";
        if($data!=null) {
            $data['key_google']=$this->site_key3;
            $data['name_key_google']=$this->nametoken3;
            return $this->render->renderizar($archivo,$data,$this->directorio.$directory."resend-email.php","Reenvio de Email"); #DECIMO RENDER
        }
        else 
            return $this->render->renderizar($archivo);
    }

    function success($directory,$data=null){
        $archivo=$this->directorio.$directory."index.php";
    
        if($data!=null) {
            return $this->render->renderizar($archivo,$data,$this->directorio.$directory."success.php","Exitoso"); #DUODECIMO RENDER
        }
        else{
            return $this->render->renderizar($archivo);  #DOCEAVO RENDER
        }
    }
    function failed($directory,$data=null){
        $archivo=$this->directorio.$directory."index.php";
        if($data!=null) return $this->render->renderizar($archivo,$data,$this->directorio.$directory."failed.php","Fallido");
        else return $this->render->renderizar($archivo);
    }
    function failed_email($directory,$data=null){
        $archivo=$this->directorio.$directory."index.php";
        if($data!=null) return $this->render->renderizar($archivo,$data,$this->directorio.$directory."failed-email.php","Fallo de Email");
        else return $this->render->renderizar($archivo);
    }
    function resend_email($directory,$data=null){
        $archivo=$this->directorio.$directory."index.php";
        if($data!=null) return $this->render->renderizar($archivo,$data,$this->directorio.$directory."msm-resend-email.php","Reenvio del Correo de Verificacion");
        else return $this->render->renderizar($archivo);
    }
    function success_email($directory,$data=null){
        $archivo=$this->directorio.$directory."index.php";
        if($data!=null) return $this->render->renderizar($archivo,$data,$this->directorio.$directory."success-email.php","Envio Exitoso");
        else return $this->render->renderizar($archivo);
    }
    function success_verify($directory,$data=null){
        $archivo=$this->directorio.$directory."index.php";
        if($data!=null) return $this->render->renderizar($archivo,$data,$this->directorio.$directory."success-verify.php","Verificacion Exitosa");
        else return $this->render->renderizar($archivo);
    }
}
?>