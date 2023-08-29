<?php namespace MyApp\Core\DataBase;
use PDO;
require_once "src/config/config_data_base.php";

trait DataBase {
    protected function connect()
    {
        try{
            $dsn='mysql:host='.HOST.'; dbname='.DB_NOMBRE;
            $conexion= new PDO($dsn,DB_USUARIO , DB_CONTRA);
            $conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $conexion->exec(CHARACTER);
            return $conexion;
        }catch(PDOExeption $e){
            $conexion=null;
            return null;
        }
    }
}
?>