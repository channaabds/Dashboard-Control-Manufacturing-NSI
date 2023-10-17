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
    }
</script>
