<div class="text-start modal fade" id="editModal{{ $machineRepair->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Data {{ $machineRepair->dataMesin->no_mesin }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3" method="POST" action="/purchasing/dashboard-waiting-sparepart/{{ $machineRepair->id }}">
          @csrf
          @method('put')
          <div class="col-6">
            <label for="noMesin" class="form-label">Pilih Mesin <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="noMesin" name="noMesin" list="dataMesin" value="{{ $machineRepair->dataMesin->no_mesin }}" readonly disabled/>
            <datalist id="dataMesin">
              @foreach ($machines as $machine)
              <option>{{ $machine->no_mesin }}</option>
              @endforeach
            </datalist>
          </div>
          <div class="col-6">
            <label for="request" class="form-label">Masukan Request</label>
            <input type="text" class="form-control" id="request" name="request" required value="{{ $machineRepair->request }}" readonly disabled>
          </div>
          <div class="col-6">
            <label for="analisa" class="form-label">Analisa</label>
            <input type="text" class="form-control" id="analisa" name="analisa" value="{{ $machineRepair->analisa }}" readonly disabled>
          </div>
          <div class="col-6">
            <label for="pic" class="form-label">Enter PIC</label>
            <input type="text" class="form-control" id="pic" name="pic" value="{{ $machineRepair->pic }}" readonly disabled>
          </div>
          <div class="col-6">
            <label for="prl" class="form-label">Enter PRL</label>
            <input type="date" class="form-control" name="prl" id="prl" value="{{ $machineRepair->prl }}" readonly disabled>
          </div>
          <div class="col-6">
            <label for="po" class="form-label">Enter PO</label>
            <input type="text" class="form-control" id="po" name="po" value="{{ $machineRepair->po }}">
          </div>
          <div class="col-6">
            <label for="kedatangan_po" class="form-label">Enter Kedatangan PO</label>
            <input type="text" class="form-control" id="kedatangan_po" name="kedatangan_po" value="{{ $machineRepair->kedatangan_po }}">
          </div>
          <div class="col-6">
            <label for="sparepart" class="form-label">Sparepart</label>
            <input type="text" class="form-control" id="sparepart" name="sparepart" value="{{ $machineRepair->sparepart }}" readonly disabled>
          </div>
          <div class="col-6">
            <label for="kedatangan_prl" class="form-label">Enter Kedatangan Request PRL</label>
            <input type="date" class="form-control" name="kedatangan_prl" id="kedatangan_prl" value="{{ $machineRepair->kedatangan_prl }}">
          </div>
          <div class="col-6">
            <label for="tgl_kerusakan" class="form-label">Tanggal Kerusakan</label>
            <input type="datetime-local" class="form-control" name="tgl_kerusakan" id="tanggalKerusakan" value="{{ $machineRepair->tgl_kerusakan }}" readonly disabled>
          </div>
          <div class="col-6">
            <label for="status" class="form-label">Pilih Status</label>
            <select class="form-select" aria-label="Default select example" name="status" id="status" disabled>
              <option {{ $machineRepair->status_mesin == "OK Repair (Finish)" ? "selected" : "" }} value="OK Repair (Finish)">OK Repair (Finish)</option>
              <option {{ $machineRepair->status_mesin == "Waiting Repair" ? "selected" : "" }} value="Waiting Repair">Waiting Repair</option>
              <option {{ $machineRepair->status_mesin == "Waiting Sparepart" ? "selected" : "" }} value="Waiting Sparepart">Waiting Sparepart</option>
              <option {{ $machineRepair->status_mesin == "On Repair" ? "selected" : "" }} value="On Repair">On Repair</option>
            </select>
          </div>
          <div class="col-6">
            <label for="aktivitas" class="form-label">Pilih Status Aktivitas</label>
            <select class="form-select" aria-label="Default select example" name="aktivitas" id="aktivitas" disabled>
              <option value="Running" {{ $machineRepair->status_aktifitas == 'Running' ? 'selected' : '' }}>Running</option>
              <option value="Stop" {{ $machineRepair->status_aktifitas == 'Stop' ? 'selected' : '' }}>Stop</option>
            </select>
          </div>
          <div class="col-6">
            <label for="aksi" class="form-label">Aksi</label>
            <input type="text" class="form-control" id="aksi" name="aksi" value="{{ $machineRepair->aksi }}" readonly disabled>
          </div>
          <div class="col-6">
            <label for="bagian_rusak" class="form-label">Bagian yang Rusak</label>
            <input type="text" class="form-control" id="bagian_rusak" name="bagian_rusak" value="{{ $machineRepair->bagian_rusak }}" readonly disabled>
          </div>
          <div class="col-6">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control" style="height: 100px" readonly disabled>{{ $machineRepair->deskripsi }}</textarea>
          </div>
          <div class="col-6">
            <label for="sebab" class="form-label">Sebab</label>
            <textarea name="sebab" id="sebab" class="form-control" style="height: 100px" readonly disabled>{{ $machineRepair->sebab }}</textarea>
          </div>
          <div class="text-center">
            <button type="reset" class="btn btn-secondary">Reset</button>
            <button type="submit" class="btn btn-warning">Edit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
