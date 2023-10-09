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


  const table = new DataTable('#tableMesinRusak', {
    paging: false,
    ordering: false,
  });

  document.querySelectorAll('#minRusak, #maxRusak').forEach((el) => {
    el.addEventListener('change', () => table.draw());
  });

</script>
