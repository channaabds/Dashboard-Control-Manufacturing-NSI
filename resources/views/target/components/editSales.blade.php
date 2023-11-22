<div class="text-start modal fade" id="editTargetSales">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Target QMP Sales Selama Satu Tahun</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3" method="POST" action="/target/update-sales">
          @csrf
          @method('put')
          <div class="col-6">
            <div class="col-12">
              <label for="januari" class="form-label">Januari</label>
              <input type="number" class="form-control" id="januari" name="januari" value="{{ $targetSales->januari }}">
            </div>
            <div class="col-12">
              <label for="februari" class="form-label">Februari</label>
              <input type="number" class="form-control" id="februari" name="februari" value="{{ $targetSales->februari }}">
            </div>
            <div class="col-12">
              <label for="maret" class="form-label">Maret</label>
              <input type="number" class="form-control" id="maret" name="maret" value="{{ $targetSales->maret }}">
            </div>
            <div class="col-12">
              <label for="april" class="form-label">April</label>
              <input type="number" class="form-control" id="april" name="april" value="{{ $targetSales->april }}">
            </div>
            <div class="col-12">
              <label for="mei" class="form-label">Mei</label>
              <input type="number" class="form-control" id="mei" name="mei" value="{{ $targetSales->mei }}">
            </div>
            <div class="col-12">
              <label for="juni" class="form-label">Juni</label>
              <input type="number" class="form-control" id="juni" name="juni" value="{{ $targetSales->juni }}">
            </div>
          </div>
          <div class="col-6">
            <div class="col-12">
              <label for="juli" class="form-label">Juli</label>
              <input type="number" class="form-control" id="juli" name="juli" value="{{ $targetSales->juli }}">
            </div>
            <div class="col-12">
              <label for="agustus" class="form-label">Agustus</label>
              <input type="number" class="form-control" id="agustus" name="agustus" value="{{ $targetSales->agustus }}">
            </div>
            <div class="col-12">
              <label for="september" class="form-label">September</label>
              <input type="number" class="form-control" id="september" name="september" value="{{ $targetSales->september }}">
            </div>
            <div class="col-12">
              <label for="oktober" class="form-label">Oktober</label>
              <input type="number" class="form-control" id="oktober" name="oktober" value="{{ $targetSales->oktober }}">
            </div>
            <div class="col-12">
              <label for="november" class="form-label">November</label>
              <input type="number" class="form-control" id="november" name="november" value="{{ $targetSales->november }}">
            </div>
            <div class="col-12">
              <label for="desember" class="form-label">Desember</label>
              <input type="number" class="form-control" id="desember" name="desember" value="{{ $targetSales->desember }}">
            </div>
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
