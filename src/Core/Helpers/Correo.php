<?php namespace MyApp\Core\Helpers;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "src/Core/ExternalLibs/PHPMailer/Exception.php";
require_once "src/Core/ExternalLibs/PHPMailer/PHPMailer.php";
require_once "src/Core/ExternalLibs/PHPMailer/SMTP.php";
    
    class Correo{
        private $php_mail;
        private $server_mail;
        private $client_mail;
        private $reply;
        private $user;
        private $password;
        private $name_server;
        private $name_client;
        private $subject;
        private $content;
        private $host;

        function __construct(){
        }

        #Carga los datos para enviar
        public function prepare_mail($server_mail, $client_mail, $reply, $password,$name_server,$name_client, $subject, $content, $host){
            $this->php_mail= new PHPMailer(true);
            $this->server_mail= $server_mail;
            $this->client_mail= $client_mail;
            $this->reply= $reply;
            $this->password= $password;
            $this->name_server= $name_server;
            $this->name_client= $name_client;
            $this->subject= $subject;
            $this->content=$content;
            $this->host=$host;
        }
        #Envia el email
        public function send_email($port=587,$local=true){
            return self::run_mail($port,$local);
        }
        #Retorna true si el correo se envio con exito o false si hubo algun error
        private function  run_mail($port,$localhost){
            try {
                //Server settings
                $this->php_mail->SMTPDebug = SMTP::DEBUG_OFF;                             // Enable verbose debug output
                $this->php_mail->isSMTP();                                                // Send using SMTP
                $this->php_mail->Host       = $this->host;                                // Set the SMTP server to send through
                $this->php_mail->SMTPAuth   = true;                                       // Enable SMTP authentication
                $this->php_mail->Username   = $this->server_mail;                         // SMTP username
                $this->php_mail->Password   = $this->password;                            // SMTP password
                $this->php_mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;             // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $this->php_mail->Port       = $port;                                      // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                if($localhost){
                    $this->php_mail->SMTPOptions = array(
                        'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                        )
                    );
                }
                //Recipients
                $this->php_mail->setFrom($this->server_mail, $this->name_server);
                $this->php_mail->addAddress($this->client_mail, $this->name_client);       // El nombre aparece en el correo copia xddd        
                $this->php_mail->addCC($this->reply);
                
                // Content
                $this->php_mail->isHTML(true);                                             // Set email format to HTML
                $this->php_mail->Subject = $this->subject;
                $this->php_mail->Body    = $this->content;
                
                $val= $this->php_mail->send();
                $this->php_mail->ClearAddresses(); 
                
                return $val;
            } catch (Exception $e) {
                return false;
            }
        }
    }

?>