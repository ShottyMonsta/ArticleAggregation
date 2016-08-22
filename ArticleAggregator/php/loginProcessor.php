<?php
	
	ini_set("session.save_path", "/home/unn_w13019529/sessionData");
	session_start();

	//Get if the user is logged in...
	$loggedIn;
	if (isset($_SESSION['loggedIn']))
	{
		$loggedIn = $_SESSION['loggedIn'];
	}
	else
	{
		$loggedIn = false;
	}

	//Declare user vars
	$userEmail;
	$password;
	$userID;
	//If logged in
	if ($loggedIn)
	{
		//Create a new login processor object
		//(loggingIn = false, email|password|userID are irrelevant because user is logging out)
		$loginProcessor = new loginProcessor(!$loggedIn, "", "", "");
	}
	//If we are not logged in
	else
	{
		//Then request email and password
		$userEmail = $_REQUEST['userEmail'];
		$password = $_REQUEST['password'];
		//Assume user ID = 0
		$userID = 0;
		//Create new login processor object
		//(loggingIn = true, pass email, pass and id)
		$loginProcessor = new loginProcessor(!$loggedIn, $userEmail, $password, $userID);
	}


	//Object for processing login/out requests from user
	class loginProcessor
	{
		//Database reference
		private $db;
		
		//Declare user vars
		private $userEmail;
		private $password;
		private $userID;

		//Instantiate
		public function __construct($loggingIn, $userEmail, $password, $userID)
		{
			//If not logging in then call logout
			if (!$loggingIn)
			{
				$this->logout();
			}
			//Else we are logging in so initialize and login
			else
			{
				$this->init ($userEmail, $password, $userID);
				$this->login();
			}
		}

		//Set member variables
		function init($userEmail, $password, $userID)
		{
			//Connect to database
			$this->db = new PDO("mysql:host=localhost; dbname=unn_w13019529", 'unn_w13019529', 'Kotor4Noway2');
			$this->userEmail = $userEmail;
			$this->password = $password;
			$this->userID = $userID;
		}

		//Unset user name and logged state
		function logout()
		{
			$_SESSION['userName'] = null;
			$_SESSION['loggedIn'] = null;

			//Throw back to index.php
			header('Location: index.php');
			exit();
		}


		//Get user names from the databse and cross reference this user with the table
		//This means hashing the password and cross referencing against the hash in the db
		function login()
		{
			//Set up SQL to select the user id matching the user email
			$userIDRequest = "SELECT subscriberID FROM subscriber WHERE email='$this->userEmail'";

			//Prepare and then execute the query
			$userIDStatement = $this->db->prepare($userIDRequest);
			$userIDStatement->bindParam(':subscriberID', $ID, PDO::PARAM_INT);

			//get the user ID Table
			$userIDStatement->execute();

			//Get the user ID from the table
			while ($obj = $userIDStatement->fetchObject())
			{
				$row = (array) $obj;
				foreach ($row as $ID)
				{
					$this->userID = $ID;
					break;
				}
				break;
			}

			//Check if this is a valid user
			if ($this->isValidUser($this->userEmail))
			{
				//Check if the password is corret
				if (password_verify($this->password, $this->getUserHash($this->userEmail)))
				{
					//Ok the user is legit! Set session variables
					$_SESSION['userEmail'] = $this->userEmail;
					$_SESSION['userID'] = $this->userID;
					$_SESSION['loggedIn'] = true;
					
					//Throw back to index.php
					header('Location: index.php');
					exit();
				}
				//Password not legit! Access denied! This message will explode in 3...2...
				else
				{
					//Throw back to login page
					header('Location: loginPage.php');
					exit();
				}
			}
			//Come on you can't even remember your username?
			else
			{
				//Throw back to login page
				header('Location: loginPage.php');
				exit();
			}
		}

		//Returns true if passed user email is in the db
		function isValidUser($userEmail)
		{
			//Get the database
			$database = new PDO("mysql:host=localhost; dbname=unn_w13019529", 'unn_w13019529', 'Kotor4Noway2');

			//Set up SQL to select emails from subscriber table
			$emailRequest = "Select email FROM subscriber";

			//Prepare and then execute the query
			$emailStatement = $database->prepare($emailRequest);
			$emailStatement->bindParam(':email', $userEmail, PDO::PARAM_STR);

			//Get the email table
			$emailStatement->execute();

			//Return true if user name is in the database
			while ($obj = $emailStatement->fetchObject())
			{
				$row = (array) $obj;
				foreach ($row as $user)
				{
					if ($user == $userEmail)
					{
						return true;
					}
				}
			}
			return false;
		}

		//Returns the password hash of the passed user
		function getUserHash($userEmail)
		{
			//Get the database
			$database = new PDO("mysql:host=localhost; dbname=unn_w13019529", 'unn_w13019529', 'Kotor4Noway2');

			//Set up SQL to select users table
			$hashRequest = "Select password FROM subscriber WHERE email = '$userEmail'";

			//Prepare and then execute the query
			$hashStmt = $database->prepare($hashRequest);
			$hashStmt->execute();

			//Return true if user name is in the database
			while ($obj = $hashStmt->fetchObject())
			{
				$row = (array) $obj;
				foreach ($row as $hash)
				{
					//Return the hash
					return $hash;
				}
			}
		}
	}
?>