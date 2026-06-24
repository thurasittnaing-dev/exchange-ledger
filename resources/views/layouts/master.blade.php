<div style="width:20rem">
    <div class="mb-3">
        <form action="{{ route('users.impersonate') }}" method="POST" id="impersonate_form">
            @csrf
            <select name="account_id" id="master_select" class="form-control lib-s2">
                <option value="">Choose Account</option>

                @foreach (getUsers() as $key => $account)
                    <option value="{{ $account->id }}" {{ $account->id == $user->id ? 'selected' : '' }}>
                        {{ $account->name }} ({{ \App\Enums\Roles::tryFrom($account->role)?->label() }})
                    </option>
                @endforeach
            </select>
        </form>
    </div>
</div>
