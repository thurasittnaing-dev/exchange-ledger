<div class="d-flex justify-content-between align-items-center py-3">
    <div>
        {{ $title }}
    </div>

    <div class="d-flex gap-2">
        @isset($actions)
            {{ $actions }}
        @endisset
    </div>
</div>
