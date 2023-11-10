<div class="text-start modal fade" id="editTargetSales">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Target QMP Sales</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3" method="POST" action="/target/update-sales">
          @csrf
          @method('put')
          <input type="hidden" name="id" value="{{ $target->id }}">
          <div class="col-12">
            <div class="col-12">
              <label for="target_qmp" class="form-label">Target QMP sales selama satu tahun</label>
              <input type="number" class="form-control" id="target_qmp" name="target_qmp" value="{{ $target->target_qmp }}">
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
