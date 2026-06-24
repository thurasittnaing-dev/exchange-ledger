<script type="module">
    document.addEventListener('DOMContentLoaded', function() {

        function getFilters() {
            return {
                account_id: $('select[name="account_id"]').val() || '',
                reference_type: $('select[name="reference_type"]').val() || '',
                date_from: $('#date_from').val() || '',
                date_to: $('#date_to').val() || '',
            };
        }

        const table = $('#account-balance-histories-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            searching: false,
            order: [[1, 'desc']],

            ajax: {
                url: '{{ route('account_balance_histories.data') }}',
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
                    data: 'account_name',
                    orderable: false
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

            window.location.href = "{{ route('account_balance_histories.export') }}?" + params.toString();
        }

        $('#export-xlsx').on('click', function() {
            excelExport();
        });

    })
</script>
