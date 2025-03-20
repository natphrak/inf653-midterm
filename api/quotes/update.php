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
    include_once '../../models/Quote.php';

    $database = new Database();
    $db = $database->connect();

    $quote = new Quote($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id) && isset($data->quote) && isset($data->author_id) && isset($data->category_id)) {
        $quote->id = $data->id;
        $quote->quote = $data->quote;
        $quote->author_id = $data->author_id;
        $quote->category_id = $data->category_id;

        $updatedQuote = $quote->update($quote->id, $quote->quote, $quote->author_id, $quote->category_id);

        if($updatedQuote) {
            echo json_encode($updatedQuote);
        } else {
            echo json_encode(
                array('message' => 'Quote not updated')
            );
        }
    } else {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }