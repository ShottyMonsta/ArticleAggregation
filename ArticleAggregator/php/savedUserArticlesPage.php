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
		<!--Common header across all pages please see index.php for the comments-->
		<header>
			<div id='logo'>
				<div id='logo_text'>
					<h1><a href='index.php'>Article<span class='logo_colour'> Aggregator</span></a></h1>
					<h2>Aggregates all the latest articles for you to read at your leisure!</h2>
				</div>
			</div>
			<nav>
				<div id='menu_container'>
					<ul class='menu' id='nav'>
						<li><a href='index.php'>Home</a></li>
						<?php
							if (isset($_SESSION['loggedIn']))
							{
								$loggedIn = $_SESSION['loggedIn'];
								if ($loggedIn)
								{
									echo "
									<li><a href='#'>Account</a>
					              		<ul>
					                		<li><a href='savedUserArticlesPage.php'>Saved Articles</a></li>
					               			<li><a href='loginProcessor.php'>Logout</a>
					                  	</ul>
						            </li>";
								}
								else
								{
									echo "<li><a href='loginPage.php'>Login</a></li>";
								}
							}
							else
							{
								echo "<li><a href='loginPage.php'>Login</a></li>";
							}
						?>
					</ul>
				</div>
			</nav>
		</header>
		<div id='site_content'>
			<div class='content'>
				<!--Page specific
					Create a new article processor and get the saved article(saved page = true)
					-->
				<h1>Saved Articles</h1>
				<?php
					include ('articleProcessor.php');
					$articleProcessor = new articleProcessor();
					$articleProcessor->getArticles(true);
				?>
			</div>
		</div>
		<div id='scroll'>
			<a title='Scroll to the top' class='top' href='#'><img src='../images/top.png' alt='top' /></a>
		</div>
		<footer>
			<p><a href='index.php'>Home</a>
		</footer>
		</div>

		<!--See index.php-->
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
		<script type="text/javascript">
			//OK here is slightly different than index.php as the colors toggle between grey/red
			function buttonToggle(button, id)
		    {
		    	if (button.value == "Not Ready")
		    	{
		    		button.value = "Ready";
		    		button.style.backgroundColor = "#B22222";
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