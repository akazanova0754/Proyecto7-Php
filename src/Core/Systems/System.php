<?php namespace MyApp\Core\Systems;
use MyApp\Core\Helpers\Encriptar;
use MyApp\Core\Systems\Register\Register;
use MyApp\Core\Systems\Login\Login;
use MyApp\Core\Systems\VerifyAccount\VerifyAccount;
    class System{
        private $sesion;
        private $autenticated;
        private $encript;
        public function __construct($sesion){
            $this->sesion=$sesion;
            $this->encript=new Encriptar();
        }
        //Añade el hash 20-25 caracteres para la verificacion de la cuenta
        public function push_hash(&$data){
            $cadena=$this->encript->generated_randon(rand(20,30));
            $data['hash']=$cadena;
        }

        #login
        public function verify_response_login($data){
            $login=new Login($this->sesion,$data);
            
            return $login->get_response();
        }

        #register
        public function verify_response_register($data,$mode=true){
            $register=new Register($this->sesion,$data,$mode);
            return $register->get_response();
        }
        #Authenticated Account
        public function verify_response_autenticated_account($data,$mode=true){
            $verify_account=new VerifyAccount($this->sesion,$data,$mode);
            return $verify_account->get_response();
        }
        
        #admin
        public function Admin($key){

        }

        #language

        #cookies

       

        
        
    }
?>