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
            <h1>History Data Mesin Rusak</h1>
        </div>

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">

                            <div class="row mb-3">
                                <!-- Kolom Filter -->
                                <div class="col-md-6">
                                    <form action="/maintenance/history" method="get" id="filterForm">
                                        <table border="0" cellspacing="5" cellpadding="5">
                                            <tbody>
                                                <tr>
                                                    <td scope="col">Minimum Date:</td>
                                                    <td scope="col">
                                                        <input type="date" class="form-control" id="minDate"
                                                            name="minDate" value="{{ request('minDate') }}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td scope="col">Maximum Date:</td>
                                                    <td scope="col">
                                                        <input type="date" class="form-control" id="maxDate"
                                                            name="maxDate" value="{{ request('maxDate') }}">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>

                                <!-- Kolom Export -->
                                <div class="col-md-6">
                                    <form action="/export/machine-repairs-history" method="post" id="exportForm">
                                        @csrf
                                        <table border="0" cellspacing="5" cellpadding="5">
                                            <tbody>
                                                <!-- Menyembunyikan inputan Minimum dan Maximum Date -->
                                                <tr style="display: none;">
                                                    <td scope="col">Minimum date:</td>
                                                    <td scope="col">
                                                        <input type="text" class="form-control" id="minRusak"
                                                            name="min" value="{{ request('minDate') }}">
                                                    </td>
                                                </tr>
                                                <tr style="display: none;">
                                                    <td scope="col">Maximum date:</td>
                                                    <td scope="col">
                                                        <input type="text" class="form-control" id="maxRusak"
                                                            name="max" value="{{ request('maxDate') }}">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>

                            <!-- Tombol Filter, Reset Filter, dan Export -->
                            <div class="d-flex justify-content-start mt-3">
                                <button type="submit" form="filterForm" class="btn btn-primary">Filter</button>
                                <a href="/maintenance/history" class="btn btn-secondary ms-2">Reset Filter</a>
                                <button type="submit" form="exportForm" class="btn btn-success ms-2">Export</button>
                            </div>

                            <!-- Tabel Data -->
                            <table class="table table-fixed table-bordered table-striped"
                                style="overflow-x: scroll; display: block; table-layout: fixed; width: 100%;"
                                id="tableMesinRusak">
                                <thead class="mt-4">
                                    <tr>
                                        <th hidden style="width: 10;">search</th>
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
                                        <th scope="col">Tanggal Update</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($machineRepairs as $machineRepair)
                                        <tr>
                                            <td hidden style="width: 10; ">{{ $machineRepair->search }}</td>
                                            <th scope="row">
                                                {{ ($machineRepairs->currentPage() - 1) * $machineRepairs->perPage() + $loop->iteration }}
                                            </th>
                                            <td style="width: 1000px">{{ $machineRepair->dataMesin->no_mesin }}</td>
                                            <td>{{ $machineRepair->dataMesin->tipe_mesin }}</td>
                                            <td>{{ $machineRepair->dataMesin->tipe_bartop }}</td>
                                            <td>{{ $machineRepair->pic }}</td>
                                            <td>{{ $machineRepair->request }}</td>
                                            <td>{{ $machineRepair->analisa }}</td>
                                            <td>{{ $machineRepair->aksi }}</td>
                                            <td>{{ $machineRepair->sparepart }}</td>
                                            <td>{{ $machineRepair->prl }}</td>
                                            <td>{{ $machineRepair->po }}</td>
                                            <td>{{ $machineRepair->kedatangan_po }}</td>
                                            <td>{{ $machineRepair->kedatangan_prl }}</td>
                                            <td>{{ $machineRepair->tgl_kerusakan }}</td>
                                            <td>{{ $machineRepair->status_mesin }}</td>
                                            @php
                                                // Memisahkan total_downtime menjadi komponen
                                                $downtimeParts = explode(':', $machineRepair->total_downtime);
                                                $days = $downtimeParts[0];
                                                $hours = $downtimeParts[1];
                                                $minutes = $downtimeParts[2];
                                                $seconds = $downtimeParts[3];
                                            @endphp

                                            <td id="downtime{{ $machineRepair->id }}"
                                                class="{{ $machineRepair->status_aktifitas == 'Stop' ? 'bg-danger text-light' : 'bg-success text-light' }}">
                                                {{ $days }} hari<br>
                                                {{ $hours }} jam<br>
                                                {{ $minutes }} menit<br>
                                                {{ $seconds }} detik
                                            </td>

                                            <td>{{ $machineRepair->status_aktifitas }}</td>
                                            <td>{{ $machineRepair->updated_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>

                            <div class="pagination-links d-flex justify-content-center mt-3">
                                {!! $machineRepairs->appends(request()->query())->links('pagination::bootstrap-4') !!}
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.js"></script>

    @include('maintenance.components.dashboard-repair.dataTableHistory')

    {{-- @include('run-downtime.index') --}}
@endsection
