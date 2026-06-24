<div class="col-md-3">
    <x-form.input type="search" name="name_en" label="Distinct Name English"
        placeholder="Enter Distinct Name In English" />
</div>

<div class="col-md-3">
    <x-form.input type="search" name="name_mm" label="Distinct Name Myanmar"
        placeholder="Enter Distinct Name In Myanmar" />
</div>

<div class="col-md-3">
    <x-form.input type="search" name="code" label="Code" placeholder="Code" />
</div>

<div class="col-md-3">
    <x-form.select name="division_id" label="Division" :options="$divisions" empty_label="All Divisions" class="lib-s2" />
</div>
