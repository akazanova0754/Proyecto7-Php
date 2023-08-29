<?php namespace MyApp\Core\Systems\VerifyAccount;

use MyApp\Core\Helpers\Correo;
use MyApp\Core\Helpers\Encriptar;
use MyApp\Core\Systems\VerifyAccount\verify_account_model;

class VerifyAccount{
    private $sesion;
    private $response;

    private $url_msm=URL_COMPLETA.'/Register/activar_account';
    private $url_verify=URL_COMPLETA.'/Register/activar_account/';

    private $directory_template_correo='Views/Components/Mails/Register/index.php';

    public function __construct($sesion, $data=null, $mode=true){

        $this->encript =new Encriptar();
        $this->sesion=$sesion;
        
        
        #Primer Modo: Permite enviar un email y un mensaje-respuesta al usuario registrado. 
        if($mode){
            $n_intentos=0;
            $model=new verify_account_model();
            
            if(!$model->desapproved_to_send_the_email($data['user'],$data['mail'],$n_intentos)){
                $msm_key=$this->encript->generated_randon(rand(15,20)); #Genero un cadena de 15-20 caracteres
                $this->sesion->create_cookie_space_sms_verify_account($msm_key."-".$data['user']);#Crea una cookkie para el espacio en donde se mostrara un mensaje en respuesta a la creacion de la cuenta
                
                #Key para el link del verify account (10caracteres)
                $key=$this->encript->generated_randon(4).'$'.$this->encript->generated_randon(5);
                $content=self::contenido_mail_verify_account($data['hash'],$key); # Carga el contenido del email
                $this->response=self::enviar_mail($content,$data); # Envia el email al usuario
                if($this->response){
                    $model->increment_attemps_user($data['user'],$n_intentos);
                }

            }else {
                $this->response=false;
            }

        #Sengundo Modo: Permite verificar el enlace de activacion de la cuenta del usuario.
        }else{
            $key=$data;
            $model=new verify_account_model();
            return $this->response=$model->process_verify_account($data);
        }
        
    }
    public function get_response(){
        return $this->response;
    }
    private function enviar_mail($content,$data){
        $correo=new Correo();
        $correo->prepare_mail('rhyoarias@gmail.com',
                            $data['mail'],          // Correo del usuario
                            'rhyoarias@gmail.com',  // Correo del admin
                            'odxlrodfyeyjbdrq',     // Contraseña del correo del admin
                            'Cybergames',           // Nombre de la App
                            $data['name'],          // Nombre del usuario
                            'Correo de Verificacion Game Zone', //Subject del correo
                            $content,               // Contenido del correo
                            'smtp.gmail.com');      // Protocolo smtp del correo
        $val=$correo->send_email();
        unset($correo);
        return $val;
    }
    private function contenido_mail_verify_account($cadena,$key){
        $html=file_get_contents($this->directory_template_correo);

        $html=str_replace('mynamesapp','Game Zone',$html); //Nombre de Aplicacion
        $html=str_replace('miurlimagen',URL_COMPLETA.'/Views/public/img/General/ds.jpg',$html); //Nombre de la imagen
        

        $html=str_replace('milink',
            $this->url_verify.
            'vkey='.$cadena.'-'.// Hash de la verificacion (5+|20 o 30|).
            'serial='.$key // Key de la verificacion (17 caracteres).
            ,$html); 


        return $html;
    }
}
?>