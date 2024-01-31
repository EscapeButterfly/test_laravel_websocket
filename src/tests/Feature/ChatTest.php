<?php

namespace Tests\Feature;

use App\Events\ChatMessage;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ChatTest extends TestCase
{
    public function testThatAuthenticatedUserCanAccessChat(): void
    {
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->get('/chat');

        $response->assertStatus(200);
    }

    public function testThatAuthenticatedUserCanSendMessage(): void
    {
        $sender   = User::factory()->create();
        $receiver = User::factory()->create();

        $response = $this->actingAs($sender)
            ->post('/chat/send', [
                'message'     => 'Some random message',
                'receiver_id' => $receiver->id
            ]);
        $response->assertStatus(200);
    }

    public function testThatMessageEventDispatched(): void
    {
        \Event::fake();
        $sender   = User::factory()->create();
        $receiver = User::factory()->create();

        $this->actingAs($sender)
            ->post('/chat/send', [
                'message'     => 'Some random message',
                'receiver_id' => $receiver->id
            ]);
        \Event::assertDispatched(ChatMessage::class);
    }

    public function testThatAuthenticatedUserCanReceiveChatHistory(): void
    {
        $sender   = User::factory()->create();
        $receiver = User::factory()->create();

        $this->actingAs($sender)
            ->post('/chat/send', [
                'message'     => 'Some random message',
                'receiver_id' => $receiver->id
            ]);

        $response = $this->actingAs($receiver)
            ->get('/chat/messages/' . $sender->id);

        $response->assertJson(fn(AssertableJson $json) => $json->has(1)
            ->first(fn(AssertableJson $json) => $json->where('message', 'Some random message')
                ->etc()
            )
        );
    }
}
