<?php
// src/AppBundle/Email.php
namespace AppBundle;


class Email
{


    /**
    * Send email
    *
    * @param $mailer
    * @param $from
    * @param $to
    * @param $subject
    * @param $text
    * Example call: $this->get('app.Email')->send($this->get('mailer'),$this->container->getParameter('mailer_user'),$to,$subject,$text);
    */
    public function send($mailer,$from,$to,$subject,$text)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($text)
        ;
        $mailer->send($message);
        return true;
    }
}
