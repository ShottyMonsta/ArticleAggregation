<?php
	ini_set("session.save_path", "/home/unn_w13019529/sessionData");
	session_start();
	
	//Are we saving or removing?
	$saving;
	if (isset($_POST['isSaving']))
	{
		$saving = $_POST['isSaving'];
		if ($saving == "false")
		{
			$saving = false;
		}
		else
		{
			$saving = true;
		}
	}
	else
	{
		$saving = false;
	}

	//Create a new prefs processor and call update
	$processor = new userPreferencesProcessor($saving);
	$processor->updateUserPrefs();

	//This object is concerned with saving/removing user->article relationships from the saved_articles table
	class userPreferencesProcessor
	{
		//db
		private $db;

		//Get the logged in user ID
		private $userID;

		//Is the user saving?
		private $isSaving;

		//Connect to db
		//Set member vars
		function __construct ($saving)
		{
			$this->db = new PDO("mysql:host=localhost; dbname=unn_w13019529", 'unn_w13019529', 'Kotor4Noway2');
			$this->userID = $_SESSION['userID'];
			$this->isSaving = $saving;
		}

		//If user is saving, then call save prefs
		//Else call remove prefs
		public function updateUserPrefs()
		{
			if ($this->isSaving)
			{
				//Set up SQL to select emails from subscriber table
				$saveRequest = "SELECT articleID FROM articles";
				$this->savePrefs($saveRequest);
			}
			else
			{
				//Set up SQL to select emails from subscriber table
				$removeRequest = "SELECT * FROM saved_articles WHERE subscriberID='$this->userID'";
				$this->removePrefs($removeRequest);
			}
		}

		//Saves the articles to the user->article relationship table
		function savePrefs($articleRequest)
		{
			//Prepare and then execute the query
			$articleStatement = $this->db->prepare($articleRequest);
			//Get the article table
			$articleStatement->execute();

			while ($obj = $articleStatement->fetchObject())
			{
				$row = (array) $obj;

				//Ascertain if this article is wanted
				$wantsArticle = false;
				$articleID = 0;
				foreach ($row as $ID)
				{
					if (isset($_REQUEST[$ID]))
					{
						//$ID comes from the hidden input fields which are linked to each toggler
						//See article processor for other end of system
						$wantsArticle = $_REQUEST[$ID];
						$articleID = $ID;
						break;
					}
				}
	
				//IF the user wants this article
				if ($wantsArticle == "true")
				{
					//Add foreign key for this user and map it to foreign key for this article
					$saveArticleRequest = "INSERT INTO saved_articles VALUES (:articleID, :subscriberID)";

					//Prepare and then execute the query
					$saveArticleRequest = $this->db->prepare($saveArticleRequest);
					$saveArticleRequest->bindParam(':articleID', $articleID, PDO::PARAM_INT);
					$saveArticleRequest->bindParam(':subscriberID', $this->userID, PDO::PARAM_STR);

					//Exectute request
					$saveArticleRequest->execute();
				}
			}
			
			//All articles have been iterated
			//Throw back to index.php
			header('Location: index.php');
			exit();
		}

		//Pretty much the same as save prefs but in reverse
		function removePrefs($articleRequest)
		{
			//Prepare and then execute the query
			$articleStatement = $this->db->prepare($articleRequest);
			//Get the article table
			$articleStatement->execute();

			while ($obj = $articleStatement->fetchObject())
			{
				$row = (array) $obj;

				$articleID;

				$iterations = 0;
				foreach ($row as $data)
				{
					if ($iterations == 0)
					{
						$articleID = $data;
					}
					$iterations += 1;
				}

				if (isset($_REQUEST[$articleID]))
				{
					$removeArticle = $_REQUEST[$articleID];
					if ($removeArticle == "true")
					{
						//Remove foreign key for this user and this article
						$removeArticleRequest = "DELETE FROM saved_articles WHERE articleID='$articleID' AND subscriberID='$this->userID'";

						//Prepare and then execute the query
						$removeArticleStatement = $this->db->prepare($removeArticleRequest);

						$removeArticleStatement->execute();
					}
				}
			}

			//Throw user back to savedArticlesPage.php
			header('Location: savedUserArticlesPage.php');
			exit();
		}
	}
?>