<?php include_once 'php/header.php'; 

include_once 'php/userClass.php';
include_once 'php/connectionToDB.php';
include_once 'php/arhivaLogiranja.php';
include_once 'php/userFunctions.php';
include_once 'php/notifikacijaFunctions.php';



$email = '';

if(isset($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == true))
{
	header("Location: /RWA_ducani/index.php");
}

if(isset($_POST['email']))
{
	$email = $_POST['email'];
}

?>

<section id="login-Form">
	<div class="container">
	        <div class="row centered-form">
	        <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
	        	<div class="panel panel-default">
	        		<div class="panel-heading">
				    		<h3 class="panel-title">Ulogirajte se u svoj račun</h3>
				 			</div>
				 			<div class="panel-body">
				    		<form action='login.php' method='post'>
				    			<div class="form-group">
				    				<input type='email' name='email' id="email" class="form-control input-sm" placeholder="Email Adresa"required value=<?php echo($email)?>>
				    			</div>
				    			<div class="row">
				    				<div class="col-xs-12 col-sm-12 col-md-12">
				    					<div class="form-group">
				    						<input type="password" name="password" id="password" class="form-control input-sm" placeholder="Password" required>
				    					</div>
				    				</div>
				    			</div>
				    			
				    			<input type="submit" value="Ulogiraj se" class="btn btn-xxl btn-yellow btn-info btn-block">
								
								<?php
									if(isset($_POST['email']) && isset($_POST['password']))
									{
										$loggedIn = checkCredentials($_POST['email'], $_POST['password']);
										if($loggedIn == true)
										{
											echo("Uspješno logiranje!");
											logIn($_POST['email']);
											
											$user = getUserObject($_SESSION['userId']);
											
											//get timestamp zadnjeg logiranja
											$timestamp = lastLogged($_SESSION['userId']);
											
											//provjeri ako ima novih komentara
											$_SESSION['newNotifications'] = checkNotifications($user->id, $user->najDucan, $timestamp);
											header("Location: /RWA_ducani/index.php");
										}
										else
										{
											echo("<div class='alert alert-danger'>Email i odgovarajuća šifa se ne podudaraju </div>");
										}
									}
								?>
								
								<a href='index.php'> Povratak na početnu stranicu</a>, <a href='register.php'>Registracija</a>
				    		</form>
				    	</div>
		    		</div>
	    		</div>
	    	</div>
	    </div>
</section>
    





<?php include_once 'php/footer.php'; ?>

