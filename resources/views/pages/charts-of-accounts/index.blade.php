@extends('layouts.app')

@section('title', 'Chart of Accounts')

@section('content')
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
                <tr class="text-center">
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Code</th>
                    <th>Name</th>
                    <th style="width: 15%;">Normal Balance</th>
                    <th style="width: 10%;">Active</th>
                    <th style="width: 20%;">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- Modals --}}
@include('pages.charts-of-accounts.modals.form')
@include('pages.charts-of-accounts.modals.alert')
@include('pages.charts-of-accounts.modals.delete')
@endsection

@push('styles')
<style>
    #coaTable td { vertical-align: middle; }
    #coaTable .btn { padding: 2px 6px; }
</style>
@endpush

@push('scripts')
<script>
$(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    let table = $('#coaTable').DataTable({
        processing: true,
        ajax: "{{ route('ajax.chart-of-accounts.list') }}",
        columns: [
            { data: null, className: "text-center", render: (data,type,row,meta)=>meta.row+meta.settings._iDisplayStart+1 },
            { data: 'code', className: "text-center" },
            { data: 'name' },
            { data: 'normal_balance', className: "text-center", render: d => d==='DR'?'Debit':'Credit' },
            { data: 'is_active', className: "text-center", render: d => d ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' },
            { data: 'id', className: "text-center", orderable: false, searchable: false,
                render: id => `
                <div class="d-flex justify-content-center gap-1">
                    <button class="btn btn-info btn-sm btn-detail" data-id="${id}"><i class='bx bx-show'></i></button>
                    <button class="btn btn-warning btn-sm btn-edit" data-id="${id}"><i class='bx bx-edit-alt'></i></button>
                    <button class="btn btn-danger btn-sm btn-delete" data-id="${id}"><i class='bx bx-trash'></i></button>
                </div>`
            }
        ],
        columnDefs: [{ targets: [0,3,4,5], orderable:false }]
    });

    let coaModal = new bootstrap.Modal(document.getElementById('coaModal'));
    let alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
    let confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));

    function showAlert(message, isSuccess = true){
        let color = isSuccess ? "text-success" : "text-danger";
        $('#alertMessage').html(`<i class='bx ${isSuccess?"bx-check-circle":"bx-x-circle"}'></i> <span class="${color}">${message}</span>`);
        alertModal.show();
        setTimeout(()=>alertModal.hide(),2000);
    }

    // Fungsi set mode form
    function setFormMode(readonly = false){
        $('#code, #name').prop('readonly', readonly);
        $('#normal_balance').prop('disabled', readonly);
        $('#is_active').prop('disabled', readonly);

        // Gunakan prop hidden agar tombol benar-benar hilang
        $('#btnSave').prop('hidden', readonly);
    }

    // Add COA
    $('#btnAdd').click(function(){
        $('#coaForm')[0].reset();
        $('#coaId').val('');
        $('#coaModalTitle').html("<i class='bx bx-plus-circle'></i> Add Chart of Account");
        setFormMode(false);
        $('#is_active').prop('checked', true);
        coaModal.show();
    });

    // Edit / Detail
    $('#coaTable').on('click', '.btn-detail, .btn-edit', function(){
    let id = $(this).data('id');
    let isEdit = $(this).hasClass('btn-edit');

    $.get("{{ route('ajax.chart-of-accounts.detail', ':id') }}".replace(':id', id), function(res){
        if(res.status==='success'){
            let data = res.data;
            $('#coaId').val(data.id);
            $('#code').val(data.code);
            $('#name').val(data.name);
            $('#normal_balance').val(data.normal_balance);
            $('#is_active').prop('checked', data.is_active==1);

            $('#coaModalTitle').html(isEdit 
                ? "<i class='bx bx-edit-alt'></i> Edit Chart of Account"
                : "<i class='bx bx-show'></i> View Chart of Account");

            // **Pastikan ini dipanggil terakhir** setelah semua field diisi
            setFormMode(!isEdit); 
            coaModal.show();
        } else showAlert(res.message,false);
    });
});

    // Submit
    $('#coaForm').submit(function(e){
        e.preventDefault();
        let id = $('#coaId').val();
        let method = id ? 'PUT':'POST';
        let url = id ? "{{ route('ajax.chart-of-accounts.edit', ':id') }}".replace(':id', id) : "{{ route('ajax.chart-of-accounts.create') }}";
        let payload = {
            code: $('#code').val(),
            name: $('#name').val(),
            normal_balance: $('#normal_balance').val(),
            is_active: $('#is_active').is(':checked')?1:0
        };
        $.ajax({
            url: url,
            method: method,
            contentType:'application/json',
            data: JSON.stringify(payload),
            success: function(res){
                showAlert(res.message, res.status==='success');
                if(res.status==='success'){ table.ajax.reload(); coaModal.hide(); }
            },
            error: function(xhr){ showAlert('Server Error: '+(xhr.responseJSON?.message||'Unknown'), false); }
        });
    });

    // Delete
    $('#coaTable').on('click', '.btn-delete', function(){
        $('#deleteId').val($(this).data('id'));
        confirmModal.show();
    });

    $('#btnConfirmDelete').click(function(){
        let id = $('#deleteId').val();
        $.ajax({
            url: "{{ route('ajax.chart-of-accounts.delete', ':id') }}".replace(':id', id),
            method: 'DELETE',
            success: function(res){
                confirmModal.hide();
                showAlert(res.message,res.status==='success');
                if(res.status==='success') table.ajax.reload();
            },
            error:function(xhr){
                confirmModal.hide();
                showAlert('Server Error: '+(xhr.responseJSON?.message||'Unknown'), false);
            }
        });
    });
});
</script>
@endpush