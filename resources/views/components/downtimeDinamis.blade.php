<script>
  async function getDataDowntime() {
    try {
      const response = await $.ajax({
        url: '/downtime',
        method: 'GET',
        dataType: 'json',
      });

      return response;
    } catch (error) {
      throw new Error('Error: ' + error);
    }
  }

  async function setViewDowntime() {
    try {
      const dataDowntime = await getDataDowntime();
      var i = 1;
      dataDowntime.forEach((data) => {
        // console.log(data.downtime);
        if (data.downtime !== null) {
          let downtimeRef = document.querySelector('#downtime' + data.id);
          downtimeRef.innerHTML = data.downtime;
        }
        i++;
      });
    } catch (error) {
      console.error(error);
    }
  }

  setInterval(setViewDowntime, 1000);

</script>
