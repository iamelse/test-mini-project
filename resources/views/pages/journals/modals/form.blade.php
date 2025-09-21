<!-- Modal Journal Form -->
<div class="modal fade" id="journalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="journalForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="journalModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="journalId">

                    <div class="mb-3">
                        <label for="ref_no" class="form-label">Ref No</label>
                        <input type="text" class="form-control" id="ref_no" required>
                    </div>

                    <div class="mb-3">
                        <label for="posting_date" class="form-label">Posting Date</label>
                        <input type="date" class="form-control" id="posting_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="memo" class="form-label">Memo</label>
                        <textarea class="form-control" id="memo" rows="2"></textarea>
                    </div>

                    <hr>
                    <h6>Journal Lines</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="linesTable">
                            <thead>
                                <tr class="text-center">
                                    <th style="width:5%">#</th>
                                    <th style="width:35%">Account</th>
                                    <th style="width:25%">Debit</th>
                                    <th style="width:25%">Credit</th>
                                    <th style="width:10%">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-sm btn-success" id="btnAddLine">
                        <i class="bx bx-plus-circle"></i> Add Line
                    </button>
                </div>
                <div class="modal-footer">
                    <span class="me-auto fw-semibold">Total Debit: <span id="totalDebit">0</span> | Total Credit: <span id="totalCredit">0</span></span>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnSave">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let lineIndex = 0;

// Hitung total debit & credit
function updateTotals() {
    let totalDebit = 0, totalCredit = 0;
    $('#linesTable tbody tr').each(function() {
        let debit = parseFloat($(this).find('.line-debit').val()) || 0;
        let credit = parseFloat($(this).find('.line-credit').val()) || 0;
        totalDebit += debit;
        totalCredit += credit;
    });
    $('#totalDebit').text(totalDebit.toLocaleString());
    $('#totalCredit').text(totalCredit.toLocaleString());
}

// Tambah baris line
function addLine(data={account_id:'', debit:0, credit:0}, readonly=false) {
    lineIndex++;
    let row = `<tr data-index="${lineIndex}">
        <td class="text-center">${lineIndex}</td>
        <td><input type="text" class="form-control line-account" value="${data.account_id}" required ${readonly?'readonly':''}></td>
        <td><input type="number" min="0" step="0.01" class="form-control line-debit text-end" value="${data.debit}" ${readonly?'readonly':''}></td>
        <td><input type="number" min="0" step="0.01" class="form-control line-credit text-end" value="${data.credit}" ${readonly?'readonly':''}></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm btn-remove-line" ${readonly?'style="display:none"':''}><i class="bx bx-trash"></i></button>
        </td>
    </tr>`;
    $('#linesTable tbody').append(row);
    updateTotals();
}

// Event tambah & hapus line
$('#btnAddLine').click(()=> addLine());
$('#linesTable').on('click','.btn-remove-line', function(){ $(this).closest('tr').remove(); updateTotals(); });
$('#linesTable').on('input','.line-debit, .line-credit', updateTotals);

// Modal Bootstrap
let journalModal = new bootstrap.Modal(document.getElementById('journalModal'));

// RESET modal saat ditutup
$('#journalModal').on('hidden.bs.modal', function () {
    $('#journalForm')[0].reset();
    $('#linesTable tbody').empty();
    lineIndex = 0;
    $('#ref_no, #posting_date, #memo').prop('readonly', false);
    $('#btnSave, #btnAddLine').show();
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
});

// BUTTON CREATE / ADD NEW
$('#btnAddJournal').click(function(){
    // reset semua
    $('#journalForm')[0].reset();
    $('#journalId').val('');
    $('#linesTable tbody').empty();
    lineIndex = 0;

    $('#journalModalTitle').html("<i class='bx bx-plus-circle'></i> Add Journal");
    $('#ref_no, #posting_date, #memo').prop('readonly', false);
    $('#btnSave, #btnAddLine').show();

    journalModal.show();
});

// Load data untuk Edit / Detail
$('#journalTable').on('click', '.btn-detail, .btn-edit', function(){
    let id = $(this).data('id');
    let isEdit = $(this).hasClass('btn-edit');

    $.get("{{ route('ajax.journals.detail', ':id') }}".replace(':id', id), function(res){
        if(res.status==='success'){
            let data = res.data;
            let formattedDate = data.posting_date ? new Date(data.posting_date).toISOString().split('T')[0] : '';

            $('#journalId').val(data.id);
            $('#ref_no').val(data.ref_no);
            $('#posting_date').val(formattedDate);
            $('#memo').val(data.memo);

            $('#linesTable tbody').empty(); lineIndex=0;
            if(data.lines) data.lines.forEach(line=>{
                addLine({
                    account_id: line.account_id,
                    debit: line.debit,
                    credit: line.credit
                }, !isEdit); // readonly kalau detail
            });

            $('#journalModalTitle').html(isEdit ? "<i class='bx bx-edit-alt'></i> Edit Journal" : "<i class='bx bx-show'></i> View Journal");

            // Header fields & tombol
            $('#ref_no, #posting_date, #memo').prop('readonly', !isEdit);
            if(!isEdit){
                $('#btnSave, #btnAddLine').hide();
            } else {
                $('#btnSave, #btnAddLine').show();
            }

            journalModal.show();
        } else showAlert(res.message,false);
    }).fail(xhr=> showAlert('Server Error: '+(xhr.responseJSON?.message||'Unknown'), false));
});

// Submit form dengan lines
$('#journalForm').submit(function(e){
    e.preventDefault();
    let id = $('#journalId').val();
    let method = id ? 'PUT' : 'POST';
    let url = id ? "{{ route('ajax.journals.edit', ':id') }}".replace(':id', id) : "{{ route('ajax.journals.create') }}";

    let lines = [];
    $('#linesTable tbody tr').each(function(){
        lines.push({
            account_id: $(this).find('.line-account').val(),
            debit: parseFloat($(this).find('.line-debit').val()) || 0,
            credit: parseFloat($(this).find('.line-credit').val()) || 0
        });
    });

    let payload = {
        ref_no: $('#ref_no').val(),
        posting_date: $('#posting_date').val(),
        memo: $('#memo').val(),
        status: 'posted',
        lines: lines
    };

    $.ajax({
        url: url,
        method: method,
        contentType: 'application/json',
        data: JSON.stringify(payload),
        success: function(res){
            showAlert(res.message, res.status==='success');
            if(res.status==='success'){
                table.ajax.reload();
                journalModal.hide();
            }
        },
        error: function(xhr){
            showAlert('Server Error: '+(xhr.responseJSON?.message||'Unknown'), false);
        }
    });
});
</script>
@endpush