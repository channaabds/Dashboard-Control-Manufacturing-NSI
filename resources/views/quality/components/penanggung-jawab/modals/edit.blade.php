  {{-- Modal Edit Data --}}
  <div class="text-start modal fade" id="editModal{{ $pic->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Data {{ $pic->nama }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Vertical Form Tambah Data -->
          <form class="row g-3" method="POST" action="/list-pic/{{ $pic->id }}">
            @csrf
            @method('put')
            <input type="hidden" name="id" value="{{ $pic->id }}">
            <div class="col-6">
              <label for="nama" class="form-label">Nama</label>
              <input type="text" class="form-control" id="nama" name="nama" required value="{{ $pic->nama }}">
            </div>
            <div class="col-6">
              <label for="departement" class="form-label">Departement</label>
              <input type="text" class="form-control" id="departement" name="departement" required value="{{ $pic->departement }}">
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
