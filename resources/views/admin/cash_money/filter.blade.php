<div class="col-md-4">
    <x-form.select name="reference_type" label="Type" :options="$referenceTypes" empty_label="All Types" class="lib-s2" />
</div>

<div class="col-md-4">
    <x-form.input type="text" name="date_from" label="Date From" placeholder="dd-mm-yyyy" class="lib-flatpickr" />
</div>

<div class="col-md-4">
    <x-form.input type="text" name="date_to" label="Date To" placeholder="dd-mm-yyyy" class="lib-flatpickr" />
</div>
