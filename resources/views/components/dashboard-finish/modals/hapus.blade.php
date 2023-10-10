<div class="text-start modal fade" id="deleteModal{{ $machineFinish->id }}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Hapus Data {{ $machineFinish->dataMesin->no_mesin }} ?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3" method="POST" action="/dashboard-finish/{{ $machineFinish->id }}">
          @csrf
          @method('delete')
          <input type="hidden" name="id" value="{{ $machineFinish->id }}">
          <h5 class="fw-bold text-center">Yakin Akan Menghapus Data {{ $machineFinish->dataMesin->no_mesin }} ?</h5>
          <div class="text-center">
            <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Batal</button>
            <button type="submit" class="btn btn-danger">Hapus</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
