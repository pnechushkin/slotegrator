<?php


namespace App\Fctories;

use App\Exceptions\PrizePaidOutExceptions;
use App\Interfaces\PrizePaidOutInterface;
use App\Models\Prize;
use App\Services\PaidOutMoney;
use App\Services\PaidOutObject;
use App\Services\PaidOutPoints;
use Illuminate\Support\Facades\Log;
use Str;

/**
 * Factory for PaidOut types
 *
 * Class PaidOutFactory
 * @package App\Fctories
 */
class PaidOutFactory
{
    /**
     * @param string $typePrize
     * @return PrizePaidOutInterface
     * @throws PrizePaidOutExceptions
     */
    public function paidOut(string $typePrize)
    {
        $typePrizeClassName = Str::ucfirst(Str::lower($typePrize));
        $className = "App\Services\PaidOut$typePrizeClassName";
        $classExists = class_exists($className);
        if ($classExists) {
            switch ($typePrize) {
                case Prize::MONEY:
                    return new PaidOutMoney();
                case Prize::POINTS:
                    return new PaidOutPoints();
                case Prize::OBJECT:
                    return new PaidOutObject();
                default:
                    Log::error("Undefined type prize $typePrize for paid out");
                    throw new PrizePaidOutExceptions('Undefined type prize');
            }
        } else {
            Log::error("Undefined class $className for paid out");
            throw new PrizePaidOutExceptions("Undefined class $className");
        }

    }
}
