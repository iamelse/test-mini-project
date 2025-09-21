@extends('layouts.app')

@section('title', 'Journals')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 fw-semibold mb-0">
                <i class='bx bxs-notepad'></i> Journals
            </h2>
            <button class="btn btn-primary d-flex align-items-center gap-1" id="btnAdd">
                <i class='bx bx-plus-circle'></i> Add Journal
            </button>
        </div>

        <div class="table-responsive">
            <table id="journalTable" class="table table-hover table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-center">
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">Ref No</th>
                        <th style="width: 15%;">Date</th>
                        <th>Memo</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 20%;">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modals --}}
@include('pages.journals.modals.form')
@include('pages.journals.modals.alert')
@include('pages.journals.modals.delete')
@endsection

@push('scripts')
<script>
$(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    let table = $('#journalTable').DataTable({
        processing: true,
        ajax: "{{ route('ajax.journals.list') }}",
        columns: [
            {
                data: null,
                className: "text-center",
                render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
            },
            { data: 'ref_no', className: "text-center fw-bold" },
            { 
                data: 'posting_date',
                className: "text-center",
                render: d => d ? new Date(d).toLocaleDateString() : ''
            },
            { data: 'memo' },
            { 
                data: 'status',
                className: "text-center",
                render: d => `<span class="badge bg-primary text-uppercase">${d}</span>`
            },
            { 
                data: 'id',
                className: "text-center",
                orderable: false,
                searchable: false,
                render: id => `
                    <div class="d-flex justify-content-center gap-1">
                        <button class="btn btn-info btn-sm btn-detail" data-id="${id}" title="Detail">
                            <i class='bx bx-show'></i>
                        </button>
                        <button class="btn btn-warning btn-sm btn-edit" data-id="${id}" title="Edit">
                            <i class='bx bx-edit-alt'></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-delete" data-id="${id}" title="Delete">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>`
            }
        ],
        columnDefs: [
            { targets: [0, 4, 5], orderable: false }
        ]
    });

    let journalModal = new bootstrap.Modal(document.getElementById('journalModal'));
    let alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
    let confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));

    function showAlert(message, isSuccess = true) {
        $('#alertMessage').html(
            `<i class='bx ${isSuccess ? "bx-check-circle text-success" : "bx-x-circle text-danger"} me-2'></i>
             <span>${message}</span>`
        );
        alertModal.show();
        setTimeout(() => alertModal.hide(), 2000);
    }

    function setFormMode(readonly = false) {
        $('#ref_no, #posting_date, #memo').prop('readonly', readonly);
        readonly ? $('#btnSave').addClass('d-none') : $('#btnSave').removeClass('d-none');
    }

    $('#btnAdd').click(function() {
        $('#journalForm')[0].reset();
        $('#journalId').val('');
        $('#journalModalTitle').html("<i class='bx bx-plus-circle'></i> Add Journal");
        setFormMode(false);
        journalModal.show();
    });

    $('#journalTable').on('click', '.btn-detail, .btn-edit', function() {
        let id = $(this).data('id');
        let isEdit = $(this).hasClass('btn-edit');

        $.get("{{ route('ajax.journals.detail', ':id') }}".replace(':id', id), function(res) {
            if (res.status === 'success') {
                let data = res.data;

                // Format posting_date ke YYYY-MM-DD untuk input date
                let formattedDate = '';
                if (data.posting_date) {
                    let d = new Date(data.posting_date);
                    formattedDate = d.toISOString().split('T')[0]; // contoh: 2025-07-01
                }

                $('#journalId').val(data.id ?? '');
                $('#ref_no').val(data.ref_no ?? '');
                $('#posting_date').val(formattedDate);
                $('#memo').val(data.memo ?? '');

                $('#journalModalTitle').html(isEdit 
                    ? "<i class='bx bx-edit-alt'></i> Edit Journal"
                    : "<i class='bx bx-show'></i> View Journal");

                setFormMode(!isEdit);
                journalModal.show();
            } else {
                showAlert(res.message, false);
            }
        }).fail(function(xhr) {
            showAlert('Server Error: ' + (xhr.responseJSON?.message ?? 'Unknown error'), false);
        });
    });

    $('#journalForm').submit(function(e) {
        e.preventDefault();
        let id = $('#journalId').val();
        let method = id ? 'PUT' : 'POST';
        let url = id 
            ? "{{ route('ajax.journals.edit', ':id') }}".replace(':id', id)
            : "{{ route('ajax.journals.create') }}";

        let payload = {
            ref_no: $('#ref_no').val(),
            posting_date: $('#posting_date').val(),
            memo: $('#memo').val(),
            status: 'posted'
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
                    journalModal.hide();
                }
            },
            error: function(xhr) {
                showAlert('Server Error: ' + (xhr.responseJSON?.message ?? 'Unknown error'), false);
            }
        });
    });

    $('#journalTable').on('click', '.btn-delete', function() {
        $('#deleteId').val($(this).data('id'));
        confirmModal.show();
    });

    $('#btnConfirmDelete').click(function() {
        let id = $('#deleteId').val();
        $.ajax({
            url: "{{ route('ajax.journals.delete', ':id') }}".replace(':id', id),
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
@endpush