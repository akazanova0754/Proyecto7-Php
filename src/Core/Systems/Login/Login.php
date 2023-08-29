<?php namespace MyApp\Core\Systems\Login;

use MyApp\Core\Helpers\Correo;
use MyApp\Core\Helpers\Encriptar;
use MyApp\Core\Systems\Login\login_model;

class Login{
    private $model;
    private $response;

    public function __construct($sesion,$data){
        #Codigo para modificar
        $this->model=new login_model(); #Creo una instancia del modelo.
        $this->response=$this->model->valited_user($data); #Valido las credenciales del usuario

        if ($this->response==1) $sesion->iniciar_sesion($data['user'],$data['user']); #Creo una sesion.
    }

    //Funcion que devuelve true o false en respuesta a la validacion de credenciales
    public function get_response(){
        return $this->response;
    }
}
?>