@inject('carbon', 'Carbon\Carbon')
<div class="modal fade" id="tambahData" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Claim NCR / LOT TAG</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="POST" action="/quality/home">
                    @csrf
                    <div class="col-6">
                        <label for="departement" class="form-label">Departement <span class="text-danger">*</span></label>
                        <select class="form-select" aria-label="Default select example" name="departement" id="departement" required>
                            <option value="IPQC" selected>IPQC</option>
                            <option value="OQC">OQC</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label for="section" class="form-label">Section <span class="text-danger">*</span></label>
                        <select class="form-select" aria-label="Default select example" name="section" id="section" required>
                            <option value="CAM" selected>CAM</option>
                            <option value="CNC">CNC</option>
                            <option value="MFG2">MFG2</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label for="no_ncr_lot" class="form-label">No NCR/Lot Tag <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="no_ncr_lot" name="no_ncr_lot" required>
                    </div>
                    <div class="col-6">
                        <label for="part_no" class="form-label">Part No <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="part_no" name="part_no" required>
                    </div>
                    <div class="col-6">
                        <label for="lot_no" class="form-label">Lot No <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="lot_no" name="lot_no" required>
                    </div>
                    <div class="col-6">
                        <label for="mesin" class="form-label">Mesin <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="mesin" name="mesin" required>
                    </div>
                    <div class="col-6">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" aria-label="Default select example" name="status" id="status" required>
                            <option value="OPEN" selected>OPEN</option>
                            <option value="CLOSE">CLOSE</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                        <select class="form-select" aria-label="Default select example" name="keterangan" id="keterangan" required>
                            <option value="NCR" selected>NCR</option>
                            <option value="LOT TAG">LOT TAG</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label for="defect" class="form-label">Defect <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="defect" id="defect" required>
                    </div>
                    <div class="col-6">
                        <label for="standard" class="form-label">Standard <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="standard" id="standard" required>
                    </div>
                    <div class="col-6">
                        <label for="actual" class="form-label">Actual <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="actual" name="actual" required>
                    </div>
                    <div class="col-6">
                        <label for="sampling" class="form-label">Sampling Qty <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="sampling" name="sampling" required>
                    </div>
                    <div class="col-6">
                        <label for="qty_check" class="form-label">Qty Check <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="qty_check" name="qty_check" required>
                    </div>
                    <div class="col-6">
                        <label for="ng" class="form-label">NG</label>
                        <input type="number" class="form-control" id="ng" name="ng">
                    </div>
                    <div class="col-6">
                        <label for="ng_pic" class="form-label">NG PIC <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ng_pic" name="ng_pic" required>
                    </div>
                    <div class="col-6">
                        <label for="pic_departement" class="form-label">PIC Departement <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="pic_departement" name="pic_departement" required>
                    </div>
                    <div class="col-6">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="col-6">
                        <label for="deadline" class="form-label">Deadline <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="deadline" name="deadline" required>
                    </div>
                    <div class="col-6">
                        <label for="pic_input" class="form-label">PIC Input <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="pic_input" name="pic_input" required>
                    </div>
                    <div class="col-6">
                        <label for="judgement" class="form-label">Judgement</label>
                        <input type="text" class="form-control" id="judgement" name="judgement">
                    </div>
                    <div class="col-6">
                        <label for="penyebab" class="form-label">Penyebab</label>
                        <textarea name="penyebab" id="penyebab" class="form-control" style="height: 100px"></textarea>
                    </div>
                    <div class="col-6">
                        <label for="action" class="form-label">Action</label>
                        <textarea name="action" id="action" class="form-control" style="height: 100px"></textarea>
                    </div>
                    <div class="col-12 text-center">
                        <label for="pembahasan" class="form-label">Pembahasan</label>
                        <input type="text" class="form-control" id="pembahasan" name="pembahasan">
                    </div>
                    <div class="text-center">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
