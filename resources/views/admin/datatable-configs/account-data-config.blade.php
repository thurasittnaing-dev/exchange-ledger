<script type="module">
    document.addEventListener('DOMContentLoaded', function() {

        function getFilters() {
            return {
                name: $('#name').val() || '',
                bank_type_id: $('select[name="bank_type_id"]').val() || '',
                provider: $('select[name="provider"]').val() || '',
            };
        }

        const table = $('#accounts-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            searching: false,

            ajax: {
                url: '{{ route('accounts.data') }}',
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
                    data: 'bank_type_name'
                },
                {
                    data: 'provider_label'
                },
                {
                    data: 'balance_formatted'
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

            window.location.href = "{{ route('accounts.export') }}?" + params.toString();
        }

        $('#export-xlsx').on('click', function() {
            excelExport();
        });

    })
</script>
