<?php

namespace App\Models;

use App\Exceptions\PrizePaidOutExceptions;
use App\Fctories\PaidOutFactory;
use App\Observers\PrizeObserver;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Class Prize
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property int $value
 * @property int $paid_out
 * @property string $created_at
 * @property string $updated_at
 */
class Prize extends Model
{
    /**
     * @var string
     */
    const MONEY = 'money';
    /**
     * @var int
     */
    const MONEY_MIN = 10;
    /**
     * @var int
     */
    const MONEY_MAX = 100;
    /**
     * @var string
     */
    const POINTS = 'points';
    /**
     * @var int
     */
    const POINTS_MIN = 100;
    /**
     * @var int
     */
    const POINTS_MAX = 1000;
    /**
     * @var string
     */
    const OBJECT = 'object';
    /**
     * @var array
     */
    const TYPES_PRIZES = [self::MONEY, self::POINTS, self::OBJECT,];
    /**
     * @var string
     */
    const BANK = 'bank';
    /**
     * @var array
     */
    const TYPES_PAID_OUT_MONEY = [self::BANK];


    /**
     * @var string
     */
    protected $table = 'Prizes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'type', 'value', 'paid_out',
    ];
    /**
     * @var User
     */
    private $user;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @param int|null $user_id
     * @return array
     */
    public static function getRandomPrize(int $user_id = null): array
    {
        $type = Prize::TYPES_PRIZES[array_rand(Prize::TYPES_PRIZES)];
        switch ($type) {
            case Prize::MONEY:
                $value = rand(Prize::MONEY_MIN, Prize::MONEY_MAX);
                break;
            case Prize::POINTS:
                $value = rand(Prize::POINTS_MIN, Prize::POINTS_MAX);
                break;
            default:
                $value = 1;
        }
        if ($user_id) {
            self::insert([
                'type' => $type,
                'value' => $value,
                'user_id' => $user_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return [
            'type' => $type,
            'value' => $value,
        ];
    }

    /**
     * @param $id
     * @return bool|string
     */
    public static function paidOut($id)
    {
        $prize = self::find($id);
        if (empty($prize)) {
            Log::error("Undefined prize with id $id");
            return 'Undefined prize.';
        }
        if (!empty($prize->paid_out)) {
            Log::error("Repeated request for a prize with id $id");
            return "Prize has already been issued";
        }

        DB::beginTransaction();
        try {
            $prize->update(['paid_out' => true, 'updated_at' => now()]);
            $paidOut = (new PaidOutFactory())->paidOut($prize->type);
            $paidOut->prizePaidOut($id);
            DB::commit();
            return true;
        } catch (Exception | PrizePaidOutExceptions $e) {
            Log::error("Error paid out prize with id $id : " . $e->getMessage());
            DB::rollback();
            return 'Undefined error. Please try again later.';
        }
    }

}
