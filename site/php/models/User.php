<?php
	require_once '../repositories/UserRepository.php';
	class User {
		// For the sake of the project, we will declare all the fields public
	    public $userId;
	    public $password;
	    public $userType;
	    public $membershipPlan;
	    public $phone;
	    public $email;
	    public $addressStreet;
	    public $addressCity;
	    public $addressProvince;
	    public $addressCountry;
	    public $addressPostalCode;

	    public function authenticateUser($email, $password)
	    {
            $userRepository = new UserRepository();
            $result = $userRepository->authenticateUser($email, $password);
            if ($result->num_rows > 0) {
            	while ($row = $result->fetch_assoc()) {
            		$this->email = $row["email"];
            		$this->password = $row["password"];
            	}
            	return 'success';
            } else {
            	return null;
            }
	    }
	}
?>
