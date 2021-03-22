<?php


namespace App\Services;


use Symfony\Component\Mailer\MailerInterface;

class MailerManager
{
public function confirmRegistration(User $user,MailerInterface $mailer)
{
    return "test";
}
}