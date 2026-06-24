<script type="module">
    document.addEventListener('DOMContentLoaded', function() {

        function getFilters() {
            return {
                name: $('#name').val(),
                username: $('#username').val(),
            };
        }

        const table = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            searching: false,

            ajax: {
                url: '{{ route('users.data') }}',
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
                    data: 'full_name'
                },
                {
                    data: 'username'
                },
                {
                    data: 'permission'
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
            drawCallback: function(settings) {
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

            window.location.href = "{{ route('users.export') }}?" + params.toString();
        }

        $('#export-xlsx').on('click', function() {
            excelExport();
        });
    });
</script>
