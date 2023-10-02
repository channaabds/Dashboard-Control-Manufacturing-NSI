@extends('layouts.main')

@section('content')
@inject('carbon', 'Carbon\Carbon')
<main id="main" class="main">
  @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif
  <div class="pagetitle">
    <h1>Dashboard Data Mesin OK (Finish)</h1>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <div class="card-title">
              <h5>Data Mesin OK (Finish)</h5>
            </div>

            <table border="0" cellspacing="5" cellpadding="5">
              <form action="/export-mesin-ok" method="post">
                @csrf
                <tbody>
                  <tr>
                    <td scope="col">Minimum date:</td>
                    <td scope="col"><input type="text" id="min" name="min"></td>
                    <td rowspan="2"><button type="submit" class="btn btn-success">Export</button></td>
                  </tr>
                  <tr>
                    <td scope="col">Maximum date:</td>
                    <td scope="col"><input type="text" id="max" name="max"></td>
                  </tr>
                </tbody>
              </form>
            </table>

            <!-- Table with stripped rows -->
            <table class="table table-bordered table-striped"
              style="overflow-x: scroll; display: block; table-layout: fixed; width: 100%;" id="tableMesinFinish">
              <thead class="mt-4">
                <tr>
                  <th hidden style="width: 10; ">search</th>
                  <th scope="col">No</th>
                  <th scope="col">No Mesin</th>
                  <th scope="col">Tipe Mesin</th>
                  <th scope="col">Tipe Bartop</th>
                  <th scope="col">PIC</th>
                  <th scope="col">Request</th>
                  <th scope="col">Analisa</th>
                  <th scope="col">Aksi</th>
                  <th scope="col">Sparepart</th>
                  <th scope="col">PRL</th>
                  <th scope="col">PO</th>
                  <th scope="col">Kedatangan PO</th>
                  <th scope="col">Kedatangan Request PRL</th>
                  <th scope="col">Tgl Kerusakan</th>
                  <th scope="col">Status Mesin</th>
                  <th scope="col">Downtime</th>
                  <th scope="col">Status Aktivitas</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($machinesOnFinish as $machineOnFinish)
                <tr>
                  <td hidden style="width: 10; ">{{ $machineOnFinish->search }}</td>
                  <th scope="row">{{ $loop->iteration }}</th>
                  <td>{{ $machineOnFinish->dataMesin->no_mesin }}</td>
                  <td>{{ $machineOnFinish->dataMesin->tipe_mesin }}</td>
                  <td>{{ $machineOnFinish->dataMesin->tipe_bartop }}</td>
                  <td>{{ $machineOnFinish->pic }}</td>
                  <td>{{ $machineOnFinish->request }}</td>
                  <td>{{ $machineOnFinish->analisa }}</td>
                  <td>{{ $machineOnFinish->action }}</td>
                  <td>{{ $machineOnFinish->sparepart }}</td>
                  <td>{{ $machineOnFinish->prl }}</td>
                  <td>{{ $machineOnFinish->po }}</td>
                  <td>{{ $machineOnFinish->kedatangan_po }}</td>
                  <td>{{ $machineOnFinish->kedatangan_prl }}</td>
                  <td>{{ $machineOnFinish->tgl_kerusakan }}</td>
                  <td>{{ $machineOnFinish->status_mesin }}</td>
                  <td>
                    <?php
                      $totalDowntime = $machineOnFinish->dataDowntime->total_downtime;
                      $array = explode(":", $totalDowntime);
                      echo $array[0] . ' Hari</br>' . $array[1] . ' Jam</br>' . $array[2] . ' Menit</br>' . $array[3] . ' Detik</br>';
                    ?>
                  </td>
                  <td>
                    {{ $machineOnFinish->status_aktifitas }}

                  </td>
                  <td class="text-center">
                    <button class="btn btn-danger mb-1" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $machineOnFinish->id }}">Hapus</button>
                    @include('components.mesin-ok.modals.hapus')
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <!-- End Table with stripped rows -->

          </div>
        </div>

      </div>
    </div>
  </section>

  {{-- @include('components.dashboard.modals.tambah') --}}

</main>
{{-- JQuery dan datatables --}}
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.js"></script>

{{-- komponen datatable --}}
{{-- @include('components.mesin-ok.dataTable') --}}
@include('components.mesin-ok.dataTable')

{{-- <script>
  function refreshDowntime() {
    $.ajax({
      url: '/refresh-downtime',
      method: 'GET',
      dataType: 'json',
      success: function(response1) {
        cek = response;
        // console.log(response);
        // $('#downtime').html(response);
        $.ajax({
          url: '/'
        })
      },
      error: function(xhr, status, error) {
        console.error('Error: ' + error);
      }
    });
  }

  setInterval(refreshDowntime, 1000);
</script> --}}

@endsection
