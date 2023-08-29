<?php namespace MyApp\Core;
    class Filtro{
        #Filtros para los metodos de las clases
        
        // Solo se aceptan numeros
        function calculadora($param){
            return ($param!=null && is_numeric($param))?true:false;
        }
        function redactar($param){
            return strlen($param)<5;
        }
        function activar_account($param){
            # Hash puede ser de 20 a 30 caracteres y el serial de 10 caracteres
            # Se suman 12 caracteres adicionales para los identificadores + 1 (-) caracter
           
            $val=false;
            if((42 <= strlen($param)) && (strlen($param)<=53)){
                //Cuenta el numero de apariciones de los substring dentro de la cadena
                if(substr_count($param,'vkey')==1 && substr_count($param,'serial')==1){
                    $val=true;
                }
            }
            return  $val;
        }

        #Aqui van las funciones para aplicar un filtro personalizado a una clase o metodo
        // function cara(){
        //     return true;
        // }
    }
?>