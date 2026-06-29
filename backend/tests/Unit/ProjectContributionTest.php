<?php

namespace Tests\Unit;

use App\Support\ProjectContribution;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ProjectContributionTest extends TestCase
{
    #[DataProvider('sanitizeCases')]
    public function test_sanitize_contribution_areas(array $input, array $expected): void
    {
        $this->assertSame($expected, ProjectContribution::sanitize($input));
    }

    public static function sanitizeCases(): array
    {
        return [
            'frontend only' => [['frontend'], ['frontend']],
            'full stack combo' => [['frontend', 'backend'], ['frontend', 'backend']],
            'drops invalid keys' => [['frontend', 'invalid'], ['frontend']],
            'empty falls back to full stack' => [[], ['frontend', 'backend']],
            'dedupes values' => [['backend', 'backend', 'api'], ['backend', 'api']],
        ];
    }

    public function test_labels_for_areas(): void
    {
        $this->assertSame(
            ['Frontend', 'API'],
            ProjectContribution::labelsFor(['frontend', 'api']),
        );
    }
}
