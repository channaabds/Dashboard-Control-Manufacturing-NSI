<div class="text-start modal fade" id="editModal{{ $d->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Data {{ $d->no_ncr_lot }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="row g-3" method="POST" action="/quality/dashboard-oqc/{{ $d->id }}">
              @csrf
              @method('put')
              <input type="hidden" name="id" value="{{ $d->id }}">
              <div class="col-6">
                  <label for="departement" class="form-label">Departement <span class="text-danger">*</span></label>
                  <select class="form-select" aria-label="Default select example" name="departement" id="departement" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'disabled' }} required>
                      <option value="IPQC" {{ $d->departement == "IPQC" ? "selected" : "" }}>IPQC</option>
                      <option value="OQC" {{ $d->departement == "OQC" ? "selected" : "" }}>OQC</option>
                  </select>
              </div>
              <div class="col-6">
                  <label for="section" class="form-label">Section <span class="text-danger">*</span></label>
                  <select class="form-select" aria-label="Default select example" name="section" id="section" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'disabled' }} required>
                      <option value="CAM" {{ $d->section == "CAM" ? "selected" : "" }}>CAM</option>
                      <option value="CNC" {{ $d->section == "CNC" ? "selected" : "" }}>CNC</option>
                      <option value="MFG2" {{ $d->section == "MFG2" ? "selected" : "" }}>MFG2</option>
                  </select>
              </div>
              <div class="col-6">
                  <label for="no_ncr_lot" class="form-label">No NCR/Lot Tag <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="no_ncr_lot" name="no_ncr_lot" value="{{ $d->no_ncr_lot }}" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }} required>
              </div>
              <div class="col-6">
                  <label for="part_no" class="form-label">Part No <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="part_no" name="part_no" value="{{ $d->part_no }}" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }} required>
              </div>
              <div class="col-6">
                  <label for="lot_no" class="form-label">Lot No <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="lot_no" name="lot_no" value="{{ $d->lot_no }}" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }} required>
              </div>
              <div class="col-6">
                  <label for="mesin" class="form-label">Mesin <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="mesin" name="mesin" value="{{ $d->mesin }}" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }} required>
              </div>
              <div class="col-6">
                  <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                  <select class="form-select" aria-label="Default select example" name="status" id="status" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'disabled' }} required>
                      <option value="OPEN" {{ $d->status == "OPEN" ? "selected" : "" }}>OPEN</option>
                      <option value="CLOSE" {{ $d->status == "CLOSE" ? "selected" : "" }}>CLOSE</option>
                  </select>
              </div>
              <div class="col-6">
                  <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                  <select class="form-select" aria-label="Default select example" name="keterangan" id="keterangan" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'disabled' }} required>
                      <option value="NCR" {{ $d->keterangan == "NCR" ? "selected" : "" }}>NCR</option>
                      <option value="LOT TAG" {{ $d->keterangan == "LOT TAG" ? "selected" : "" }}>LOT TAG</option>
                  </select>
              </div>
              <div class="col-6">
                  <label for="defect" class="form-label">Defect <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="defect" id="defect" value="{{ $d->defect }}" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }} required>
              </div>
              <div class="col-6">
                  <label for="standard" class="form-label">Standard <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="standard" id="standard" value="{{ $d->standard }}" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }} required>
              </div>
              <div class="col-6">
                  <label for="actual" class="form-label">Actual <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="actual" name="actual" value="{{ $d->actual }}" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }} required>
              </div>
              <div class="col-6">
                  <label for="sampling" class="form-label">Sampling Qty <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="sampling" name="sampling" value="{{ $d->sampling }}" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }} required>
              </div>
              <div class="col-6">
                  <label for="qty_check" class="form-label">Qty Check <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" id="qty_check" name="qty_check" value="{{ $d->qty_check }}" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }} required>
              </div>
              <div class="col-6">
                  <label for="ng" class="form-label">NG</label>
                  <input type="number" class="form-control" id="ng" name="ng" value="{{ $d->ng }}" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }}>
              </div>
              {{-- <div class="col-6">
                <label for="ng_pic" class="form-label">DEPARTMENT <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="ng_pic" name="ng_pic" value="{{ $d->departmnet }}" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }} required>
            </div> --}}
              <div class="col-6">
                  <label for="ng_pic" class="form-label">NG PIC <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="ng_pic" name="ng_pic" value="{{ $d->ng_pic }}" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }} required>
              </div>
              <!-- <div class="col-6">
                  <label for="pic_departement" class="form-label">PIC Departement <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="pic_departement" name="pic_departement" value="{{ $d->pic_departement }}" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }} required>
              </div> -->
              <div class="col-6">
                  <label for="leader" class="form-label">LEADER <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="leader" name="leader" value="{{ $d->leader }}" {{ (auth()->user()->departement == 'qc') ? '' : 'readonly disabled' }} required>
              </div>
              <div class="col-6">
                  <label for="shift" class="form-label">SHIFT <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="shift" name="shift" value="{{ $d->shift }}" {{ (auth()->user()->departement == 'qc') ? '' : 'readonly disabled' }} required>
              </div>
              <div class="col-6">
                  <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="date" name="date" value="{{ $d->date }}" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }} required>
              </div>
              <div class="col-6">
                  <label for="deadline" class="form-label">Deadline <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="deadline" name="deadline" value="{{ $d->deadline }}" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }} required>
              </div>
              <div class="col-6">
                  <label for="pic_input" class="form-label">PIC Input <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="pic_input" name="pic_input" value="{{ $d->pic_input }}" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }} required>
              </div>
              <div class="col-6">
                  <label for="judgement" class="form-label">Judgement</label>
                  <input type="text" class="form-control" id="judgement" name="judgement" value="{{ $d->judgement }}" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }}>
              </div>
              <!-- <div class="col-6">
                  <label for="penyebab" class="form-label">Penyebab</label>
                  <textarea name="penyebab" id="penyebab" class="form-control" style="height: 100px" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }}>{{ $d->penyebab }}</textarea>
              </div> -->

              <div class="col-6 text-center">
                  <label for="4m_make" class="form-label">4M WHY MAKE</label>
                  <input type="text" class="form-control" style="height: 100px" id="4m_make" name="4m_make" value="{{ $d->{'4m_make'} }}" {{ (auth()->user()->departement == 'qc') ? '' : 'readonly disabled' }}>
              </div>
              <div class="col-6 text-center">
                  <label for="w_make" class="form-label">WHY MAKE</label>
                  <input type="text" class="form-control" id="penyebab" name="penyebab" style="height: 100px" value="{{ $d->penyebab }}" {{ (auth()->user()->departement == 'qc') ? '' : 'readonly disabled' }}>
              </div>
              <div class="col-6 text-center">
                  <label for="4m_loose" class="form-label">4M WHY LOOSE</label>
                  <input type="text" class="form-control" id="4m_loose" name="4m_loose" style="height: 100px" value="{{ $d->{'4m_loose'} }}" {{ (auth()->user()->departement == 'qc') ? '' : 'readonly disabled' }}>
              </div>
              <div class="col-6 text-center">
                  <label for="w_loose" class="form-label">WHY LOOSE</label>
                  <input type="text" class="form-control" id="w_loose" style="height: 100px" name="w_loose" value="{{ $d->w_loose }}" {{ (auth()->user()->departement == 'qc') ? '' : 'readonly disabled' }}>
              </div>
               <div class="col-12">
                  <label for="action" class="form-label">Action</label>
                  <textarea name="action" id="action" class="form-control" style="height: 60px" {{ ((auth()->user()->departement == 'qc') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }}>{{ $d->action }}</textarea>
              </div>
              <div class="col-12 text-center">
                  <label for="verifikasi_qa" class="form-label">Verifikasi QA</label>
                  <input type="text" class="form-control" id="verifikasi_qa" name="verifikasi_qa" value="{{ $d->verifikasi_qa }}" {{ ((auth()->user()->departement == 'qa') || (auth()->user()->departement == 'it')) ? '' : 'readonly disabled' }}>
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
