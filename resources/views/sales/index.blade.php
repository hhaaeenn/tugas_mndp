@extends('layouts.main')

@section('title', 'Sales')
@section('page-title', 'Sales')

@section('content')
<div class="card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span class="fw-bold">Data Sales</span>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <button class="btn btn-sm btn-primary" id="add-button">
                <i class="bi bi-plus-lg"></i> Tambah
            </button>
            <div class="input-group" style="width: 260px;">
                <input type="text" class="form-control form-control-sm" name="basic" id="daterange-filter" readonly>
                <label for="daterange-filter" class="input-group-text"><i class="bi bi-calendar3"></i></label>
            </div>
            <button class="btn btn-sm btn-warning" id="download-excel-button"><i class="bi bi-file-earmark-excel"></i> Per Hari</button>
            <button class="btn btn-sm btn-warning" id="download-pdf-button"><i class="bi bi-file-earmark-pdf"></i> Per Hari</button>
            <div class="dropdown">
                <button class="btn btn-sm btn-icon btn-outline-primary" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"><i class="bi bi-list-columns"></i></button>
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
                        <th>Nomor</th>
                        <th>Tanggal</th>
                        <th>Total</th>
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
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="main-form">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="text" name="date" id="input-date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <hr>
                    <h6>Daftar Item</h6>
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-sm" id="tblItems">
                            <thead>
                                <tr>
                                    <th>Inventory</th>
                                    <th width="15%">Jumlah</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Rows will be added dynamically -->
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-success btn-sm" id="btnAddItem"><i class="bi bi-plus"></i> Tambah Baris</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="download-modal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-download_title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="hidden" id="print-download_type">
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label" for="print-attendance_date">Pilih Tanggal</label>
                    <input id="print-attendance_date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="download-btn">Download</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
let dt;
let endpoint = 'sales';
let rowCount = 0;
let startDate = moment().startOf('month').format("YYYY-MM-DD");
let endDate = moment().endOf("month").format("YYYY-MM-DD");

// Mendapatkan tahun saat ini
let currentYear = moment().year();

// Menambahkan rentang untuk setiap bulan
let monthRanges = {};
for (let i = 0; i < 12; i++) {
    let startOfMonth = moment().month(i).year(currentYear).startOf('month');
    let endOfMonth = moment().month(i).year(currentYear).endOf('month');
    let monthName = startOfMonth.format('MMMM');
    monthRanges[monthName + ' ' + currentYear] = [startOfMonth, endOfMonth];
}

// Menggabungkan rentang bulan dengan rentang lainnya
let allRanges = $.extend({}, monthRanges, {

});

$('#daterange-filter').daterangepicker({
    startDate: moment(startDate),
    endDate: moment(endDate),
    ranges: allRanges,
    // {
    //     'Today': [moment(), moment()],
    //     'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    //     'Last 7 Days': [moment().subtract(6, 'days'), moment()],
    //     'Last 30 Days': [moment().subtract(29, 'days'), moment()],
    //     'This Month': [moment().startOf('month'), moment().endOf('month')],
    //     'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    // },
    opens:'left',
}, function(start, end, label) {
    startDate = moment(start).format('YYYY-MM-DD');
    endDate = moment(end).format('YYYY-MM-DD');

    drawDatatable();
});

drawDatatable();

flatpickr('#input-date', {
    mode: "single",
    disableMobile: true,
    dateFormat: "Y-m-d"
});

flatpickr('#print-attendance_date', {
    mode: "single",
    disableMobile: true,
    dateFormat: "Y-m-d"
});

// Load inventories on page load
getInventories({
    is_async: true
});

$(document).on('click', 'button#download-excel-button', function(e) {
    $('#modal-download_title').text('Download EXCEL');
    $('#print-download_type').val('excel');
    let modal = new bootstrap.Modal(document.getElementById('download-modal'));
    modal.show();
});

$(document).on('click', 'button#download-pdf-button', function(e) {
    $('#modal-download_title').text('Download PDF');
    $('#print-download_type').val('pdf');
    let modal = new bootstrap.Modal(document.getElementById('download-modal'));
    modal.show();
});

$(document).on('click', 'button#download-btn', function(e) {
    let downloadType = $('#print-download_type').val();
    let downloadDate = $('#print-attendance_date').val();

    e.preventDefault();

    showLoading('Harap Menunggu!', 'Sedang mendownload data, tunggu sampai hasil download selesai dan loading pada tab browser anda selesai!', 8000);

    window.location.href = `${BASE_URL}/api/${endpoint}/download-per-day?date=${downloadDate}&type=${downloadType}`;

    hideModal('#download-modal');
});

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
                let $filter = {};
                let $dp = $('#daterange-filter').data('daterangepicker');
                let startDate = $dp ? $dp.startDate : null;
                let endDate = $dp ? $dp.endDate : null;

                if (startDate) {
                    $filter.start_date = startDate.format('YYYY-MM-DD');
                }
                if (endDate) {
                    $filter.end_date = endDate.format('YYYY-MM-DD');
                }
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
                data: 'number',
                name: 'number'
            },
            {
                data: 'date',
                name: 'date',
                render: function(data) {
                    return data ? data.split('T')[0] : '-';
                }
            },
            { 
                data: 'total_amount',
                name: 'total_amount',
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

$(document).on('click', 'button#add-button', function() {
    $('#modal-title').text('Tambah Sales');
    resetAllInputOnForm('#main-form');
    $('#input-date').val(moment().format('YYYY-MM-DD'));

    let modal = new bootstrap.Modal(document.getElementById('add-modal'));
    modal.show();
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

/**
 * Add new row for item entry
 * @param {Object} defaultValue - Default values for the row (optional)
 */
function addRow(defaultValue = null) {
    const inventoryId = defaultValue?.id ?? '';
    const quantity = defaultValue?.quantity ?? 1;

    let html = '';
    html += '<tr id="item-row-' + rowCount + '">';
    html += '    <td>';
    html += '        <select class="form-select form-select-sm inv-select select2" name="items[' + rowCount + '][inventory_id]" required>';
    html += '            <option value="">Pilih...</option>';
    html += '        </select>';
    html += '    </td>';
    html += '    <td><input type="number" class="form-control form-control-sm inv-qty" name="items[' + rowCount + '][quantity]" required min="1" value="' + quantity + '"></td>';
    html += '    <td><button type="button" class="btn btn-danger btn-sm btn-remove" data-row="' + rowCount + '"><i class="bi bi-trash"></i></button></td>';
    html += '</tr>';

    $('#tblItems tbody').append(html);

    // Populate the new select with inventories and initialize select2
    let newSelect = $('#item-row-' + rowCount + ' .inv-select');
    buildInventories(newSelect, inventoryId);

    // Initialize select2 for the new dropdown
    newSelect.select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#add-modal'),
        width: '100%',
        placeholder: 'Pilih Inventory',
        allowClear: true
    });

    rowCount++;
}

$('#btnAddItem').on('click', function() {
    addRow();
});

$(document).on('click', '.btn-remove', function() {
    if($('#tblItems tbody tr').length > 1) {
        let row = $(this).closest('tr');
        // Destroy select2 before removing
        row.find('.select2').select2('destroy');
        row.remove();
    } else {
        Swal.fire('Perhatian!', 'Minimal harus ada 1 item.', 'warning');
    }
});

$('#main-form').on('submit', function(e) {
    e.preventDefault();

    let items = [];
    $('#tblItems tbody tr').each(function() {
        let inventoryId = $(this).find('.inv-select').val();
        let quantity = $(this).find('.inv-qty').val();

        if (inventoryId && quantity) {
            items.push({
                inventory_id: inventoryId,
                quantity: quantity,
            });
        }
    });

    if (items.length === 0) {
        Swal.fire('Perhatian!', 'Semua item harus dilengkapi sebelum menyimpan.', 'warning');
        return;
    }

    let formData = new FormData(this);
    formData.append('items', JSON.stringify(items));

    $.ajax({
        url: BASE_URL + '/api/' + endpoint,
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        beforeSend: function() {
            showLoading('Harap Menunggu!', 'Sedang menyimpan data');
        },
        success: function(res) {
            Swal.close();
            if (res.status === 'success') {
                inventoriesLoaded = false;
            }
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

$('#add-modal').on('hidden.bs.modal', function() {
    $('#main-form')[0].reset();
    // Destroy all select2 instances before emptying
    $('#tblItems tbody .select2').each(function() {
        $(this).select2('destroy');
    });
    $('#tblItems tbody').empty();
    rowCount = 0;

    // Add initial row
    addRow();
});

// Refresh inventory list when modal is opened
$('#add-modal').on('shown.bs.modal', function() {
    // Load inventories first, then initialize the first row
    getInventories({
        is_async: true
    }, function() {
        if ($('#tblItems tbody tr').length === 0) {
            addRow();
        }
    });
});
</script>
@endsection
