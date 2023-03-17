<?php
require('../auth/db.php');
ob_start();

if ($_POST && $_POST['name_paragraph'] && $_POST['id'] && $_POST['page'] && $_POST['description']) {

    $query = "UPDATE chapters SET name_paragraph = '" . $_POST['name_paragraph'] . "', description = '" .$_POST['description'] . "' WHERE id = '" . $_POST['id'] . "'";

    echo $query;
    $result = mysqli_query($con, $query) or die(mysqli_error());
    $page = $_POST['page'];

    header("Location: ../$page");

    exit(0);
}

