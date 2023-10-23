<div class="text-start modal fade" id="editTargetIpqc">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Target IPQC</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3" method="POST" action="/quality/home-edit-ipqc">
          @csrf
          @method('put')
          <div class="col-12">
            <label for="cam" class="form-label">CAM</label>
            <input type="number" class="form-control" id="cam" name="target_cam_ipqc" value="{{ $historyQuality->target_cam_ipqc }}">
          </div>
          <div class="col-12">
            <label for="cnc" class="form-label">CNC</label>
            <input type="number" class="form-control" id="cnc" name="target_cnc_ipqc" value="{{ $historyQuality->target_cnc_ipqc }}">
          </div>
          <div class="col-12">
            <label for="mfg2" class="form-label">MFG2</label>
            <input type="number" class="form-control" id="mfg2" name="target_mfg_ipqc" value="{{ $historyQuality->target_mfg_ipqc }}">
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
