<?php
include("includes/controller.php");
/*
 * index.php
 *
 * This is an example of the index page of a website. Here users will be able to login. 
 * However, like on most sites the login form doesn't just have to be on the main page,
 * but re-appear on subsequent pages, depending on whether the user has logged in or not.
 *
 * Last Updated : October 17, 2014
*/
?>

<html>
<head>
    <title><?php echo $configs->getConfig('SITE_NAME'); ?> - Login</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: url(../img/theback1.jpg) no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}

.vertical-offset-100{
    padding-top:100px;
}
</style>
</head>
<body>
<?php
/**
 * User has already logged in, so display relavent links, including
 * a link to the admin center if the user is an administrator.
 */

if($session->logged_in){
?>
<?php
header('Location: dashboard.php');
?>
<?php
}
else{
$form = new Form;
/**
 * User not logged in, display the login form. If the user has already tried to login, 
 * but errors were found, they will be displayed.
 */
?>

<div class='login'>
<div class="container">
    <div class="row vertical-offset-100">
    	<div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h3 class="panel-title">Please sign in</h3>
			 	</div>
			  	<div class="panel-body">
			    	<form action="includes/process.php" method="POST">
                    <fieldset>
			    	  	<div class="form-group">
			    		    <input class="form-control" value="<?php echo Form::value("user"); ?>" placeholder="Username" name="user" type="text">
			    		    <?php echo Form::error("user"); ?>
			    		</div>
			    		<div class="form-group">
			    			<input class="form-control" value="<?php echo Form::value("pass"); ?>" placeholder="Password" name="pass" type="password" value="">
			    			<?php echo Form::error("pass"); ?>
			    		</div>
			    		
			    		<input class="btn btn-lg btn-success btn-block" type="submit" name="commit" value="Login">
			    		<input type="hidden" name="form_submission" value="login">
			    	</fieldset>
			      	</form>
			      	<p>Don't have an account?<a href="register.php"> Create one now!</a> Free!</p>
			    </div>
			</div>
		</div>
	</div>
</div>
</div>


      
<?php
}
?>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>