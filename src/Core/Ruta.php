<?php namespace MyApp\Core;
    use MyApp\Core\Error\Errores;
    // require_once "Core/Core/Error/Error.php";
    class Ruta{
        private $my_url=null;
        private $error;
        private $main_ruta="";//dejar en blanco si no se quiere una ruta principal
        private $niveles_navegacion=3;//Vista/metodo/parametro(3) || Vista/metodo(2) || Vista/parametro(1)
        private $url_relativa;
        private $url_absoluta;//La ruta real que esta en el navegador
        private $domain_web_development="web-production-f760.up.railway.app"; #El dominio de la web de desarrollo( En este caso el subdirectorio)

        function __construct($ruta){
            
            $this->my_url=empty($ruta)?"":$ruta;
            $this->error=new Errores();
            $this->my_url=rtrim($this->my_url,"/");
            $this->my_url=explode("/",$this->my_url);
            $this->url_relativa=rtrim($ruta,"/");
            $this->url_absoluta=str_replace($this->domain_web_development."/","",$_SERVER['REQUEST_URI']);// $this->url_absoluta=ltrim($_SERVER['REQUEST_URI'],"`/IHC-LAB/`");
            $this->url_absoluta=(!self::local())?substr($this->url_absoluta,1):$this->url_absoluta;
            if(($this->my_url[0]==""||$this->my_url[0]==$this->main_ruta||$this->my_url[0]=="index") && count($this->my_url)<2){
                //""
                ($this->url_absoluta!="" && trim($this->url_absoluta,"/")=="")?header("Location:".constant('URL').""):"";
                //"index"
                ($this->url_absoluta!=$this->url_relativa && trim($this->url_absoluta,"/")=="index")?header("Location:".constant('URL')."index"):"";
                //"ruta_principal"
                ($this->url_absoluta!=$this->url_relativa && (trim($this->url_absoluta,"/")==$this->main_ruta))?header("Location:".constant('URL').$this->main_ruta):"";
            }
        }
        //Trabajar con herokuuuuu <3
        private function local(){
            return ($_SERVER['HTTP_HOST']=="localhost")?true:false; 
        }
        
        function get_url_completa(){
            return "http://".$_SERVER['HTTP_HOST'].
                    (self::local()?
                    $this->domain_web_development:
                        "");
        }
        function get_url_actual(){
            return "http://".$_SERVER['HTTP_HOST'].
                    (self::local()?
                    $this->domain_web_development:
                        (($this->url_absoluta!="")?
                            "/":
                            "")).
                        $this->url_absoluta;
                    ;
        }
        //Metodos utiles
        function ruta_metodo(){
            return isset($this->my_url[1]);
        }
        function ruta_param(){
            return isset($this->my_url[2]);
        }
        function get_main_ruta(){
            return $this->main_ruta;
        }
        function get_ruta(){
            return $this->my_url;
        }
        function validar_ruta(){
            return (count($this->my_url)<=$this->niveles_navegacion)?true:false;
        }
        function refinar_url($nivel){
            $r=(!self::local())?URL:URLLOCAL;
            (count($this->my_url)<=($nivel))?($this->url_absoluta==$this->url_relativa)?"":header("Location:".$r.$this->url_relativa):"";
        }
        function denegar(){ //Muestra pantalla de no permitido
            if($this->error!=null){
                $this->error->no_permission();
            }
        }
        function error(){
            if($this->error!=null){
                file_exists($this->error->generar())?require_once($this->error->generar()):null;
            }
        }
        function errores(){
            if($this->error!=null){
                $this->error->generar();
            }
        }
        function errores2(){
            if($this->error!=null){
                $this->error->no_resource();
            }
        }
        function errores3(){
            if($this->error!=null){
                $this->error->no_found();
            }
        }
      
    }
?>