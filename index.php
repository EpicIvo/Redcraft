<?php

require_once('database/settings.php');

try {
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
} catch (Exception $e) {
    //Output JSON to the outside world with 501 error
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

//Get the required data from the database
$query = "SELECT id, title, content, date FROM news ORDER BY id DESC";
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

//Output JSON to the outside world
echo json_encode($returnData);
