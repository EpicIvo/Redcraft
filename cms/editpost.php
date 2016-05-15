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

$postid = $_GET['postId'];

//Get the required data from the database
$query = "SELECT id, title, content, date FROM news WHERE id=$postid";
$result = $connection->query($query);

//Meta information about the returnData
$returnData['meta'] = [
    "request_uri" => $_SERVER['REQUEST_URI'],
    "query" => $query,
    "row_count" => $result->num_rows
];

//Merge the data from the database with the images from flickr into the newly created returnData
while ($row = $result->fetch_assoc()) {
    $returnData['newsposts'] = [
        "id" => $row['id'],
        "title" => $row['title'],
        "content" => $row['content'],
        "date" => $row['date']
    ];
}

if (isset($_POST['submit'])) {

    $title = mysqli_escape_string($connection, $_POST['title']);
    $content = mysqli_escape_string($connection, $_POST['content']);
    $date = mysqli_escape_string($connection, $_POST['date']);

    $sqladdpost = "UPDATE news SET title='$title',content='$content',date='$date' WHERE id='$postid'";

    $orderresult = mysqli_query($connection, $sqladdpost);

    if ($orderresult) {
        header("Location: cms.php");
        exit;
    }


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

    <div class="new-post-title">
        Edit post
    </div>

    <form class="post-form" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>">

        <label for="title"></label>
        <input id="title" class="title" name="title" type="text" placeholder="title" value="<?= $returnData['newsposts']['title'] ?>"><br>

        <div class="content-container">
            <label for="content"></label>
            <textarea id="content" class="content" name="content" cols="100" rows="20" ><?= $returnData['newsposts']['content'] ?></textarea><br>
        </div>

        <label for="date"></label>
        <input id="date" class="date" name="date" type="hidden" value="<?= date("Y-m-d") ?>">

        <input type="submit" name="submit" class="submit" value="Save">

    </form>

</div>

<div class="footer">

    <a href="editlist.php">
        <div class="logout">
            Go back
        </div>
    </a>

    <div class="name">
        You are logged in as: <?= $_SESSION['loggedIn']; ?>
    </div>

</div>
</body>
</html>
