<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the user "updated" event.
     *
     * @param User $user
     * @return void
     */
    public function updated(User $user)
    {
        $this->forgetCacheUser($user);
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function deleted(User $user)
    {
        $this->forgetCacheUser($user);
    }

    protected function forgetCacheUser(User $user)
    {
        \Cache::forget('UserByMail' . $user->email);
    }
}
