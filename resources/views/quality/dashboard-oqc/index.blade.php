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
    <h1>Dashboard Data claim NCR dan Lot Tag OQC</h1>
  </div>

  <section class="section dashboard">
    <div class="row">
      <div class="col-lg-12">
        {{-- <div class="row">
          <div class="col-xxl-4 col-md-4">
            <div class="card info-card sales-card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                  </li>
                  <li><a type="button" onclick="monthFilter('{{ $carbon->now()->format('F Y') }}')" class="dropdown-item">{{ $carbon->now()->format('F Y') }}</a></li>
                  <li><a type="button" onclick="monthFilter('{{ $carbon->now()->subMonth()->format('F Y') }}')" class="dropdown-item">{{ $carbon->now()->subMonth()->format('F Y') }}</a></li>
                  <li><a type="button" onclick="monthFilter('{{ $carbon->now()->subMonths(2)->format('F Y') }}')" class="dropdown-item">{{ $carbon->now()->subMonths(2)->format('F Y') }}</a></li>
                </ul>
              </div>
              <div class="card-body">
                <h5 class="card-title">Total Downtime <span>| </span><span id="monthFilter">{{ $carbon::now()->format("F Y") }}</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-clock"></i>
                  </div>
                  <div class="ps-3">
                    <h6 class="fs-4" id="totalDowntime">0</h6>
                    <span class="text-success small pt-1 fw-bold">4</span> <span
                      class="text-muted small pt-2 ps-1">Mesin down bulan ini</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xxl-4 col-md-4">
            <div class="card info-card sales-card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                  </li>
                  <li><a type="button" onclick="monthFilter('{{ $carbon->now()->format('F Y') }}')" class="dropdown-item">{{ $carbon->now()->format('F Y') }}</a></li>
                  <li><a type="button" onclick="monthFilter('{{ $carbon->now()->subMonth()->format('F Y') }}')" class="dropdown-item">{{ $carbon->now()->subMonth()->format('F Y') }}</a></li>
                  <li><a type="button" onclick="monthFilter('{{ $carbon->now()->subMonths(2)->format('F Y') }}')" class="dropdown-item">{{ $carbon->now()->subMonths(2)->format('F Y') }}</a></li>
                </ul>
              </div>
              <div class="card-body">
                <h5 class="card-title">Total Downtime <span>| </span><span id="monthFilter">{{ $carbon::now()->format("F Y") }}</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-clock"></i>
                  </div>
                  <div class="ps-3">
                    <h6 class="fs-4" id="totalDowntime">0</h6>
                    <span class="text-success small pt-1 fw-bold">4</span> <span
                      class="text-muted small pt-2 ps-1">Mesin down bulan ini</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xxl-4 col-md-4">
            <div class="card info-card sales-card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                  </li>
                  <li><a type="button" onclick="monthFilter('{{ $carbon->now()->format('F Y') }}')" class="dropdown-item">{{ $carbon->now()->format('F Y') }}</a></li>
                  <li><a type="button" onclick="monthFilter('{{ $carbon->now()->subMonth()->format('F Y') }}')" class="dropdown-item">{{ $carbon->now()->subMonth()->format('F Y') }}</a></li>
                  <li><a type="button" onclick="monthFilter('{{ $carbon->now()->subMonths(2)->format('F Y') }}')" class="dropdown-item">{{ $carbon->now()->subMonths(2)->format('F Y') }}</a></li>
                </ul>
              </div>
              <div class="card-body">
                <h5 class="card-title">Total Downtime <span>| </span><span id="monthFilter">{{ $carbon::now()->format("F Y") }}</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-clock"></i>
                  </div>
                  <div class="ps-3">
                    <h6 class="fs-4" id="totalDowntime">0</h6>
                    <span class="text-success small pt-1 fw-bold">4</span> <span
                      class="text-muted small pt-2 ps-1">Mesin down bulan ini</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> --}}

        {{-- javascript untuk filtering bulan total downtime --}}
        @include('maintenance.components.dashboard-repair.monthFiltering')

        <div class="card">
          <div class="card-body">
            <div class="col-12 card-title d-flex justify-content-between">
              <h5>Data Claim NCR dan Lot Tag OQC</h5>
            </div>

            <table border="0" cellspacing="5" cellpadding="5">
              <form action="/export/oqc" method="POST">
                @csrf
                <tbody>
                  <tr>
                    <td scope="col">Minimum date:</td>
                    <td scope="col"><input type="text" class="form-control" id="minData" name="min"></td>
                    <td rowspan="2"><button type="submit" class="btn btn-primary">Export</button></td>
                  </tr>
                  <tr>
                    <td scope="col">Maximum date:</td>
                    <td scope="col"><input type="text" class="form-control" id="maxData" name="max"></td>
                  </tr>
                </tbody>
              </form>
            </table>


            <table class="table table-fixed table-bordered table-striped" style="overflow-x: scroll; display: block; table-layout: fixed; width: 100%;"
            id="tableDashboardOqc">
              <thead class="mt-4">
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Date</th>
                  <th scope="col">Part No</th>
                  <th scope="col">Lot No</th>
                  <th scope="col">Mesin</th>
                  <th scope="col">Defect</th>
                  <th scope="col">Standard</th>
                  <th scope="col">Actual</th>
                  <th scope="col">Sampling Qty</th>
                  <th scope="col">Qty Check</th>
                  <th scope="col">NG</th>
                  <th scope="col">%</th>
                  <th scope="col">NG PIC</th>
                  <th scope="col">PIC OQC</th>
                  <th scope="col">Penyebab</th>
                  <th scope="col">Action</th>
                  <th scope="col">Deadline</th>
                  <th scope="col">Status</th>
                  <th scope="col">PIC Input</th>
                  <th scope="col">No NCR/Lot Tag</th>
                  <th scope="col">Ket</th>
                  <th scope="col">Judgement</th>
                  <th scope="col">Pembahasan</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data as $d)
                  <tr class="{{ $d->status=='OPEN' ? 'table-danger' : 'table-success' }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $d->date }}</td>
                    <td>{{ $d->part_no }}</td>
                    <td>{{ $d->lot_no }}</td>
                    <td>{{ $d->mesin }}</td>
                    <td>{{ $d->defect }}</td>
                    <td>{{ $d->standard }}</td>
                    <td>{{ $d->actual }}</td>
                    <td>{{ $d->sampling }}</td>
                    <td>{{ $d->qty_check }}</td>
                    <td>{{ $d->ng }}</td>
                    <td>{{ number_format(($d->ng/$d->qty_check)*100, 2) }}</td>
                    <td>{{ $d->ng_pic }}</td>
                    <td>{{ $d->pic_departement }}</td>
                    <td>{{ $d->penyebab }}</td>
                    <td>{{ $d->action }}</td>
                    <td>{{ $d->deadline }}</td>
                    <td>{{ $d->status }}</td>
                    <td>{{ $d->pic_input }}</td>
                    <td>{{ $d->no_ncr_lot }}</td>
                    <td>{{ $d->keterangan }}</td>
                    <td>{{ $d->judgement }}</td>
                    <td>{{ $d->pembahasan }}</td>
                    <td class="text-center">
                      <button class="btn btn-warning mb-1" type="button" data-bs-toggle="modal"
                      data-bs-target="#editModal{{ $d->id }}">Edit</button>
                      @include('quality.components.dashboard-oqc.modals.edit')
                      <button class="btn btn-danger mb-1" type="button" data-bs-toggle="modal"
                      data-bs-target="#deleteModal{{ $d->id }}">Hapus</button>
                      @include('quality.components.dashboard-oqc.modals.hapus')
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>

          </div>
        </div>

      </div>
    </div>
  </section>

</main>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.js"></script>

@include('quality.components.dashboard-oqc.dataTable')

@endsection
