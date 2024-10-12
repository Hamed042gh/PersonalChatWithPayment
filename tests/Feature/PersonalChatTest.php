<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Message;
use App\Livewire\PersonalChat;
use App\Events\PersonalChatEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PersonalChatTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // 1. Test if the PersonalChat component exists on the page
    public function test_component_exists_on_the_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get('/chat')
            ->assertSeeLivewire(PersonalChat::class);
    }

    // 2. Test displaying all users
    public function test_displays_all_users()
    {
        $user1 = User::factory()->create(['messages_count' => 0]);
        $user2 = User::factory()->create(['messages_count' => 0]);

        $this->actingAs($user1); 

        Livewire::test(PersonalChat::class)
            ->assertViewHas('users', function ($users) use ($user2) {
                return $users->contains($user2);
            });
    }

    // 3. Test if the PersonalChat component renders successfully
    public function test_renders_successfully()
    {
        $user = User::factory()->create([
            'messages_count' => 0,
        ]);

        $this->actingAs($user);

        Livewire::test(PersonalChat::class)
            ->assertStatus(200)
            ->assertSee('You have sent');
    }

    // 4. Test choosing a user for starting a chat
    public function test_choose_user_for_start_chat()
    {
        $user1 = User::factory()->create(['messages_count' => 0]);
        $user2 = User::factory()->create(['messages_count' => 0]);
        $this->actingAs($user1);

        $component = Livewire::test(PersonalChat::class)
            ->call('chooseUser', $user2->id);

        $this->assertEquals($user2->id, $component->get('selectedUser')->id);
    }

    // 5. Test loading messages sent by users
    public function test_load_messages_sending_by_users()
    {
        $user1 = User::factory()->create(['messages_count' => 0]);
        $user2 = User::factory()->create(['messages_count' => 0]);

        $this->actingAs($user1);

        Message::create([
            'content' => 'message from sender',
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
        ]);
        Message::create([
            'content' => 'message from receiver',
            'sender_id' => $user2->id,
            'receiver_id' => $user1->id,
        ]);

        $component = Livewire::test(PersonalChat::class)
            ->call('chooseUser', $user2->id)
            ->call('loadMessages');

        $this->assertCount(2, $component->get('messages'));
        $this->assertEquals('message from sender', $component->get('messages')[0]['content']);
        $this->assertEquals('message from receiver', $component->get('messages')[1]['content']);
    }

    // 6. Test sending a message
    public function test_sending_message()
    {
        $user1 = User::factory()->create(['messages_count' => 0]);
        $user2 = User::factory()->create(['messages_count' => 0]);

        $this->actingAs($user1);

        $component = Livewire::test(PersonalChat::class)
            ->call('chooseUser', $user2->id);

        $component->set('newMessage', 'message from user1')
            ->call('handleMessageSubmission');

        $this->assertCount(1, Message::where('sender_id', $user1->id)->where('receiver_id', $user2->id)->get());
        $this->assertEquals('message from user1', Message::latest()->first()->content);

        $component->call('loadMessages');
        $this->assertCount(1, $component->get('messages'));
        $this->assertEquals('message from user1', $component->get('messages')[0]['content']);
    }

    // 7. Test if newMessage field is required
    public function test_newMessage_field_is_required()
    {
        $user1 = User::factory()->create(['messages_count' => 0]);

        $this->actingAs($user1);
        Livewire::test(PersonalChat::class)
            ->set('newMessage', '')
            ->call('handleMessageSubmission')
            ->assertHasErrors(['newMessage' => 'required']);
    }

    // 8. Test message creation broadcasts an event
    public function test_creating_a_message_broadcast()
    {
        Event::fake();

        $user1 = User::factory()->create(['messages_count' => 0]);
        $user2 = User::factory()->create(['messages_count' => 0]);
        $this->actingAs($user1);

        Livewire::test(PersonalChat::class)
            ->call('chooseUser', $user2->id)
            ->set('newMessage', 'message from user1')
            ->call('handleMessageSubmission');

        Event::assertDispatched(PersonalChatEvent::class, function ($event) use ($user1, $user2) {
            return $event->message->content === 'message from user1' &&
                $event->message->sender_id === $user1->id &&
                $event->message->receiver_id === $user2->id;
        });
    }

    // 9. Test handle message submission process
    public function test_handle_message_submission()
    {
        $user1 = User::factory()->create(['messages_count' => 0]);
        $user2 = User::factory()->create(['messages_count' => 0]);

        $this->actingAs($user1);

        Livewire::test(PersonalChat::class)
            ->call('chooseUser', $user2->id)
            ->set('newMessage', 'Hello, user 2!')
            ->call('handleMessageSubmission')
            ->assertSessionHasNoErrors();

        $this->assertCount(1, Message::where('sender_id', $user1->id)->where('receiver_id', $user2->id)->get());
        $this->assertEquals('Hello, user 2!', Message::latest()->first()->content);
    }

    // 10. Test limited messages until 3 are sent
    public function test_limited_messages_until_3()
    {
        $user1 = User::factory()->create(['messages_count' => 0]);
        $user2 = User::factory()->create(['messages_count' => 0]);
        $this->actingAs($user1);
       

        $component = Livewire::test(PersonalChat::class)
            ->call('chooseUser', $user2->id);

        $component->set('newMessage', 'message 1 from user1')
            ->call('handleMessageSubmission');
        $this->assertCount(1, Message::where('sender_id', $user1->id)->where('receiver_id', $user2->id)->get());

        $component->set('newMessage', 'message 2 from user1')
            ->call('handleMessageSubmission');
        $this->assertCount(2, Message::where('sender_id', $user1->id)->where('receiver_id', $user2->id)->get());

        $component->set('newMessage', 'message 3 from user1')
            ->call('handleMessageSubmission');
        $this->assertCount(3, Message::where('sender_id', $user1->id)->where('receiver_id', $user2->id)->get());

        $component->set('newMessage', 'message 4 from user1')
            ->call('handleMessageSubmission')
            ->assertRedirect('/subscribe');
    }

    // 11. Test message creation successfully
    public function test_create_message_successfully()
    {
        $user1 = User::factory()->create(['messages_count' => 0]);
        $user2 = User::factory()->create(['messages_count' => 0]);

        $this->actingAs($user1);

        Livewire::test(PersonalChat::class)
            ->call('chooseUser', $user2->id)
            ->set('newMessage', 'Hello, this is a test message.')
            ->call('handleMessageSubmission');

        $this->assertDatabaseHas('messages', [
            'content' => 'Hello, this is a test message.',
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id
        ]);

        $this->assertEquals(1, $user1->fresh()->messages_count);
    }

    // 12. Test creating a message updates user's messages count
    public function test_creating_message_updates_user_messages_count()
    {
        $user1 = User::factory()->create(['messages_count' => 0]);
        $user2 = User::factory()->create(['messages_count' => 0]);

        $this->actingAs($user1);

        $component = Livewire::test(PersonalChat::class)
            ->call('chooseUser', $user2->id)
            ->set('newMessage', 'Another test message from user1')
            ->call('handleMessageSubmission');

        $this->assertEquals(1, $user1->fresh()->messages_count);
        $this->assertEquals(0, $user2->fresh()->messages_count);
    }

    
}
