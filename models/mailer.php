<?php

class mailer
{

    public function sendMail($topic, $receiverEmail, $cc, $bcc, $msg, $attachFilePath)
    {
        require_once '' . ROOT . DS . 'libs' . DS . 'PHPMailer' . DS . 'PHPMailerAutoload.php';
        $mail = new PHPMailer;
        $mail->CharSet = "utf-8";
        //        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';
        // Specify main and backup SMTP servers
        $mail->Port = 587; //port
        $mail->isSMTP(); 
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'xxx';                 // SMTP username
        $mail->Password = 'xxx';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

        $mail->From = "service@sacict-award.com";
        $mail->FromName = "service@sacict-award.com";

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

        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $topic;
        $mail->Body = $msg;
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if (!$mail->send())
        {
            echo "Message could not be sent, Error: " . $mail->ErrorInfo;
//            throw new Exception("Message could not be sent, Error: " . $mail->ErrorInfo);
        }
        else
        {
            return true;
        }
    }

}
