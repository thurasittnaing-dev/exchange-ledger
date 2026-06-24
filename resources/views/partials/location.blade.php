@if (is_null($data?->division_id) && is_null($data?->district_id))
    -
@else
    <div class="text-dark"><small>{{ $data?->division?->name_mm ?? '-' }}</small></div>
    <div>
        @if ($data?->district)
            <small class="text-muted">({{ $data?->district?->name_mm }})</small>
        @endif
    </div>
@endif
