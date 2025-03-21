<?php
    class Database {
        // DB Params
        private $host;
        private $port;
        private $dbname;
        private $username;
        private $password;
        private $conn;

        public function __construct() {
            $this->username = getenv('USERNAME');
            $this->password = getenv('PASSWORD');
            $this->dbname = getenv('DBNAME');
            $this->host = getenv('HOST');
            $this->port = 5432;
        }

        // DB Connect
        public function connect() {
            if ($this->conn) {
                // if connection already exists, return it
                return $this->conn;
            } else {
                $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname};sslmode=require;";
                try {
                    $this->conn = new PDO($dsn, $this->username, $this->password);
                    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    return $this->conn;
                } catch(PDOException $e) {
                    echo 'Connection Error: ' . $e->getMessage();
                }
            }
        }
    }