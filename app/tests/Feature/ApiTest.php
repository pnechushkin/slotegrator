<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\CreatesApplication;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    use CreatesApplication;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function getApiToken()
    {
        $user = User::orderByRaw("Rand()")->first();
        $this->putJson('/api/get-api-token',['email' => $user->email, 'password' => 'password'])
            ->seeJson([
                'success' => true,
            ]);

    }
}
