<script>

    function monthFilter() {
        let data = document.querySelector('#filterByMonth');

        $.ajax({
            url: '/get-total-downtime-by-month',
            method: 'POST',
            data: {filter: data.value},
            success: function (response) {
                var totalDTDisplayRef = document.querySelector('#totalDowntime');
                totalDTDisplayRef.innerHTML = response;
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error('Error: ' + error);
            }
        })
    }

</script>
