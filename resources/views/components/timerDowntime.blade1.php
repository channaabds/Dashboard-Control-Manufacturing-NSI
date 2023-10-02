<script>
  // async function getDataStartDowntime() {
  //   try {
  //     const response = await $.ajax({
  //       url: '/get-current-downtime',
  //       method: 'GET',
  //       dataType: 'json',
  //     });

  //     return response;
  //   } catch (error) {
  //     throw new Error('Error: ' + error);
  //   }
  // }

  async function getDataStartDowntime() {
    try {
      const response = await $.ajax({
        url: '/get-current-downtime',
        method: 'GET',
        dataType: 'json',
      });

      return response;
      // console.log(response);
    } catch (error) {
      throw new Error('Error: ' + error);
    }
  }

  // async function setStartViewDowntime() {
  //   try {
  //     var dataStartDowntime = await getDataStartDowntime();
  //     Object.keys(dataStartDowntime).forEach((key) => {
  //       var value = dataStartDowntime[key];
  //       let downtimeRef = document.querySelector('#downtime' + key);
  //       downtimeRef.innerHTML = value;
  //     });
  //   } catch (error) {
  //     console.error(error);
  //   }
  // }

  async function setStartViewDowntime() {
    try {
      var dataStartDowntime = await getDataStartDowntime();
      // var value = [];
      var value = Object.keys(dataStartDowntime).map((key) => {
        var valueTmp = dataStartDowntime[key];
        var value1 = valueTmp.split(':');
        return value1;
        // let downtimeRef = document.querySelector('#downtime' + key);
        // downtimeRef.innerHTML = value;
      });
      return value;
    } catch (error) {
      console.error(error);
    }
  }

  // window.onload = async function() {
  //   try {
  //     var result = await getDataStartDowntime();
  //     console.log(result);
  //     Object.keys(result).forEach((key) => {
  //       var value = result[key];
  //       let downtimeRef = document.querySelector('#downtime' + key);
  //       downtimeRef.innerHTML = value;
  //     });
  //   } catch (error) {
  //     console.error(error);
  //   }
  // };


  async function cek() {
    try {
      var result = await getDataStartDowntime();
      // return result;
      var coba = [];
      var cek = [];
      result.forEach((data)=> {
        coba[data.id] = data.downtime;
        return this.hours = data.downtime;
        // cek[coba.id] = coba.split(':');
        // console.log(data);
      });
      // return coba;
    } catch (error) {
      console.error(error);
    }
  }

  var global = 0;

  window.onload = async function() {
    try {
      var result = await getDataStartDowntime();
      var coba = [];
      var cek = [];
      result.forEach((data)=> {
        coba = data.downtime;
         cek.push(coba.split(':'));
        // console.log(data);
      });
      global = cek;
      console.log(cek);
    } catch (error) {
      console.error(error);
    }
  };

  setInterval(() => {
    console.log(global);
  }, 1000);

  // window.setTimeout( async () => {
  //   try {
  //     await setStartViewDowntime;
  //   } catch (error) {
  //     console.error(error);
  //   }
  // },10000);
  // setInterval(async () => {
  //   var coba = await cek();
  //   console.log(coba);
  // }, 1000);
  // setInterval(setStartViewDowntime, 1000);

</script>
