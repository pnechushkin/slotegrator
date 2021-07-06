<?php


namespace App\Interfaces;


use App\Exceptions\PrizePaidOutExceptions;
use App\Models\Prize;

interface MoneyPaidOutInterface extends PrizePaidOutInterface
{
    /**
     * Method for type paid out af prize
     *
     * @param string $type
     * @return bool
     * @throws PrizePaidOutExceptions
     */
    public function setTypePaidOut(string $type = Prize::BANK): bool;
}
