<!--Please see index.php for comments
	Any other comments in this doc are those specific to only this doc-->

<?php
	ini_set("session.save_path", "/home/unn_w13019529/sessionData");
	session_start();
?>


<!DOCTYPE html>
<html lang='en'>

<head>
	<title>Article Aggregator</title>
	<meta name='description' content='aggregates various articles and allows users to save them' />
	<meta name='keywords' content='articles, aggregation, assignment' />
	<meta name='author' content='Thomas Cook' />
	<link rel='stylesheet' type='text/css' href='../css/base.css' />
</head>

<body>
	<div id='main'>
		<header>
			<div id='logo'>
				<div id='logo_text'>
					<h1><a href='index.html'>Article<span class='logo_colour'> Aggregator</span></a></h1>
					<h2>Aggregates all the latest articles for you to read at your leisure!</h2>
				</div>
			</div>
			<nav>
				<div id='menu_container'>
					<ul class='menu' id='nav'>
						<li><a href='index.php'>Home</a></li>
							<li><a href='#'>Account</a>
			              		<ul>
			                		<li><a href='loginPage.php'>Login</a></li>
			               			<li><a href='signupPage.php'>Sign Up</a>
			                  	</ul>
				       	 	</li>
					</ul>
				</div>
			</nav>
		</header>
		<div id='site_content'>
			<div class='content'>
				<h1>Signup</h1>
				<!--
					Signup form, calls signup processor when submitted
					-->
				<form action = 'signupProcessor.php' class='loginForm' method = 'post'>
					<fieldset class='loginForm'>
						<label for = 'userEmail'>Email:</label>
						<input type = 'text' name = 'userEmail'/>
						<label for = 'userName'>Name:</label>
						<input type = 'text' name = 'userName'/>
						<label for = 'password'>Password:</label>
						<input type = 'password' name = 'password'/>
						<input type = 'submit' name = 'Signup' value = 'Signup' />
					</fieldset>
				</form>
			</div>
		</div>
		<div id='scroll'>
			<a title='Scroll to the top' class='top' href='#'><img src='../images/top.png' alt='top' /></a>
		</div>
		<footer>
			<p><a href='index.php'>Home</a>
		</footer>
		</div>

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

	</body>
</html>