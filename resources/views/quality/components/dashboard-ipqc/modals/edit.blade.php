<div class="text-start modal fade" id="editModal{{ $d->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Data {{ $d->no_ncr_lot }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3" method="POST" action="/quality/dashboard-ipqc/{{ $d->id }}">
          @csrf
          @method('put')
          <input type="hidden" name="id" value="{{ $d->id }}">
          <div class="col-6">
            <label for="no_ncr_lot" class="form-label">No NCR/Lot Tag</label>
            <input type="text" class="form-control" id="no_ncr_lot" name="no_ncr_lot" value="{{ $d->no_ncr_lot }}">
        </div>
          <div class="col-6">
              <label for="part_no" class="form-label">Part No</label>
              <input type="text" class="form-control" id="part_no" name="part_no" value="{{ $d->part_no }}">
          </div>
          <div class="col-6">
              <label for="lot_no" class="form-label">Lot No</label>
              <input type="text" class="form-control" id="lot_no" name="lot_no" value="{{ $d->lot_no }}">
          </div>
          <div class="col-6">
              <label for="mesin" class="form-label">Mesin</label>
              <input type="text" class="form-control" id="mesin" name="mesin" value="{{ $d->mesin }}">
          </div>
          <div class="col-6">
              <label for="status" class="form-label">Status</label>
              <select class="form-select" aria-label="Default select example" name="status" id="status">
                  <option value="CLOSE" {{ $d->status == "CLOSE" ? "selected" : "" }}>CLOSE</option>
                  <option value="OPEN" {{ $d->status == "OPEN" ? "selected" : "" }}>OPEN</option>
              </select>
          </div>
          <div class="col-6">
              <label for="keterangan" class="form-label">Ket</label>
              <select class="form-select" aria-label="Default select example" name="keterangan" id="keterangan">
                  <option value="NCR" {{ $d->keterangan == "NCR" ? "selected" : "" }}>NCR</option>
                  <option value="LOT TAG" {{ $d->keterangan == "LOT TAG" ? "selected" : "" }}>LOT TAG</option>
              </select>
          </div>
          <div class="col-6">
              <label for="defect" class="form-label">Defect</label>
              <input type="text" class="form-control" name="defect" id="defect" value="{{ $d->defect }}">
          </div>
          <div class="col-6">
              <label for="standard" class="form-label">Standard</label>
              <input type="text" class="form-control" name="standard" id="standard" value="{{ $d->standard }}">
          </div>
          <div class="col-6">
              <label for="actual" class="form-label">Actual</label>
              <input type="text" class="form-control" id="actual" name="actual" value="{{ $d->actual }}">
          </div>
          <div class="col-6">
              <label for="sampling" class="form-label">Sampling Qty</label>
              <input type="text" class="form-control" id="sampling" name="sampling" value="{{ $d->sampling }}">
          </div>
          <div class="col-6">
              <label for="qty_check" class="form-label">Qty Check</label>
              <input type="number" class="form-control" id="qty_check" name="qty_check" value="{{ $d->qty_check }}">
          </div>
          <div class="col-6">
              <label for="ng" class="form-label">NG</label>
              <input type="number" class="form-control" id="ng" name="ng" value="{{ $d->ng }}">
          </div>
          <div class="col-6">
              <label for="ng_pic" class="form-label">NG PIC</label>
              <input type="text" class="form-control" id="ng_pic" name="ng_pic" value="{{ $d->ng_pic }}">
          </div>
          <div class="col-6">
              <label for="approve_pic" class="form-label">Approve PIC</label>
              <input type="text" class="form-control" id="approve_pic" name="approve_pic" value="{{ $d->approve_pic }}">
          </div>
          <div class="col-6">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" value="{{ $d->date }}">
          </div>
          <div class="col-6">
              <label for="deadline" class="form-label">Deadline</label>
              <input type="date" class="form-control" id="deadline" name="deadline" value="{{ $d->deadline }}">
          </div>
          <div class="col-6">
              <label for="pic_input" class="form-label">PIC Input</label>
              <input type="text" class="form-control" id="pic_input" name="pic_input" value="{{ $d->pic_input }}">
          </div>
          <div class="col-6">
              <label for="judgement" class="form-label">Judgement</label>
              <input type="text" class="form-control" id="judgement" name="judgement" value="{{ $d->judgement }}">
          </div>
          <div class="col-6">
              <label for="penyebab" class="form-label">Penyebab</label>
              <textarea name="penyebab" id="penyebab" class="form-control" style="height: 100px">{{ $d->penyebab }}</textarea>
          </div>
          <div class="col-6">
              <label for="action" class="form-label">Action</label>
              <textarea name="action" id="action" class="form-control" style="height: 100px">{{ $d->action }}</textarea>
          </div>
          <div class="col-12 text-center">
              <label for="pembahasan" class="form-label">Pembahasan</label>
              <input type="text" class="form-control" id="pembahasan" name="pembahasan" value="{{ $d->pembahasan }}">
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
