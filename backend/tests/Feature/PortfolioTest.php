<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
                'skills', 'services', 'projects', 'experiences', 'testimonials', 'socials',
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

    public function test_contact_endpoint_validates_input(): void
    {
        $this->postJson('/api/contact', ['name' => '', 'email' => 'bad', 'message' => ''])
            ->assertStatus(422);
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
            ['/admin/projects'],
            ['/admin/skills'],
            ['/admin/services'],
            ['/admin/experience'],
            ['/admin/education'],
            ['/admin/testimonials'],
            ['/admin/socials'],
            ['/admin/messages'],
        ];
    }
}
