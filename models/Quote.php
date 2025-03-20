<?php
    Class Quote {
        // DB stuff
        private $conn;
        private $table = 'quotes';

        // Quotes properties
        public $id;
        public $quote;
        public $author_id;
        public $category_id;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Create quote
        public function create($quote, $author_id, $category_id) {
            // Create query
            $query = "INSERT INTO " . $this->table . " (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)";
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            // Bind
            $stmt->bindParam(':quote', $quote);
            $stmt->bindParam(':author_id', $author_id);
            $stmt->bindParam(':category_id', $category_id);
            // Execute query
            if($stmt->execute()) {
                // Get last inserted ID
                $last_id = $this->conn->lastInsertId();

                // then return quote information
                return [
                    "id" => $last_id,
                    "quote" => $quote,
                    "author_id" => $author_id,
                    "category_id" => $category_id
                ];
            }
            // Print error if something goes wrong
            printf("Error: %s. \n", $stmt->error);

            return false;
        }

        // Read all quotes unless there are parameters
        public function read($author_id = null, $category_id = null) {
            $query = "SELECT
                    q.id,
                    q.quote,
                    a.author,
                    c.category
                    FROM " . $this->table . " q
                    JOIN authors a ON q.author_id = a.id
                    JOIN categories c ON q.category_id = c.id
                    WHERE 1=1"; // returns true, so it makes possible to append queries

            // append to queries if there's an author_id or category_id
            if ($author_id) {
                $query .= " AND q.author_id = :author_id";
            }
            if ($category_id) {
                $query .= " AND q.category_id = :category_id";
            }

            // Bind
            $stmt = $this->conn->prepare($query);
            // Bind parameters if provided
            if ($author_id) {
                $stmt->bindParam(":author_id", $author_id);
            }
            if ($category_id) {
                $stmt->bindParam(":category_id", $category_id);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Read single
        public function read_single($id) {
            // Create query
            $query = "SELECT
                        q.id,
                        q.quote,
                        a.author,
                        c.category
                    FROM " . $this->table . " q 
                    JOIN authors a ON q.author_id = a.id 
                    JOIN categories c ON q.category_id = c.id 
                    WHERE q.id = :id";
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            // Bind
            $stmt->bindParam(':id', $id);
            // Execute query
            $stmt->execute();

            // Returns single quote with ID, author, and category
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // Update
        public function update($id, $quote, $author_id, $category_id) {
            // Create query
            $query = "UPDATE " . $this->table . "
                    SET
                    quote = :quote, 
                    author_id = :author_id, 
                    category_id = :category_id 
                    WHERE id = :id";
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            // Bind
            $stmt->bindParam(':quote', $quote);
            $stmt->bindParam(':author_id', $author_id);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':id', $id);

            // Execute query
            if ($stmt->execute()) {
                return [
                    "id" => $id,
                    "quote" => $quote,
                    "author_id" => $author_id,
                    "category_id" => $category_id
                ];
            }

            return false;
        }

        // Delete
        public function delete($id) {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            // Bind
            $stmt->bindParam(':id', $id);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            return false;
        }

    }