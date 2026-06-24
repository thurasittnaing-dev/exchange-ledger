<div class="lh-sm">
    <div class="text-dark" style="font-size: 0.9rem;">
        {{ $user->position?->label() ?? '-' }}
    </div>
    @if ($user->is_temporary_officer)
        <span class="badge rounded-pill bg-label-warning mt-1">ယာယီတာဝန်ခံ</span>
    @endif
</div>
