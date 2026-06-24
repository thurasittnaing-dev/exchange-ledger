<div>
    <small class="text-dark">{{ dateFormat($data->created_at, true) }}</small>
    <div>
        <small class="text-muted">{{ $data->created_at->diffForHumans() }}</small>
    </div>
</div>
