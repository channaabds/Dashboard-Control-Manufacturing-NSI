<div class="text-start modal fade" id="editTargetMaintenance">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Target Maintenance</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3" method="POST" action="/target/update-maintenance">
          @csrf
          @method('put')
          <input type="hidden" name="id" value="{{ $targetMaintenance->id }}">
          <div class="col-12">
            <div class="col-12">
              <label for="target_maintenance" class="form-label">Target downtime selama satu bulan</label>
              <input type="number" class="form-control" id="target_maintenance" name="target_maintenance" value="{{ $targetMaintenance->target_maintenance }}">
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
