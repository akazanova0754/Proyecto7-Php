<?php namespace MyApp\Core\Systems\Register;

use MyApp\Core\Systems\Register\register_model;

class Register{
    private $model;
    private $response;
    private $sesion;
    
    private $limite_usuarios;

    public function __construct($sesion,$data,$mode){
        
        $this->sesion=$sesion;
        $this->limite_usuarios=1000;

        #Primer Modo: Permite registrar a un nuevo usuario
        if($mode){
        
            self::space_work($data);
        
        }
        #Segundo modo: Permite actualizar una nueva hash para la verificacion de la cuenta de un usuario
        else {
            self::new_hash($data);
        }
        
    }
    //Funcion que devuelve true o false en respuesta al registro
    public function get_response(){
        return $this->response;
    }
    private function space_work($data){
        #Codigo para modificar
        $this->model=new register_model(); #Creo una instancia del modelo.
        if($this->model->get_count_all_user()<=$this->limite_usuarios){ #Permite saber si se alcanzo el limite de usuarios registrados

            $this->response=$this->model->register_user($data); #Llama a la funcion del modelo y registro los datos.
        
        }else{
            $this->response=0;
        }
    }
    private function new_hash($data){
        $this->model=new register_model();
        $this->response=$this->model->update_hash($data);
    }
}
?>