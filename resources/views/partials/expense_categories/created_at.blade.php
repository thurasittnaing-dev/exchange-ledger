<div>
    <small class="text-dark">{{ dateFormat($expenseCategory->created_at, true) }}</small>
    <div>
        <small class="text-muted">{{ $expenseCategory->created_at->diffForHumans() }}</small>
    </div>
</div>
