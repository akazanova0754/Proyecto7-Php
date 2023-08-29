<?php 
    class Project { 
        private $view; 
        private $sesion; 
        private $model; 

        function __construct($view,$model,$sesion){
            $this->view=$view;
            $this->model=$model;
            $this->sesion=$sesion;
            $this->view->generar_vista_main($data=[]);
        }
    }
?>