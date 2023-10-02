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
    <h1>Master Data Mesin</h1>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex justify-content-between">
              <h5>Data Mesin</h5>
              <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#tambahData">
                Tambah Data
              </button>
            </div>

            <!-- Table with stripped rows -->
            <table class="table table-bordered table-striped"
              style="" id="tableMesin">
              <thead class="mt-4">
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">No Mesin</th>
                  <th scope="col">Tipe Mesin</th>
                  <th scope="col">Tipe Bartop</th>
                  <th scope="col">Serial Mesin</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($machines as $machine)
                <tr>
                  <th scope="row">{{ $loop->iteration }}</th>
                  <td>{{ $machine->no_mesin }}</td>
                  <td>{{ $machine->tipe_mesin }}</td>
                  <td>{{ $machine->tipe_bartop }}</td>
                  <td>{{ $machine->seri_mesin }}</td>
                  <td class="text-center">
                    <button class="btn btn-warning mb-1" type="button" data-bs-toggle="modal" data-bs-target="#editModal{{ $machine->id }}">Edit</button>
                    @include('components.mesin.modals.edit')
                    <button class="btn btn-danger mb-1" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $machine->id }}">Hapus</button>
                    @include('components.mesin.modals.hapus')
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

@include('components.mesin.modals.tambah')

</main>

{{-- komponen datatable --}}
@include('components.mesin.dataTable')

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