<?php

session_start();
require_once "/Applications/XAMPP/htdocs/Redcraft/database/settings.php";

try {
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
} catch (Exception $e) {
    //Output JSON to the outside world with 501 error
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

//Hier wordt gekeken of de gebruiker al is ingelogd, als dat zo is dan wordt de gebruiker doorgestuurd naar de bestelpagina
if (isset($_SESSION['loggedIn'])) {
    header("Location: cms.php");
    exit;
}

// Als er op login wordt gedrukt dan checkt de code of de ingevoerde naam en het wachtwoord samen in de database voorkomen
// Als dat zo is dan stuurt de pagina je door naar de bestelpagina en anders zegt hij dat het niet klopt
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
