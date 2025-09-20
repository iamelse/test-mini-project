<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chart of Accounts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex">

    <!-- Sidebar -->
    <nav class="d-flex flex-column bg-dark min-vh-100 p-3" style="width: 240px;">
        <a href="#" class="nav-link text-secondary d-flex align-items-center gap-2">
            <i class='bx bxs-dashboard'></i> Dashboard
        </a>
        <a href="#" class="nav-link active bg-secondary text-white rounded d-flex align-items-center gap-2">
            <i class='bx bxs-book-content'></i> Chart of Accounts
        </a>
        <a href="#" class="nav-link text-secondary d-flex align-items-center gap-2">
            <i class='bx bxs-user'></i> Users
        </a>
        <a href="#" class="nav-link text-secondary d-flex align-items-center gap-2">
            <i class='bx bxs-report'></i> Reports
        </a>
    </nav>

    <!-- Main Content -->
    <div class="d-flex flex-column flex-grow-1">

        <!-- Navbar -->
        <div class="d-flex justify-content-between align-items-center bg-white border-bottom px-3 py-2">
            <span class="fw-bold"><i class='bx bxs-bank'></i> My Finance App</span>
            <div>
                <a href="#" class="text-dark me-3"><i class='bx bx-bell'></i></a>
                <a href="#" class="text-dark"><i class='bx bx-user-circle'></i></a>
            </div>
        </div>

        <!-- Page Content -->
        <div class="p-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h4 fw-semibold mb-0">
                            <i class='bx bxs-chart'></i> Chart of Accounts
                        </h2>
                        <button class="btn btn-primary d-flex align-items-center gap-1" id="btnAdd">
                            <i class='bx bx-plus-circle'></i> Add COA
                        </button>
                    </div>
                    <table id="coaTable" class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Code</th>
                                <th>Name</th>
                                <th width="15%">Normal Balance</th>
                                <th width="10%">Active</th>
                                <th width="20%">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
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

    <!-- Modal Notifikasi -->
    <div class="modal fade" id="alertModal" tabindex="-1">
      <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content text-center">
          <div class="modal-body" id="alertMessage"></div>
        </div>
      </div>
    </div>

    <!-- Modal Konfirmasi Delete -->
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

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
    $(function() {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        let table = $('#coaTable').DataTable({
            processing: true,
            ajax: "{{ route('ajax.chart-of-accounts.list') }}",
            columns: [
                { data: 'id' },
                { data: 'code' },
                { data: 'name' },
                { data: 'normal_balance', render: d => d === 'DR' ? 'Debit' : 'Credit' },
                { data: 'is_active', render: d => d ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' },
                { data: 'id', render: id => `
                    <button class="btn btn-info btn-sm d-flex align-items-center gap-1 btn-detail" data-id="${id}">
                        <i class='bx bx-show'></i> Detail
                    </button>
                    <button class="btn btn-warning btn-sm d-flex align-items-center gap-1 btn-edit" data-id="${id}">
                        <i class='bx bx-edit-alt'></i> Edit
                    </button>
                    <button class="btn btn-danger btn-sm d-flex align-items-center gap-1 btn-delete" data-id="${id}">
                        <i class='bx bx-trash'></i> Delete
                    </button>`
                }
            ]
        });

        let coaModal = new bootstrap.Modal(document.getElementById('coaModal'));
        let alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
        let confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));

        function showAlert(message, isSuccess = true) {
            let color = isSuccess ? "text-success" : "text-danger";
            $('#alertMessage').html(`<i class='bx ${isSuccess ? "bx-check-circle" : "bx-x-circle"}'></i> 
                <span class="${color}">${message}</span>`);
            alertModal.show();
            setTimeout(() => alertModal.hide(), 2000);
        }

        function setFormMode(readonly = false) {
            $('#code, #name').prop('readonly', readonly);
            $('#normal_balance').prop('disabled', readonly);
            $('#is_active').prop('disabled', readonly);
            readonly ? $('#btnSave').hide() : $('#btnSave').show();
        }

        $('#btnAdd').click(function() {
            $('#coaForm')[0].reset();
            $('#coaId').val('');
            $('#coaModalTitle').html("<i class='bx bx-plus-circle'></i> Add Chart of Account");
            setFormMode(false);
            $('#is_active').prop('checked', true);
            coaModal.show();
        });

        $('#coaTable').on('click', '.btn-detail, .btn-edit', function() {
            let id = $(this).data('id');
            let isEdit = $(this).hasClass('btn-edit');

            $.get("{{ route('ajax.chart-of-accounts.detail', ':id') }}".replace(':id', id), function(res) {
                if (res.status === 'success') {
                    let data = res.data;
                    $('#coaId').val(data.id);
                    $('#code').val(data.code);
                    $('#name').val(data.name);
                    $('#normal_balance').val(data.normal_balance);
                    $('#is_active').prop('checked', data.is_active == 1);
                    $('#coaModalTitle').html(isEdit 
                        ? "<i class='bx bx-edit-alt'></i> Edit Chart of Account"
                        : "<i class='bx bx-show'></i> View Chart of Account");
                    setFormMode(!isEdit);
                    coaModal.show();
                } else {
                    showAlert(res.message, false);
                }
            });
        });

        $('#coaForm').submit(function(e) {
            e.preventDefault();
            let id = $('#coaId').val();
            let method = id ? 'PUT' : 'POST';
            let url = id 
                ? "{{ route('ajax.chart-of-accounts.edit', ':id') }}".replace(':id', id)
                : "{{ route('ajax.chart-of-accounts.create') }}";

            let payload = {
                code: $('#code').val(),
                name: $('#name').val(),
                normal_balance: $('#normal_balance').val(),
                is_active: $('#is_active').is(':checked') ? 1 : 0
            };

            $.ajax({
                url: url,
                method: method,
                contentType: 'application/json',
                data: JSON.stringify(payload),
                success: function(res) {
                    showAlert(res.message, res.status === 'success');
                    if (res.status === 'success') {
                        table.ajax.reload();
                        coaModal.hide();
                    }
                },
                error: function(xhr) {
                    showAlert('Server Error: ' + (xhr.responseJSON?.message ?? 'Unknown error'), false);
                }
            });
        });

        $('#coaTable').on('click', '.btn-delete', function() {
            $('#deleteId').val($(this).data('id'));
            confirmModal.show();
        });

        $('#btnConfirmDelete').click(function() {
            let id = $('#deleteId').val();
            $.ajax({
                url: "{{ route('ajax.chart-of-accounts.delete', ':id') }}".replace(':id', id),
                method: 'DELETE',
                success: function(res) {
                    confirmModal.hide();
                    showAlert(res.message, res.status === 'success');
                    if (res.status === 'success') table.ajax.reload();
                },
                error: function(xhr) {
                    confirmModal.hide();
                    showAlert('Server Error: ' + (xhr.responseJSON?.message ?? 'Unknown error'), false);
                }
            });
        });
    });
    </script>
</body>
</html>