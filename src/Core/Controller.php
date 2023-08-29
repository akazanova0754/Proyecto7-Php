<?php namespace MyApp\Core;
    use MyApp\Core\Error\Errores;
    use MyApp\config\vars_controller;

    class Controller{
        use vars_controller;

        private $control;//url en un array
        private $main_ruta;//el nombre de la ruta main (url)

        private $controller=null;
        private $model=null;
        private $view;

        private $archivo_controller;
        private $archivo_modelo;

        private $sesion;
        private $error;
        
        function __construct($ruta_normal,$main_ruta="",$sesion){
            $this->error=new Errores();
            $this->sesion=$sesion;
            $this->control=$ruta_normal;
            $this->main_ruta=$main_ruta;
            $this->control_principal=trim($this->control_principal,"+@=?¿* /");
            self::language();
            self::theme();
            self::mode();
        }
        #Para el aviso de cookies
        function cookie_agree(){
            if(!isset($_COOKIE[$this->sesion->name_cookie_policy()])){
                require($this->directory_cookie_policy."index.php");
            }
        }
        function language(){
            $language=$this->sesion->get_cookie_language();
            if($language!=null)
                $this->main_language=$language;
        }
        function theme(){
            $theme=$this->sesion->get_sesion_theme(); #Retorna la sesion o null

            if($theme!=null)
                $theme=$this->sesion->get_cookie_theme(); #Retorna la cookie o null
            
            if($theme!=null)
                $this->main_theme=$theme;
        
        }
        function mode(){
            $mode=$this->sesion->get_cookie_mode(); #Retorna la cookie o null
            if($mode!=null)
                $this->main_mode=$mode;

        }


        function generar_model(){
            $this->archivo_modelo=str_replace(".php","",$this->archivo_controller);
            $this->archivo_modelo=str_replace("Controllers/","",$this->archivo_modelo);
            $this->clase=$this->archivo_modelo."_model";

            $this->archivo_modelo="Models/".$this->archivo_modelo."/".$this->archivo_modelo."_model.php";
            // var_dump($this->archivo_modelo);
            if(file_exists($this->archivo_modelo)){
                // var_dump($this->archivo_modelo); 
                require_once($this->archivo_modelo);
                if(class_exists($this->clase,false)){
                    $clase=$this->clase;
                    $this->model=new $clase();
                }else{
                    $this->error->falla_archivo_model();//msm1
                }
            }
               
               
            
        }
        function generar_controller(){
            if(($this->control[0]==="index"||$this->control[0]==$this->main_ruta||$this->control[0]=="")){
                if(class_exists($this->control_principal, false)){

                    $visible=(!isset($this->control[1]))?true:false;
                    $class=$this->control_principal;
                  
                    $this->view=new View($this->control_principal,$visible,$this->main_language,$this->main_theme,$this->main_mode); #Primer View
                    self::set_view_atributes();
                    $this->controller=new $class($this->view,$this->model,$this->sesion);

                }else{
                    $this->error->falla_control($this->control[0]);//msm2
                }
            }else{
                if(class_exists($this->control[0],false)){

                    $visible=(!isset($this->control[1]))?true:false;
                    
                    
                    $this->view=new View($this->control[0],$visible,$this->main_language,$this->main_theme,$this->main_mode); #Segundo View
                    self::set_view_atributes();
                    $this->controller= new $this->control[0]($this->view,$this->model,$this->sesion);    

                }else{
                    $this->error->falla_control($this->control[0]);//msm3
                }
            }
        }
        private function set_view_atributes(){
            if(isset($this->control[1])){
                if(is_callable(array($this->control[0],$this->control[1]),false))
                    $this->view->set_method($this->control[1]);
                if(isset($this->control[2])){
                    if(is_callable(array(new Filtro(),$this->control[1]),false)){
                        $filtro=new Filtro();
                        $m=$this->control[1];
                        $p=$this->control[2];
                        if($filtro->$m($p)){
                            $this->view->set_param($this->control[2]);
                        }
                    }
                }
            }
        }
        //Comprueba que si el archivo del controlador existe
        function validar_control(){
            $this->archivo_controller=(ucfirst(strtolower($this->control[0]))!=$this->control[0])?"":"Controllers/".$this->control[0].".php";
            $val=(!empty($this->control_principal)&& $this->control_principal==$this->control[0])?false:true;
            
            if(($this->control[0]=="index"||$this->control[0]==$this->main_ruta||$this->control[0]=="") && !empty($this->control_principal)){
                $this->archivo_controller="Controllers/".$this->control_principal.".php";
            }
            
            return (file_exists($this->archivo_controller) && $val)?true:false;
        }
        
        //Clases que requieran de usuarios
        function require_user(){
            require_once($this->archivo_controller);
            
            $validar=true;
            
            if(($this->control[0]=="index"||$this->control[0]==$this->main_ruta||$this->control[0]=="")){//Se comprueba si se necesita de credenciales
                if(class_exists($this->control_principal,false)){
                    $validar = !property_exists($this->control_principal,"privacity");
                }
            }else{
                // var_dump($this->control_principal);    
                if(!class_exists($this->control_principal,false)){
                    $validar = !property_exists($this->control[0],"privacity");
                }else{
                    $validar = !property_exists($this->control_principal,"privacity");
                }
            }
            //Comprobamos credenciales
            if(!$validar){
                $validar=$this->sesion->validar_acceso();
            }
            
            if($this->sesion->validar_acceso()){
                if($this->control[0]==$this->control_register || $this->control[0]==$this->control_login){
                    $validar=false;
                }
            }
            
            //Retorno True si tiene acceso
            return $validar;
        }

        function error_privacity_login_register(){
            return $this->sesion->validar_acceso();
        }
        //Clases que se desactiven cuando se haya iniciado session
        function change_class(){
            require_once($this->archivo_controller);
            $validar=true;
            if(($this->control[0]=="index"||$this->control[0]==$this->main_ruta||$this->control[0]=="")){//Se comprueba si se necesita de credenciales
                $validar = !property_exists($this->control_principal,"change");
           
            }else{
                $validar = !property_exists($this->control[0],"change");
            }
            //Comprobamos credenciales
            if(!$validar){
                $validar=!$this->sesion->validar_acceso();
            }
            //Retorno True si tiene acceso
            return $validar;
        }
        #Comprueba que el metodo existe y puede ser llamado
        function validar_metodo(){
            return (method_exists($this->controller,$this->control[1]) && is_callable(array($this->controller,$this->control[1])))?true:false;
        }
        function validar_parametro($filtro){
            $param=$this->control[2];
            $metodo=$this->control[1];
            if(method_exists($filtro,$metodo)){
                return $filtro->$metodo($param);
            }
            return false;
        }
        function generar_metodo(){
            if(isset($this->control[1])){
                $metodo=$this->control[1];
                $this->controller->$metodo();
            }
        }
        function generar_metodo_parametro(){
            if(isset($this->control[2])){
                $metodo=$this->control[1];
                $param=$this->control[2];
                $this->controller->$metodo($param);
            }
        }
    }
?>