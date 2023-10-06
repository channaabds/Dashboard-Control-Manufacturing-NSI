{{-- Modal Tambah Data --}}
<div class="text-start modal fade" id="editModal{{ $machineOnRepair->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Data {{ $machineOnRepair->dataMesin->no_mesin }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Vertical Form Tambah Data -->
        <form class="row g-3" method="POST" action="/dashboard/{{ $machineOnRepair->id }}">
          {{-- @csrf --}}
          @method('put')
          <input type="hidden" name="id" value="{{ $machineOnRepair->id }}">
          <div class="col-6">
            <label for="noMesin" class="form-label">Pilih Mesin <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="noMesin" name="noMesin" list="dataMesin" value="{{ $machineOnRepair->dataMesin->no_mesin }}" required/>
            <datalist id="dataMesin">
                @foreach ($machines as $machine)
                <option>{{ $machine->no_mesin }}</option>
                @endforeach
            </datalist>
          </div>
          <div class="col-6">
            <label for="request" class="form-label">Masukan Request</label>
            <input type="text" class="form-control" id="request" name="request" required value="{{ $machineOnRepair->request }}">
          </div>
          <div class="col-6">
            <label for="analisa" class="form-label">Analisa</label>
            <input type="text" class="form-control" id="analisa" name="analisa" value="{{ $machineOnRepair->analisa }}">
          </div>
          <div class="col-6">
            <label for="pic" class="form-label">Enter PIC</label>
            <input type="text" class="form-control" id="pic" name="pic" value="{{ $machineOnRepair->pic }}">
          </div>
          <div class="col-6">
            <label for="prl" class="form-label">Enter PRL</label>
            <input type="date" class="form-control" name="prl" id="prl" value="{{ $machineOnRepair->prl }}">
          </div>
          <div class="col-6">
            <label for="po" class="form-label">Enter PO</label>
            <input type="text" class="form-control" id="po" name="po" value="{{ $machineOnRepair->po }}">
          </div>
          <div class="col-6">
            <label for="kedatanganPo" class="form-label">Enter Kedatangan PO</label>
            <input type="text" class="form-control" id="kedatanganPo" name="kedatanganPo" value="{{ $machineOnRepair->kedatangan_po }}">
          </div>
          <div class="col-6">
            <label for="sparepart" class="form-label">Sparepart</label>
            <input type="text" class="form-control" id="sparepart" name="sparepart" value="{{ $machineOnRepair->sparepart }}">
          </div>
          <div class="col-6">
            <label for="kedatanganPrl" class="form-label">Enter Kedatangan Request PRL</label>
            <input type="date" class="form-control" name="kedatanganPrl" id="kedatanganPrl" value="{{ $machineOnRepair->kedatangan_prl }}">
          </div>
          <div class="col-6">
            <label for="tanggalKerusakan" class="form-label">Tanggal Kerusakan</label>
            <input type="datetime-local" class="form-control" name="tanggalKerusakan" id="tanggalKerusakan" value="{{ $machineOnRepair->tgl_kerusakan }}">
          </div>
          <div class="col-6">
            <label for="status" class="form-label">Pilih Status</label>
            <select class="form-select" aria-label="Default select example" name="status" id="status" required>
              <option value="" disabled selected>Stop by Prod</option>
              <option {{ $machineOnRepair->status_mesin == "OK Repair (Finish)" ? "selected" : "" }} value="OK Repair (Finish)">OK Repair (Finish)</option>
              <option {{ $machineOnRepair->status_mesin == "Waiting Repair" ? "selected" : "" }} value="Waiting Repair">Waiting Repair</option>
              <option {{ $machineOnRepair->status_mesin == "Waiting Sparepart" ? "selected" : "" }} value="Waiting Sparepart">Waiting Sparepart</option>
              <option {{ $machineOnRepair->status_mesin == "On Repair" ? "selected" : "" }} value="On Repair">On Repair</option>
            </select>
          </div>
          <div class="col-6">
            <label for="aktivitas" class="form-label">Pilih Status Aktivitas</label>
            <select class="form-select" aria-label="Default select example" name="aktivitas" id="aktivitas" required>
              <option value="Running" {{ $machineOnRepair->status_aktifitas == 'Running' ? 'selected' : '' }}>Running</option>
              <option value="Stop" {{ $machineOnRepair->status_aktifitas == 'Stop' ? 'selected' : '' }}>Stop</option>
            </select>
          </div>
          <div class="col-6">
            <label for="aksi" class="form-label">Aksi</label>
            <input type="text" class="form-control" id="aksi" name="aksi" value="{{ $machineOnRepair->aksi }}">
          </div>
          <div class="col-6">
            <label for="bagianRusak" class="form-label">Bagian yang Rusak</label>
            <input type="text" class="form-control" id="bagianRusak" name="bagianRusak" value="{{ $machineOnRepair->bagian_rusak }}">
          </div>
          <div class="col-6">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control" style="height: 100px">{{ $machineOnRepair->deskripsi }}</textarea>
          </div>
          <div class="col-6">
            <label for="sebab" class="form-label">Sebab</label>
            <textarea name="sebab" id="sebab" class="form-control" style="height: 100px">{{ $machineOnRepair->sebab }}</textarea>
          </div>
          <div class="text-center">
              <button type="reset" class="btn btn-secondary">Reset</button>
              <button type="submit" class="btn btn-warning">Edit</button>
          </div>
        </form>
        <!-- Vertical Form -->
      </div>
    </div>
  </div>
</div>
