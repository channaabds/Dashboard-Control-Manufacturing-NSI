  {{-- Modal Selesai Data --}}
  <div class="text-start modal fade" id="selesaiModal{{ $machineOnRepair->id }}" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Mesin {{ $machineOnRepair->dataMesin->no_mesin }} Sudah Selesai Diperbaiki ?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Vertical Form Tambah Data -->
          <form class="row g-3" method="POST" action="/dashboard/finish/{{ $machineOnRepair->id }}">
            @csrf
            <input type="hidden" name="id" value="{{ $machineOnRepair->id }}">
            <h5 class="fw-bold text-center">Apakah Mesin {{ $machineOnRepair->dataMesin->no_mesin }} Sudah Selesai Diperbaiki ?</h5>
            <div class="text-center">
                <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Selesai</button>
            </div>
          </form>
          <!-- Vertical Form -->
        </div>
      </div>
    </div>
  </div>
