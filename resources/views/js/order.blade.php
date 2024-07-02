<script>
    $('#customer_phone').select2();
    $('#customer').css("pointer-events","none");

    $("form").submit(function(e) {
        e.preventDefault();
        if ($("input[name='order_item_check[]']").val() === undefined) {
            alertWarning('Harap Lengkapi Data!');
        } else {
            Swal.fire({
                title: 'Apakah Anda Yakin Simpan Data?',
                icon: 'question',
                showCancelButton: true,
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'btn btn-primary rounded-5 mr-2 mb-3',
                    cancelButton: 'btn btn-danger rounded-5 mb-3',
                },
                buttonsStyling: false,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    alertProcess();
                    $('form').unbind('submit').submit();
                }
            })
        }
    });

    function createOrder() {
        Swal.fire({
            title: "Pilih Tipe Order",
            icon: "question",
            showConfirmButton: false,
            showCancelButton: false,
            showCloseButton: false,
            html: `<div class="d-flex justify-content-center my-2">
                <a href="{{ url('order/create') }}/0" class="btn btn-primary mr-2 rounded-5">Tambah Order</a>
                <a href="{{ url('order/create') }}/1" class="btn btn-primary rounded-5">Penukaran Point</a>
                </div>
            `
        });
    }

    function customerCheck() {

        let type = $('#type').val();
        let customer = $('#customer_phone').val();

        if (customer != null) {
            $('#customer').val(customer);

            if (type == 1) {
                $.get('{{ url('customer') }}/' + customer, {}).done(function(data) {
                    $('#customer_point').val(data.customer.point);
                    catalogue();
                }).fail(function(xhr, status, error) {
                    alertError(error);
                });
            }
        }

    }

    function catalogue(page) {
        let type = $('#type').val();
        let search = $('#search').val();
        let point = $('#customer_point').val();

        if (type == 0) {
            $('.catalogue').html('');
            $('#process').addClass('d-block');

            if (page != undefined) {
                $.get("{{ route('order.catalogueMenu') }}", {
                    page: page,
                    type: type,
                    search: search
                }).done(function(data) {
                    $('#process').removeClass('d-block');
                    $('.catalogue').html(data);
                }).fail(function(xhr, status, error) {
                    alertError(error);
                });
            } else {
                $.get("{{ route('order.catalogueMenu') }}", {
                    type: type,
                    search: search
                }).done(function(data) {
                    $('#process').removeClass('d-block');
                    $('.catalogue').html(data);
                }).fail(function(xhr, status, error) {
                    alertError(error);
                });
            }
        } else {
            if (point != '') {
                $('.catalogue').html('');
                $('#process').addClass('d-block');
                $('#customer_none').removeClass('d-block');

                if (page != undefined) {
                    $.get("{{ route('order.catalogueMenu') }}", {
                        page: page,
                        type: type,
                        point: point,
                        search: search
                    }).done(function(data) {
                        $('#process').removeClass('d-block');
                        $('.catalogue').html(data);
                    }).fail(function(xhr, status, error) {
                        alertError(error);
                    });
                } else {
                    $.get("{{ route('order.catalogueMenu') }}", {
                        type: type,
                        point: point,
                        search: search
                    }).done(function(data) {
                        $('#process').removeClass('d-block');
                        $('.catalogue').html(data);
                    }).fail(function(xhr, status, error) {
                        alertError(error);
                    });
                }
            } else {
                $('#customer_none').addClass('d-block');
            }
        }

    }

    function addMenu(id) {

        let type = $('#type').val();

        if (type == 0) {

            let total_percentage = $('#total_percentage').val();

            $.get('{{ url('menu') }}/' + id, {}).done(function(data) {
                if ($("#order_item_qty_" + data.menu.id).length == 0) {

                    let tr = $("<tr id='menu_" + data.menu.id + "'></tr>");

                    let td_menu_name = $("<td>" +
                        data.menu.name +
                        "<input type='hidden' name = 'order_item[" + data.menu.id +
                        "][menu]'" +
                        "value = '" + data.menu.id + "' > " +
                        "</td>");

                    let td_menu_qty = $("<td>" +
                        "<input type='number' class='form-control text-center' name='order_item[" +
                        data
                        .menu.id + "][qty]' " +
                        "id='order_item_qty_" + data.menu.id + "' min='1' value='1'" +
                        "readonly required> " +
                        "</td>"
                    );

                    let td_menu_price = $("<td align='right'>" +
                        "Rp. <span id='price_show_" + data.menu.id + "'>" +
                        currencyFormat(data.menu.price) +
                        "</span>,-" +
                        "<input type='hidden' id='order_item_price_" + data.menu.id +
                        "' name = 'order_item[" +
                        data.menu.id + "][price]'" +
                        "value = '" + data.menu.price + "' > " +
                        "</td>"
                    );

                    let total_point = parseInt(data.menu.price) * parseFloat(total_percentage);

                    let td_menu_point = $("<td align='right'>" +
                        "<span id='point_show_" + data.menu.id + "'>" +
                        currencyFormat(total_point) +
                        "</span>" +
                        "<input type='hidden' id='order_item_point_" + data.menu.id +
                        "' name = 'order_item[" +
                        data.menu.id + "][point]'" +
                        "value = '" + total_point + "' > " +
                        "</td>"
                    );

                    let td_menu_action = $(
                        "<td align='center'>" +
                        "<button type='button' class='delete-row btn btn-sm btn-danger rounded-5' value='Delete'><i class='fas fa-trash'></i></button>" +
                        "<input type='hidden' class='form-control' name='order_item_check[]' value='" +
                        data.menu.id +
                        "'>" +
                        "</td>"
                    );

                    // Append Tr Element
                    (tr.append(td_menu_name).append(td_menu_qty).append(td_menu_price).append(
                        td_menu_point).append(
                        td_menu_action));

                    // Append To Table
                    $("#order_item_table tbody").append(tr);
                } else {
                    let last_qty = $("#order_item_qty_" + data.menu.id).val();
                    let total_qty = parseInt(last_qty) + 1;
                    let total_price = parseInt(data.menu.price) * total_qty;
                    let total_point = total_price * parseFloat(total_percentage);

                    $('#price_show_' + data.menu.id).html(currencyFormat(total_price));
                    $('#point_show_' + data.menu.id).html(currencyFormat(total_point));
                    $('#order_item_qty_' + data.menu.id).val(total_qty);
                    $('#order_item_price_' + data.menu.id).val(total_price);
                    $('#order_item_point_' + data.menu.id).val(total_point);
                }
                accumulationItem();
            }).fail(function(xhr, status, error) {
                alertError(error);
            });

        } else {

            $.get('{{ url('promo-point') }}/' + id, {}).done(function(data) {
                if ($("#order_item_qty_" + data.promo_point.menu.id).length == 0) {

                    let tr = $("<tr id='menu_" + data.promo_point.menu.id + "'></tr>");

                    let td_menu_name = $("<td>" +
                        data.promo_point.menu.name +
                        "<input type='hidden' name = 'order_item[" + data.promo_point.menu.id +
                        "][menu]'" +
                        "value = '" + data.promo_point.menu.id + "' > " +
                        "<input type='hidden' name = 'order_item[" + data.promo_point.menu.id +
                        "][promo_point]'" +
                        "value = '" + data.promo_point.id + "' > " +
                        "</td>");

                    let td_menu_qty = $("<td>" +
                        "<input type='number' class='form-control text-center' name='order_item[" +
                        data.promo_point.menu.id + "][qty]' " +
                        "id='order_item_qty_" + data.promo_point.menu.id + "' min='1' value='" +
                        data.promo_point.qty + "'" +
                        "required readonly> " +
                        "</td>"
                    );

                    let td_menu_point = $("<td align='right'>" +
                        "<span id='point_show_" + data.promo_point.menu.id + "'>" +
                        currencyFormat(data.promo_point.point) +
                        "</span>" +
                        "<input type='hidden' id='order_item_point_" + data.promo_point.menu.id +
                        "' name = 'order_item[" +
                        data.promo_point.menu.id + "][point]'" +
                        "value = '" + data.promo_point.point + "' > " +
                        "</td>"
                    );

                    let td_menu_action = $(
                        "<td align='center'>" +
                        "<button type='button' class='delete-row btn btn-sm btn-danger rounded-5' value='Delete'><i class='fas fa-trash'></i></button>" +
                        "<input type='hidden' class='form-control' name='order_item_check[]' value='" +
                        data.promo_point.menu.id +
                        "'>" +
                        "</td>"
                    );

                    // Append Tr Element
                    (tr.append(td_menu_name).append(td_menu_qty).append(
                        td_menu_point).append(
                        td_menu_action));

                    // Append To Table
                    $("#order_item_table tbody").append(tr);
                } else {
                    let last_qty = $("#order_item_qty_" + data.promo_point.menu.id).val();
                    let total_qty = parseInt(last_qty) + parseInt(data.promo_point.qty);
                    let real_qty = total_qty / parseInt(data.promo_point.qty)
                    let total_point = parseFloat(data.promo_point.point) * real_qty;

                    $('#point_show_' + data.promo_point.menu.id).html(currencyFormat(total_point));
                    $('#order_item_qty_' + data.promo_point.menu.id).val(total_qty);
                    $('#order_item_point_' + data.promo_point.menu.id).val(total_point);
                }
                accumulationItem();
            }).fail(function(xhr, status, error) {
                alertError(error);
            });
        }

    }

    // Currency Format
    function currencyFormat(value) {
        return value.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
    }

    $("table#order_item_table").on("click", ".delete-row", function(event) {
        $(this).closest("tr").remove();
        accumulationItem();
    });

    function accumulationItem() {
        let total_price = 0;
        let total_point = 0;
        let type = $('#type').val();

        if (type == 0) {
            $("input[name='order_item_check[]']")
                .map(function() {
                    total_price += parseInt($('#order_item_price_' + $(this).val())
                        .val());

                    total_point += parseFloat($('#order_item_point_' + $(this).val())
                        .val());
                });

            $('#total_price_show').html('Rp. ' + currencyFormat(total_price) + ',-');
            $('#total_point_show').html(currencyFormat(total_point));
            $('#total_price').val(total_price);
            $('#total_point').val(total_point);
        } else {
            $("input[name='order_item_check[]']")
                .map(function() {
                    total_point += parseFloat($('#order_item_point_' + $(this).val())
                        .val());
                });

            $('#total_point_show').html(currencyFormat(total_point));
            $('#total_point').val(total_point);

            let customer_point = parseFloat($('#customer_point').val());
            let last_point = customer_point - total_point;

            $("input[name='point_product[]']")
                .map(function(index) {
                    if ($(this).val() > last_point) {
                        $('.btn-add-' + index).attr('disabled', true);
                    } else {
                        $('.btn-add-' + index).removeAttr('disabled');
                    }
                });
        }
    }

    function dataTable() {
        const url = $('#datatable-url').val();
        $('#datatable-order').DataTable({
            autoWidth: false,
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: url,
                error: function(xhr, error, code) {
                    alertError(xhr.statusText);
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    width: '5%',
                    searchable: false
                },
                {
                    data: 'created_at',
                    defaultContent: '-',
                },
                {
                    data: 'type',
                    defaultContent: '-',
                },
                {
                    data: 'total_price',
                    class: 'text-right',
                    defaultContent: '-',
                },
                {
                    data: 'total_point',
                    class: 'text-right',
                    defaultContent: '-',
                },
                {
                    data: 'action',
                    width: '20%',
                    defaultContent: '-',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    }

    function destroyRecord(id) {
        let token = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
            title: 'Apakah Anda Yakin Hapus Data?',
            icon: 'question',
            showCancelButton: true,
            allowOutsideClick: false,
            customClass: {
                confirmButton: 'btn btn-primary rounded-5 mr-2 mb-3',
                cancelButton: 'btn btn-danger rounded-5 mb-3',
            },
            buttonsStyling: false,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                alertProcess();
                $.ajax({
                    url: '{{ url('order') }}/' + id,
                    type: 'DELETE',
                    cache: false,
                    data: {
                        _token: token
                    },
                    success: function(data) {
                        location.reload();
                    },
                    error: function(xhr, error, code) {
                        alertError(error);
                    }
                });
            }
        })
    }
</script>
