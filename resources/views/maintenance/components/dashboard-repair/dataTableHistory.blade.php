<script>
    DataTable.ext.search.push(function(settings, data, dataIndex) {
        let min = minDate.val();
        let max = maxDate.val();

        let date = new Date(data[0]);

        if (
            (min === null && max === null) ||
            (min === null && date <= max) ||
            (min <= date && max === null) ||
            (min <= date && date <= max)
        ) {
            return true;
        }
        return false;
    });

    // Create date inputs
    minDate = new DateTime('#minRusak', {
        format: 'MMMM Do YYYY'
    });
    maxDate = new DateTime('#maxRusak', {
        format: 'MMMM Do YYYY'
    });


    const searchableColumns = [1, 16, 18];
    const table = new DataTable('#tableMesinRusak', {
        paging: false,
        // order: [[1, 'asc']],
        ordering: false,
        info: false,
        initComplete: function() {
            this.api()
                .columns()
                .every(function(index) {
                    let column = this;
                    let titleElement = column.header().textContent;
                    let title = titleElement.textContent;

                    // Create input element
                    if (!searchableColumns.includes(index)) {
                        titleElement.textContent = '';

                        let label = document.createElement('label');
                        // label.textContent = ": ";
                        column.header().appendChild(label);

                        let input = document.createElement('input');
                        input.style.width = '50px';
                        input.placeholder = 'cari...';
                        column.header().appendChild(input);

                        // Event listener for user input
                        input.addEventListener('keyup', () => {
                            if (column.search() !== this.value) {
                                column.search(input.value).draw();
                            }
                        });
                    }
                });
        }
    });

    document.querySelectorAll('#minRusak, #maxRusak').forEach((el) => {
        el.addEventListener('change', () => table.draw());
    });

    // Menyalin nilai dari form filter ke form export
    document.getElementById('minDate').addEventListener('input', function() {
        document.getElementById('minRusak').value = this.value;
    });

    document.getElementById('maxDate').addEventListener('input', function() {
        document.getElementById('maxRusak').value = this.value;
    });

    // Update nilai form export sebelum submit
    document.getElementById('filterForm').addEventListener('submit', function() {
        document.getElementById('minRusak').value = document.getElementById('minDate').value;
        document.getElementById('maxRusak').value = document.getElementById('maxDate').value;
    });


    // $('#filterForm').on('submit', function(e) {
    //     e.preventDefault(); // Mencegah halaman ter-refresh
    //     var formData = $(this).serialize();

    //     $.ajax({
    //         url: $(this).attr('action'),
    //         method: 'GET',
    //         data: formData,
    //         success: function(response) {
    //             // Update bagian yang terpengaruh (misalnya tabel) tanpa mereload halaman
    //             $('#tableMesinRusak').html($(response).find('#tableMesinRusak').html());
    //         }
    //     });
    // });
</script>




{{-- <script>
    let minDate = new DateTime('#minRusak', {
        format: 'YYYY-MM-DD'
    });
    let maxDate = new DateTime('#maxRusak', {
        format: 'YYYY-MM-DD'
    });

    const table = new DataTable('#tableMesinRusak', {
        processing: true,
        serverSide: true,
        ajax: {
            url: '/machine-repair-history', // URL untuk controller
            type: 'POST',
            data: function(d) {
                d.minDate = minDate.val();
                d.maxDate = maxDate.val();
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        },
        columns: [{
                data: 'mesin_id',
                name: 'mesin_id'
            },
            {
                data: 'tipe_mesin',
                name: 'tipe_mesin'
            },
            {
                data: 'tipe_bartop',
                name: 'tipe_bartop'
            },
            {
                data: 'pic',
                name: 'pic'
            },
            {
                data: 'request',
                name: 'request'
            },
            {
                data: 'analisa',
                name: 'analisa'
            },
            {
                data: 'aksi',
                name: 'aksi'
            },
            {
                data: 'sparepart',
                name: 'sparepart'
            },
            {
                data: 'prl',
                name: 'prl'
            },
            {
                data: 'kedatangan_po',
                name: 'kedatangan_po'
            },
            {
                data: 'kedatangan_prl',
                name: 'kedatangan_prl'
            },
            {
                data: 'tgl_kerusakan',
                name: 'tgl_kerusakan'
            },
            {
                data: 'status_mesin',
                name: 'status_mesin'
            },
            {
                data: 'total_downtime',
                name: 'total_downtime'
            },
            {
                data: 'status_aktifitas',
                name: 'status_aktifitas'
            },
            {
                data: 'updated_at',
                name: 'updated_at'
            },
        ]
    });

    $('#minRusak, #maxRusak').on('change', function() {
        table.draw();
    });
</script> --}}






{{-- <script>
    let minDate, maxDate;

    // Initialize DateTime pickers
    document.addEventListener("DOMContentLoaded", function() {
        minDate = new DateTime('#minRusak', {
            format: 'MMMM Do YYYY'
        });
        maxDate = new DateTime('#maxRusak', {
            format: 'MMMM Do YYYY'
        });

        // Menangani event perubahan pada input tanggal
        document.querySelectorAll('#minRusak, #maxRusak').forEach((el) => {
            el.addEventListener('change', function() {
                table.draw(); // Redraw table on date change
            });
        });
    });

    // Define custom search function for DataTable filtering based on date range
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        let min = minDate.val();
        let max = maxDate.val();
        let date = new Date(data[0]); // Assuming the date is in the first column (index 0)

        if (
            (min === null && max === null) ||
            (min === null && date <= max) ||
            (min <= date && max === null) ||
            (min <= date && date <= max)
        ) {
            return true;
        }
        return false;
    });

    // Initialize DataTable
    const searchableColumns = [1, 16, 18];
    const table = new DataTable('#tableMesinRusak', {
        paging: false,
        ordering: false,
        initComplete: function() {
            this.api().columns().every(function(index) {
                let column = this;
                let titleElement = column.header().textContent;

                // Only add search inputs to non-searchable columns
                if (!searchableColumns.includes(index)) {
                    titleElement.textContent = '';
                    let label = document.createElement('label');
                    column.header().appendChild(label);

                    let input = document.createElement('input');
                    input.style.width = '50px';
                    input.placeholder = 'cari...';
                    column.header().appendChild(input);

                    // Event listener for user input
                    input.addEventListener('keyup', () => {
                        if (column.search() !== input.value) {
                            column.search(input.value).draw();
                        }
                    });
                }
            });
        }
    });

    // Event listener untuk tombol export yang mengirimkan data ke kedua controller
    document.getElementById('exportButton').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default form submission

        // Ambil nilai tanggal dari input
        const minDateValue = minDate.val();
        const maxDateValue = maxDate.val();

        // Kirim data ke controller ExportController (untuk export data)
        fetch('/export/machine-repairs', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                body: JSON.stringify({
                    min: minDateValue,
                    max: maxDateValue
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Export successful:', data);
            })
            .catch(error => {
                console.error('Export failed:', error);
            });

        // Kirim data ke controller MachineRepairHistoryController (untuk menampilkan history)
        fetch('/machine-repairs/history', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                body: JSON.stringify({
                    min: minDateValue,
                    max: maxDateValue
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('History data successful:', data);
                // Anda dapat melakukan tindakan lebih lanjut di sini, seperti memperbarui DataTable dengan data terbaru
            })
            .catch(error => {
                console.error('History data failed:', error);
            });
    });
</script> --}}



{{-- <script>
    // Define date inputs
    let minDate = new DateTime('#minRusak', {
        format: 'MMMM Do YYYY'
    });
    let maxDate = new DateTime('#maxRusak', {
        format: 'MMMM Do YYYY'
    });

    // DataTable filtering logic
    DataTable.ext.search.push(function(settings, data, dataIndex) {
        let min = minDate.val();
        let max = maxDate.val();
        let date = new Date(data[0]);

        if (
            (min === null && max === null) ||
            (min === null && date <= max) ||
            (min <= date && max === null) ||
            (min <= date && date <= max)
        ) {
            return true;
        }
        return false;
    });

    const searchableColumns = [1, 16, 18];
    const table = new DataTable('#tableMesinRusak', {
        paging: false,
        ordering: false,
        initComplete: function() {
            this.api()
                .columns()
                .every(function(index) {
                    let column = this;
                    let titleElement = column.header();
                    let title = titleElement.textContent;

                    // Skip non-searchable columns
                    if (!searchableColumns.includes(index)) {
                        titleElement.textContent = '';

                        let label = document.createElement('label');
                        column.header().appendChild(label);

                        let input = document.createElement('input');
                        input.style.width = '50px';
                        input.placeholder = 'cari...';
                        column.header().appendChild(input);

                        // Event listener for input
                        input.addEventListener('keyup', () => {
                            if (column.search() !== input.value) {
                                column.search(input.value).draw();
                            }
                        });
                    }
                });
        }
    });

    // Add event listener for date input changes
    document.querySelectorAll('#minRusak, #maxRusak').forEach((el) => {
        el.addEventListener('change', () => {
            table.draw();

            // Get date values
            let min = minDate.val();
            let max = maxDate.val();

            // Prepare payload
            let payload = {
                min: min,
                max: max,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content') // CSRF token
            };

            // Send data to ExportController
            fetch('/export/machine-repairs', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': payload._token
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('ExportController Response:', data);
                })
                .catch(error => console.error('Error sending to ExportController:', error));

            // Send data to MachineRepairHistoryController
            fetch('/machine-repair-history', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': payload._token
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('MachineRepairHistoryController Response:', data);
                })
                .catch(error => console.error('Error sending to MachineRepairHistoryController:',
                    error));
        });
    });
</script> --}}
