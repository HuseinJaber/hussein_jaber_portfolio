<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Support\PortfolioSections;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SectionSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_portfolio_api_includes_section_settings(): void
    {
        $response = $this->getJson('/api/portfolio');

        $response->assertOk()
            ->assertJsonStructure([
                'profile' => [
                    'sections' => array_keys(PortfolioSections::defaults()),
                    'section_order',
                    'section_copy' => array_keys(PortfolioSections::defaults()),
                ],
            ]);

        foreach (PortfolioSections::defaults() as $key => $default) {
            $this->assertSame($default, $response->json("profile.sections.{$key}"));
        }

        $this->assertSame(PortfolioSections::defaultOrder(), $response->json('profile.section_order'));
        $this->assertSame(PortfolioSections::defaultCopy()['work']['title'], $response->json('profile.section_copy.work.title'));
    }

    public function test_profile_sections_can_be_disabled(): void
    {
        Profile::current()->update([
            'sections' => array_merge(PortfolioSections::defaults(), ['testimonials' => false]),
        ]);

        $this->getJson('/api/portfolio')
            ->assertOk()
            ->assertJsonPath('profile.sections.testimonials', false)
            ->assertJsonPath('profile.sections.about', true);
    }

    public function test_profile_section_order_can_be_customized(): void
    {
        $customOrder = ['work', 'about', 'contact', 'services', 'skills', 'experience', 'certifications', 'testimonials', 'newsletter'];

        Profile::current()->update([
            'section_order' => $customOrder,
        ]);

        $this->getJson('/api/portfolio')
            ->assertOk()
            ->assertJsonPath('profile.section_order', $customOrder);
    }

    public function test_profile_section_copy_can_be_customized(): void
    {
        $copy = PortfolioSections::defaultCopy();
        $copy['work']['title'] = 'Featured projects';
        $copy['work']['subtitle'] = 'A curated list of client work.';

        Profile::current()->update(['section_copy' => $copy]);

        $this->getJson('/api/portfolio')
            ->assertOk()
            ->assertJsonPath('profile.section_copy.work.title', 'Featured projects')
            ->assertJsonPath('profile.section_copy.work.subtitle', 'A curated list of client work.');
    }
}
