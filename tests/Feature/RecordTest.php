<?php

namespace Tests\Feature;

use App\Models\Token;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecordTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->passHash = '$2y$10$CTzremgP2usZ54vQg6O8f./ChQOKU5eqLz2NkKrBKAOMy8.vlk34u';
        $this->token = 'VVAOGVldNBVNRdzSzmnX0MkbufvqDAwjARfnR78m6NzqB3YJWP';
        $this->tokenHash = '$2y$04$JjXruAYgYlXC.WvwBvhMI.CmEPxcC0gYLg0WyidlRiDunuKgIPmXK';
        $this->data = json_encode([]);
    }

    public function test_unauthorized_action_is_aborted()
    {
        $response = $this->post(route('records.store'), ['data' => $this->data]);
        $response->assertStatus(403);
    }

    public function test_record_can_be_created()
    {
        $this->seed();
        $user = User::first();

        Token::create([
            'token' => $this->tokenHash,
            'user_id' => $user->id,
            'expires_at' => now()->addMinutes(5)->format('Y-m-d H:i:s'),
        ]);

        $response = $this->withHeader('AuthToken', $this->token)
            ->post(route('records.store', ['data' => $this->data]));

        $response->assertStatus(200);

        $responseGet = $this->withHeader('AuthToken', $this->token)
            ->get(route('records.store.get', ['data' => $this->data]));

        $responseGet->assertStatus(200);
    }

    public function test_expired_token_not_give_access()
    {
        $this->seed();
        $user = User::first();

        Token::create([
            'token' => $this->tokenHash,
            'user_id' => $user->id,
            'expires_at' => now()->subMinutes(10)->format('Y-m-d H:i:s'),
        ]);

        $response = $this->withHeader('AuthToken', $this->token)
            ->post(route('records.store', ['data' => $this->data]));

        $response->assertStatus(403);

        $responseGet = $this->withHeader('AuthToken', $this->token)
            ->get(route('records.store.get', ['data' => $this->data]));

        $responseGet->assertStatus(403);
    }
}
