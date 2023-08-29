<?php namespace MyApp\config;
    trait vars_session{
        private $url_redirecion=""; // Ruta despues de redireccionar
        //Para aceptar el uso de cookies
        private $duration_cookie_agree = 7776000; # 3 Meses
        private $cookie_agree="cookie_policy";
        
        //Para iniciar la sesion
        private $duration_cookie_sesion=7200;#2hrs

        private $name_sesion="user";
        private $name_maincookie="sesion";

        //Para verificar la cuenta
        private $duration_sms_space_verify=300; #5min
        private $duration_cookie_verify=3600; #1hr
        
        private $name_msm_cookieverify="msmautenticated";
        private $name_verifycookie_account="autenticated";

        //Para la cookie de cambio de idioma
        private $duration_cookie_language=2592000; # 30 Dias

        private $name_languagecookie="language";

        //Para la cokkie de temas y modos
        private $name_sesion_theme="tema";
        private $name_cookie_theme="tema";
        private $duration_cookie_theme=2592000; # 30 Dias
        private $name_cookie_mode="modo";
        private $duration_cookie_mode=86400; # 1 Dia
        
    }
?>