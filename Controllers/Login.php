<?php
use MyApp\Core\Helpers\Form;
use MyApp\Core\Systems\System;
class Login{
    // private $privacity; #Permite que solo se acceda con una credencial
    private $view; #Objeto View
    private $user;
    private $sesion;

    private $system;
    private $form;
    
    //Para llamar a una vista utiliza los metodos generar_vista_main y generar_view
    function __construct($view,$model,$sesion){
        $this->view=$view;
        $this->model=$model;
        $this->sesion=$sesion;
        
        $this->system=new System($sesion);
        $this->form=new Form();
        self::work_space();
    }
     
    private function work_space(){
        $key=null;
        $method=$this->view->get_method();
        if($this->view->get_space()){
            $key=$this->form->get_key_form();
            $this->view->run_login($this->form->get_name_form(), $key);     
        }
        $this->form->generate_key_formlogin($method,$key,null);       
            
    }
    function otra(){
        print "otra";
    }
    function validar(){
       
        if($this->form->confirm_form_login()){
            if($this->form->valited_google_form_v3()){

               #Inicio el proceso de verificacion de credenciales
               $data=[];
               $data=$this->form->limpiar_data(['user','pass']);
               
                $val=$this->system->verify_response_login($data);
                switch($val){
                    #No se encuentra registrado
                    case 0:
                        // print "La contraseña o el usuario son incorrectos.";
                        $this->view->failed("Login/",$data['user']);
                        header("refresh:8;".URL_COMPLETA."/Login");
                        break;
                    #Todo salio correctamente
                    case 1:
                        // print "Bienvenido ".$data['user'];
                        $this->view->success("Login/",$data['user']);
                        break;
                    #La cuenta no esta verificada
                    case 2:
                        $key=$this->form->get_key_form();
                        $data['key']=$key;
                        $data['name-form']="resend-form";
                        $this->form->generate_key_form('cript',$key);
                        $this->view->template_resend_email("Login/",$data);
                        break;
                    #La cuenta esta baneada/suspendida
                    case 3:
                        print "Su cuenta esta suspendida. No puede iniciar sesion.";
                        header("refresh:8;".URL_COMPLETA."/Login");
                        break;
                    #El limite de intentos de verificacion ha sido superado
                    case 4:
                        print "Su cuenta no esta verificada. Y ha superado el limite de intentos para verificarla.";
                        header("refresh:8;".URL_COMPLETA."/Login");
                        break;
                }
                
            }else{
                $this->view->error_generico();
            }
        }else{
            $this->view->error_generico();
        }

    }
   
    function resend_email(){
        
        if($this->form->confirm_form_key('cript','cript','resend-form')){

            if($this->form->valited_google_form_v3()){

                $data=$this->form->limpiar_data(['resend-user','resend-email']); 
                $this->system->push_hash($data);#Añado el nuevo codigo a la matriz
            
                if($this->system->verify_response_register($data,false)){ # Actualiza el hash para la verificacion de la cuenta de usuario
                    
                    $data['mail']=$data['resend-email'];
                    $data['name']=$data['resend-user'];
                    $data['user']=$data['resend-user'];
                    
                    if($this->system->verify_response_autenticated_account($data)){ # Permite enviar un correo al usuario y obtener un mensaje-respuesta.
                        
                        $this->view->resend_email("Login/",$data['user']); # Redireccion al mensaje-respuesta(success). 
                        $this->sesion->create_cookie_verify_account($data['user']); # Crea una cokkie para el espacio en donde se verificara la cuenta del usuario
                        unset($data);
                        header("refresh:10;".URL_COMPLETA."/Register/activar_account");
                    
                    }else{

                        #Hubo un error en el envio del correo.
                        $this->view->failed_email("Register/",$data['user']); # Mensaje de Error
                        unset($data);
                    
                    }

                }else{

                    print "No se encontro el correo: ".$data['resend-email']." asociado a la cuenta ".$data['resend-user'];
                }

            }else{

                $this->view->error_generico();
            }

        }else{

            $this->view->error_generico();
        }
    }
}
?>