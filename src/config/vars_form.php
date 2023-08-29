<?php namespace MyApp\config;
    trait vars_form{
        //Se utiliza como name predeterminada de un formulario
        private $name_form="myform";
        //Se utilizan para acceder por un corto periodo de tiempo a formularios/avisos.
        private $name_keylogin="coin";
        private $name_keyregister="key";
        private $cookie_verify_account="verify";

        //key captcha google V2
        private $site_key2="6LeAIpMaAAAAAK4u5ihpKAV9habLDMFrjsvlORNx";
        private $secret_key2="6LeAIpMaAAAAAG1Cvr7Xs-uuKbBR8v6NOqkvb4wZ";
        private $nametoken2="g-recaptcha-response";

        //key captcha google V3
        private $site_key3="6LfgoYoaAAAAADq7B7CT1gktzbYuzr1o84dWDpVX";
        private $secret_key3="6LfgoYoaAAAAAC5o5RsdEpmb5J0KCws5-GsYEAO3";
        private $nametoken3="google-response-token";
    }
?>