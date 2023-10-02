<script>
    function autoUpdate() {
      var currentTime = new Date();
      var years = currentTime.getFullYear();
      var months = currentTime.getMonth() + 1;
      var dates = currentTime.getDate();
      var hours = currentTime.getHours();
      var minutes = currentTime.getMinutes();
      var seconds = currentTime.getSeconds();

      var coba = { tahun: years, bulan: months, tanggal: dates, jam: hours, menit: minutes, detik: seconds };

      var cek = years + '-' + months + '-' + dates + ' ' + hours + ':' + minutes + ':' + seconds;
      var kirim = { now: cek };

      // var data = {
      //   nama: 'John Doe',
      //   email: 'johndoe@example.com'
      // };

      $.ajax({
        url: '/cek', // Ganti dengan URL yang sesuai
        method: 'POST',
        data: kirim,
        success: function(response) {
          // Tangani respons dari server
          console.log(`Downtime diupdate pada ${response}`);
        },
        error: function(xhr, status, error) {
          // Tangani kesalahan jika terjadi
          console.error('Error: ' + error);
        }
      });
    }

    setInterval(autoUpdate, 1000);

</script>
