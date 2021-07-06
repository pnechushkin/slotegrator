<?php


namespace App\Interfaces;

use App\Exceptions\PrizePaidOutExceptions;

interface PrizePaidOutInterface
{
    /**
     * Method for issuing af prize
     *
     * @param int $id
     * @return bool
     * @throw PrizePaidOutExceptions
     */
    public function prizePaidOut(int $id): bool;

}
