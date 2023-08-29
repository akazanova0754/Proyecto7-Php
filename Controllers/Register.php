<?php
    use MyApp\Core\Helpers\Form;
    use MyApp\Core\Systems\System;
    
    class Register{
        //Construccion del Login
        private $view; 
        private $sesion; 
        private $model; 

        private $system;
        private $form;
    
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
                $this->view->run_register($this->form->get_name_form(),$key);     
            }
            $this->form->generate_key_formregister($method,$key,null);            
        }   
        
        function validacion(){
            $val=$this->form->confirm_form_register();
            var_dump($val);
            if($val){

                if($this->form->valited_google_form_v2()){
                    //Coloco los names del form para obtener su valor en un arreglo
                    $data=$this->form->limpiar_data(['user','pass','pass2','name','lastname','birthday','nationality','mail']); 
                    
                    $this->system->push_hash($data);#Añado el codigo a la matriz
                    $val=$this->system->verify_response_register($data);# Registra los datos en la DataBase
                    var_dump($val);
                    
                    switch($val){

                        #Cuando se ha llegado al limite de usuarios para registrar
                        case 0:
                            $this->view->failed("Register/"); # Redireccion al mensaje-respuesta(failed) de falla de registro.
                            unset($data);
                            header("refresh:5;".URL_COMPLETA."/Register");
                            break;

                        #Cuando El correo o el nombre de usuario ya estan siendo utilizados
                        case 1:
                            print "Su nombre de usuario o correo ya estan en uso.";
                            header("refresh:5;".URL_COMPLETA."/Register");
                            break;
                        
                        #Cuando se ha registrado al usuario correctamente
                        case 2:
                                if($this->system->verify_response_autenticated_account($data)){ # Permite enviar un correo al usuario y obtener un mensaje-respuesta.

                                    $this->view->success("Register/",$data['user']); # Redireccion al mensaje-respuesta(success). 
                                    $this->sesion->create_cookie_verify_account($data['user']); # Crea una cokkie para el espacio en donde se verificara la cuenta del usuario
                                    
                                    unset($data);
                                    header("refresh:10;".URL_COMPLETA."/Register/activar_account");
                                }else{
                                    #Hubo un error en el envio del correo.
                                    $this->view->failed_email("Register/",$data['user']); # Mensaje de Error
                                    unset($data);
                                
                                }
                            break;

                        #Cuando no se pudo crear el perfil de usuario
                        case 3:
                            print "Hubo complicacion no se pudo crear su perfil.";
                            break;

                    }

                }else{
                    $this->view->error_generico();
                }
            }else{
                $this->view->error_generico();
            }
        }

        #Aqui se muestra el msm de respuesta del email y tambienn la seccion para la verificacion de la cuenta.
        function activar_account($param=null){
            # Modo 1: Se muestra el sms de respuesta del email
            if($param==null && $this->sesion->validar_space_msm_cookie_verify()){

                #El contenido de la cookie debe ser una cadena de:
                /*
                    Nick no mayor a 20 caracteres ni menor a 1.
                    Un caracteres identificador.
                    Una cadena ramdon de 15-20 caracteres.
                */

                if(17<=strlen($_COOKIE['msmautenticated']) && strlen($_COOKIE['msmautenticated'])<=41){
                    $user=$_COOKIE['msmautenticated'];
                    $user=explode('-',$user);
                    if(count($user)>0 && count($user)<3){
                        $this->view->success_email("Register/",$user[1]); # Mensaje Exitoso del correo enviado
                    }else{
                        $this->view->error_generico2();
                    }
                }else{
                    $this->view->error_generico2();
                }
            
            # Modo 2: Aqui se valida la cuenta del usuario
        
            }elseif($param!=null &&  $this->sesion->validar_cookie_verify_account()){
               
                #Code
                $url=$this->view->get_param();//Pido el parametro de la url
                $url=explode('-',$url);
                if(count($url)==2){
                    $vkey=str_replace("vkey=","",$url[0]);//20-30 caracteres
                    $serial=str_replace("serial=","",$url[1]);
                    $serial=explode('$',$serial);

                    if(count($serial)==2){
                        if(strlen($serial[0])==4 && strlen($serial[1])==5){
                            $serial1=$serial[0];//4caracteres
                            $serial2=$serial[1];//5caracteres
                            $data=[];
                            $data['user']=$this->sesion->get_cookie_verify_account();
                            $data['key']=$vkey;
                        
                            if($this->system->verify_response_autenticated_account($data,false)){ //Autentica la cuenta del usuario
                                $this->view->success_verify("Register/",$data['user']);
                                $this->sesion->close_cookie_verify_account();
                                unset($data);//Elimina la variable con que almacena el hash y el usuario
                                header("refresh:5;".URL_COMPLETA."/Login");//Redirige hacia el login
                            }else{
                                unset($data);//Elimina la variable con que almacena el hash y el usuario
                                $this->view->error_generico2();
                            }

                        }else {
                            $this->view->error_generico2();
                        }
            
                    }else{   
                        $this->view->error_generico2();
                    }
                }else {
                    $this->view->error_generico2();
                }
                
            
            # Modo 3: Aqui se muestra un error si no hay cookies u code en la url

            }else{
                $this->view->error_generico2();
                
            }
        }


        //Valida los datos a nivel de servidor
        private function valited_data($data){
            
        }
    }

?>