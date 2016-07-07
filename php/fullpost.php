<?php
require_once('/Applications/XAMPP/htdocs/Redcraft/database/settings.php');

$postId = $_GET['id'];

try {
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
} catch (Exception $e) {
    //Output JSON to the outside world with 501 error
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

//Get the required data from the database
$query = "SELECT id, title, content, date FROM news WHERE id='$postId'";
$result = $connection->query($query);

//Meta information about the returnData
$returnData['meta'] = [
    "request_uri" => $_SERVER['REQUEST_URI'],
    "query" => $query,
    "row_count" => $result->num_rows
];

//Merge the data from the database with the images from flickr into the newly created returnData
while ($row = $result->fetch_assoc()) {
    $returnData['newsposts'][] = [
        "id" => $row['id'],
        "title" => $row['title'],
        "content" => $row['content'],
        "date" => $row['date']
    ];
}

//Free the results and the connection
$result->close();
$connection->close();

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Full post</title>
    <link rel="stylesheet" href="../style/fullpost.css">
</head>
<body>

<div class="header">

    <div class="ip-adress-container">
        <div class="ip-adress-play-at">
            Play at:
        </div>
        <div class="ip-adress">
            play.redcraft.org
        </div>
    </div>

    <div class="menu-link">
        <div class="menu-link-icon">
            <a href="../html/home.html">
                <div class="menu-link-clickable">
                    a
                </div>
            </a>
        </div>
    </div>

    <div class="main-title">
        <div class="main-title-text">
            <img class="title-image" src="../media/title1.png" alt="title">
        </div>
    </div>

</div>

<div class="content">
    <div class="news-updates-post">
        <div class="news-updates-post-date">
            <?= $returnData["newsposts"][0]["date"] ?>
        </div>
        <div class="news-updates-post-title">
            <?= $returnData["newsposts"][0]["title"] ?>
        </div>
        <div class="news-updates-post-content">
            <?= $returnData["newsposts"][0]["content"] ?>
        </div>
    </div>

    <div class="comments">
        <div class="comments-title">
            Comments
        </div>
    </div>
</div>

</body>
</html>
