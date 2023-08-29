<?php namespace MyApp\Core;

use MyApp\config\vars_manejador;

class Manejador{
    use vars_manejador;

    function __construct(){
        for ($i=0; $i <count($this->controllers) ; $i++) { 
            #Primero: Generar los controllers
            $conten_controller=self::get_content_controller($this->controllers[$i][0]);
            self::generated_file($this->dir_controller.$this->controllers[$i][0],".php",$conten_controller);

            #Segundo: Generar los modelos
            if($this->controllers[$i][1]){
                $conten_model=self::get_content_model($this->controllers[$i][0]);
                self::generated_dir($this->dir_model.$this->controllers[$i][0]);
                self::generated_file($this->dir_model.$this->controllers[$i][0]."/".$this->controllers[$i][0]."_model",".php",$conten_model);

            }

            #Tercero para las views(van en subcarpeta)
            self::generated_dir($this->dir_view.$this->controllers[$i][0]); //Genero la carpeta
            self::generated_file($this->dir_view.$this->controllers[$i][0]."/index",".php"," "); //Creo un index

            #Cuarto los scripts(van en subcarpeta)
            self::generated_dir($this->dir_public."js/".$this->controllers[$i][0]);
        }
            self::generated_dir($this->dir_public."js/Login");
            self::generated_dir($this->dir_public."js/Register");
        #Quinto los estilos(van en subcarpeta)
        for ($j=1; $j <= $this->themes_styles; $j++) { 
            
            $dir=$this->dir_public."css/Theme".$j;
            self::generated_dir($dir); #Genero el directorio de los temas

            $dir_nor=$dir."/normalize";
            $dir_dark=$dir."/dark";

            self::generated_dir($dir_nor);
            self::generated_dir($dir_dark);

            self::generated_dir($dir_nor."/Login");
            self::generated_dir($dir_nor."/Register");
            self::generated_dir($dir_dark."/Login");
            self::generated_dir($dir_dark."/Register");

            for ($i=0; $i <count($this->controllers) ; $i++) { 
                $sub_dir1=$dir_nor."/".$this->controllers[$i][0];
                $sub_dir2=$dir_dark."/".$this->controllers[$i][0];
                self::generated_dir($sub_dir1); #Genero los subdirectorios de los temas
                self::generated_dir($sub_dir2);
            }
            
        }
        
    }

    #Permite crear los archivos
    function generated_file($directory_file,$type_file,$contenido){
        $directory_file=$directory_file.$type_file;

        $val=file_exists($directory_file);
        
        if(!$val){
            $miarch=fopen($directory_file,"w+");
            fwrite($miarch,$contenido) or die("No se pudo escribir");
            fclose($miarch);
        }
        
    }
    #Permite crear directorios
    function generated_dir($directory_dir){
     
        $val=file_exists($directory_dir);
        
        if(!$val){
            mkdir($directory_dir);
        }
    
    }

    #Retorna el contenido para el controlador
    function get_content_controller($name_controller){
        return $contenido='<?php 
    class '.$name_controller.' { 
        private $view; 
        private $sesion; 
        private $model; 

        function __construct($view,$model,$sesion){
            $this->view=$view;
            $this->model=$model;
            $this->sesion=$sesion;
        }
    }
?>';
    }

    #Retorna el contenido para el modelo
    function get_content_model($name_model){
        return $contenido='<?php 
use MyApp\Core\Model;
use MyApp\Core\Helpers\Encriptar;

    class '.$name_model.'_model extends Model { 
        
    }
?>';
    }


}
?>