<?php

namespace App\Models;

use Cache;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Psr\SimpleCache\InvalidArgumentException;

/**
 *  Class User
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $api_token
 * @property string $created_at
 * @property string $updated_at
 */
class User extends Authenticatable
{

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prizes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Prize::class, 'user_id', 'id');
    }

    /**
     * @param string $mail
     * @return Model|object|static|null
     */
    public static function getUserByMail(string $mail)
    {
        $user = Cache::get('UserByMail' . $mail);
        if (!empty($user)) {
            return $user;
        }
        $user = self::where('email', $mail)->first();
        if (empty($user)){
            return null;
        }
        if (empty($user->api_token)) {
            $user->api_token = \Str::random(80);
            $user->save();
            Cache::forget('UserByMail' . $user->email);
        }
        try {
            Cache::set('UserByMail' . $mail, $user);
        } catch (InvalidArgumentException $exception) {
            Log::error('Error set cache UserByMail', [$exception->getMessage()]);
        }

        return $user;

    }

}
