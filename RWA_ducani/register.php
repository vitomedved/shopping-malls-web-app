<?php 

include_once 'php/header.php';

include_once 'php/connectionToDB.php';
include_once 'php/arhivaLogiranja.php';
include_once 'php/userFunctions.php';
//include 'login.php';



$email = '';

?>

<section id="register-Form">
	<div class="container" id="register-Form">
	        <div class="row centered-form">
	        <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
	        	<div class="panel panel-default">
	        		<div class="panel-heading">
				    		<h3 class="panel-title">Registrirajte se na Kupac Life</h3>
				 			</div>
				 			<div class="panel-body">
				    		<form action='register.php' method='post'>
				    			<div class="form-group">
				    				<input type="email" name="email" id="email" class="form-control input-sm" placeholder="Email Adresa" required value=<?php echo($email)?>>
				    			</div>
				    			<div class="row">
				    				<div class="col-xs-6 col-sm-6 col-md-6">
				    					<div class="form-group">
				    						<input type="password" name="password" id="password" class="form-control input-sm" placeholder="Password" required>
				    					</div>
				    				</div>
				    				<div class="col-xs-6 col-sm-6 col-md-6">
				    					<div class="form-group">
				    						<input type="password" name="repeat_password" id="password_confirmation" class="form-control input-sm" placeholder="Potvrdite Password" required>
				    					</div>
				    				</div>
				    			</div>
								<div class="row">
									<div class="col-xs-6 col-sm-6 col-md-6">
				    					<div class="form-group">
				    						<input type="text" name="ime" id="ime" class="form-control input-sm" placeholder="Ime" required>
				    					</div>
				    				</div>
				    				<div class="col-xs-6 col-sm-6 col-md-6">
				    					<div class="form-group">
				    						<input type="text" name="prezime" id="prezime" class="form-control input-sm" placeholder="Prezime" required>
				    					</div>
				    				</div>						
								</div>
				    			
				    			<input type="submit" value="Registracija" class="btn btn-xxl btn-yellow btn-info btn-block">
				    		
								<?php
		
									if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['repeat_password']) && isset($_POST['ime']) && isset($_POST['prezime']))
									{
										$email = $_POST['email'];
										if($_POST['password'] == $_POST['repeat_password'])
										{
											$registered = userExist($_POST['email'], $_POST['password']);
											if($registered)
											{
												echo("<div class='alert alert-danger'>This email is already registered.</div>");
											}
											else
											{
												if(newUser($_POST['email'], $_POST['password'], $_POST['ime'], $_POST['prezime']))
												{
													$_SESSION['newNotifications'] = 0;
													header("Location: /RWA_ducani/index.php");
												}
												else
													echo("Ne mogu te registrirati");
											}
										}
										else
										{
											echo("<div class='alert alert-danger'>Passwords do not match</div>");
										}
									}
		
								?>
								<a href='index.php'> Povratak na početnu stranicu</a>, <a href='login.php'>Prijava</a>
				    		</form>
				    	</div>
		    		</div>
	    		</div>
	    	</div>
			
	    </div>
	</section>
    <?php
	include_once 'php/footer.php';
	?>