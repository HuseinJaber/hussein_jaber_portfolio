<?php

namespace Tests\Feature;

use App\Mail\ContactNotificationMail;
use App\Mail\ContactReceivedMail;
use App\Mail\NewsletterSubscriptionNotificationMail;
use App\Mail\NewsletterWelcomeMail;
use App\Models\Certification;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use App\Support\PortfolioMail;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PortfolioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    private function admin(): User
    {
        return User::where('email', 'admin@huseinjaber.com')->firstOrFail();
    }

    public function test_public_portfolio_api_returns_seeded_content(): void
    {
        $response = $this->getJson('/api/portfolio');

        $response->assertOk()
            ->assertJsonStructure([
                'profile' => ['name', 'title'],
                'skills', 'services', 'projects', 'experiences', 'certifications', 'testimonials', 'socials',
            ])
            ->assertJsonPath('profile.name', 'Hussein Jaber');

        $this->assertNotEmpty($response->json('projects'));
    }

    public function test_contact_endpoint_stores_message(): void
    {
        $payload = [
            'name' => 'Jane Client',
            'email' => 'jane@example.com',
            'subject' => 'New website',
            'message' => 'I would like to hire you.',
        ];

        $this->postJson('/api/contact', $payload)
            ->assertCreated()
            ->assertJsonStructure(['message', 'id']);

        $this->assertDatabaseHas('contact_messages', ['email' => 'jane@example.com']);
    }

    public function test_contact_endpoint_sends_emails(): void
    {
        Mail::fake();

        $payload = [
            'name' => 'Jane Client',
            'email' => 'jane@example.com',
            'subject' => 'New website',
            'message' => 'I would like to hire you.',
        ];

        $this->postJson('/api/contact', $payload)->assertCreated();

        Mail::assertSent(ContactReceivedMail::class, fn ($mail) => $mail->hasTo('jane@example.com'));
        Mail::assertSent(ContactNotificationMail::class, fn ($mail) => $mail->hasTo(PortfolioMail::ownerEmail()));
    }

    public function test_newsletter_endpoint_stores_subscriber_and_sends_emails(): void
    {
        Mail::fake();

        $this->postJson('/api/newsletter', ['email' => 'reader@example.com'])
            ->assertCreated()
            ->assertJsonStructure(['message']);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'reader@example.com',
            'is_active' => true,
        ]);

        Mail::assertSent(NewsletterWelcomeMail::class, fn ($mail) => $mail->hasTo('reader@example.com'));
        Mail::assertSent(NewsletterSubscriptionNotificationMail::class, fn ($mail) => $mail->hasTo(PortfolioMail::ownerEmail()));
    }

    public function test_newsletter_endpoint_handles_duplicate_subscription(): void
    {
        Mail::fake();

        NewsletterSubscriber::create(['email' => 'reader@example.com', 'is_active' => true]);

        $this->postJson('/api/newsletter', ['email' => 'reader@example.com'])
            ->assertOk();

        Mail::assertNothingSent();
    }

    public function test_newsletter_endpoint_validates_input(): void
    {
        $this->postJson('/api/newsletter', ['email' => 'not-an-email'])
            ->assertStatus(422);
    }

    public function test_contact_endpoint_validates_input(): void
    {
        $this->postJson('/api/contact', ['name' => '', 'email' => 'bad', 'message' => ''])
            ->assertStatus(422);
    }

    public function test_analytics_endpoint_stores_event(): void
    {
        $payload = [
            'session_id' => 'test-session-abc123',
            'event_type' => 'page_view',
            'path' => '/',
            'referrer' => 'https://google.com',
            'user_agent' => 'PHPUnit',
        ];

        $this->postJson('/api/analytics', $payload)->assertNoContent();

        $this->assertDatabaseHas('analytics_events', [
            'session_id' => 'test-session-abc123',
            'event_type' => 'page_view',
            'path' => '/',
        ]);
    }

    public function test_analytics_endpoint_validates_input(): void
    {
        $this->postJson('/api/analytics', [
            'session_id' => '',
            'event_type' => 'invalid',
            'path' => '',
        ])->assertStatus(422);
    }

    public function test_certification_credential_endpoint_serves_pdf(): void
    {
        Storage::fake('certifications');

        $filename = 'test-cert.pdf';
        Storage::disk('certifications')->put($filename, '%PDF-1.4 test');

        $certification = Certification::published()->firstOrFail();
        $certification->update(['credential_file' => $filename]);

        $this->get("/api/certifications/{$certification->id}/credential")
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_certification_credential_endpoint_rejects_missing_file(): void
    {
        $certification = Certification::published()->firstOrFail();
        $certification->update(['credential_file' => null]);

        $this->get("/api/certifications/{$certification->id}/credential")
            ->assertNotFound();
    }

    public function test_admin_routes_require_authentication(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    #[DataProvider('adminRoutes')]
    public function test_admin_pages_render_for_authenticated_user(string $route): void
    {
        $this->actingAs($this->admin())
            ->get($route)
            ->assertOk();
    }

    public static function adminRoutes(): array
    {
        return [
            ['/admin'],
            ['/admin/profile-content'],
            ['/admin/sections'],
            ['/admin/projects'],
            ['/admin/projects/create'],
            ['/admin/project-categories'],
            ['/admin/tech-stacks'],
            ['/admin/skills'],
            ['/admin/services'],
            ['/admin/experience'],
            ['/admin/education'],
            ['/admin/certifications'],
            ['/admin/testimonials'],
            ['/admin/socials'],
            ['/admin/messages'],
            ['/admin/newsletter'],
            ['/admin/analytics'],
        ];
    }
}
