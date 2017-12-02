<?php
    // MYSQL queries for the User Relation
    require '../Connection.php';

    class UserRepository {
        private $connection;
        public function __construct()
        {
            $this->connection = openConnection();
        }

        public function authenticateUser($email, $password)
        {
            $sql = "SELECT * FROM Users ";
            $sql .= "WHERE email='$email' AND password='$password';";
            $result = $this->connection->query($sql);
            closeConnection($this->connection);
            return $result;
        }
    }
 ?>
