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
    include_once '../../models/Quote.php';

    $database = new Database();
    $db = $database->connect();

    $quote = new Quote($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->quote) && isset($data->author_id) && isset($data->category_id)) {
        if ($quote->create($data->quote, $data->author_id, $data->category_id)) {
            echo json_encode(
                array('message' => 'Quote Created')
            );
        } else {
            echo json_encode(
                array('message' => 'Quote Not Created')
            );
        }
    } else {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }
