<div class="d-flex align-items-center gap-3">

    @if ($user->profile_image)
        <img src="{{ route('users.showProfile', $user) }}" alt="{{ $user->name }}"
            class="rounded-circle border shadow-sm" style="width: 40px; height: 40px; object-fit: cover;" loading="lazy">
    @else
        <img src="{{ asset('assets/images/avater.jpg') }}" alt="{{ $user->name }}"
            class="rounded-circle border shadow-sm" style="width: 40px; height: 40px; object-fit: cover;" loading="lazy">
    @endif

    <div class="lh-sm">
        <div class="fw-semibold text-dark" style="font-size: 0.9rem;">{{ $user->name }}</div>
        <div class="text-secondary mt-1 text-primary" style="font-size: 0.8rem;">
            {{ \App\Enums\Roles::tryFrom($user?->role)?->label() }}</div>
    </div>
</div>
