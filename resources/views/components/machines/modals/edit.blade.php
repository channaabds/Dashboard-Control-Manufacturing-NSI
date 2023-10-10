 <div class="text-start modal fade" id="editModal{{ $machine->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Data {{ $machine->no_mesin }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3" method="POST" action="/machines/{{ $machine->id }}">
          @csrf
          @method('put')
          <input type="hidden" name="id" value="{{ $machine->id }}">
          <div class="col-6">
            <label for="no_mesin" class="form-label">No Mesin</label>
            <input type="text" class="form-control" id="no_mesin" name="no_mesin" required value="{{ $machine->no_mesin }}">
          </div>
          <div class="col-6">
            <label for="tipe_mesin" class="form-label">Tipe Mesin</label>
            <input type="text" class="form-control" id="tipe_mesin" name="tipe_mesin" required value="{{ $machine->tipe_mesin }}">
          </div>
          <div class="col-6">
            <label for="tipe_bartop" class="form-label">Tipe Bartop</label>
            <input type="text" class="form-control" id="tipe_bartop" name="tipe_bartop" required value="{{ $machine->tipe_bartop }}">
          </div>
          <div class="col-6">
            <label for="seri_mesin" class="form-label">Serial Mesin</label>
            <input type="text" class="form-control" id="seri_mesin" name="seri_mesin" required value="{{ $machine->seri_mesin }}">
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
