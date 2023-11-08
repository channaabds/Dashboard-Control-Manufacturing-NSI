<script>

    function monthFilter() {
        let data = document.querySelector('#filterByMonth');

        $.ajax({
            url: '/get-total-downtime-by-month',
            method: 'POST',
            data: {filter: data.value},
            success: function (response) {
                var totalDTDisplayRef = document.querySelector('#totalDowntime');
                var totalDTDHoursisplayRef = document.querySelector('#totalDowntimeHours');
                totalDTDisplayRef.innerHTML = response.days;
                totalDTDHoursisplayRef.innerHTML = response.hours;
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error('Error: ' + error);
            }
        })
    }

</script>
