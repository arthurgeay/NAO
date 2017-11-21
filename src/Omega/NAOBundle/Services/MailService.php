<?php

namespace Omega\NAOBundle\Services;


class MailService
{
    public function getMailService($bodyEmail, $mail, $subject)
    {
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
            ->setUsername('naoprojet.contact')
            ->setPassword('_/:7(#?,3Xp7');
        //création d'un objet mailer
        $mailer = \Swift_Mailer::newInstance($transport);
        $message = \Swift_Message::newInstance();
        $message->setSubject($subject);
        $message->setFrom('naoprojet.contact@gmail.com');
        $message->setTo($mail);
        // pour envoyer le message en HTML
        $message->setBody($bodyEmail,'text/html');
        //envoi du message
        $mailer->send($message);
    }

    public function getMailContactService($bodyEmail, $mail, $subject)
    {
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
            ->setUsername('naoprojet.contact')
            ->setPassword('_/:7(#?,3Xp7');
        //création d'un objet mailer
        $mailer = \Swift_Mailer::newInstance($transport);
        $message = \Swift_Message::newInstance();
        $message->setSubject($subject);
        $message->setFrom($mail);
        $message->setTo('naoprojet.contact@gmail.com');
        // pour envoyer le message en HTML
        $message->setBody($bodyEmail,'text/html');
        //envoi du message
        $mailer->send($message);
    }

}