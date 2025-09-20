<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">
          <i class="bx bx-trash"></i> Confirm Delete
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <p class="fw-semibold mb-0">Are you sure you want to delete this COA?</p>
        <input type="hidden" id="deleteId">
      </div>
      <div class="modal-footer justify-content-center border-top">
        <button type="button" class="btn btn-secondary d-flex align-items-center gap-1" data-bs-dismiss="modal">
          <i class="bx bx-x-circle"></i> Cancel
        </button>
        <button type="button" class="btn btn-danger d-flex align-items-center gap-1" id="btnConfirmDelete">
          <i class="bx bx-trash"></i> Yes, Delete
        </button>
      </div>
    </div>
  </div>
</div>