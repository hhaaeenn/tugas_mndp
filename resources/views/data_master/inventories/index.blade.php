@extends('layouts.main')

@section('title', $title)
@section('page-title', $title)

@section('content')
<div class="card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span class="fw-bold">Data Inventories</span>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <input type="text" id="filterSearch" class="form-control form-control-sm" placeholder="Kode atau Nama..." style="width: 180px;">
            <select id="filterStock" class="form-select form-select-sm" style="width: 160px;">
                <option value="">Semua Stok</option>
                <option value="available">Tersedia (&gt;0)</option>
                <option value="empty">Habis (=0)</option>
                <option value="low">Rendah (&lt;10)</option>
            </select>
            <button type="button" class="btn btn-primary btn-sm" id="filter-button">
                <i class="bi bi-funnel"></i> Filter
            </button>
            <button type="button" class="btn btn-secondary btn-sm" id="reset-button">
                <i class="bi bi-arrow-clockwise"></i> Reset
            </button>
            <button type="button" class="btn btn-primary btn-sm" id="add-button">
                <i class="bi bi-plus-lg"></i> Tambah
            </button>
            <button type="button" class="btn btn-success btn-sm" id="import-button">
                <i class="bi bi-file-earmark-arrow-up"></i> Import
            </button>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"><i class="bi bi-list-columns"></i></button>
                <ul class="dropdown-menu dropdown-menu-end p-2" id="main-table_colvis">
                    <li class="dropdown-header text-center">Show Table Title</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="main-table" class="table table-bordered table-hover w-100">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@section('modal')
<div class="modal fade" id="add-modal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="main-form">
                @csrf
                <input type="hidden" name="id" id="input-id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode</label>
                        <input type="text" name="code" id="input-code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" id="input-name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stock" id="input-stock" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="price" id="input-price" class="form-control" step="0.01" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="import-modal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="import-modal_title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="import-form">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Upload File</label>
                        <input type="file" class="form-control" id="input-file" name="file" accept=".csv,text/csv" required>
                        <div class="form-text">Format: <b>name,price,stock</b> (kode dibuat otomatis). Baris pertama boleh header.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="submit-import">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
let endpoint = 'inventories';
let dt;

$('#filterStock').select2({
    theme: 'bootstrap-5',
    width: '100%',
    placeholder: 'Semua Stok',
    allowClear: true,
    dropdownParent: $(document.body)
});

drawDatatable();

function drawDatatable() {
    dt = $('#main-table').addClass('nowrap').DataTable({
        dom:
            "<'row mb-3'<'col-md-6'B><'col-md-6'f>>" +
            "<'row'<'col-12'tr>>" +
            "<'row mt-3'<'col-md-5'i><'col-md-4'p><'col-md-3 text-end'l>>",
        buttons: [
            {
                extend: 'excel',
                className: 'btn btn-sm btn-primary border-0',
                text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'pdf',
                className: 'btn btn-sm btn-primary border-0',
                text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                exportOptions: { columns: ':visible' }
            },
            $.extend( true, {}, buttonExportInput, {
                extend: 'print',
                className: 'btn btn-sm btn-primary border-0',
                text: '<i class="bi bi-printer"></i> Print',
                orientation: 'portrait',
                customize: function(win) {
                    $(win.document.body)
                        .find('table')
                        .removeClass('table-dashed')
                        .removeClass('table-hover')
                        .removeClass('digi-dataTable')
                        .removeClass('table-striped');

                    $(win.document.body)
                        .find('table')
                        .addClass('export-print');

                    $(win.document.body)
                        .find('h1')
                        .addClass('title-print');
                }
            }),
        ],
        destroy: true,
        pageLength: 10,
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: false,
        searchDelay: 750,
        ajax: {
            url: BASE_URL + '/api/' + endpoint + '_datatables',
            type: 'POST',
            headers: {
                'Authorization': TOKEN,
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: function (d) {
                let $filter = {
                    search: $('#filterSearch').val(),
                    stock: $('#filterStock').val()
                };
                d.filter = $filter;
            }
        },
        columns: [
            {
                data: 'id',
                name: 'id',
                visible: false
            },
            {
                data: 'code',
                name: 'code'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'stock',
                name: 'stock'
            },
            { 
                data: 'price',
                name: 'price',
                render: function(data) {
                    return formatCurrencyIdr(data);
                }
            },
            { 
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                className: 'text-end'
            }
        ],
        order: [[0, 'desc']],
        initComplete: function(settings, json) {
            let html = '';

            $("#main-table th").each(function(index) {
                html += '<li>';
                html += '    <div class="form-check">';
                html += '        <input class="form-check-input main-toggle-vis" type="checkbox" id="main-table_'+(index+1)+'" data-column="'+(index+1)+'" checked>';
                html += '        <label class="form-check-label" for="main-table_'+(index+1)+'">';
                html += $(this).text();
                html += '        </label>';
                html += '    </div>';
                html += '</li>';
            });

            $('#main-table_colvis').html(html);

            $('input.main-toggle-vis').each(function() {
                $(this).on('click', function(e) {
                    let columnIdx = $(this).data('column');
                    let column = dt.column(columnIdx);
                    column.visible(!column.visible());
                });
            });
        },
    });
    $('#main-table_filter input').addClass('form-control form-control-sm').attr('placeholder', 'Search...');
    $('#main-table_length select').addClass('form-control form-control-sm px-3');
}

$(document).on('click', 'button#filter-button', function() {
    dt.ajax.reload();
});

$(document).on('click', 'button#reset-button', function() {
    $('#filterSearch').val('');
    $('#filterStock').val('').trigger('change');
    dt.ajax.reload();
});

$(document).on('click', 'button#add-button', function() {
    resetAllInputOnForm('#main-form');
    $('#modal-title').text('Tambah Inventory');
    let modal = new bootstrap.Modal(document.getElementById('add-modal'));
    modal.show();
});

$(document).on('click', 'button#import-button', function() {
    $('#import-form')[0].reset();
    $('#import-modal_title').text('Import Inventories');
    let modal = new bootstrap.Modal(document.getElementById('import-modal'));
    modal.show();
});

$('#import-form').on('submit', function(e) {
    e.preventDefault();

    let fileInput = document.getElementById('input-file');
    if (!fileInput.files.length) {
        Swal.fire('Perhatian!', 'Pilih file terlebih dahulu.', 'warning');
        return;
    }

    let formData = new FormData(this);

    $.ajax({
        url: BASE_URL + '/api/' + endpoint + '/import',
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            showLoading('Harap Menunggu!', 'Sedang mengunggah data');
        },
        success: function(response) {
            Swal.close();
            if (response.data) {
                Swal.fire({
                    title: response.status.toUpperCase(),
                    html: (typeof response.data === 'object' && response.status != 'error' ? 'Sukses Import Data Inventori' : (typeof response.data === 'string') ? '<div class="text-start">' + response.data + '</div>' : '<div class="text-start">' + response.data.join('<br>') + '</div>'),
                    showConfirmButton: true,
                    confirmButtonColor: '#0760ef',
                    icon: response.status,
                    allowOutsideClick: false,
                    width: '600px'
                }).then(function() {
                    hideModal('#import-modal');
                    inventoriesLoaded = false;
                    $('#main-table').DataTable().ajax.reload(null, false);
                });
            } else {
                inventoriesLoaded = false;
                showAlertOnSubmit(response, '#import-modal', ['#main-table']);
            }
        },
        error: function(jqXHR) {
            Swal.close();
            let errorRes = jqXHR.responseJSON;
            Swal.fire({
                title: errorRes ? errorRes.message : 'Gagal Import.',
                html: (errorRes && errorRes.data) ? '<div class="text-start">' + (typeof errorRes.data === 'string' ? errorRes.data : errorRes.data.join('<br>')) + '</div>' : '',
                showConfirmButton: true,
                confirmButtonColor: '#0760ef',
                icon: 'error',
                allowOutsideClick: false,
                width: '600px'
            });
        }
    });
});

$(document).on('click', 'a#edit-data', function(e) {
    e.preventDefault();
    let id = $(this).data('id');
    $.ajax({
        url: BASE_URL + '/api/' + endpoint + '/' + id,
        type: 'GET',
        headers: {
            'Authorization': TOKEN
        },
        dataType: 'JSON',
        beforeSend: function() {
            resetAllInputOnForm('#main-form');
            showLoading('Harap Menunggu!', 'Sedang mengambil data');
        },
        success: function(data) {
            $('#input-id').val(data.id);
            $('#input-code').val(data.code);
            $('#input-name').val(data.name);
            $('#input-stock').val(data.stock);
            $('#input-price').val(data.price);
            $('#modal-title').text('Edit Inventory');
            let modal = new bootstrap.Modal(document.getElementById('add-modal'));
            modal.show();
            Swal.close();
        }
    });
});

$(document).on('click', 'a#view-data', function(e) {
    e.preventDefault();
    let id = $(this).data('id');
    let row = dt.rows().data().toArray().find(r => r.id == id);

    Swal.fire({
        title: 'Detail Inventory',
        html: (function() {
            let html = '';
            html += '<table class="table table-sm table-borderless text-start">';
            html += '<tr><td width="30%">Kode</td><td>: <b>' + row.code + '</b></td></tr>';
            html += '<tr><td>Nama</td><td>: <b>' + row.name + '</b></td></tr>';
            html += '<tr><td>Stok</td><td>: <b>' + row.stock + '</b></td></tr>';
            html += '<tr><td>Harga</td><td>: <b>Rp ' + Number(row.price).toLocaleString('id-ID') + '</b></td></tr>';
            html += '</table>';
            return html;
        })(),
        icon: 'info',
        confirmButtonText: 'Tutup'
    });
});

$(document).on('click', 'a#delete-data', function(e) {
    e.preventDefault();
    let id = $(this).data('id');
    showPopupWithAction(
        'Apakah Anda Yakin ?',
        'Menghapus data ini ?',
        'warning',
        'DELETE',
        null,
        BASE_URL + '/api/' + endpoint + '/' + id,
        '',
        ['#main-table']
    );
});

$('#main-form').on('submit', function(e) {
    e.preventDefault();

    let id = $('#input-id').val();
    let method = id ? 'PUT' : 'POST';
    let url = id ? BASE_URL + '/api/' + endpoint + '/' + id : BASE_URL + '/api/' + endpoint;

    let formData = new FormData(this);

    $.ajax({
        url: url,
        type: method,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        beforeSend: function() {
            showLoading('Harap Menunggu!', 'Sedang mengambil data');
        },
        success: function(res) {
            Swal.close();
            // Refresh inventory cache
            inventoriesLoaded = false;
            showAlertOnSubmit(res, '#add-modal', ['#main-table']);
        },
        error: function(jqXHR) {
            Swal.close();
            let errorRes = jqXHR.responseJSON;
            Swal.fire({
                title: errorRes ? errorRes.message : 'Gagal menyimpan data.',
                html: (errorRes && errorRes.data) ? '<div class="text-start">' + (typeof errorRes.data === 'string' ? errorRes.data : errorRes.data.join('<br>')) + '</div>' : '',
                showConfirmButton: true,
                confirmButtonColor: '#0760ef',
                icon: 'error',
                allowOutsideClick: false,
                width: '600px'
            });
        }
    });
});
</script>
@endsection
