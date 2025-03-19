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
    include_once '../../models/Author.php';

    $database = new Database();
    $db = $database->connect();

    $author = new Author($db);

    $author->id = isset($_GET['id']) ? $_GET['id'] : die();

    $result = $author->read_single($_GET['id']);
    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(
            array('message' => 'author_id Not Found')
        );
    }