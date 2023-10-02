<script>
    function monthFilter(request) {

        $.ajax({
            url: '/get-total-downtime-by-month',
            method: 'POST',
            data: {filter: request},
            success: function (response) {
                var monthDisplayRef = document.querySelector('#monthFilter');
                var totalDTDisplayRef = document.querySelector('#totalDowntime');
                monthDisplayRef.innerHTML = request;
                totalDTDisplayRef.innerHTML = response;
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error('Error: ' + error);
            }
        })
        // console.log(request);
    }
    // function monthFilter(request) {
    //     var monthDisplayRef = document.querySelector('#monthFilter')
    //     if (request == 'bulanIni') {
    //         monthDisplayRef.innerHTML = 'Bulan Ini';

    //         $.ajax({
    //             url: '/get-total-downtime-by-month',
    //             method: 'POST',
    //             data: {filter: 'bulan ini'},
    //             success: function (response) {
    //                 console.log(response);
    //             },
    //             error: function(xhr, status, error) {
    //                 console.error('Error: ' + error);
    //             }
    //         })
    //     }

    //     if (request == 'bulanLalu') {
    //         monthDisplayRef.innerHTML = 'Bulan Lalu';
    //         $.ajax({
    //             url: '/get-total-downtime-by-month',
    //             method: 'POST',
    //             data: {filter: 'bulan lalu'},
    //             success: function (response) {
    //                 console.log(response);
    //             },
    //             error: function(xhr, status, error) {
    //                 console.error('Error: ' + error);
    //             }
    //         })
    //     }

    //     if (request == 'tahunIni') {
    //         monthDisplayRef.innerHTML = 'Tahun Ini';
    //         $.ajax({
    //             url: '/get-total-downtime-by-month',
    //             method: 'POST',
    //             data: {filter: 'tahun ini'},
    //             success: function (response) {
    //                 console.log(response);
    //             },
    //             error: function(xhr, status, error) {
    //                 console.error('Error: ' + error);
    //             }
    //         })
    //     }
    // }
</script>
