<?php

namespace Tests\Feature;

use App\Livewire\Admin\MessageManager;
use App\Mail\ContactReplyMail;
use App\Models\ContactMessage;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class MessageReplyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_admin_can_send_reply_from_messages_page(): void
    {
        Mail::fake();

        $admin = User::where('email', 'admin@huseinjaber.com')->firstOrFail();
        $message = ContactMessage::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'subject' => 'Project inquiry',
            'message' => 'Hello, I would like to discuss a project.',
            'is_read' => false,
        ]);

        Livewire::actingAs($admin)
            ->test(MessageManager::class)
            ->call('select', $message->id)
            ->call('openReply')
            ->set('replySubject', 'Re: Project inquiry')
            ->set('replyBody', 'Thanks for reaching out! Happy to chat next week.')
            ->call('sendReply')
            ->assertHasNoErrors();

        Mail::assertSent(ContactReplyMail::class, function (ContactReplyMail $mail) use ($message) {
            return $mail->hasTo($message->email)
                && $mail->replySubject === 'Re: Project inquiry'
                && str_contains($mail->replyBody, 'Happy to chat');
        });
    }
}
