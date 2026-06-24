<script type="module">
    document.addEventListener('DOMContentLoaded', function() {

        function getFilters() {
            return {
                reference_type: $('select[name="reference_type"]').val() || '',
                date_from: $('#date_from').val() || '',
                date_to: $('#date_to').val() || '',
            };
        }

        const table = $('#cash-money-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            searching: false,
            order: [[1, 'desc']],

            ajax: {
                url: '{{ route('cash_money.data') }}',
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
                    data: 'created_at_formatted',
                    name: 'created_at'
                },
                {
                    data: 'reference_type_label',
                    orderable: false
                },
                {
                    data: 'action_label',
                    orderable: false
                },
                {
                    data: 'amount_formatted'
                },
                {
                    data: 'running_balance_formatted'
                },
                {
                    data: 'description'
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

            window.location.href = "{{ route('cash_money.export') }}?" + params.toString();
        }

        $('#export-xlsx').on('click', function() {
            excelExport();
        });

    })
</script>
