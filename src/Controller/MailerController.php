<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    /**
     * @Route("/email")
     */
    public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())

        // simple string
        ->from('mailtrap@example.com')

        // object
            ->from(new Address('mailtrap@example.com'))

        // name object
            ->from(new NamedAddress('mailtrap@example.com', 'Mailtrap'));

        $mailer->send($email);

        // …
        return new Response(
            'Email was sent'
        );
    }
}
