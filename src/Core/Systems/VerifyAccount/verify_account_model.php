<?php namespace MyApp\Core\Systems\VerifyAccount;

use MyApp\Core\Model;
use MyApp\Core\Helpers\Encriptar;

class verify_account_model extends Model{
    #Proceso de verificacion de la cuenta
    public function process_verify_account($data){
        //Comprueba si el usuario es apto para la validacion de la cuenta
        $numero_intentos=0;
        $hash="";
        
        if(!self::is_repeat_validated($data['user'],$numero_intentos,$hash)){
            //Verifico la cuenta
            $hash=trim($hash);
            return self::verify_account($data['user'],$data['key'],$hash,2);
        }else{
            return false;
        }
    }

    #Verifica la cuenta
    protected function verify_account($user,$clave,$hash,$numero_intentos){
        $encript=new Encriptar();
        if($encript->verify_my_hash($clave,$hash)){
            self::increment_attemps_user($user,$numero_intentos); //Incremento en +1 los intentos de verificacion por parte del usuario
            self::update('Usuarios',['VerifyAccount'],[true],['User'],[$user],true);
            return true;
        }
        
        return false;
    }



    #Aumenta en +1 el numero de intentos
    public function increment_attemps_user($user,$numero_intentos){
        $numero_intentos++;
        self::update('Usuarios',['Attempts'],[$numero_intentos],['User'],[$user],true);
    }

    #True si el usuario ya posee una cuenta verificada, alcanzo su maximo numero de intentos o no existe en Base de Datos
    # y Entrega el numero de intentos de verificacion hechos por el usuario
    protected function is_repeat_validated($user,&$numero_intentos,&$hash){
        $val=self::select('Usuarios',['User'],[$user],true);
        if($val==null){
            return true;
        }else{
            if(!$val[0]['VerifyAccount'] && $val[0]['Attempts']<=3){
                $numero_intentos=$val[0]['Attempts'];
                $hash=$val[0]['HashRegister'];
                return false;
            }
            return true;
        }
    }

    public function desapproved_to_send_the_email($user,$mail,&$numero_intentos){
        $val=self::select('Usuarios',['User','Mail'],[$user,$mail],true);
        if($val==null){
            return true;
        }else{
            if(!$val[0]['VerifyAccount'] && $val[0]['Attempts']<3){
                $numero_intentos=$val[0]['Attempts'];
                return false;
            }
            return true;
        }
    }
}
?>