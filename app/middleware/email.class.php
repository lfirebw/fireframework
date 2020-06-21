<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

Import::Core("helpers/phpmailer/Exception",false);
Import::Core("helpers/phpmailer/PHPMailer",false);
Import::Core("helpers/phpmailer/SMTP",false);

Import::Model('configemailModel','configEmail');

class email{
    private $address;
    private $emailFrom;
    private $ccAddress;
    private $bccAddress;
    private $host;
    private $port;
    private $username;
    private $password;
    private $subject;
    private $body;
    private $altBody;
    private $fileAttach;
    
    public function __construct(){
        $this->reset();
        $obj = new configEmail();
        $rs = $obj->select(array('estado'=>1));
        if(!empty($rs)){
            $tmp_r = end($rs);
            $this->host = $tmp_r['host'];
            $this->username = $tmp_r['username'];
            $this->password = $tmp_r['password'];
            $this->port = $tmp_r['port'];
        }
    }
    /**
     * Privates
     */
    private function reset(){
        $this->address = array();
        $this->ccAddress = array();
        $this->bccAddress = array();
        $this->fileAttach = array();
    }
    private function verifyemail(&$obj,$email){
        if(strpos($email,"@") !== false){
            $obj[] = $email;
            return true;
        }
        return null;
    }
    private function verifyDirFiles($filename){
        $arr = array();
        if(is_string($filename)){
            $arr[] = $filename;
        }else if(is_array($filename)){
            $arr = $filename;
        }
        if(!empty($arr)){
            foreach($arr as $value){
                if(is_dir($value)){
                    $this->fileAttach[] = $value;
                }
            }
        }
    }
    /**
     * Logic
     */
    public function send(){
        $mail = new PHPMailer(true);
        try{
            //Server settings
            $mail->SMTPDebug = 0; //SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = $this->host;                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = $this->username;                     // SMTP username
            $mail->Password   = $this->password;                               // SMTP password
            $mail->SMTPSecure = "ssl"; //PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port       = $this->port;                                    // TCP port to connect to
            
            //Recipients
            $mail->setFrom('noreply@bravewolfdev.com', 'noreply');
            if(empty($this->address)){
                throw new Exception("Address is empty");
            }
            foreach($this->address as $value){
                $mail->addAddress($value);
            }
            if(!empty($this->ccAddress)){
                foreach($this->ccAddress as $value){
                    $mail->addCC($value);
                }
            }
            if(!empty($this->bccAddress)){
                foreach($this->bccAddress as $value){
                    $mail->addBCC('bcc@example.com');
                }
            }
            // Attachments
            if(!empty($this->fileAttach)){
                foreach($this->fileAttach as $value){
                    $mail->addAttachment($value);
                }
            }
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = @$this->subject;
            $mail->Body    = @$this->body;
            if(!empty($this->altBody)){
                $mail->AltBody = $this->altBody;
            }
            // var_dump($mail);exit();
            $mail->send();
            return true;
        }catch(Exception $e){
            throw new Exception("Error al enviar el correo: ".$e->getMessage());
        }
    }
    /**
     * Setters
     */
    public function addSubject($str){
        $this->subject = $str;
    }
    public function addBody($str){
        $this->body = $str;
    }
    public function addAltBody($str){
        $this->altBody = $str;
    }
    public function addAddress($str){
        if(is_string($str)){
            //verify if is correct format
            $this->verifyemail($this->address,$str);
        }else if (is_array($str)){
            foreach($str as $val){
                $this->verifyemail($this->address,$val);
            }
        }
    }
    public function addBccAddress($str){
        if(is_string($str)){
            //verify if is correct format
            $this->verifyemail($this->bccAddress,$str);
        }else if (is_array($str)){
            foreach($str as $val){
                $this->verifyemail($this->bccAddress,$val);
            }
        }
    }
    public function addCcAddress($str){
        if(is_string($str)){
            //verify if is correct format
            $this->verifyemail($this->ccAddress,$str);
        }else if (is_array($str)){
            foreach($str as $val){
                $this->verifyemail($this->ccAddress,$val);
            }
        }
    }
    public function addAttach($filename){
        $this->verifyDirFiles($filename);
    }
    
}

?>