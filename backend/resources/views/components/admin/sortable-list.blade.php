@props(['method' => 'updateSortOrder'])

<div
    {{ $attributes->merge(['class' => 'space-y-3']) }}
    x-data
    x-init="$nextTick(() => window.initAdminSortable && window.initAdminSortable($el, @this, '{{ $method }}'))"
    data-sortable-list
>
    {{ $slot }}
</div>
