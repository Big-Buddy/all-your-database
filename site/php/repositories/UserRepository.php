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
            $this->closeConnection();
            return $result;
        }

        public function createNewUser($user) 
        {
            $sqlCheckIfUserExists = "SELECT * FROM `users` WHERE UserID = 'sakuwsasdha@verizon.net' LIMIT 1";
            $userExists = $this->returnResult($sqlCheckIfUserExists);
            if ($userExists->num_rows > 0) {
                return false;
            }

            $sqlCreateNewUser = "INSERT INTO `users` (`UserID`, `Password`, `UserType`, `MembershipPlan`, `Phone`, 
            `Email`, `AddressStreet`, `AddressCity`, `AddressProvince`, `AddressCountry`, `AddressPostalCode`) ";
            $sqlCreateNewUser .= "VALUES ('$user->userID','$user->password','$user->userType','$user->membershipPlan','$user->phone','$user->email','$user->addressStreet',
            '$user->addressCity','$user->addressProvince','$user->addressCountry','$user->addressPostalCode');";

            return $this->returnResult($sqlCreateNewUser);
        }

        public function returnResult($sql) 
        {
            $result = $this->connection->query($sql);
            return $result;
        }    

        public function closeConnection()
        {
            closeConnection($this->connection);
        }

    }
 ?>
