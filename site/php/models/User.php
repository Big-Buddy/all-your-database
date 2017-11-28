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

	    public function authenticateUser()
	    {
            $userRepository = new UserRepository();
            $result = $userRepository->authenticateUser($this->email, $this->password);
	    	return $result->fetch_assoc();
	    }
	}
?>
