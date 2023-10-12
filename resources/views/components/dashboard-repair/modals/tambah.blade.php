@inject('carbon', 'Carbon\Carbon')
<div class="modal fade" id="tambahData" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Mesin Rusak</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="POST" action="/dashboard-repair">
                    @csrf
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="stopByProd" name="stopByProd" value="1">
                            <label class="form-check-label" for="stopByProd">
                                Stop by Production?
                            </label>
                        </div>
                    </div>
                    <div class="col-6">
                        <label for="noMesin" class="form-label">Pilih Mesin <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="noMesin" name="noMesin" list="dataMesin" required/>
                        <datalist id="dataMesin">
                            @foreach ($machines as $machine)
                                <option>{{ $machine->no_mesin }}</option>
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-6">
                        <label for="request" class="form-label">Masukan Request <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="request" name="request" required>
                    </div>
                    <div class="col-6">
                        <label for="analisa" class="form-label">Analisa</label>
                        <input type="text" class="form-control" id="analisa" name="analisa">
                    </div>
                    <div class="col-6">
                        <label for="pic" class="form-label">Enter PIC <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="pic" name="pic" required/>
                    </div>
                    <div class="col-6">
                        <label for="prl" class="form-label">Enter PRL</label>
                        <input type="date" class="form-control" name="prl" id="prl">
                    </div>
                    <div class="col-6">
                        <label for="po" class="form-label">Enter PO</label>
                        <input type="text" class="form-control" id="po" name="po">
                    </div>
                    <div class="col-6">
                        <label for="kedatanganPo" class="form-label">Enter Kedatangan PO</label>
                        <input type="text" class="form-control" id="kedatanganPo" name="kedatangan_po">
                    </div>
                    <div class="col-6">
                        <label for="sparepart" class="form-label">Sparepart</label>
                        <input type="text" class="form-control" id="sparepart" name="sparepart">
                    </div>
                    <div class="col-6">
                        <label for="kedatanganPrl" class="form-label">Enter Kedatangan Request PRL</label>
                        <input type="date" class="form-control" name="kedatangan_prl" id="kedatanganPrl">
                    </div>
                    <div class="col-6">
                        <label for="tanggalKerusakan" class="form-label">Tanggal Kerusakan</label>
                        <input type="datetime-local" class="form-control" name="tgl_kerusakan" id="tanggalKerusakan" step="any">
                    </div>
                    <div class="col-6">
                        <label for="inputStatusMesin" class="form-label">Pilih Status <span class="text-danger">*</span></label>
                        <select class="form-select" aria-label="Default select example" name="status_mesin" id="inputStatusMesin" required>
                            <option value="" selected>Status</option>
                            <option value="OK Repair (Finish)">OK Repair (Finish)</option>
                            <option value="Waiting Repair">Waiting Repair</option>
                            <option value="Waiting Sparepart">Waiting Sparepart</option>
                            <option value="On Repair">On Repair</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label for="aktivitas" class="form-label">Pilih Status Aktivitas <span class="text-danger">*</span></label>
                        <select class="form-select" aria-label="Default select example" name="status_aktifitas" id="aktivitas" required>
                            <option value="" selected>Status Aktivitas</option>
                            <option value="Running">Running</option>
                            <option value="Stop">Stop</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label for="aksi" class="form-label">Aksi</label>
                        <input type="text" class="form-control" id="aksi" name="aksi">
                    </div>
                    <div class="col-6">
                        <label for="bagianRusak" class="form-label">Bagian yang Rusak</label>
                        <input type="text" class="form-control" id="bagianRusak" name="bagian_rusak">
                    </div>
                    <div class="col-6">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" style="height: 100px"></textarea>
                    </div>
                    <div class="col-6">
                        <label for="sebab" class="form-label">Sebab</label>
                        <textarea name="sebab" id="sebab" class="form-control" style="height: 100px"></textarea>
                    </div>
                    <div class="col-12 text-center" id="isFinish" style="display: none">
                        <label for="finish" class="form-label">Finish</label>
                        <input type="datetime-local" class="form-control" name="finish" id="finish" value="" step="any">
                    </div>
                    <div class="text-center">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>

                <script>
                    const statusSelect = document.getElementById("inputStatusMesin");
                    const additionalInfoDiv = document.getElementById("isFinish");
                    const input = document.getElementById("finish");

                    statusSelect.addEventListener("change", function () {
                        console.log('berubah');
                        console.log(statusSelect.value);
                        if (statusSelect.value === "OK Repair (Finish)") {
                            additionalInfoDiv.style.display = "block";
                            // input.required = true;
                        } else {
                            additionalInfoDiv.style.display = "none";
                            // input.required = false;
                        }
                    });
                </script>

            </div>
        </div>
    </div>
</div>
