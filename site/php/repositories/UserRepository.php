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

        public function getUserObject($username)
        {
            $sql = "SELECT * FROM Users ";
            $sql .= "WHERE UserID='$username';";
            $result = $this->connection->query($sql);
            $this->closeConnection();
            return $result;
        }
        public function createNewUser($user) 
        {
            $sqlCheckIfUserExists = "SELECT * FROM `users` WHERE UserID = '$user->userID' OR Email = '$user->email' LIMIT 1";
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
        public function updateMembership($username, $payment, $newPlan){

            /*Update membership*/
            /*Only do step 2 if step 1 runs successfuly!*/
            /*Step 1: Send payment to DB */
            $sql = "INSERT INTO `Payments` (PayingUserID, RentedSpaceID, AmountInCADCents, CardNumber, CardExpiryDate, CardSecurityCode, CardholderName, CardCompany, CardType, PaymentDate) ";
            $sql .= "VALUES ('$username', NULL, '$payment->amountInCADCents', '$payment->cardNumber', '$payment->cardExpiryDate', '$payment->cardSecurityCode', '$payment->cardholderName', '$payment->cardCompany', '$payment->cardType', now()); ";
            $result = $this->returnResult($sql);
            /*Step 2: update membership */
            if($result){
                $sql = "Update Users set MembershipPlan = '$newPlan' where UserID = '$username';";
                $result = $this->connection->query($sql);
            }
            
            $this->closeConnection();
            return $result;
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
