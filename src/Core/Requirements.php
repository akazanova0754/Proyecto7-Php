<?php namespace MyApp\Core;

use MyApp\config\vars_manejador;


class Requirements{
    use vars_manejador;
    private $fonts=array(    
        "https://fonts.googleapis.com/css?family=Amatic+SC&display=swap",
        "https://fonts.googleapis.com/css?family=Barlow+Condensed&display=swap",
        "https://fonts.googleapis.com/css2?family=Montserrat&display=swap"
    );

    private $dir_css_aux;
    private $dir_script_aux;
    private $requeriments;

    function __construct($theme,$mode,$dir){
        $theme=self::valited_theme($theme);
        $mode=self::valited_mode($mode);
        $n_styles=$this->themes_styles;
        $mode=($mode==1)?"normalize/":"dark/";
        $directory="Theme".$theme."/".$mode.$dir;   
        $this->requirements=self::get_estilos($directory)."\n".self::get_scripts($dir)."\n".self::get_fonts();
       
    }
    function requirements(){
        return $this->requirements;
    }

    function valited_theme($theme){
        for ($i=1; $i <= $this->themes_styles; $i++) { 
            if($i==$theme){
                return $theme;
            }
        }
        return 1;
    }
    function valited_mode($mode){
        if($mode==1 || $mode==0){
            return $mode;
        }
        return 1;

    }
    function get_fonts(){
        $fonts=array_reduce($this->fonts,"self::convertir_array_a_link_fonts");
        return $fonts;
    }
    function get_scripts($directory){//Home
        
        $ruta_general=$this->dir_public."js/General";
        
        $script_generales=self::select_name_archivos_directorios($ruta_general,false,".js");
        $this->dir_script_aux=$ruta_general."/";
        $script_generales=array_reduce($script_generales,"self::convertir_array_a_link_js_html");


        $ruta=$this->dir_public."js/".$directory;
        $mis_scripts=self::select_name_archivos_directorios($ruta,false,".js");
        $this->dir_script_aux=$ruta."/";
        $mis_scripts=array_reduce($mis_scripts,"self::convertir_array_a_link_js_html");

        $mis_scripts=$script_generales."\n".$mis_scripts;
        
        return $mis_scripts;
    }
    
    function get_estilos($directory){//ThemeX/mode/Carpeta

        #Selecciono los archivos css generales
        $ruta_general=$this->dir_public."css/General";
        $this->dir_css_aux=$ruta_general."/";

        $mis_estilos_generales=self::select_name_archivos_directorios($ruta_general,false,".css");

        $mis_estilos_generales=array_reduce($mis_estilos_generales,"self::convertir_array_a_link_css_html");
        
        #Selecciono los archivos css especificos
        
        $ruta_especifica=$this->dir_public."css/".$directory;
        $this->dir_css_aux=$ruta_especifica."/";
        $mis_estilos_espc=self::select_name_archivos_directorios($ruta_especifica,false,".css");
        $mis_estilos_espc=array_reduce($mis_estilos_espc,"self::convertir_array_a_link_css_html");

        #Mis estilos
        $mis_estilos=$mis_estilos_generales."\n".$mis_estilos_espc;
       
        return $mis_estilos;
    }

    #Devuelve un array de los nombres de directorios o archivos que en el primer orden.
    function select_name_archivos_directorios($dir,$mode=true,$type_file=null){

        $ruta=[];
        // Abre un directorio conocido, y procede a leer el contenido

        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {

                    if($mode){

                        #Modo1: Encuentra solo directorios
                        $condicion=strpos((String)$file,".")==false && $file!="." && $file!="..";

                    }else{
                        #Modo2 Encuentra solo archivos css
                        $condicion=strpos($file,$type_file)!=false;
                    }

                    if($condicion){
                        array_push($ruta,$file);
                    }
                }
                closedir($dh);
            }
        }
        return $ruta;
    }


    #Convierte los items de un array a una cadena de texto css-html
    protected function convertir_array_a_link_css_html($hilo,$item){
        $hilo.='<link rel="stylesheet" href="'.URL_COMPLETA.'/'.$this->dir_css_aux.$item.'">'."\n";
        return $hilo;
    }

    #Convierte los items de un array a una cadena de texto js-html
    protected function convertir_array_a_link_js_html($hilo,$item){
        $hilo.='<script src="'.URL_COMPLETA.'/'.$this->dir_script_aux.$item.'"></script>'."\n";
        return $hilo;
    }
    public function convertir_array_a_link_fonts($hilo,$item){
        $hilo.='<link href="'.$item.'" rel="stylesheet">';
        return $hilo;
    }
    #Convierte los items de un array a una cadena de texto
    protected function convertir_array_a_texto($hilo,$item){
        $hilo.=$item."\n";
        return $hilo;
    }
    
    

    #Devuelve un array con la ruta de los archivos de x tipo encontrados dentro de los (subdirectorios de primer orden) del directorio padre especificado en el parametro
    protected function select_ruta_subdir_archivos($dir,$type_file){

        $subdir=[];
            $ruta_mis_archivos=[];
            $subdir=self::select_name_archivos_directorios($dir); //Todos los subdirectorios

            for ($i=0; $i < count($subdir) ; $i++) { 

                $ruta_subdir=$dir.$subdir[$i]."/";
                $comprimido_subdirectorio=self::select_name_archivos_directorios($ruta_subdir,false,$type_file); #Obtengo los archivos(css*) de los subdirectorios dentro de arrays
               
                if(!empty($comprimido_subdirectorio)){
                    $archivos_subdirectorio=[];
                    for($j=0;$j<count($comprimido_subdirectorio);$j++){
                        $archivo=$comprimido_subdirectorio[$j];
                        array_push($archivos_subdirectorio,$subdir[$i]."/".$archivo); #Agrego cada ruta de los archivos en un solo array   subdirectorio/archivo
                    }
                   
                    $ruta_mis_archivos=array_merge($ruta_mis_archivos,$archivos_subdirectorio);
                }
            }
        return $ruta_mis_archivos;
    }

    
}
?>