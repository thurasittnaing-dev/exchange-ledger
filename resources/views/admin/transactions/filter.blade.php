<div class="col-md-3">
    <x-form.select name="account_id" label="Account" :options="$accounts" empty_label="All Accounts" class="lib-s2" />
</div>

<div class="col-md-3">
    <x-form.select name="type" label="Type" :options="$types" empty_label="All Types" class="lib-s2" />
</div>

<div class="col-md-3">
    <x-form.select name="fee_type" label="Fee Type" :options="$feeTypes" empty_label="All Fee Types" class="lib-s2" />
</div>

<div class="col-md-3">
    <x-form.input type="text" name="date_from" label="Date From" placeholder="dd-mm-yyyy" class="lib-flatpickr" />
</div>

<div class="col-md-3">
    <x-form.input type="text" name="date_to" label="Date To" placeholder="dd-mm-yyyy" class="lib-flatpickr" />
</div>
