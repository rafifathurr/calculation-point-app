<script>
    function getData() {

        let phone = $('#phone').val();

        $('.content').html('');
        alertProcess();

        $.ajax({
            url: '{{ url('guest/get-data') }}',
            type: 'GET',
            cache: false,
            data: {
                phone: phone,
            },
            success: function(data) {
                Swal.close();
                if (Object.keys(data).length != 0) {
                    $('.content').html(data);
                } else {
                    alertError("Tidak Terdapat Data");
                }
            },
            error: function(xhr, error, code) {
                alertError(code);
            }
        });
    }
</script>
