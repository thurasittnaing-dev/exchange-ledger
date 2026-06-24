<script type="module">
    document.addEventListener('DOMContentLoaded', function() {

        function getFilters() {
            return {
                name: $('#name').val(),
                code: $('#code').val(),
            };
        }

        const table = $('#sub-expense-categories-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            searching: false,

            ajax: {
                url: '{{ route('expense_categories.sub_expense_categories.data') }}',
                data: function(d) {
                    d.parent_id = '{{ $expenseCategory->id }}';
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
                    data: 'code'
                },
                {
                    data: 'type'
                },
                {
                    data: 'parent_name'
                },
                {
                    data: 'status'
                },
                {
                    data: 'created_at'
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

            window.location.href = "{{ route('expense_categories.export') }}?" + params.toString();
        }

        $('#export-xlsx').on('click', function() {
            excelExport();
        });

    })
</script>
