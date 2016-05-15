<?php
require_once '/Applications/XAMPP/htdocs/Redcraft/database/settings.php';

session_start();
if (!isset($_SESSION['loggedIn'])) {
    header("Location: login.php");
    exit;
}

try {
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
} catch (Exception $e) {
    //Output JSON to the outside world with 501 error
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

if (isset($_POST['submit'])) {

    $title = mysqli_escape_string($connection, $_POST['title']);
    $content = mysqli_escape_string($connection, $_POST['content']);
    $date = mysqli_escape_string($connection, $_POST['date']);

    $sqladdpost = "INSERT INTO news (title, content, date) VALUES ('$title','$content','$date') ";

    $orderresult = mysqli_query($connection, $sqladdpost);

    if ($orderresult) {
        echo "<div class='succes-message'>The post has been posted!</div>";
    }
    else{}

}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Redcraft - Content Management System</title>
    <link rel="stylesheet" type="text/css" href="style/cms.css">
    <script src='tinymce/js/tinymce/tinymce.min.js'></script>
    <script>
        tinymce.init({
            selector: '#content',
            theme: 'modern',
            width: 800,
            height: 400,
            plugins: [
                'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
                'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
                'save table contextmenu directionality emoticons template paste textcolor'
            ],
            content_css: 'css/content.css',
            toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons'
        });

    </script>
</head>
<body>

<div class="page-title">
    Redcraft - Content management system
</div>

<div class="post-form-container">

    <a href="editlist.php">
        <div class="edit-posts">
            Edit posts
        </div>
    </a>

    <div class="new-post-title">
        New post
    </div>

    <form class="post-form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>">

        <label for="title"></label>
        <input id="title" class="title" name="title" type="text" placeholder="title"><br>

        <div class="content-container">
            <label for="content"></label>
            <textarea id="content" class="content" name="content" cols="100" rows="20" placeholder="Today's news is..."></textarea><br>
        </div>

        <label for="date"></label>
        <input id="date" class="date" name="date" type="hidden" value="<?= date("Y-m-d") ?>">

        <input type="submit" name="submit" class="submit" value="Post">

    </form>

</div>

<div class="footer">

    <a href="logout.php">
        <div class="logout">
            log out
        </div>
    </a>

    <div class="name">
        You are logged in as: <?= $_SESSION['loggedIn']; ?>
    </div>

</div>
</body>
</html>
