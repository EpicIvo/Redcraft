<?php
require_once '/Applications/XAMPP/htdocs/Redcraft/database/settings.php';

session_start();
if (!isset($_SESSION['loggedIn'])) {
    header("Location: login.php");
    exit;
}

$postId = $_GET['postId'];

try {
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
} catch (Exception $e) {
    //Output JSON to the outside world with 501 error
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

$deletequery = "DELETE FROM news WHERE id='$postId'";

$deletequeryresult = mysqli_query($connection, $deletequery);

if ($deletequery) {
    header("Location: editlist.php");
    exit;
}else{
    echo "something went wrong";
}


