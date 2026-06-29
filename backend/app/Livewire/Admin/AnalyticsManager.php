<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesCancelledRecords;
use App\Models\AnalyticsEvent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Analytics')]
class AnalyticsManager extends Component
{
    use ManagesCancelledRecords, WithPagination;

    public string $period = '7d';

    protected $queryString = ['period'];

    public function updatedPeriod(): void
    {
        $this->resetPage();
    }

    public function clearOld(): void
    {
        AnalyticsEvent::notCancelled()
            ->where('created_at', '<', now()->subDays(90))
            ->update(['cancelled' => true]);

        session()->flash('status', 'Events older than 90 days were cancelled (kept in backup).');
    }

    private function periodStart(): ?Carbon
    {
        return match ($this->period) {
            'today' => now()->startOfDay(),
            '7d' => now()->subDays(7),
            '30d' => now()->subDays(30),
            default => null,
        };
    }

    public function cancelEvent(int $id): void
    {
        AnalyticsEvent::findOrFail($id)->cancelRecord();
        session()->flash('status', 'Event cancelled.');
    }

    public function restoreEvent(int $id): void
    {
        AnalyticsEvent::cancelledOnly()->findOrFail($id)->restoreRecord();
        session()->flash('status', 'Event restored.');
    }

    public function render()
    {
        $from = $this->periodStart();
        $base = AnalyticsEvent::query()->since($from);

        $pageViews = (clone $base)->ofType(AnalyticsEvent::TYPE_PAGE_VIEW)->count();
        $uniqueVisitors = (clone $base)->distinct()->count('session_id');
        $sectionClicks = (clone $base)->ofType(AnalyticsEvent::TYPE_SECTION_CLICK)->count();
        $sectionViews = (clone $base)->ofType(AnalyticsEvent::TYPE_SECTION_VIEW)->count();

        $sectionStats = (clone $base)
            ->whereIn('event_type', [
                AnalyticsEvent::TYPE_SECTION_CLICK,
                AnalyticsEvent::TYPE_SECTION_VIEW,
            ])
            ->whereNotNull('section')
            ->select(
                'section',
                DB::raw("SUM(CASE WHEN event_type = 'section_click' THEN 1 ELSE 0 END) as clicks"),
                DB::raw("SUM(CASE WHEN event_type = 'section_view' THEN 1 ELSE 0 END) as views"),
            )
            ->groupBy('section')
            ->orderByDesc('clicks')
            ->orderByDesc('views')
            ->get();

        $maxSectionTotal = max(1, $sectionStats->max(fn ($row) => $row->clicks + $row->views) ?? 1);

        $topReferrers = (clone $base)
            ->ofType(AnalyticsEvent::TYPE_PAGE_VIEW)
            ->whereNotNull('referrer')
            ->where('referrer', '!=', '')
            ->select('referrer', DB::raw('COUNT(*) as total'))
            ->groupBy('referrer')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('livewire.admin.analytics-manager', [
            'stats' => [
                ['label' => 'Page views', 'value' => $pageViews, 'hint' => 'Total visits in period'],
                ['label' => 'Unique visitors', 'value' => $uniqueVisitors, 'hint' => 'Distinct sessions'],
                ['label' => 'Section clicks', 'value' => $sectionClicks, 'hint' => 'Nav & anchor clicks'],
                ['label' => 'Section views', 'value' => $sectionViews, 'hint' => 'Scrolled into view'],
            ],
            'sectionStats' => $sectionStats,
            'maxSectionTotal' => $maxSectionTotal,
            'topReferrers' => $topReferrers,
            'events' => $this->cancelledQuery(clone $base)->latest()->paginate(15),
        ]);
    }
}
