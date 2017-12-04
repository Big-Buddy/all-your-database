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

        public function updateMembership($object)
        {
            $sqlInsertPayment = "INSERT INTO `payments`(`PayingUserID`, `RentedSpaceID`, `AmountInCADCents`, `CardNumber`, `CardExpiryDate`, `CardSecurityCode`, `CardholderName`, `CardCompany`, `CardType`, `PaymentDate`) ";
            if ($object->rentedSpaceID == null) {
                //for some odd reason, if rented space id = null, it does not work, have to directly write null
                $sqlInsertPayment .= "VALUES ('$object->payingUserID',null,'$object->amountInCADCents','$object->cardNumber','$object->cardExpiryDate','$object->cardSecurityCode','$object->cardholderName', '$object->cardCompany','$object->cardType', NOW())";
            } else {
                $sqlInsertPayment .= "VALUES ('$object->payingUserID','$object->rentedSpaceID','$object->amountInCADCents','$object->cardNumber','$object->cardExpiryDate','$object->cardSecurityCode','$object->cardholderName', '$object->cardCompany','$object->cardType', NOW())";
            }
            $result = $this->returnResult($sqlInsertPayment);
            if ($result) {
                $sqlUpdateMembership = "UPDATE Users SET MembershipPlan='$object->membershipPlan' WHERE UserID = '$object->payingUserID'";
                $result = $this->returnResult($sqlUpdateMembership);
            }

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
