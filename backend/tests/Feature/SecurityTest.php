<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_non_admin_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_public_registration_is_disabled_by_default(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_api_post_rejects_unknown_origin_outside_testing(): void
    {
        $this->app['env'] = 'production';

        $this->withHeaders(['Origin' => 'https://evil.example'])
            ->postJson('/api/contact', [
                'name' => 'Attacker',
                'email' => 'bad@evil.example',
                'message' => 'Hello',
            ])
            ->assertForbidden();
    }

    public function test_contact_form_strips_html_tags(): void
    {
        $this->postJson('/api/contact', [
            'name' => '<script>alert(1)</script>Jane',
            'email' => 'jane@example.com',
            'message' => '<b>Hello</b> world',
        ])->assertCreated();

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'jane@example.com',
            'name' => 'alert(1)Jane',
            'message' => 'Hello world',
        ]);
    }

    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/login');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
    }
}
