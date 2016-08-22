<?php
    ini_set("session.save_path", "/home/unn_w13019529/sessionData");

	//Object for processing articles and outputting HTML table or form
	//Table is for logged out index page
	//Form is for logged in index and saved articles pages
	class articleProcessor
	{
		//Reference to the databse
		private $db;

		//Contsructor sets up the db connection
		function __construct()
		{
			$this->db = new PDO("mysql:host=localhost; dbname=unn_w13019529", 'unn_w13019529', 'Kotor4Noway2');
		}

		//This method is called after the object is constructed...
		//Works out what state the website is in and calls the relevant table/form method
		//The calling php script will tell this method if it is on the saved articles page
		public function getArticles($isSavedPage)
		{
			//Check if the loggedin session var is set
			if (isset($_SESSION['loggedIn']))
			{
				//It is... so temp store it
				$loggedIn = $_SESSION['loggedIn'];
				//If user is logged in, make form
				//Make saved articles form if on saved page
				//Else make full form
				if ($loggedIn)
				{
					if ($isSavedPage)
					{
						//Get the users ID
						//We know its set because we are logged in!
						$userID = $_SESSION['userID'];
						//Get the article details from the saved articles table where subscriberID=userID
						//INNER JOIN on articles (I am using a table to store all the articles and cross reference them in the saved_articles table)
						//This avoids repeating long strings from the articles multiple times for different users in the DB
						//Each article appears once and only once in the articles table
						//Many-Many relationship...each article can have multiple subs and each sub can have multiple articles...
						//Hence this structure was chosen: https://en.wikipedia.org/wiki/Many-to-many_(data_model)
						//Hopefully this isn't an assignment fail...
						$articleRequest = "SELECT articles.articleID, articles.title, articles.link, articles.description FROM saved_articles INNER JOIN articles ON saved_articles.articleID=articles.articleID WHERE subscriberID='$userID'";
						$this->makeForm($articleRequest, $isSavedPage);
					}
					else
					{
						//Select all article data from articles
						$articleRequest = "SELECT articleID, title, link, description FROM articles";
						$this->makeForm($articleRequest, $isSavedPage);
					}
				}
				else
				{
					//Set up SQL to select emails from subscriber table
					$articleRequest = "SELECT title, link, description FROM articles";
					$this->makeTable($articleRequest);
				}
			}
			else
			{
				//Set up SQL to select emails from subscriber table
				$articleRequest = "SELECT title, link, description FROM articles";
				$this->makeTable($articleRequest);
			}
		}

		//This method makes a table with $articleRequest articles in
		function makeTable($articleRequest)
		{
			//Prepare and then execute the query
			$articleStatement = $this->db->prepare($articleRequest);
			$articleStatement->bindParam(':title', $title, PDO::PARAM_STR);
			$articleStatement->bindParam(':link', $link, PDO::PARAM_STR);
			$articleStatement->bindParam(':description', $description, PDO::PARAM_STR);

			//Get the article table
			$articleStatement->execute();

			//Start the table
			echo "<table style='width:100%'>";

			//While there are objects(rows) left in the table pull them out
			while ($obj = $articleStatement->fetchObject())
			{
				//Cast to array
				$row = (array) $obj;

				//Article detail declarations
				$title;
				$link;
				$description;

				//Track iterations for hacky fix
				$iterations = 0;
	
				//Iterate through row data
				foreach ($row as $data)
				{
					//If 1st iter then we are looking at title
					if ($iterations == 0)
					{
						$title = $data;
					}
					//2nd iter... link
					else if ($iterations == 1)
					{
						$link = $data;
					}
					//3rd iter... description
					else if ($iterations == 2)
					{
						$description = $data;
					}
					//++iter
					$iterations += 1;
				}
				//^LOL^

				//Start table row
				echo "<tr>";
					//Start table data
					echo "<td>";
						//Spit out article data using 'expander' package structure...
						echo "<div class='article'>";
							echo "<a class='expander' href='#'>".$title."</a>";
							echo "<div class='hiddenContent'>";
								echo "</br><a href='".$link."'>".$link."</a>";
								echo "<p>".$description."</p>";
							echo "</div>";
						echo "</div>";
					echo "</td>";
				echo "</tr>";
			}
			//Finish table
			echo "</table>";
		}

		//This method makes a form with $articleRequest articles in it
		//If its saved page then we also alter the forms buttons
		function makeForm($articleRequest, $isSavedPage)
		{
			//Prepare and then execute the query
			$articleStatement = $this->db->prepare($articleRequest);
			$articleStatement->bindParam(':ID', $articleID, PDO::PARAM_INT);
			$articleStatement->bindParam(':title', $title, PDO::PARAM_STR);
			$articleStatement->bindParam(':link', $link, PDO::PARAM_STR);
			$articleStatement->bindParam(':description', $description, PDO::PARAM_STR);

			//Get the article table
			$articleStatement->execute();

			//Start form
			echo "<form action='userPrefsProcessor.php' method='post'>";
				//If we are on saved page
				if ($isSavedPage)
				{
					//Create the user remove
					echo "<input type='hidden' name='isSaving' value='false'>";
				}
				else
				{
					//Create the user save
					echo "<input type='hidden' name='isSaving' value='true'>";
				}

				//Start table within form
				echo "<table style='width:100%'>";
				
				//Track the article count (if its 0 at the end we don't show the "remove articles" button)
				$articleCount = 0;
				//While object still remains in the table fetch it
				while ($obj = $articleStatement->fetchObject())
				{
					//Cast to array
					$row = (array) $obj;

					//Declare article detail vars
					$id;
					$title;
					$link;
					$description;
					
					//Hacky iterations trick again...!
					$iterations = 0;
					//Iterate row	
					foreach ($row as $data)
					{
						//Same as table...
						if ($iterations == 0)
						{
							$id = $data;
						}
						else if ($iterations == 1)
						{
							$title = $data;
						}
						else if ($iterations == 2)
						{
							$link = $data;
						}
						else if ($iterations == 3)
						{
							$description = $data;
						}
						$iterations += 1;
					}

					//Start table row
					echo "<tr>";
						//Start table data
						echo "<td>";
							echo "<div class='article'>";
								echo "<a class='expander' href='#'>".$title."</a>";
								echo "<div class='hiddenContent'>";
									echo "</br><a href='".$link."'>".$link."</a>";
									echo "<p>".$description."</p>";
								echo "</div>";
							echo "</div>";
						//Close table data
						echo "</td>";
						//Start table data
						echo "<td class='saver'>";
							//If we are on saved page then make remove toggler
							if ($isSavedPage)
							{
								echo "<input type='button' value='Not Ready' onclick='buttonToggle(this, ".$id.")'>";
							}
							//Else we must be on index so make save toggler
							else
							{
								echo "<input type='button' value='Not Ready' onclick='buttonToggle(this, ".$id.")'>";
							}
							//Make hidden field for tracking button state
							echo "<input type='hidden' name='".$id."' id='".$id."' value='false'>";
						echo "</td>";
					//Close table row
					echo "</tr>";
					//++articleCount
					$articleCount += 1;
				}
				//Close table
				echo "</table>";
				//If we are on saved page
				if ($isSavedPage)
				{
					//And article count > 0
					if ($articleCount > 0)
					{
						//Create the user remove button
						echo "<input type='submit' class='Remove' value='Remove'>";
					}
					//Else user has no saved articles so let them know!
					else
					{
						echo "<p>You have no saved articles!</p>";
					}
				}
				else
				{
					//Create the user save button
					echo "<input type='submit' value='Save'>";
				}
			//Close form
			echo "</form>";
		}
	}
?>


