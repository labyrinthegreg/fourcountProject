<?php

namespace App\Service ;

use Psr\Log\LoggerInterface;


/**
 * undocumented class
 */
class Balance 
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
    $this->logger = $logger;
    }

    public function initArray($participants)
    {
        global $array;
        if (!$array) {
            foreach ($participants as $participant) {
                $array[trim($participant)] = 0;
            }
        }
    }

    public function setBalance($expenses)
    {
        global $array;
        foreach ($expenses as $expense ) {
            $dette = number_format( $expense->getAmount() / count($expense->getUsers()) , 2);
            $array[$expense->getPaidBy()->getName()] += $expense->getAmount();
            foreach ($expense->getUsers() as $user ) {
                $array[$user->getName()] -= $dette;
            }
        }
        return $array;
    }
}
