<div class="modal fade" id="coaModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="coaForm">
        <div class="modal-header bg-light">
          <h5 class="modal-title" id="coaModalTitle">
            <i class='bx bx-plus-circle'></i> Add Chart of Account
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="coaId">
            <div class="mb-3">
                <label class="form-label fw-semibold">Code</label>
                <input type="text" id="code" class="form-control" placeholder="Enter code" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Name</label>
                <input type="text" id="name" class="form-control" placeholder="Enter account name" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Normal Balance</label>
                <select id="normal_balance" class="form-select" required>
                    <option value="DR">Debit (DR)</option>
                    <option value="CR">Credit (CR)</option>
                </select>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="is_active" checked>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
        </div>
        <div class="modal-footer border-top">
          <button type="submit" class="btn btn-success d-flex align-items-center gap-1" id="btnSave">
            <i class='bx bx-check-circle'></i> Save
          </button>
          <button type="button" class="btn btn-secondary d-flex align-items-center gap-1" data-bs-dismiss="modal">
            <i class='bx bx-x-circle'></i> Close
          </button>
        </div>
      </form>
    </div>
  </div>
</div>