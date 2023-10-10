<script>
  DataTable.ext.search.push(function (settings, data, dataIndex) {
    let min = minDate.val();
    let max = maxDate.val();

    let date = new Date(data[0]);

    if (
      (min === null && max === null)
      || (min === null && date <= max)
      || (min <= date && max === null)
      || (min <= date && date <= max)
    ) {
      return true;
    }
    return false;
  });

  // Create date inputs
  minDate = new DateTime('#minRusak', { format: 'MMMM Do YYYY' });
  maxDate = new DateTime('#maxRusak', { format: 'MMMM Do YYYY' });


  const searchableColumns = [1, 16, 18];
  const table = new DataTable('#tableMesinRusak', {
    paging: false,
    // order: [[1, 'asc']],
    ordering: false,
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

  document.querySelectorAll('#minRusak, #maxRusak').forEach((el) => {
    el.addEventListener('change', () => table.draw());
  });

</script>
