<?php namespace MyApp\Core\Systems\Register;

use MyApp\Core\Model;
use MyApp\Core\Helpers\Encriptar;

class register_model extends Model{

    function register_user($data){
        #Verifica si un usuario esta registrado con el mismo nick o correo.
        if(self::repeat_account($data['user'],$data['mail'])) {
            return 1; #El correo o el nombre de usuario ya estan siendo utilizados.
            exit;
        }

        $encript=new Encriptar();
        
        $data['hash']=$encript->my_hash($data['hash']); //Encripto la clave de verificacion 
        $contra=$encript->my_hash($data['pass']); //Encripto la contraseña

        #Registro al usuario
        $val=self::insert('Usuarios', 
            ["User", "Password", "Name", "LastName", "Birthday", "Nationality", "Mail", "HashRegister"],
            [$data['user'], $contra,$data['name'],$data['lastname'],$data['birthday'],$data['nationality'],$data['mail'],$data['hash']]
        );

        #Creo el perfil del usuario
        if($val){
            $id=self::get_id_user($data['user'],$data['mail']);
            $val= self::insert('Perfiles',
                ["IdUsuario","ZonaAcceso"],
                [$id,$data['nationality']]
            );
            if($val)
                return 2; #Se registro todo correctamente
        }
        return 3; #No se pudo crear el perfil de usuario

    }
    #Retorna el Id del usuario
    function get_id_user($user,$mail){
        $data=self::select('Usuarios',['User','Mail'],[$user,$mail],false);
        return $data[0]["Id"];
    }
    #Comprueba si existe una cuenta con el mismo nombre de usuario o email.
    function repeat_account($user, $mail){
        $data=self::select('Usuarios',['User','Mail'],[$user,$mail],false);
        return count($data)>0?true:false;
    }
    function exist_user($user,$mail){
        $res=self::select('Usuarios',['User','Mail'],[$user,$mail],true);
        return count($res)>0;
    }
    #Renueva el codigo
    function update_hash($data){
        if(self::exist_user($data['resend-user'],$data['resend-email'])){
            $encript=new Encriptar();
            $hash=$encript->my_hash($data['hash']);
            return self::update('Usuarios', ['HashRegister'], [$hash], ['User','Mail'], [$data['resend-user'],$data['resend-email']],true);

        }
        return false;
    } 
    #Permite saber cuantos usuarios se han registrado
    function get_count_all_user(){
        $data=self::selectall('Usuarios');
        return count($data);
    }

}   
?>