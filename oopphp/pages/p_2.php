<!doctype html>
<html lang="ru">
<head>
    <?php
    require('../auth/db.php');
    include("../auth/auth_session.php");

    $urlPage = explode("/kurs/", $_SERVER['REQUEST_URI'])[1];
    $chapter = mysqli_query($con, "SELECT * FROM chapters WHERE page =  '" . $urlPage . "'") or die(mysqli_error());
    $chapterText = mysqli_fetch_array($chapter);
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?php echo($chapterText['name']); ?></title>
    <link rel="icon" href="../icon.ico" type="image/x-icon">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.muicss.com/mui-latest/css/mui.min.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.muicss.com/mui-latest/js/mui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.19.0/standard-all/ckeditor.js"></script>
</head>
<body>
<script src="../js/main.js"></script>

<!--Скролл-->
<div class="mb-3 before">
    <a href="p_1.php">
        <img src="../img/before.png" title="Попередня глава">
    </a>
</div>

<div class="mb-3 next">
    <a href="p_3.php">
        <img src="../img/next.png" title="Наступна глава">
    </a>
</div>

<div id="top" class="mr-3 mb-3">
    <a href="#">
        <img src="../img/up.png" alt="Вверх">
    </a>
</div>

<!--Навигация-->
<nav class="navbar navbar-expand-xl navbar-light sticky-top position-fixed nav_ver_tt">
    <div class="container-fluid">
        <a href="index.php" class="navbar-brand"><img src="../img/logo.png" alt="logo"></a>
        <span id="user"></span>
        <button class=" navbar-toggler" type="button" data-toggle="collapse" data-target="#ResponsiveNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="ResponsiveNav">
            <ul class="navbar-nav ml-auto">
                <?php
                $nav_bar = mysqli_query($con, "SELECT * FROM nav_bar") or die(mysqli_error());
                while ($row = mysqli_fetch_assoc($nav_bar)) {
                    if (explode("/", $row['page'])[0] == 'pages') {
                        $row_explode = explode("pages/", $row['page'])[1];
                    } else {
                        $row_explode = 'http://' . $_SERVER['HTTP_HOST'] . '/' . explode("/", $_SERVER['REQUEST_URI'])[1] . '/' . $row['page'];
                    }
                    ?>
                    <li class="nav-item">
                        <a href="<?php echo($row_explode); ?>" class="nav-link"><?php echo($row['name']); ?></a>
                    </li>
                <?php } ?>
                <li>
                    <?php if ($_SESSION && $_SESSION['username']) { ?>
                        <a id="log-out" href="../auth/logout.php" class="nav-link"
                           title="Вийти"><?php echo $_SESSION['username']; ?></a>
                    <?php } else { ?>
                        <a href="../auth/login.php" class="nav-link" id="modal-btn">Увійти</a>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="boxer">
    <!--Первый блок-->
    <?php
    //save comment

    $user = null;

    if ($_SESSION['username']) {
        $user_query = mysqli_query($con, "SELECT id, role_id FROM users WHERE username =  '" . $_SESSION['username'] . "'") or die(mysqli_error());
        $user = mysqli_fetch_assoc($user_query);
    }

    if ($_POST && $_POST['comment'] && $chapterText['id'] && strlen($_POST['comment']) > 0) {
        $create_datetime = date("Y-m-d H:i:s");
        $page_explode = explode("pages/", $row['page'])[1];
        $query = "INSERT into `comments` (id_page, id_user, comment	, create_datetime)
                     VALUES ('" . $chapterText['id'] . "', '" . $user['id'] . "', '" . $_POST['comment'] . "', '$create_datetime')";
        $result = mysqli_query($con, $query);
        header("Location: $page_explode#comment");
    }

    //display all comments

    $query1 = "SELECT c.id, c.comment, c.create_datetime, u.username FROM comments AS c LEFT JOIN users AS u ON c.id_user = u.id WHERE c.id_page = '" . $chapterText['id'] . "' ORDER BY create_datetime DESC";
    $comments = mysqli_query($con, $query1) or die(mysqli_error());

    //likes

    if ($user && $chapterText) {

        $likes_query = "SELECT * FROM likes WHERE page_id = '" . $chapterText['id'] . "' AND user_id = '" . $user['id'] . "'";
        $likes_result = mysqli_query($con, $likes_query) or die(mysqli_error());
        $likes = mysqli_fetch_assoc($likes_result);
    }

    $count_likes_query = "SELECT COUNT(id) FROM likes WHERE page_id = '" . $chapterText['id'] . "' AND rating_action = 'like'";
    $count_likes_result = mysqli_query($con, $count_likes_query) or die(mysqli_error());
    $count_likes = mysqli_fetch_assoc($count_likes_result);

    $count_dislikes_query = "SELECT COUNT(id) FROM likes WHERE page_id = '" . $chapterText['id'] . "' AND rating_action = 'dislike'";
    $count_dislikes_result = mysqli_query($con, $count_dislikes_query) or die(mysqli_error());
    $count_dislikes = mysqli_fetch_assoc($count_dislikes_result);
    ?>

    <div class="row alert chapter">
        <div class="col-12 mt-5">
            <h1 class="display-5"><?php echo($chapterText['name']); ?></h1>
        </div>
    </div>
    <hr>
    <!--Второй блок-->
    <div class="text mb-5">
        <form method="post" name="edit" action="../utils/chapter.php">
            <div id="content">

                <p class="lead"><?php echo($chapterText['name_paragraph']); ?></p>

                <?php if ($_SESSION && $_SESSION['username'] && $user['role_id'] === '2') { ?>
                    <div class="edit"><span>[</span><a id="edit">Виправити</a><span>]</span></div>
                <?php } ?>
                <?php echo($chapterText['description']); ?>
            </div>
            <div id="editor" class="mt-5">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="name_paragraph" id="title" placeholder="Заголовок"
                           value="<?php echo($chapterText['name_paragraph']); ?>">
                    <input type="hidden" name="id" value="<?= $chapterText['id'] ?>">
                    <input type="hidden" name="page" value="<?= $chapterText['page'] ?>">
                </div>

                <textarea name="description" id="content_edit"><?php echo($chapterText['description']); ?></textarea>
                <script>
                    CKEDITOR.config.height = 500;
                    // CKEDITOR.config.toolbar = [
                    //     [ 'Source', '-', 'Bold', 'Italic', 'Insert Code Snippet' ]
                    // ];
                    // CKEDITOR.config.extraPlugins = 'codeTag';
                    const config = {
                        extraPlugins: 'codesnippet',
                        codeSnippet_theme: 'monokai_sublime',
                        // height: 356,
                        removeButtons: 'PasteFromWord'
                    };
                    CKEDITOR.replace('content_edit', config);

                </script>
                <div class="mt-3 save_edit">
                    <button type="submit" name="submit" class="btn btn-success">Зберегти</button>
                </div>
            </div>
        </form>
    </div>

    <!--    <div class="flex_div chapter-text">-->
    <!--        <div class="text mb-5">-->
    <!--            <p class="lead">--><?php //echo($chapterText['name_paragraph']); ?><!--</p>-->
    <!--            --><?php //echo($chapterText['description']); ?>
    <!--        </div>-->
    <!--    </div>-->

    <div class="reaction mb-5" id="reaction">
        <?php
        if ($user) { ?>
            <span id="count_likes"><?php echo(current($count_likes)); ?></span>
            <span data-id="<?php echo $chapterText['id'] ?>" data-user-id="<?php echo $user['id'] ?>"
                  class="<?php echo $likes['rating_action'] === 'like' ? 'unlike' : 'like' ?>"
                  id="like"><img src="../img/like.png"></span>
            <span id="count_dislikes"><?php echo(current($count_dislikes)); ?></span>
            <span data-id="<?php echo $chapterText['id'] ?>" data-user-id="<?php echo $user['id'] ?>"
                  class="<?php echo $likes['rating_action'] === 'dislike' ? 'undislike' : 'dislike' ?>"
                  id="dislike"><img src="../img/dislike.png"></span>
        <?php } else { ?>
            <span id="count_likes"><?php echo(current($count_likes)); ?></span>
            <a href="../auth/login.php" class="like"><img src="../img/like.png"></a>
            <span id="count_dislikes"><?php echo(current($count_dislikes)); ?></span>
            <a href="../auth/login.php" class="dislike"><img src="../img/dislike.png"></a>
        <?php } ?>
    </div>
    <div class="comments_box mb-5">

        <?php if ($_SESSION && $_SESSION['username']) { ?>
            <form method="post" name="comment">
                <div id="comment" class="comment">
                    <div id="counter">150</div>
                    <textarea maxlength="150" type="comment" name="comment" id="count"
                              class="text_comm"></textarea>
                    <button type="submit" name="submit" class="btn send_massage">Надіслати</button>
                </div>
            </form>
        <?php } ?>

        <div id="comment_list" class="comment_list">
            <?php while ($row = mysqli_fetch_assoc($comments)) { ?>
                <div>
                    <h4 class="user_mess">
                        <?= $row['username'] ?>
                        <em><small>дата: <?= $row['create_datetime'] ?></small></em>
                    </h4>
                    <h5 class="text_mess"><?= $row['comment'] ?>
                        <?php if ($_SESSION && $_SESSION['username'] && $user['role_id'] === '2') { ?>
                            <button class="delete" title="Видалити"
                                    data-id="<?= $row['id'] ?>">
                            </button>
                        <?php } ?>
                    </h5>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<!--Футер-->
<footer class="container-fluid">
    <div class="container-fluid">
        <div class="row padding text-center">
            <div class="col-12">
                <h2>Контакты</h2>
            </div>
            <div class="col-12 social padding">
                <a href="https://twitter.com/" target="_blank"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://www.youtube.com/" target="_blank"><i class="fab fa-youtube"></i></a>
                <a href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook"></i></a>
                <a href="https://myaccount.google.com/" target="_blank"><i class="fab fa-google-plus-g"></i></a>
            </div>
            <hr>
            <div class="col-12">
                <h6>2022 Денис Чахальян, по всем вопросам пишите по адресу
                    denys.chakhalian@nure.ua</h6>
            </div>
        </div>
    </div>
</footer>


<script src="../js/reaction.js"></script>
<script src="../js/delete.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>


</body>
</html>
