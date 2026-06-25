<script type="module">
    document.addEventListener('DOMContentLoaded', function() {

        function fmt(n) {
            return Number(n || 0).toLocaleString();
        }

        function getFilters() {
            return {
                account_id: $('select[name="account_id"]').val() || '',
                type: $('select[name="type"]').val() || '',
                fee_type: $('select[name="fee_type"]').val() || '',
                date_from: $('#date_from').val() || '',
                date_to: $('#date_to').val() || '',
            };
        }

        function updateSummary(summary) {
            if (!summary) return;

            $('#profit-cash').text(fmt(summary.fee_cash_profit));
            $('#profit-emoney').text(fmt(summary.fee_emoney_profit));
            $('#profit-total').text(fmt(summary.total_profit));

            $('#total_count').text(fmt(summary.count));
            $('#footer-count').text(fmt(summary.count));
            $('#footer-amount').text(fmt(summary.amount));
            $('#footer-fee').text(fmt(summary.fee_amount));
            $('#footer-cash-impact').text(fmt(summary.total_cash_impact));
            $('#footer-emoney-impact').text(fmt(summary.total_emoney_impact));
            $('#footer-cash-profit').text(fmt(summary.fee_cash_profit));
            $('#footer-emoney-profit').text(fmt(summary.fee_emoney_profit));
        }

        const table = $('#transactions-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            order: [[1, 'desc']],

            ajax: {
                url: '{{ route('transactions.data') }}',
                data: function(d) {
                    Object.assign(d, getFilters());
                },
            },

            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'text-muted',
                },
                {
                    data: 'created_at_formatted',
                    name: 'created_at',
                },
                {
                    data: 'account_label',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'type_badge',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'fee_type_label',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'amount_formatted',
                    orderable: false,
                    searchable: false,
                    className: 'text-end fw-medium',
                },
                {
                    data: 'fee_amount_formatted',
                    orderable: false,
                    searchable: false,
                    className: 'text-end',
                },
                {
                    data: 'cash_impact_formatted',
                    orderable: false,
                    searchable: false,
                    className: 'text-end text-success',
                },
                {
                    data: 'emoney_impact_formatted',
                    orderable: false,
                    searchable: false,
                    className: 'text-end text-primary',
                },
                {
                    data: 'fee_cash_profit_formatted',
                    orderable: false,
                    searchable: false,
                    className: 'text-end text-success',
                },
                {
                    data: 'fee_emoney_profit_formatted',
                    orderable: false,
                    searchable: false,
                    className: 'text-end text-primary',
                },
            ],

            drawCallback: function(settings) {
                const response = settings.json;
                if (response) {
                    updateSummary(response.summary);
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
            window.location.href = "{{ route('transactions.export') }}?" + params.toString();
        }

        $('#export-xlsx').on('click', excelExport);
    });
</script>
