<div class="text-start modal fade" id="editTargetQuality">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Target Quality</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3" method="POST" action="/quality/home-edit-ipqc">
          @csrf
          @method('put')
          <div class="col-6">
            <h5>IPQC</h5>
            <div class="col-12">
              <label for="camIpqc" class="form-label">CAM</label>
              <input type="number" class="form-control" id="camIpqc" name="target_cam_ipqc" value="{{ $targetQuality->target_cam_ipqc }}">
            </div>
            <div class="col-12">
              <label for="cncIpqc" class="form-label">CNC</label>
              <input type="number" class="form-control" id="cncIpqc" name="target_cnc_ipqc" value="{{ $targetQuality->target_cnc_ipqc }}">
            </div>
            <div class="col-12">
              <label for="mfgIpqc" class="form-label">MFG2</label>
              <input type="number" class="form-control" id="mfgIpqc" name="target_mfg_ipqc" value="{{ $targetQuality->target_mfg_ipqc }}">
            </div>
          </div>
          <div class="col-6">
            <h5>OQC</h5>
            <div class="col-12">
              <label for="camOqc" class="form-label">CAM</label>
              <input type="number" class="form-control" id="camOqc" name="target_cam_oqc" value="{{ $targetQuality->target_cam_oqc }}">
            </div>
            <div class="col-12">
              <label for="cncOqc" class="form-label">CNC</label>
              <input type="number" class="form-control" id="cncOqc" name="target_cnc_oqc" value="{{ $targetQuality->target_cnc_oqc }}">
            </div>
            <div class="col-12">
              <label for="mfgOqc" class="form-label">MFG2</label>
              <input type="number" class="form-control" id="mfgOqc" name="target_mfg_oqc" value="{{ $targetQuality->target_mfg_oqc }}">
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
