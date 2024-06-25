<script>
    $("form").submit(function(e) {
        e.preventDefault();
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
    });

    function dataTable() {
        const url = $('#datatable-url').val();
        $('#datatable-rule').DataTable({
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
                    data: 'name',
                    defaultContent: '-',
                },
                {
                    data: 'availability',
                    defaultContent: '-',
                },
                {
                    data: 'status',
                    defaultContent: '-',
                },
                {
                    data: 'percentage',
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

    function changeAvailability(element) {

        if (element.value == 0) {
            if ($('#availability-form')[0].className == 'd-block') {
                $('#availability-form').removeClass('d-block')
                $('#availability-form').addClass('d-none');
                $('#year').attr('required', false);
                $('#month').attr('required', false);
                $('#day').attr('required', false);
                $('#year').val('');
            }
        } else {
            if (element.value == 1) {
                if ($('#availability-form')[0].className == 'd-none') {
                    $('#availability-form').removeClass('d-none')
                    $('#availability-form').addClass('d-block');
                    $('#year').attr('required', true);
                    $('#month').attr('required', true);
                    $('#day').attr('required', true);
                    $('#year').val('').change();
                }
            }
        }

        $('#month').val('');
        $('#day').val('');
    }

    function yearConfiguration() {

        let year = $('#year').val();

        if (year == '') {
            year = null;
        }

        $.ajax({
            url: '{{ url('rule-calculation-point/date-configuration') }}',
            type: 'GET',
            cache: false,
            data: {
                year: year,
                month: null
            },
            success: function(data) {
                $('#month')
                    .find('option')
                    .remove();
                $.each(data, function(key, val) {
                    $('#month')
                        .append($('<option>', {
                            value: key,
                            text: val
                        }));
                });
            },
            error: function(xhr, error, code) {
                alertError(error);
            }
        });
    }

    function monthConfiguration() {
        let year = $('#year').val();
        let month = $('#month').val();

        if (year == '') {
            year = null;
        }

        $.ajax({
            url: '{{ url('rule-calculation-point/date-configuration') }}',
            type: 'GET',
            cache: false,
            data: {
                year: year,
                month: month
            },
            success: function(data) {
                $('#day')
                    .find('option')
                    .remove();
                $.each(data, function(key, val) {
                    $('#day')
                        .append($('<option>', {
                            value: key,
                            text: val
                        }));
                });
            },
            error: function(xhr, error, code) {
                alertError(error);
            }
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
                    url: '{{ url('rule-calculation-point') }}/' + id,
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
