<?php
require('../auth/db.php');

if (isset($_POST['action'])) {
    $post_id = $_POST['post_id'];
    $action = $_POST['action'];
    $user_id = $_POST['user_id'];
    $reaction_query = "SELECT * FROM likes WHERE page_id = '" . $post_id . "' AND user_id = '" . $user_id . "'";
    $reaction_result = mysqli_query($con, $reaction_query) or die(mysqli_error());
    $likes = mysqli_fetch_assoc($reaction_result);

    switch ($action) {
        case 'like':
            if ($likes && $likes['rating_action']) {
                $sql = "UPDATE likes 
         	   SET rating_action='like'
         	   WHERE page_id = '" . $post_id . "' AND user_id = '" . $user_id . "'";
            } else {
                $sql = "INSERT INTO likes (user_id, page_id, rating_action) 
         	   VALUES ($user_id, $post_id, 'like') 
         	   ON DUPLICATE KEY UPDATE rating_action='like'";
            }
            break;
        case 'dislike':
            if ($likes && $likes['rating_action']) {
                $sql = "UPDATE likes 
         	   SET rating_action='dislike'
         	   WHERE page_id = '" . $post_id . "' AND user_id = '" . $user_id . "'";
            } else {
                $sql = "INSERT INTO likes (user_id, page_id, rating_action) 
               VALUES ($user_id, $post_id, 'dislike') 
         	   ON DUPLICATE KEY UPDATE rating_action='dislike'";
            }
            break;
        case 'undislike':
        case 'unlike':
            $sql = "DELETE FROM likes WHERE user_id=$user_id AND page_id=$post_id";
            break;
        default:
            break;
    }


    mysqli_query($con, $sql);
    echo getRating($post_id, $con);
    exit(0);

}

function getRating($id, $con)
{

    //

    $count_likes_query = "SELECT COUNT(id) AS likes FROM likes WHERE page_id = '" . $id . "' AND rating_action = 'like'";
    $count_likes_result = mysqli_query($con, $count_likes_query) or die(mysqli_error());
    $count_likes = mysqli_fetch_assoc($count_likes_result);

    $count_dislikes_query = "SELECT COUNT(id) AS dislikes FROM likes WHERE page_id = '" . $id . "' AND rating_action = 'dislike'";
    $count_dislikes_result = mysqli_query($con, $count_dislikes_query) or die(mysqli_error());
    $count_dislikes = mysqli_fetch_assoc($count_dislikes_result);

    $rating = [
        'likes' => $count_likes['likes'],
        'dislikes' => $count_dislikes['dislikes']
    ];
    return json_encode($rating);
}