$(document).ready(function () {

    $('.delete').on('click', function () {

        let id = $(this).data('id');

        $.ajax({
            url: '../utils/delete.php',
            type: 'post',
            data: {
                'id': id,
            },
            success: function (data) {
             location.reload();
            }
        });

    });

});