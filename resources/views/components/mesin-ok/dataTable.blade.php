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


    const searchableColumns = [1, 15, 18];
    const table = new DataTable('#tableMesinFinish', {
      order: false,
      // order: [[1, 'asc']],
      searching: false,
      initComplete: function () {
        this.api()
          .columns()
          .every(function (index) {
            let column = this;
            let titleElement = column.header().textContent;
            let title = titleElement.textContent;

            // Create input element
            if (!searchableColumns.includes(index)) {
              titleElement.textContent = '';

              let label = document.createElement('label');
              label.textContent = ": ";
              column.header().appendChild(label);

              let input = document.createElement('input');
              input.placeholder = 'cari...';
              // input.placeholder = titleElement;
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
