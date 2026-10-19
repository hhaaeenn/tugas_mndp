    <!-- jQuery, Bootstrap, DataTables & Plugins -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS & Export Buttons -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <!-- Moment & Daterangepicker JS -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Global AJAX Settings -->
    <script>
        const BASE_URL = "{{ url('') }}";
        const TOKEN = 'Bearer ' + (localStorage.getItem('api_token') || '');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Global inventory data cache
        let Inventories = [];
        let inventoriesLoaded = false;

        /**
         * Get inventories with caching
         * @param {Object} params - {element: jQuery selector, is_async: boolean, selected_val: value, filter: string}
         * @param {Function} callback - Callback function to execute after data is loaded
         */
        function getInventories(params, callback = null) {
            let element = params['element'];
            let isAsync = params['is_async'] ?? true;
            let selectedVal = params['selected_val'] ?? '';
            let filter = params['filter'] ?? '';

            if (inventoriesLoaded) {
                if (element) {
                    buildInventories(element, selectedVal);
                }
                callback && callback(Inventories);
                return;
            }

            $.ajax({
                url: BASE_URL + "/api/inventories?all=true" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function (res) {
                    Inventories = res;
                    inventoriesLoaded = true;

                    if (element) {
                        buildInventories(element, selectedVal);
                    }

                    callback && callback(Inventories);
                },
                error: function (xhr) {
                    console.error('getInventories error:', xhr.status, xhr.responseText);
                    callback && callback([]);
                }
            });
        }

        /**
         * Build inventory options for select element
         * @param {jQuery} element - Select element to populate
         * @param {string} selectedVal - Value to be selected
         */
        function buildInventories(element, selectedVal = '') {
            element.html('<option value="">Pilih...</option>');
            
            Inventories.forEach(function(inv) {
                let priceFormatted = Number(inv.price).toLocaleString('id-ID');
                let text = inv.name + ' (Stok: ' + inv.stock + ', Harga: Rp' + priceFormatted + ')';
                let option = $('<option></option>')
                    .val(inv.id)
                    .text(text)
                    .attr('data-price', inv.price)
                    .attr('data-stock', inv.stock);
                
                if (inv.id == selectedVal) {
                    option.prop('selected', true);
                }
                
                element.append(option);
            });

            // Initialize or refresh Select2 if element has it
            if (element.hasClass('select2-hidden-accessible')) {
                element.select2('destroy');
            }
            element.select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih inventory...',
                allowClear: true,
                dropdownParent: element.closest('.modal').length ? element.closest('.modal') : $(document.body)
            });
        }

        function showLoading(title, message, timer = 0) {
            Swal.fire({
                title: title,
                html: message,
                didOpen: function() {
                    Swal.showLoading();
                },
                timer: timer
            });
        }

        function showFailedAlert(msg) {
            Swal.fire({
                title: 'Gagal',
                showConfirmButton: true,
                confirmButtonColor: '#0760ef',
                icon: 'error',
                html: msg
            });
        }

        function hideModal(modal) {
            if (!modal) return;
            let modalEl = document.querySelector(modal);
            if (modalEl) {
                let modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        }

        function showAlertOnSubmit(params, modal, table, reload, reloadBlank) {
            if (params.status == 'success') {
                setTimeout(function() {
                    Swal.fire({
                        title: 'Sukses',
                        text: params.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1000
                    }).then(function() {
                        if (modal) {
                            hideModal(modal);
                        }
                        if (table) {
                            let tables = Array.isArray(table) ? table : [table];
                            $.each(tables, function(index, item) {
                                if ($.fn.DataTable.isDataTable(item)) {
                                    $(item).DataTable().ajax.reload(null, false);
                                }
                            });
                        }
                        if (reload) {
                            window.location.replace(reload);
                        }
                        if (reloadBlank) {
                            window.open(reloadBlank, '_blank');
                        }
                    });
                }, 200);
            } else {
                showFailedAlert(params.message);
            }
        }

        function showPopupWithAction(title, subtitle, icon, method, data, url, modal, table, reload, callback = null) {
            Swal.fire({
                title: title,
                html: subtitle,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Batal',
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        headers: {
                            'Authorization': 'Bearer ' + (localStorage.getItem('api_token') || ''),
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: data,
                        type: method,
                        beforeSend: function() {
                            showLoading('Harap Menunggu!', 'Sedang memproses data...');
                        }
                    }).done(function(data) {
                        Swal.close();
                        if (data.status == 'success') {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            if (modal) {
                                hideModal(modal);
                            }
                            if (table) {
                                let tables = Array.isArray(table) ? table : [table];
                                $.each(tables, function(index, item) {
                                    if ($.fn.DataTable.isDataTable(item)) {
                                        $(item).DataTable().ajax.reload(null, false);
                                    }
                                });
                            }
                            if (typeof callback === 'function') {
                                callback(data);
                            }
                            if (reload) {
                                window.location.replace(reload);
                            }
                        } else {
                            Swal.fire('Error!', data.message, 'error');
                        }
                    }).fail(function(data) {
                        Swal.close();
                        let errorRes = data.responseJSON;
                        showFailedAlert(errorRes ? errorRes.message : 'Terjadi kesalahan.');
                    });
                }
            });
        }

        function resetAllInputOnForm(formId) {
            $(formId).find('input, textarea').val('');
            $(formId).find('input[type="checkbox"]').prop('checked', false);
            $(formId).find('input[type="radio"]').prop('checked', false);
            $(formId).find('select').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy').val('').select2({
                        dropdownParent: $(formId)
                    });
                } else {
                    $(formId).find('select').val('');
                }
            });
        }

        /**
         * Format currency to IDR format
         * @param {number} amount - Amount to format
         * @returns {string} Formatted currency
         */
        function formatCurrencyIdr(amount) {
            if (!amount) return 'Rp 0';
            return 'Rp ' + Number(amount).toLocaleString('id-ID');
        }

        // Fungsi helper untuk mengecek dan mengkonversi angka
        function parseNumberExportDT(value) {
            // Hapus whitespace di awal dan akhir
            let cleanValue = value == null ? '' : value.toString().trim();

            // Cek apakah string mengandung format angka Indonesia (koma sebagai pemisah ribuan, titik sebagai desimal)
            // Pattern: angka dengan koma sebagai pemisah ribuan dan opsional titik untuk desimal
            const indonesianNumberPattern = /^-?\d{1,3}(,\d{3})*(\.\d+)?$/;

            if (indonesianNumberPattern.test(cleanValue)) {
                // Hapus koma (pemisah ribuan) dan konversi ke number
                let numericValue = cleanValue.replace(/,/g, '');
                return parseFloat(numericValue);
            }

            // Jika bukan format angka yang dikenali, return string asli
            return cleanValue;
        }

        let buttonExportInput = {
            exportOptions: {
                stripHtml: true,
                stripNewlines: false,
                columns: ':visible',
                format: {
                    body: function(data, row, column, node) {
                        if (!node || !node.firstChild) {
                            return data;
                        }

                        let cellValue = data;

                        if (node.firstChild.tagName == 'SELECT') {
                            cellValue = $(node.firstChild).find(':selected').text();
                        } else if (node.firstChild.tagName == 'INPUT') {
                            cellValue = node.firstElementChild ? node.firstElementChild.value : '';
                        } else if (node.firstChild.tagName == 'TEXTAREA') {
                            cellValue = node.firstElementChild ? node.firstElementChild.value : '';
                        }

                        // Parse nilai sebagai angka jika memungkinkan
                        return parseNumberExportDT(cellValue);
                    }
                }
            }
        };
    </script>

    @yield('script')
</body>
</html>
