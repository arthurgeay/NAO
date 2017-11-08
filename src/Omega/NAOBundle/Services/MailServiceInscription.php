<?php

namespace Omega\NAOBundle\Services;


class MailServiceInscription
{
    public function getMailService($bodyEmail, $mail)
    {
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
            ->setUsername('geteolastpiece')
            ->setPassword('bmpmpnxqojewxlxc');
        //création d'un objet mailer
        $mailer = \Swift_Mailer::newInstance($transport);
        $message = \Swift_Message::newInstance();
        $message->setSubject("Votre compte a bien été enregistré");
        $message->setFrom('geteolastpiece@gmail.com');
        $message->setTo($mail);
        // pour envoyer le message en HTML
        $message->setBody($bodyEmail,'text/html');
        //envoi du message
        $mailer->send($message);
    }

}