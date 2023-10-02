{{-- <script>
    $(document).ready(function() {
      $('#tableMesinFinish').DataTable({
        // responsive: true
      });
  } );
</script> --}}

{{-- <script src="http://code.jquery.com/jquery-2.0.3.min.js" data-semver="2.0.3" data-require="jquery"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/datatables/1.9.4/css/jquery.dataTables_themeroller.css" rel="stylesheet" data-semver="1.9.4" data-require="datatables@*" />
<link href="//cdnjs.cloudflare.com/ajax/libs/datatables/1.9.4/css/jquery.dataTables.css" rel="stylesheet" data-semver="1.9.4" data-require="datatables@*" />
<link href="//cdnjs.cloudflare.com/ajax/libs/datatables/1.9.4/css/demo_table_jui.css" rel="stylesheet" data-semver="1.9.4" data-require="datatables@*" />
<link href="//cdnjs.cloudflare.com/ajax/libs/datatables/1.9.4/css/demo_table.css" rel="stylesheet" data-semver="1.9.4" data-require="datatables@*" />
<link href="//cdnjs.cloudflare.com/ajax/libs/datatables/1.9.4/css/demo_page.css" rel="stylesheet" data-semver="1.9.4" data-require="datatables@*" />
<link data-require="jqueryui@*" data-semver="1.10.0" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.0/css/smoothness/jquery-ui-1.10.0.custom.min.css" />
<script data-require="jqueryui@*" data-semver="1.10.0" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.0/jquery-ui.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/datatables/1.9.4/jquery.dataTables.js" data-semver="1.9.4" data-require="datatables@*"></script> --}}

<script>

  DataTable.ext.search.push(function (settings, data, dataIndex) {
      let min = minDate.val();
      let max = maxDate.val();
      console.log(typeof min);
      console.log(typeof max);

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
    minDate = new DateTime('#min', {
        format: 'MMMM Do YYYY'
    });
    maxDate = new DateTime('#max', {
        format: 'MMMM Do YYYY'
    });


    const table = new DataTable('#tableMesinFinish');

  document.querySelectorAll('#min, #max').forEach((el) => {
      el.addEventListener('change', () => table.draw());
  });

</script>









































{{-- <script>
  const minEl = document.querySelector('#min');
  const maxEl = document.querySelector('#max');

  // Custom range filtering function
  DataTable.ext.search.push(function (settings, data, dataIndex) {
      // let min = parseInt(minEl.value, 10);
      let min = parseInt(minEl.value, 10);
      console.log(typeof min);
      // let min = minEl.value;
      // let max = parseInt(maxEl.value, 10);
      let max = parseInt(maxEl.value, 10);
      // let max = maxEl.value;
      // let age = parseFloat(data[0]) || 0; // use data for the age column
      // let age = parseInt(data[1]); // use data for the age column
      let age = parseFloat(data[0]); // use data for the age column
      // let age = data[1]; // use data for the age column

      let date = new Date(data[0]);
      // if (min == age) {
      //     return true;
      // }

      // return false;
      if (
          (isNaN(min) && isNaN(max)) ||
          (isNaN(min) && date <= max) ||
          (min <= date && isNaN(max)) ||
          (min <= date && date <= max)
      ) {
          return true;
      }

      return false;
    //   if (
    //       (isNaN(min) && isNaN(max)) ||
    //       (isNaN(min) && age <= max) ||
    //       (min <= age && isNaN(max)) ||
    //       (min <= age && age <= max)
    //   ) {
    //       return true;
    //   }

    //   return false;
  });

  const table = new DataTable('#tableMesinFinish');

  // Changes to the inputs will trigger a redraw to update the table
  minEl.addEventListener('input', function () {
      table.draw();
  });
  // minEl.addEventListener('input', function () {
  //     table.draw();
  // });
  maxEl.addEventListener('input', function () {
      table.draw();
  });
</script> --}}
