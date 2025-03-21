<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'OPTIONS') {
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
        exit();
    }

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    $database = new Database();
    $db = $database->connect();

    $quote = new Quote($db);

    if (isset($_GET['author_id'])) {
        $author_id = $_GET['author_id'];
    } else {
        $author_id = null;
    }
    if (isset($_GET['category_id'])) {
        $category_id = $_GET['category_id'];
    } else {
        $category_id = null;
    }

    $result = $quote->read($author_id, $category_id);

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }