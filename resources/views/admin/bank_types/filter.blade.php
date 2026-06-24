<div class="col-md-6">
    <x-form.input type="search" name="name" label="Bank Type Name" placeholder="Bank Type Name" />
</div>

<div class="col-md-6">
    <x-form.select name="provider" label="Provider" :options="$providers" empty_label="All Providers" class="lib-s2" />
</div>
