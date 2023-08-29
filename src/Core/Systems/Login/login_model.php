<?php namespace MyApp\Core\Systems\Login;

use MyApp\Core\Model;
use MyApp\Core\Helpers\Encriptar;

class login_model extends Model{

    //Falta actualizar datos del perfil al iniciar sesion (listo)
    //Verificar la contraseña encriptada                    (listo)
    //Falta actualizar el estado(1) en el perfil del Usuario (listo)

    #Valido las credenciales del usuario
    function valited_user($data){
        $response=self::select('Usuarios',["User"],[$data['user']],true);
        
        if($response!=null){

            if(count($response)>0){ //Se comprueba si existe un usuario con esas credenciales

                $hash=$response[0]['Password'];
                $encript=new Encriptar();

                if($encript->verify_my_hash($data['pass'],$hash)){ # Verificar la contraseña encriptada

                    if($response[0]['VerifyAccount']==true){ #Verificar si la cuenta esta verificada
                    
                        $id=$response[0]['Id'];
                        $response=self::select('Perfiles',["IdUsuario"],[$id],true);

                        if($response!=null){ #Extraer el status del usuario

                            if($response[0]['IdStatus']==1){ #Verificar si el usuario esta baneado

                                #Actualizar la hora y zona de acceso del usuario
                                self::update('Perfiles', ['AccessDate','IdEstado'], ['CURRENT_TIMESTAMP',1], ['IdUsuario'], [$id], true);
                                
                                return 1;
                            }else{
                                return 3;
                            }    
                        }

                    }else{
                        if($response[0]['Attempts']>=3){ #Verificar si se ha superado el maximo numero de intentos para verificar
                            return 4;
                        }else{
                            return 2;
                        }   
                    
                    }
                }
                
            }
        }
        


        return 0;
    }
  
}   
?>