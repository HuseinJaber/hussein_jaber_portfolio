<?php

namespace App\Models;

use App\Models\Concerns\HasCancelled;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class AnalyticsEvent extends Model
{
    use HasCancelled;

    public const TYPE_PAGE_VIEW = 'page_view';

    public const TYPE_SECTION_CLICK = 'section_click';

    public const TYPE_SECTION_VIEW = 'section_view';

    protected $guarded = [];

    protected $casts = [
        'cancelled' => 'boolean',
    ];

    public function scopeSince(Builder $query, ?Carbon $from): Builder
    {
        if ($from) {
            $query->where('created_at', '>=', $from);
        }

        return $query->notCancelled();
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('event_type', $type);
    }
}
