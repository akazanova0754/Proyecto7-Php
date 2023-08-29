<?php namespace MyApp;
    use MyApp\Core\Session;
    use MyApp\Core\Ruta;
    use MyApp\Core\Filtro;
    use MyApp\Core\Controller;
    use MyApp\Core\View;
    use MyApp\Core\Manejador;

    class App{
        private $url;
        private $controller;

        function __construct(){
            new Manejador();
        }


        public function prepare_app(){
   
            $this->url=new Ruta(isset($_GET['url'])?$_GET['url']:null);
            $this->controller=new Controller($this->url->get_ruta(),$this->url->get_main_ruta(),new Session());
            define('URL_COMPLETA',$this->url->get_url_completa());//SOLO PARA ENTORNO DE DESARROLLO
            
        }

        public function cookie_policy($mode=false){
            if($mode){
                $this->controller->cookie_agree();//Aviso de Politica de cookies.
            }
        }

        public function run(){
            
            if($this->url->validar_ruta() && $this->controller->validar_control()){ //Se comprueba si existe un controlador
                $this->url->refinar_url(1);//La url no permite exceso caracteres '/' a nivel 1
                if($this->controller->require_user()){ // Se comprueba si se necesita de credenciales
                    //Se genera el modelo
                    $this->controller->generar_model();
                    //Se genera el controlador y la vista asociada a el
                    $this->controller->generar_controller();

                    if($this->url->ruta_metodo()){//Se comprueba si existe un metodo para la clase en la url
                       
                        if($this->controller->validar_metodo()){ //Se comprueba si el metodo esta creado
                            
                            $this->url->refinar_url(2); //La url no permite exceso caracteres '/' a nivel 2
                            
                            if($this->url->ruta_param()){ //Se comprueba si existe un parametro para el metodo en la url
                                
                                if($this->controller->validar_parametro(new Filtro())){ //Se comprueba la validez del parametro por medio de un filtro
                                    
                                    $this->controller->generar_metodo_parametro(); //Se genera el metodo con el parametro

                                    $this->url->refinar_url(3); //La url no permite exceso caracteres '/' a nivel 3
                          
                                }else{
                                    $this->url->errores3();
                                }
                            }else{
                                
                                $this->controller->generar_metodo(); //Se genera el metodo
                            }

                        }else{
                            
                            $this->url->errores(); //Podria ir errores2
                        }
                    }

                }else{
                    if($this->url->ruta_metodo()){
                        $this->url->errores();
                    }else{
                        if($this->controller->error_privacity_login_register()){
                            $this->url->errores();
                        }else{
                            $this->url->denegar();
                        }
                       
                    }
                }
            }else{
                
                $this->url->errores();
            }
        }
       
    }
?>