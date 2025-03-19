<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-,Authorization,X-Requested-With');
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

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->author)) {
        if ($author->create($data->author)) {
            echo json_encode(
                array('message' => 'Author Created')
            );
        } else {
            echo json_encode(
                array('message' => 'Author Not Created')
            );
        }
    } else {
        echo json_encode(
            array('message' => 'Error: Author required')
        );
    }
