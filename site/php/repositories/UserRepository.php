<?php
    // MYSQL queries for the User Relation
    include 'Connection.php';
    include 'models/User.php';
    include 'interfaces/UserRepositoryInterface.php';

    class UserRepository implements UserRepositoryInterface {
        private $connection;
        public function __construct() {
            $this->connection = openConnection();
        }

        public function authenticateUser(User $user) {
            $sql = "SELECT * FROM Users ";
            $sql .= "WHERE email='$user->email' AND password='$user->password'";
            $result = $this->connection->query($sql);

            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                $user = new User();
                $user->email = $row['email'];
                $user->password = $row['password'];
                return $user;
              }
            }
        }
    }
 ?>
