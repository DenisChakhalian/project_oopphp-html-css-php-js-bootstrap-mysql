<?php
require('../auth/db.php');

if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
    $query = "DELETE FROM comments WHERE id = '" . $id . "'";
    $result = mysqli_query($con, $query) or die(mysqli_error());
    exit(0);
}


