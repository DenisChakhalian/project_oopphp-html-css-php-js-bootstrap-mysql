$(document).ready(function () {

    $('#like').on('click', function () {

        let post_id = $(this).data('id');
        let user_id = $(this).data('user-id');
        let $clicked_btn = $(this);
        let action = 'like';
        if ($clicked_btn.hasClass('like')) {
            action = 'like';
        } else if ($clicked_btn.hasClass('unlike')) {
            action = 'unlike';
        }


        $.ajax({
            url: '../utils/reaction.php',
            type: 'post',
            data: {
                'action': action,
                'post_id': post_id,
                'user_id': user_id
            },
            success: function (data) {
               const res = JSON.parse(data);
                if (action === "like") {
                    $clicked_btn.removeClass('like');
                    $clicked_btn.addClass('unlike');
                    if ($("#dislike").hasClass('undislike')) {
                        $("#dislike").removeClass('undislike');
                        $("#dislike").addClass('dislike');
                    }
                } else if (action === "unlike") {
                    $clicked_btn.removeClass('unlike');
                    $clicked_btn.addClass('like');
                }

                $("#count_likes").text(res.likes);
                $("#count_dislikes").text(res.dislikes);
                //
                //
                // $clicked_btn.siblings('i.fa-thumbs-down').removeClass('fa-thumbs-down').addClass('fa-thumbs-o-down');
            }
        });

    });

    $('#dislike').on('click', function () {
        let post_id = $(this).data('id');
        let user_id = $(this).data('user-id');
        let $clicked_btn = $(this);
        let action = 'dislike';
        if ($clicked_btn.hasClass('dislike')) {
            action = 'dislike';
        } else if ($clicked_btn.hasClass('undislike')) {
            action = 'undislike';
        }
        $.ajax({
            url: '../utils/reaction.php',
            type: 'post',
            data: {
                'action': action,
                'post_id': post_id,
                'user_id': user_id
            },
            success: function (data) {
                const res = JSON.parse(data);
                // let res = JSON.parse(data);
                if (action === "dislike") {
                    $clicked_btn.removeClass('dislike');
                    $clicked_btn.addClass('undislike');
                    if ($("#like").hasClass('unlike')) {
                        $("#like").removeClass('unlike');
                        $("#like").addClass('like');
                    }
                } else if (action === "undislike") {
                    $clicked_btn.removeClass('undislike');
                    $clicked_btn.addClass('dislike');
                }
                //
                $("#count_likes").text(res.likes);
                $("#count_dislikes").text(res.dislikes);
                //
                //
                // $clicked_btn.siblings('i.fa-thumbs-up').removeClass('fa-thumbs-up').addClass('fa-thumbs-o-up');
            }
        });

    });

});