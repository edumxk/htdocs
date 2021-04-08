<?php
use PHPMailer\PHPMailer\PHPMailer;
date_default_timezone_set('America/Sao_Paulo');
header('Content-type: text/html; charset=ISO-8859-1');
require 'vendor/autoload.php';


class Mailer{
    function sendMail($numrat){
        $mail = new PHPMailer;
        $mail->isSMTP();
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 0;
        $mail->Host = 'smtp.kokar.com.br';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'danfe@kokar.com.br';
        $mail->Password = 'D@nfe@kokar2018';
        $mail->setFrom('danfe@kokar.com.br', 'Chamados Kokar');
        $mail->addReplyTo('eduardo.cavalcante@kokar.com.br', 'Kokar Tintas');

        $mail->addAddress('eduardo.cavalcante@kokar.com.br', 'Kokar Tintas');
        $mail->addAddress('valeria.cardoso@kokar.com.br', 'Valeria Cardoso');
        //$mail->addAddress('pedrossc88@gmail.com', 'Kokar Tintas');
        $mail->Subject = utf8_decode('Autorização de RAT Nº'.$numrat.' - Sistema de Chamados Kokar');
        //Read an HTML message body from an external file, convert referenced images to embedded,
        $body = utf8_decode(
            "
            <body style=\"margin: 0; padding :0;\">
                <h2>RAT Nº".$numrat."</h2>
                <p>A RAT em anexo acaba de ser autorizada.</p>
            </body>
            ");
        $mail->IsHTML(true);
        $mail->Body = $body;
        $mail->addAttachment($_SERVER["DOCUMENT_ROOT"] . "/modulos/modChamados/Recursos/pdfGerados/rat".$numrat.".pdf");


        //Replace the plain text body with one created manually
        //$mail->AltBody = 'This is a plain-text message body';
        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');
        //send the message, check for errors
        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message sent!';
        }
    }


 
}