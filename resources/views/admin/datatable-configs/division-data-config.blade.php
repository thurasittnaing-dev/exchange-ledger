<script type="module">
    document.addEventListener('DOMContentLoaded', function() {  

        function getFilters() {
            return {
                name_en: $('#name_en').val(),
                name_mm: $('#name_mm').val(),
                code: $('#code').val(),
            };
        }

        const table = $('#divisions-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            searching: false,

            ajax: {
                url: '{{ route('divisions.data') }}',
                data: function(d) {
                    return $.extend(d, getFilters());
                },
            },

            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name_en'
                },
                {
                    data: 'name_mm'
                },
                {
                    data: 'code'
                },
                {
                    data: 'actions',
                    orderable: false,
                    searchable: false
                },
            ],

            "drawCallback": function(settings) {
                var response = settings.json;
                if (response) {
                    var filteredCount = response.recordsFiltered;

                    $('#total_count').html(filteredCount);
                }
            }
        });

        $('#search-btn').on('click', function() {
            table.ajax.reload();
        });

        function excelExport() {
            const params = new URLSearchParams({
                ...getFilters()
            });

            window.location.href = "{{ route('divisions.export') }}?" + params.toString();
        }

        $('#export-xlsx').on('click', function() {
            excelExport();
        });

    })
</script>
