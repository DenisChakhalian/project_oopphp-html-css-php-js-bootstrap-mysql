<!doctype html>
<html lang="ru">
<head>
    <?php
    require('../auth/db.php');
    include("../auth/auth_session.php");

    $urlPage = explode("/kurs/", $_SERVER['REQUEST_URI'])[1];
    $chapter = mysqli_query($con, "SELECT * FROM nav_bar WHERE page =  '" . $urlPage . "'") or die(mysqli_error());
    $chapterText = mysqli_fetch_assoc($chapter);
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
</head>
<body>
<script src="../js/main.js"></script>

<!--Скролл-->

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
    <div class="row alert chapter">
        <div class="col-12 mt-5">
            <h1 class="display-5"><?php echo($chapterText['name']); ?></h1>
        </div>
    </div>
    <hr>
    <!--Второй блок-->
    <div class="chapter-list">
        <ul class="list-group">
            <?php
            $chapter_page = mysqli_query($con, "SELECT * FROM chapters WHERE id_chapter =  '" . $chapterText['id'] . "'") or die(mysqli_error());
            $count_chapter = mysqli_query($con, "SELECT COUNT(name) FROM chapters WHERE id_chapter='" . $chapterText['id'] . "'") or die(mysqli_error());
            $count_chapters = mysqli_fetch_assoc($count_chapter);
            for ($i = 1; $i <= current($count_chapters); $i++) {
                while ($row = mysqli_fetch_assoc($chapter_page)) {
                    $row_explode = explode("pages/", $row['page'])[1]; ?>
                    <li class="list-group-item list-group-item-info"><a
                                href="<?php echo($row_explode); ?>"><?php echo($i . ". " . $row['name']); ?></a></li>
                    <?php break;
                } ?>
                <?php
            } ?>
        </ul>
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


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>


</body>
</html>
