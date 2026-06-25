<div class="col-md-3">
    <x-form.input type="text" name="date_from" label="Date From" placeholder="dd-mm-yyyy"
        class="lib-flatpickr" :value="$filters['date_from']" />
</div>

<div class="col-md-3">
    <x-form.input type="text" name="date_to" label="Date To" placeholder="dd-mm-yyyy"
        class="lib-flatpickr" :value="$filters['date_to']" />
</div>

<div class="col-md-3">
    <x-form.select name="wallet_type" label="Wallet" :options="$options['wallet_types']"
        empty_label="All Wallets" class="lib-s2" :selected="$filters['wallet_type']" />
</div>

<div class="col-md-3">
    <x-form.select name="account_id" label="Account" :options="$options['accounts']"
        empty_label="All Accounts" class="lib-s2" :selected="$filters['account_id']" />
</div>

<div class="col-md-3">
    <x-form.select name="bank_type_id" label="Bank Type" :options="$options['bank_types']"
        empty_label="All Bank Types" class="lib-s2" :selected="$filters['bank_type_id']" />
</div>

<div class="col-md-3">
    <x-form.select name="provider" label="Provider" :options="$options['providers']"
        empty_label="All Providers" class="lib-s2" :selected="$filters['provider']" />
</div>

<div class="col-md-3">
    <x-form.select name="transaction_type" label="Transaction Type" :options="$options['transaction_types']"
        empty_label="All Types" class="lib-s2" :selected="$filters['transaction_type']" />
</div>
