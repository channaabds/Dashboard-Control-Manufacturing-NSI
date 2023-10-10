<div class="text-start modal fade" id="selesaiModal{{ $machineRepair->id }}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Mesin {{ $machineRepair->dataMesin->no_mesin }} Sudah Selesai Diperbaiki ?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3" method="POST" action="/dashboard-repair/finish/{{ $machineRepair->id }}">
          @csrf
          <input type="hidden" name="id" value="{{ $machineRepair->id }}">
          <h5 class="fw-bold text-center">Apakah Mesin {{ $machineRepair->dataMesin->no_mesin }} Sudah Selesai Diperbaiki ?</h5>
          <div class="text-center">
            <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Batal</button>
            <button type="submit" class="btn btn-primary">Selesai</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
