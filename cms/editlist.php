<?php

session_start();
if (!isset($_SESSION['loggedIn'])) {
    header("Location: login.php");
    exit;
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit posts</title>
    <link rel="stylesheet" type="text/css" href="style/editlist.css">
</head>
<body>

<div class="page-title">
    Redcraft - Content management system
</div>

<div id="posts-list" class="posts-list">

    <div class="posts-list-title">
        What post do you want to edit?
    </div>

</div>

<div class="footer">

    <a href="cms.php">
        <div class="logout">
            Go back
        </div>
    </a>

    <div class="name">
        You are logged in as: <?= $_SESSION['loggedIn']; ?>
    </div>

</div>

<script src="js/cmsmain.js"></script>
</body>
</html>
