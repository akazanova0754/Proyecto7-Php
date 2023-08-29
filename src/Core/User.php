<?php namespace MyApp\Core;
abstract class User{
    
    abstract protected function iniciar_sesion():?bool;
    abstract protected function cerrar_sesion():?bool;    
    
    // private $sesion;

    // public function __construct($sesion){
    //     $this->sesion=$sesion;
    // }
    // public function get_sesion(){
    //     return $this->sesion;
    // }
    // public function acceder($cookie){
    //     return $this->sesion->validar_acceso($cookie);
    // }
    // public function autenticar(){
    //     return $this->sesion->validar();
    // }
}
?>