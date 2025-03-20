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
    include_once '../../models/Category.php';

    $database = new Database();
    $db = $database->connect();

    $category = new Category($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id) && isset($data->category)) {
        $category->id = $data->id;
        $category->category = $data->category;
        if($category->update($category->id, $category->category)) {
            echo json_encode(
                array(
                    "id" => $category->id,
                    "author" => $category->category
                )
            );
        } else {
            echo json_encode(
                array('message' => 'Category not updated')
            );
        }
    } else {
        echo json_encode(
            array('message' => 'Invalid input')
        );
    }