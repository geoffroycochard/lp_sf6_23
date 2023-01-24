<?php
namespace App\EventListener;

use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsEntityListener(
    event: Events::postUpdate, 
    method: 'postUpdate', 
    entity: Ticket::class
)]
class TicketUpdateNotifier 
{

    private MailerInterface $mailer; 

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function postUpdate(
        Ticket $ticket, 
        LifecycleEventArgs $event
    )
    {
        foreach ($ticket->getUsers() as $user) {
            $email = (new Email())
                ->from('hello@example.com')
                ->to($user->getEmail())
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->html('<p>See Twig integration for better HTML integration!</p>');
            ;
            $this->mailer->send($email);

        }
    }

}