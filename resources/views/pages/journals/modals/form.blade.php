<!-- Journal Form Modal -->
<div class="modal fade" id="journalModal" tabindex="-1" aria-labelledby="journalModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="journalForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="journalModalTitle"><i class='bx bx-plus-circle'></i> Add Journal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <input type="hidden" id="journalId" name="id">

          <div class="mb-3">
              <label for="ref_no" class="form-label">Ref No</label>
              <input type="text" class="form-control" id="ref_no" name="ref_no" maxlength="20" required>
          </div>

          <div class="mb-3">
              <label for="posting_date" class="form-label">Posting Date</label>
              <input type="date" class="form-control" id="posting_date" name="posting_date" required>
          </div>

          <div class="mb-3">
              <label for="memo" class="form-label">Memo</label>
              <textarea class="form-control" id="memo" name="memo" rows="3"></textarea>
          </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button id="btnSave" type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>