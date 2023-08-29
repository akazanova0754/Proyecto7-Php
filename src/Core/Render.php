<?php namespace MyApp\Core;
class Render{
    private $language;
    private $requirements;
    function __construct($archivo,$requirements){
        $this->language=$archivo;
        $this->requirements=$requirements;
    }
    function renderizar($archivo,$data="No puedes acceder a los datos internos :)",$sub_archivo=null,$title=""){
        require($this->language);
        return require_once($archivo);
    }
    
}
?>
