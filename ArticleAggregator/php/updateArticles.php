<?php
	//This object is concerned with updating the articles table from the XML feed
   class articleUpdater
	{
		//Reference to rss feed url
		private $url;

		//Set the member variable to the xml url
		//We could do this dynamically so we can get different feeds
		//(if we changed the structure of the article processor)
		function __construct()
		{
			$this->url = 'http://www.articlegeek.com/rss/books_rss.xml';
		}

		//Updates the articles in the db
		public function updateArticles()
		{
			//If url exists then load the xml data and process
			if ($this->url != null)
			{
				//Load xml data
				$data = simplexml_load_file($this->url);

				//Query the data for just the items
				$qry = "channel/item";
				$items = $data->xpath($qry);

				$rowID = 0;
				
				//Get the database
				$db = new PDO("mysql:host=localhost; dbname=unn_w13019529", 'unn_w13019529', 'Kotor4Noway2');
		
				//Set up SQL to select links from articles table
				$articleRequest = "SELECT link FROM articles";
				
				//Iterate through the xml
				foreach ($items as $item)
				{
					//Set the article detail vars
					$title = $item->title;
					$link = $item->link;
					$description = $item->description;
					//Didn't have time to do the date :-/
					$date = "Today...";
					
					//Prepare and then execute the query
					$articleStatement = $db->prepare($articleRequest);
					$articleStatement->bindParam(':link', $article, PDO::PARAM_STR);
					
					//Get the article
					$articleStatement->execute();
					
					//Ascertain if this article already exists
					$hasItem = false;
					while ($obj = $articleStatement->fetchObject())
					{
						$row = (array) $obj;
						foreach ($row as $articleLink)
						{
							if ($link == $articleLink)
							{
								$hasItem = true;
							}
							else
							{
								$hasItem = false;
							}
						}								
					}
					//If article already exists we just update the record (for instance the description changed)
					if ($hasItem)
					{
						//HANDLE ARTICLE UPDATE HERE!!
					}
					//Else this article doesn't exist in the db so add a new row
					else			
					{
						//ADD NEW ARTICLE
						$sql = "INSERT INTO articles VALUES (:ID, :Title, :Link, :Description, :DateSaved)";
						
						//Prepare and then execute the query
						$stmt = $db->prepare($sql);
						$stmt->bindParam(':ID', $rowID, PDO::PARAM_INT);
						$stmt->bindParam(':Title', $title, PDO::PARAM_STR);
						$stmt->bindParam(':Link', $link, PDO::PARAM_STR);
						$stmt->bindParam(':Description', $description, PDO::PARAM_STR);
						$stmt->bindParam(':DateSaved', $date, PDO::PARAM_STR);
						
						$stmt->execute();
					}
					//++rowID
					$rowID += 1;
				}
			}
			else
			{
				//TODO - Handle XML file not being found
				//Probably link back to index and explain to user
				echo "URL not found!";
			}
		}
	}
?>

