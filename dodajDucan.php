<?php

include_once 'php/userClass.php';
//include_once 'php/connectionToDB.php';
include_once 'php/userFunctions.php';
include_once 'php/ducanFunctions.php';
include_once 'php/header.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

//nije admin
if(getUserObject($_SESSION['userId'])->razinaOvlasti == 0)
{
	header("Location: /RWA_ducani/index.php");
}

if(isset($_POST['imeDucana']) && isset($_POST['tipDucana']) && isset($_POST['vrstaDucana']) && isset($_FILES['image']))
{
	$exist = isDucanDuplicate($_POST['imeDucana']);
	
	//slika
	$_FILES['image']['name'] = explode(' ', $_FILES['image']['name']);
	$_FILES['image']['name'] = implode('_', $_FILES['image']['name']);
	
	$target = "images/";
	if(!file_exists($target))
	{
		mkdir($target);
	}
	
	$target .= "ducani/";
	if(!file_exists($target))
	{
		mkdir($target);
	}
	
	$target .= $_POST['imeDucana']."/";
	if(!file_exists($target))
	{
		mkdir($target);
	}
	
	if(!file_exists($target.$_FILES['image']['name']))
	{
		move_uploaded_file($_FILES['image']['tmp_name'], $target.$_FILES['image']['name']);
	}
	//
	
	if($exist)
	{
		echo("Taj dućan već postoji");
	}
	else
	{
		spremiDucan($_POST['imeDucana'], $_POST['tipDucana'], $_POST['vrstaDucana'], $_FILES['image']['name']);
	}
}

?>


<section id="login-Form">
	<div class="container">
	    <div class="row centered-form">
	    	<div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
		        	<div class="panel panel-default">

		        		<div class="panel-heading">
							<h3 class="panel-title">Dodaj dućan</h3>
						</div>

						<div class="panel-body">
							<form action='dodajDucan.php' method='post' enctype="multipart/form-data">
								<div class="form-group">
				    				<input type="text" name="imeDucana" class="form-control input-sm" placeholder="Ime dućana" required/>
				    			</div>


				    			<div class="col-xs-12 col-sm-12 col-md-12">
				    					<div class="row">
				    						<div class="col-xs-6 col-sm-6 col-md-6">
				    							<h4>Tip dućana:</h4>      
				    						</div>

				    						<div class="col-xs-6 col-sm-6 col-md-6">

												<select class="form-control btn-new" name='tipDucana' required>
													<?php
													
													foreach(getTipovi() as $key => $tip)
													{
														echo "<option value='".$key."'>".$tip."</option>";
													}
													
													?>
													<!--<option value='odjeca'>Odjeća</option>
													<option value='pokloni'>Pokloni</option>
													<option value='sport'>Športska oprema</option>
													<option value='obuca'>Obuća</option>
													<option value='prehrana'>Prehrana</option>
													<option value='namjestaj'>Namještaj</option>
													<option value='igracke'>Igračke</option>
													<option value='tehnika'>Tehnička roba</option>-->
												</select>
											</div>
				    					</div>
				    					<br>
				    			</div>

			    				<div class="col-xs-12 col-sm-12 col-md-12">
			    					<div class="row">
										<div class="col-xs-6 col-sm-6 col-md-6">
				    							<h4>Veličina dućana:</h4>
				    					</div>

				    					<div class="col-xs-6 col-sm-6 col-md-6">

											<select class="form-control btn-new" name='vrstaDucana' value='trgovina' data-style="btn-new" required>
												<?php
												
												foreach(getVrste() as $key => $vrsta)
												{
													echo "<option value='".$key."'>".$vrsta."</option>";
												}
												
												?>
												<!--<option value='tvornica'>Tvornička prodaja</option>
												<option value='supermarket'>Supermarket</option>
												<option value='trgovina'>Trgovina</option>-->
											</select><br>   
				    					</div>
									</div>
								</div>
								<div class="col-md-7 col-md-offset-3">
									<label class="btn btn-xxl btn-yellow btn-info" style="margin-bottom: 25px;">
									Uploadaj sliku<input type='file' name='image' style="display: none;">
									</label>
								</div>
								<input type='submit' class="btn btn-xxl btn-yellow btn-info btn-block"/><br>

								
							</form>
						</div>

					</div>
	</div>
</section>
<div style="height: 300px"></div>
<a href='index.php'> Povratak na početnu stranicu</a>

<?php 
	include_once 'php/footer.php'; 
?>