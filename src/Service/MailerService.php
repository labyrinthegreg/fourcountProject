<?php

namespace App\Service;

use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Notifier\Notification\Notification;
 

class MailerService {

    public function __construct(){
    }

    public function sendNotification(NotifierInterface $notifier, $email, $expense){
        // ...

        
        // Create a Notification that has to be sent
        // using the "email" channel
        $notification = (new Notification('Dépense crée !!', ['email']))
            ->content($expense->getPaidBy() . ' a créé une dépense de ' . $expense->getAmount() . ' pour '
                     . $expense->getTitle() . ' le ' . $expense->getDate()->format('Y-m-d'));

        // The receiver of the Notification
        $recipient = new Recipient(
            $email,
        );

        // Send the notification to the recipient
        $notifier->send($notification, $recipient);


    }

}
