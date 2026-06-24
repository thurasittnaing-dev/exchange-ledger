<script type="module">
    document.addEventListener('DOMContentLoaded', function() {

        function getFilters() {
            return {
                module: $('#module').val(),
                code: $('#code').val(),
                name: $('#name').val(),
            };
        }

        const table = $('#permissions-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            searching: false,

            ajax: {
                url: '{{ route('permissions.data') }}',
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
                    data: 'module'
                },
                {
                    data: 'code'
                },
                {
                    data: 'name'
                },
                {
                    data: 'created_at',
                    orderable: true,
                    searchable: false
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

            window.location.href = "{{ route('permissions.export') }}?" + params.toString();
        }

        $('#export-xlsx').on('click', function() {
            excelExport();
        });
    });
</script>
