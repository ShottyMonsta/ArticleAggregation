<?php
	ini_set("session.save_path", "/home/unn_w13019529/sessionData");
	session_start();
	
	//Request the user details
	$userEmail = $_REQUEST['userEmail'];
	$userName = $_REQUEST['userName'];
	$password = $_REQUEST['password'];

	//Create a new signup processor
	$signupProcessor = new signupProcessor($userEmail, $userName, $password);

	//Object handles signing a new user up
	class signupProcessor
	{
		//Database reference
		private $db;
		//User details member vars
		private $userEmail;
		private $userName;
		private $password;

		//Constructor sets up the db connection
		//Sets the member vars
		//Hashes the passed password
		//Inserst new user into db
		public function __construct($userEmail, $userName, $password)
		{
			$this->db = new PDO("mysql:host=localhost; dbname=unn_w13019529", 'unn_w13019529', 'Kotor4Noway2');
			$this->userEmail = $userEmail;
			$this->userName = $userName;
			$this->password = password_hash($password, PASSWORD_DEFAULT);

			$this->insertUser();
		}

		//This inserts the user into the db
		function insertUser()
		{
			//Setup inster user statement
			$insertUserRequest = "INSERT INTO subscriber (email, name, password, lastLogin) VALUES ('$this->userEmail', '$this->userName', '$this->password', '')";
			$insertUserStatement = $this->db->prepare($insertUserRequest);
			$insertUserStatement->execute();

			//Setup user ID request so we can get the new user ID
			$getUserIDRequest = "SELECT subscriberID FROM subscriber WHERE email='$this->userEmail'";
			$getUserIDStatement = $this->db->prepare($getUserIDRequest);
			$getUserIDStatement->execute();

			//Get the user ID from the table
			$newUserID;
			while ($obj = $getUserIDStatement->fetchObject())
			{
				$row = (array) $obj;
				foreach ($row as $data)
				{
					$newUserID = $data;
					break;
				}
			}

			//Set the session variables
			$_SESSION['userEmail'] = $this->userEmail;
			$_SESSION['userID'] = $newUserID;
			$_SESSION['loggedIn'] = true;

			//Throw back to index.php
			header('Location: index.php');
			exit();
		}
	}
?>