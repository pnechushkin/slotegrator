<?php

namespace App\Observers;

use App\Models\Prize;

class PrizeObserver
{

    /**
     * Handle the models prize "updated" event.
     *
     * @param Prize $prize
     * @return void
     */
    public function updated(Prize $prize)
    {
        $this->forgetCacheUser($prize);
    }

    /**
     * Handle the models prize "deleted" event.
     *
     * @param Prize $prize
     * @return void
     */
    public function deleted(Prize $prize)
    {
        $this->forgetCacheUser($prize);
    }

    protected function forgetCacheUser(Prize $prize)
    {
        \Cache::forget('UserByMail' . $prize->user->email);
    }
}
