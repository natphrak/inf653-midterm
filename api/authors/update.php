<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
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

    if (isset($data->id) && isset($data->author)) {
        $author->id = $data->id;
        $author->author = $data->author;
        if($author->update($author->id, $author->author)) {
            echo json_encode(
                array(
                    "id" => $author->id,
                    "author" => $author->author
                )
            );
        } else {
            echo json_encode(
                array('message' => 'Author not updated')
            );
        }
    } else {
        echo json_encode(
            array('message' => 'Invalid input')
        );
    }