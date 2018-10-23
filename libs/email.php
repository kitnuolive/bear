<?php

class Email extends Model
{

    public $db;

    public function __construct()
    {
        $this->db = APP::$db;
    }

    private function emailDateField()
    {
        $dataField = array(
            'emailId' => 'email_id',
            'email' => 'email',
            'senderName' => 'send_name',
            'smtpServer' => 'smtp_server',
            'port' => 'port',
            'smtpAuth' => 'smtp_auth',
            'username' => 'username',
            'password' => 'password',
            'smtpSecure' => 'smtp_secure',
            'mailFooter' => 'mail_footer'
        );
        return $dataField;
    }

    public function get_email()
    {
        /* DB */
        $fieldDB = $this->emailDateField();
        /* sort */
        $obj = new stdClass();
        $page = 0;
        $tableName = "email";
        $fieldCountPage = "email_id";
        $showOutput = 10;
        $orderBy = "email_id";
        $typeSearch = array('emailId' => 'emailId'
        );
        /* test */
        $result = Model::listDb($obj, $fieldDB, $typeSearch, $tableName, $orderBy, NULL, $showOutput, $page, $fieldCountPage);

        if (isset($result))
        {
            $result->view[0]->password = $this->encrypt_decrypt('decrypt', $result->view[0]->password);
        }

        return $result->view[0];
    }

    public function send_email($emailSetting, $topic, $receiverEmail, $cc, $bcc, $msg, $attachFilePath)
    {
        require_once '' . ROOT . DS . 'libs' . DS . 'PHPMailer' . DS . 'PHPMailerAutoload.php';

        $error = null;
        $result = null;
        if (!isset($emailSetting))
        {
            $error = "No email setting obj.";
        }
        else if (!isset($receiverEmail))
        {
            $error = "No receiver'mail obj.";
        }


        $mail = new PHPMailer;
        $mail->CharSet = "utf-8";
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = $emailSetting->smtpServer;     // Specify main and backup SMTP servers
        $mail->Port = $emailSetting->port; //port
        $mail->SMTPAuth = $emailSetting->smtpAuth;                               // Enable SMTP authentication
        $mail->Username = $emailSetting->username;                 // SMTP username
        $mail->Password = $emailSetting->password;                           // SMTP password
        $mail->SMTPSecure = $emailSetting->smtpSecure;                            // Enable encryption, 'ssl' also accepted


        $mail->From = $emailSetting->email;
        $mail->FromName = $emailSetting->senderName;

        $size = sizeof($receiverEmail);
        for ($i = 0; $i < $size; $i++)
        {
            $mail->addAddress($receiverEmail[$i]);     // Add a recipient
        }

        $size = sizeof($cc);
        for ($i = 0; $i < $size; $i++)
        {
            $mail->addCC($cc[$i]);         // cc
        }

        $size = sizeof($bcc);
        for ($i = 0; $i < $size; $i++)
        {
            $mail->addBCC($bcc[$i]);         // bcc
        }

        $mail->WordWrap = 50;                                 // Set word wrap to 50 characters

        $size = sizeof($attachFilePath);
        for ($i = 0; $i < $size; $i++)
        {
            $mail->addAttachment($attachFilePath[$i]);         //  Attachment
        }

        // var_dump($mail);die();

        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $topic;
        $mail->Body = $msg;
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if (!$mail->send())
        {
            return "Message could not be sent, Error: " . $mail->ErrorInfo;
        }
        else
        {
            return true;
        }
    }

    public static function templateEmail($quotation_info)
    {
        require_once( ROOT . DS . 'libs' . DS . 'templateMail' . DS . 'templateEmail.php');

        $accountInfo = null;
        $query = new stdClass();
        $query->companySettingId = $quotation_info->companySettingId;
        $query->unique = TRUE;
        $companysetting = new companySetting();
        $header = $companysetting->companyList($query, 0);
        $header = $header->view[0];
        $header = Setting_utill::arrayToSingleObject($header);
        
        // var_dump($header);die();
        $msg = templateEmail($quotation_info,$header);
        return $msg;
    }

    protected function encrypt_decrypt($action, $string)
    {
        $output = false;

        $key = 'asldjf;asdvgpoih[4iontwehpfoivhnf3y5oejf;';

        // initialization vector 
        $iv = md5(md5($key));

        if ($action == 'encrypt')
        {
            $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, $iv);
            $output = base64_encode($output);
        }
        else if ($action == 'decrypt')
        {
            $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, $iv);
            $output = rtrim($output);
        }
        return $output;
    }

    public function set_email($email_setting)
    {
        if (empty($email_setting))
        {
            Result::setError("No email_setting.");
            Result::showResult();
        }

        $email_setting->password = $this->encrypt_decrypt('encrypt', $email_setting->password);
        return $email_setting;
    }

    public function get_pwsemail($email_setting)
    {
        if (isset($email_setting))
        {
            $email_setting->password = $this->encrypt_decrypt('decrypt', $email_setting->password);
        }
        return $email_setting;
    }

}
