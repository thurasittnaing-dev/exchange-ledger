<script type="module">
    document.addEventListener('DOMContentLoaded', function() {

        function getFilters() {
            return {
                name: $('#name').val(),
                status: $('#status').val(),
            };
        }

        const table = $('#activity-groups-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            searching: false,

            ajax: {
                url: '{{ route('activity_groups.data') }}',
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
                    data: 'is_active',
                },
                {
                    data: 'created_at',
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

            window.location.href = "{{ route('activity_groups.export') }}?" + params.toString();
        }

        $('#export-xlsx').on('click', function() {
            excelExport();
        });

    })
</script>
