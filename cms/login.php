<?php

session_start();
require_once "/Applications/XAMPP/htdocs/Redcraft/database/settings.php";


// Try to connect with the database to get the data
try {
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
} catch (Exception $e) {
    //Output JSON to the outside world with 501 error
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

//Is the user that is trying to log in already logged in?
if (isset($_SESSION['loggedIn'])) {
    header("Location: cms.php");
    exit;
}

// If you click on login the website checks if the username and password you filled in exist in the database
// If this is the case it sends you to the cms page
// If this is not the case, it shows an error message
if (isset($_POST['submit'])) {

    $name = mysqli_escape_string($connection, $_POST['name']);
    $password = mysqli_escape_string($connection, $_POST['password']);

    $queryAllUsers = "SELECT count(id) AS count FROM users WHERE name='$name' AND password='$password'";

    $result = mysqli_query($connection, $queryAllUsers);

    $array = mysqli_fetch_assoc($result);

    if ($array['count'] > 0) {

        $_SESSION['loggedIn'] = $name;

        header("Location: cms.php");
        exit;

    } else {
        echo "<div class='error'>your password doesn't appear in our database</div>";
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="style/login.css" type="text/css">
</head>
<body>

<div class="title">
    Login
</div>

<form class="form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>">

    <div class="name">
        <label class="name-label" for="name">Name:</label>
        <input class="name-input" type="text" name="name" id="name">
    </div><br>
    <div class="password">
        <label class="password-label" for="password">Wachtwoord:</label>
        <input class="password-input" type="password" name="password" id="password">
    </div>
    <input class="submit" type="submit" name="submit" value="Login"/>
</form>

</body>
</html>
