<?php
    Class Category {
        // DB stuff
        private $conn;
        private $table = 'categories';

        // Category properties
        public $id;
        public $name;
        public $bio;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Create category
        public function create($category) {
            // Create query
            $query = "INSERT INTO " . $this->table . " (category) VALUES (:category)";
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            // Bind
            $stmt->bindParam(':category', $category);
            // Execute query
            if($stmt->execute()) {
                return true;
            }
            // Print error if something goes wrong
            printf("Error: %s. \n", $stmt->error);

            return false;
        }

        public function read() {
            // Create query
            $query = "SELECT id, category FROM " . $this->table;
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            // Execute query
            $stmt->execute();

            // Returns all categories and their IDs
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function read_single($id) {
            // Create query
            $query = "SELECT id, category FROM " . $this->table . " WHERE id = :id";
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            // Bind
            $stmt->bindParam(':id', $id);
            // Execute query
            $stmt->execute();

            // Returns single category with ID
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function update($id, $category) {
            // Create query
            $query = "UPDATE " . $this->table . " SET category = :category WHERE id = :id";
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            // Bind
            $stmt->bindParam(':category', $category);
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