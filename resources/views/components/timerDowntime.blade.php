<script>

  async function getJsonDowntime() {
    const jsonString = `<?= json_encode($jsMachinesOnRepair) ?>`;
    const jsonArray = JSON.parse(jsonString);
    try {
      const response = await $.ajax({
        url: '/run-downtime',
        method: 'POST',
        data: { data: jsonArray },
      });
      return response;
    } catch (error) {
      throw new Error('Error AJAX: ' + error);
    }
  }

  setInterval(async () => {
    try {
      var jsonDowntime = await getJsonDowntime();
      if (jsonDowntime.status == 'All Machines OK') {
        console.log(jsonDowntime.status);
      } else{
        //mengubah data
        var dataDowntime = Object.entries(jsonDowntime).map(([id, downtime]) => ({id, downtime}));
        dataDowntime.forEach((data) => {
          downtime = data.downtime.split(':');
          hari = parseInt(downtime[0]);
          jam = parseInt(downtime[1]);
          menit = parseInt(downtime[2]);
          detik = parseInt(downtime[3]);

          let downtimeRef = document.querySelector('#downtime' + data.id);
          downtimeRef.innerHTML = `${hari} Hari </br> ${jam} Jam </br> ${menit} Menit </br> ${detik} Detik`;
          downtimeRef.classList.add("bg-danger");
        });
      }
    } catch (error) {
      console.error('Error: ' + error);
    }
  }, 1000);

</script>
