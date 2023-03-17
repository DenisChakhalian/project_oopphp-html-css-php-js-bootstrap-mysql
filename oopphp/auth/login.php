<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Вхід</title>
    <link rel="icon" href="../icon.ico" type="image/x-icon">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css"/>
    <link href="https://cdn.muicss.com/mui-latest/css/mui.min.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.muicss.com/mui-latest/js/mui.min.js"></script>
</head>
<body>
<script src="./js/main.js"></script>
<?php
require('db.php');
include("auth_session.php");

$urlPage = explode("/kurs/", $_SERVER['REQUEST_URI'])[1];
$chapter = mysqli_query($con, "SELECT * FROM nav_bar WHERE page =  '" . $urlPage . "'") or die(mysqli_error());
$chapterText = mysqli_fetch_assoc($chapter);
?>
<div id="top" class="mr-3 mb-3">
    <a href="#">
        <img src="../img/up.png" alt="Вверх">
    </a>
</div>
<!--Навигация-->
<nav class="navbar navbar-expand-xl navbar-light sticky-top position-fixed nav_ver_tt">
    <div class="container-fluid">
        <a href="../pages/index.php" class="navbar-brand"><img src="../img/logo.png" alt="logo"></a>
        <span id="user"></span>
        <button class=" navbar-toggler" type="button" data-toggle="collapse" data-target="#ResponsiveNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="ResponsiveNav">
            <ul class="navbar-nav ml-auto">
                <?php
                $nav_bar = mysqli_query($con, "SELECT * FROM nav_bar") or die(mysqli_error());
                while ($row = mysqli_fetch_assoc($nav_bar)) {

                    $row_explode = 'http://' . $_SERVER['HTTP_HOST'] . '/' . explode("/", $_SERVER['REQUEST_URI'])[1] . '/' . $row['page'];



                    ?>
                    <li class="nav-item">
                        <a href="<?php echo($row_explode); ?>" class="nav-link"><?php echo($row['name']); ?></a>
                    </li>
                <?php } ?>
                <li>
                    <?php if ($_SESSION && $_SESSION['username']) { ?>
                        <a id="log-out" href="logout.php" class="nav-link"
                           title="Вийти"><?php echo $_SESSION['username']; ?></a>
                    <?php } else { ?>
                        <a href="login.php" class="nav-link" id="modal-btn">Увійти</a>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="boxer">
    <!--Первый блок-->

    <div class="row alert main">
        <div class="col-12 mt-5">
            <h1 class="display-5">Віхд</h1>
        </div>

    </div>
    <hr>
    <!--Второй блок-->
    <div class="auth">
        <?php

        // When form submitted, check and create user session.
        if (isset($_POST['username'])) {
            $username = stripslashes($_REQUEST['username']);    // removes backslashes
            $username = mysqli_real_escape_string($con, $username);
            $password = stripslashes($_REQUEST['password']);
            $password = mysqli_real_escape_string($con, $password);
            // Check user is exist in the database
            $query = "SELECT * FROM `users` WHERE username='$username'
                     AND password='" . md5($password) . "'";
            $result = mysqli_query($con, $query) or die(mysql_error());
            $rows = mysqli_num_rows($result);
            if ($rows == 1) {
                $_SESSION['username'] = $username;
                // Redirect to user dashboard page
                header("Location: ../pages/index.php");
            } else {
                echo "<div class='form'>
                  <h3>Неправильно введено ім'я чи пароль.</h3><br/>
                  <p class='link'>Спробуйте <a href='login.php'>увійти</a> ще раз.</p>
                  </div>";
            }
        } else {
            ?>
            <form method="post" name="login">

                <label>
                    <input required type="text" class="form-control" name="username" placeholder="Ім'я"
                           autofocus="true"/>
                </label>
                <label>
                    <input required type="password" class="form-control" name="password" placeholder="Пароль"/>
                </label>

                <div class="auth_button">
                    <p class="link"><a href="registration.php">Зареєструватися</a></p>
                    <input type="submit" value="Увійти" name="submit" class="btn auth_btn"/>
                </div>
            </form>
            <?php
        }
        ?>

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
