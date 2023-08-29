<?php namespace MyApp\Core\Helpers;

class Encriptar{

    function __construct(){
      
    }
    public function generated_randon($longitud){
        $key = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
        $max = strlen($pattern)-1;
        // for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
        for($i=0;$i < $longitud;$i++) $key .= substr($pattern,mt_rand(0,$max),1);
        
        return $key;
    }
    #Devuelve una cadena de 60 de largo
    public function my_hash($clave){
        $hash = password_hash($clave, PASSWORD_DEFAULT, [15]);
        return $hash;
    }
    public function verify_my_hash($clave,$hash){
        return password_verify($clave,$hash);
    }
   
}   
?>