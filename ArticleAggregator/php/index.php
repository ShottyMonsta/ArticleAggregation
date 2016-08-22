<?php
	//Set session path and start session if it's not already started!
	ini_set("session.save_path", "/home/unn_w13019529/sessionData");
	session_start();
?>

<!--Declare doctype-->
<!DOCTYPE html>
<html lang='en'>

<head>
	<!--Title
		meta description
		meta keywords
		meta author
		hook up to css
	-->
	<title>Article Aggregator</title>
	<meta name='description' content='aggregates various articles and allows users to save them' />
	<meta name='keywords' content='articles, aggregation, assignment' />
	<meta name='author' content='Thomas Cook' />
	<link rel='stylesheet' type='text/css' href='../css/base.css' />
</head>

<body>
	<!--Main div-->
	<div id='main'>
		<!--Common header across all pages-->
		<header>
			<!--Put logo text here for special styling-->
			<div id='logo'>
				<div id='logo_text'>
					<!--Style logo in two parts for snazzy look-->
					<h1><a href='index.php'>Article<span class='logo_colour'> Aggregator</span></a></h1>
					<h2>Aggregates all the latest articles for you to read at your leisure!</h2>
				</div>
			</div>
			<!--navigation bar procedurally generated-->
			<nav>
				<div id='menu_container'>
					<!--Start menu-->
					<ul class='menu' id='nav'>
						<!--Home is on every page.. no proc gen-->
						<li><a href='index.php'>Home</a></li>
						<!--Go into PHP scope-->
						<?php
							//If user is logged in...
							if (isset($_SESSION['loggedIn']))
							{
								$loggedIn = $_SESSION['loggedIn'];
								if ($loggedIn)
								{
									//Spit out 'Account/Saved Articles and 'Logout' drop down
									echo "
									<li><a href='#'>Account</a>
					              		<ul>
					                		<li><a href='savedUserArticlesPage.php'>Saved Articles</a></li>
					               			<li><a href='loginProcessor.php'>Logout</a>
					                  	</ul>
						            </li>";
								}
								//Else user not logged in...
								else
								{
									//Spit out 'Account/Login and Signup' drop down
									echo "
									<li><a href='#'>Account</a>
					              		<ul>
					                		<li><a href='loginPage.php'>Login</a></li>
					               			<li><a href='signupPage.php'>Sign Up</a>
					                  	</ul>
						            </li>";
								}
							}
							//Else user not logged in...
							else
							{
								//Spit out 'Account/Login and Signup' drop down
								echo "
									<li><a href='#'>Account</a>
					              		<ul>
					                		<li><a href='loginPage.php'>Login</a></li>
					               			<li><a href='signupPage.php'>Sign Up</a>
					                  	</ul>
						            </li>";
							}
							//^^THIS SEEMS DUMB... DUPLICATED CODE^^
						?>
					</ul>
				</div>
			</nav>
		</header>
		<!--Now we are into page specific stuff...
			The previous HEADER should have been put into a php function
				as the code is repeated on several pages... refactor!!
		-->
		<div id='site_content'>
			<div class='content'>
				<!--Start articles display-->
				<h1>Articles</h1>
				<!--Go into php scope-->
				<?php
					//Include required php files
					include ('articleProcessor.php');
					include ('updateArticles.php');
					
					//Currently everytime this page is refreshed the article updater is reading from an XML file and updating the db
					//This is really dumb and should only be called once every hour or so by the server...
					//See article updater/article processor classes for explanation
					$articleUpdater = new articleUpdater();
					$articleUpdater->updateArticles();
					
					//Create an article processor and get articles(not on saved page)
					$articleProcessor = new articleProcessor();
					$articleProcessor->getArticles(false);
				?>
			</div>
		</div>
		<!--Back to common html which is on every page...
			Should be in a seperate php file (code duplication/refactor required)-->
		<div id='scroll'>
			<!--This image scrolls the page up-->
			<a title='Scroll to the top' class='top' href='#'><img src='../images/top.png' alt='top' /></a>
		</div>
		<!--Standard footer, link back to home page-->
		<footer>
			<p><a href='index.php'>Home</a>
		</footer>
		</div>
	
		<!--Bring in the open source javascripts for fancy menu animations and expandable divs-->
		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" src="../js/jquery.easing-sooper.js"></script>
		<script type="text/javascript" src="../js/jquery.sooperfish.js"></script>
		<script type="text/javascript" src="../js/jquery.expander.js"></script>
		<script type="text/javascript">
			$(document).ready(
			function()
			{
				$('ul.menu').sooperfish();
				$('.top').click(function() {$('html, body').animate({scrollTop:0}, 'fast'); return false;});
				$('.expander').simpleexpand();
			});
		</script>
		
		<!--My javascript function for state transitions of save togglers-->
		<script type="text/javascript">
			function buttonToggle(button, id)
		    {
		    	if (button.value == "Not Ready")
		    	{
		    		button.value = "Ready";
		    		button.style.backgroundColor = "#4CAF50";
		    		document.getElementById(id).value = "true";
		    	}
		    	else
		    	{
		    		button.value = "Not Ready";
		    		button.style.backgroundColor = "#A9A9A9";
		    		document.getElementById(id).value = "false";
		    	}
		    }
		</script>
	</body>
</html>