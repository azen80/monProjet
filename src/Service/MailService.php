<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailService
{
    private $mailer;
    private $paramBag;

    public function __construct(MailerInterface $mailer, ParameterBagInterface $paramBag)
    {
        $this->mailer = $mailer;
        $this->paramBag = $paramBag;
    }

    public function sendMail(string $expediteur, string $destinataire, string $sujet, string $message): void
    {
        $email = (new Email())
            ->from($expediteur)
            ->to($destinataire)
            ->subject($sujet)
            ->text($message);

        $this->mailer->send($email);
    }
}