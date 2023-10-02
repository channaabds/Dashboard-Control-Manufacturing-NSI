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
    <h1>Master Data PIC</h1>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex justify-content-between">
              <h5>Data PIC</h5>
              @if (auth()->user()->username === 'admin')
                <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#tambahData">
                  Tambah Data
                </button>
              @endif
            </div>

            <!-- Table with stripped rows -->
            <table class="table table-bordered table-striped"
              style="" id="tablePIC">
              <thead class="mt-4">
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Nama</th>
                  <th scope="col">Departement</th>
                  @if (auth()->user()->username === 'admin')
                    <th scope="col">Aksi</th>
                  @endif
                </tr>
              </thead>
              <tbody>
                @foreach ($dataPIC as $pic)
                <tr>
                  <th scope="row">{{ $loop->iteration }}</th>
                  <td>{{ $pic->nama}}</td>
                  <td>{{ $pic->departement}}</td>
                  @if (auth()->user()->username === 'admin')
                    <td class="text-center">
                      <button class="btn btn-warning mb-1" type="button" data-bs-toggle="modal" data-bs-target="#editModal{{ $pic->id }}">Edit</button>
                      @include('components.penanggung-jawab.modals.edit')
                      <button class="btn btn-danger mb-1" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $pic->id }}">Hapus</button>
                      @include('components.penanggung-jawab.modals.hapus')
                    </td>
                  @endif
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

@include('components.penanggung-jawab.modals.tambah')

</main>

{{-- komponen datatable --}}
@include('components.penanggung-jawab.dataTable')

@endsection
