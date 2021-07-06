<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Prize;

$factory->define(Prize::class, function () {
    return Prize::getRandomPrize();
});
