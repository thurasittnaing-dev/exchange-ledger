<div class="accordion mt-3" id="filter-accordion">
    <div class="card super-rounded accordion-item active mb-3">
        <h2 class="accordion-header" id="headingOne">
            <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionOne"
                aria-expanded="true" aria-controls="accordionOne">
                @if (isset($title))
                    {{ $title }}
                @else
                    Filter
                @endif
            </button>
        </h2>

        <div id="accordionOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <div class="row">
                    {{ $slot }}
                </div>

                <div class="d-flex justify-content-end mt-2 gap-2">
                    <a href="{{ url()->current() }}" class="btn btn-outline-warning">
                        Clear
                    </a>

                    <button type="button" class="btn btn-primary" id="search-btn">
                        <i class="ti ti-search"></i> Search
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
