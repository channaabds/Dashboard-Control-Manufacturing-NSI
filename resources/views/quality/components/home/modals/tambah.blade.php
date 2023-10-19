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
                        <label for="departement" class="form-label">Departement</label>
                        <select class="form-select" aria-label="Default select example" name="departement" id="departement">
                            <option value="IPQC" selected>IPQC</option>
                            <option value="OQC">OQC</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label for="section" class="form-label">Section</label>
                        <select class="form-select" aria-label="Default select example" name="section" id="section">
                            <option value="CAM" selected>CAM</option>
                            <option value="CNC">CNC</option>
                            <option value="MFG2">MFG2</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label for="no_ncr_lot" class="form-label">No NCR/Lot Tag</label>
                        <input type="text" class="form-control" id="no_ncr_lot" name="no_ncr_lot">
                    </div>
                    <div class="col-6">
                        <label for="part_no" class="form-label">Part No</label>
                        <input type="text" class="form-control" id="part_no" name="part_no">
                    </div>
                    <div class="col-6">
                        <label for="lot_no" class="form-label">Lot No</label>
                        <input type="text" class="form-control" id="lot_no" name="lot_no">
                    </div>
                    <div class="col-6">
                        <label for="mesin" class="form-label">Mesin</label>
                        <input type="text" class="form-control" id="mesin" name="mesin"/>
                    </div>
                    <div class="col-6">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" aria-label="Default select example" name="status" id="status">
                            <option value="OPEN" selected>OPEN</option>
                            <option value="CLOSE">CLOSE</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label for="keterangan" class="form-label">Ket</label>
                        <select class="form-select" aria-label="Default select example" name="keterangan" id="keterangan">
                            <option value="NCR" selected>NCR</option>
                            <option value="LOT TAG">LOT TAG</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label for="defect" class="form-label">Defect</label>
                        <input type="text" class="form-control" name="defect" id="defect">
                    </div>
                    <div class="col-6">
                        <label for="standard" class="form-label">Standard</label>
                        <input type="text" class="form-control" name="standard" id="standard">
                    </div>
                    <div class="col-6">
                        <label for="actual" class="form-label">Actual</label>
                        <input type="text" class="form-control" id="actual" name="actual">
                    </div>
                    <div class="col-6">
                        <label for="sampling" class="form-label">Sampling Qty</label>
                        <input type="text" class="form-control" id="sampling" name="sampling">
                    </div>
                    <div class="col-6">
                        <label for="qty_check" class="form-label">Qty Check <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="qty_check" name="qty_check" required>
                    </div>
                    <div class="col-6">
                        <label for="ng" class="form-label">NG <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="ng" name="ng" required>
                    </div>
                    <div class="col-6">
                        <label for="ng_pic" class="form-label">NG PIC</label>
                        <input type="text" class="form-control" id="ng_pic" name="ng_pic">
                    </div>
                    <div class="col-6">
                        <label for="pic_departement" class="form-label">PIC Departement (IPQC/OQC)</label>
                        <input type="text" class="form-control" id="pic_departement" name="pic_departement">
                    </div>
                    <div class="col-6">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date"/>
                    </div>
                    <div class="col-6">
                        <label for="deadline" class="form-label">Deadline</label>
                        <input type="date" class="form-control" id="deadline" name="deadline"/>
                    </div>
                    <div class="col-6">
                        <label for="pic_input" class="form-label">PIC Input</label>
                        <input type="text" class="form-control" id="pic_input" name="pic_input">
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
