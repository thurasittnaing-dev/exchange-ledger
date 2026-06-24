<script type="module">
    document.addEventListener('DOMContentLoaded', function() {

        function getFilters() {
            return {
                name: $('#name').val() || '',
                provider: $('select[name="provider"]').val() || '',
            };
        }

        const table = $('#bank-types-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            searching: false,

            ajax: {
                url: '{{ route('bank_types.data') }}',
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
                    data: 'name'
                },
                {
                    data: 'provider_label'
                },
                {
                    data: 'actions',
                    orderable: false,
                    searchable: false
                },
            ],

            drawCallback: function(settings) {
                var response = settings.json;
                if (response) {
                    $('#total_count').html(response.recordsFiltered);
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

            window.location.href = "{{ route('bank_types.export') }}?" + params.toString();
        }

        $('#export-xlsx').on('click', function() {
            excelExport();
        });

    })
</script>
