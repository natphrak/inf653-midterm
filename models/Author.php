<?php
    Class Author {
        // DB stuff
        private $conn;
        private $table = 'authors';

        // Author properties
        public $id;
        public $author;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Create author
        public function create($author) {
            // Create query
            $query = "INSERT INTO " . $this->table . " (author) VALUES (:author)";
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            // Bind
            $stmt->bindParam(':author', $author);
            // Execute query
            if($stmt->execute()) {
                $last_id = $this->conn->lastInsertId();
                return [
                    "id" => $last_id,
                    "author" => $author
                ];
            }
            // Print error if something goes wrong
            printf("Error: %s. \n", $stmt->error);

            return false;
        }

        public function read() {
            // Create query
            $query = "SELECT id, author FROM " . $this->table;
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            // Execute query
            $stmt->execute();

            // Returns all authors and their IDs
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function read_single($id) {
            // Create query
            $query = "SELECT id, author FROM " . $this->table . " WHERE id = :id";
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            // Bind
            $stmt->bindParam(':id', $id);
            // Execute query
            $stmt->execute();

            // Returns single author with ID
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function update($id, $author) {
            // Create query
            $query = "UPDATE " . $this->table . " SET author = :author WHERE id = :id";
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            // Bind
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':id', $id);
            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            return false;
        }

        public function delete($id) {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            return false;
        }
    }