<div class="col-md-4">
    <x-form.input type="search" name="name" label="Account Name" placeholder="Account Name" />
</div>

<div class="col-md-4">
    <x-form.select name="bank_type_id" label="Bank Type" :options="$bankTypes" empty_label="All Bank Types" class="lib-s2" />
</div>

<div class="col-md-4">
    <x-form.select name="provider" label="Provider" :options="$providers" empty_label="All Providers" class="lib-s2" />
</div>
