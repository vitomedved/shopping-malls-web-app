<?php

include_once 'php/header.php';

include_once 'php/adresaFunctions.php';
include_once 'php/ducanFunctions.php';
include_once 'php/connectionToDB.php';

//ako nije postavljen id ducana, preusmjeri na index
if(isset($_GET['id']) == false)
{
	header("Location: /RWA_ducani/index.php");
}
//ako je postavljen index ducana, provjeri ako taj id postoji u bazi
else
{
	$exist = doesDucanExist($_GET['id']);
	if(!$exist)
	{
		header("Location: /RWA_ducani/index.php");
	}
}

$ducan = getDucan($_GET['id']);

if(isset($_GET['grad']) && isset($_GET['postanskiBroj']) && isset($_GET['kucniBroj']) && isset($_GET['ulica']))
{
	$added = newAdresa($_GET['id'], $_GET['ulica'], $_GET['grad'], $_GET['postanskiBroj'], $_GET['kucniBroj']);
	if($added)
	{
		echo 'spremljeno';
		header("Location: /RWA_ducani/urediDucan.php?id=".$_GET['id']);
	}
	else
	{
		echo 'nije spremljeno';	
	}
}

if(isset($_GET['imeDucana']))
{
	updateImeDucana($_GET['id'], $_GET['imeDucana']);
	header("Location: /RWA_ducani/urediDucan.php?id=".$_GET['id']);
}

if(isset($_GET['tipDucana']))
{
	updateTip($_GET['id'], $_GET['tipDucana']);
	header("Location: /RWA_ducani/urediDucan.php?id=".$_GET['id']);
}

if(isset($_GET['vrstaDucana']))
{
	updateVrsta($_GET['id'], $_GET['vrstaDucana']);
	header("Location: /RWA_ducani/urediDucan.php?id=".$_GET['id']);
}

if(isset($_FILES['image']) && !empty($_FILES['image']['name']))
{
	unlink($ducan->urlSlike);
	
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
	
	$target .= $ducan->ime."/";
	if(!file_exists($target))
	{
		mkdir($target);
	}
	
	if(!file_exists($target.$_FILES['image']['name']))
	{
		move_uploaded_file($_FILES['image']['tmp_name'], $target.$_FILES['image']['name']);
	}
	
	updateSlika($_GET['id'], $_FILES['image']['name']);
	
	
}

$ducan = getDucan($_GET['id']);

?>

<section id="top">
      <div id="container">
        <div class="row">
        	<div class="col-md-6">
				<form action='urediDucan.php?id=<?php echo $_GET['id']; ?>' method='get'>
					<h3 class="em-text">OSNOVNI PODACI:</h3><br>
					<h4>Ime dućana: <input type='text' name='imeDucana' placeholder='Ime' value='<?php echo($ducan->ime) ?>'><br></h4>
					<h4>Tip dućana prema artiklu:
					<select name='tipDucana' required>
						<!--<option value='odjeca'>Odjeća</option>
						<option value='pokloni'>Pokloni</option>
						<option value='sport'>Športska oprema</option>
						<option value='obuca'>Obuća</option>
						<option value='prehrana'>Prehrana</option>
						<option value='namjestaj'>Namještaj</option>
						<option value='igracke'>Igračke</option>
						<option value='tehnika'>Tehnička roba</option>-->
						<?php
						foreach(/*$ducan->tipovi*/getTipovi() as $key => $t)
						{
							if($ducan->tip == $key)
							{
								echo "<option value='".$key."' selected>".$t."</option>";
							}
							else
							{
								echo "<option value='".$key."'>".$t."</option>";
							}
						}
						?>
					</select> <?php //echo 'trenutno postavljeno: '.$ducan->tip ?></h4><br>
					<h4>Vrsta dućana prema veličini: 
					<select name='vrstaDucana' value='trgovina' required>
						<!--<option value='tvornica'>Tvornička prodaja</option>
						<option value='supermarket'>Supermarket</option>
						<option value='trgovina'>Trgovina</option>-->
						<?php
						foreach(/*$ducan->vrste*/getVrste() as $key => $v)
						{
							if($ducan->vrsta == $key)
							{
								echo "<option value='".$key."' selected>".$v."</option>";
							}
							else
							{
								echo "<option value='".$key."'>".$v."</option>";
							}
						}
						?>
					</select><?php //echo 'trenutno postavljeno: '.$ducan->vrsta ?></h4><br><br>
					<input type='hidden' name='id' value='<?php echo $_GET['id']; ?>'>	
					<input type='submit' class="btn btn-default" value='Spremi'><br>
				</form>
			</div>
			<div class="col-md-6">
				<form action='urediDucan.php?id=<?php echo $_GET['id']; ?>' enctype="multipart/form-data" method='post'>
					<input type='hidden' name='id' value='<?php echo $_GET['id']; ?>'>
					<?php echo "<img class='store-img' src='" . $ducan->urlSlike ."' >"; ?><br>
					<input type='file' name='image'><br>
					<input type='submit' value='Ažuriraj sliku' class='btn inline btn-success'>
				</form>
	    	</div>
			</div>
	    </div>
	</section>
	
	<hr>


<section id="contact">
      <div id="container">
        <div class="row">
          <div class="col-md-6">
            <form action='urediDucan.php?id=<?php echo $_GET['id']; ?>' method='get'>
				<h3 class="em-text">DODAJ ADRESU:</h3><br>
              <div class="form-group">
                <label>Grad</label>
                <input type="text" class="form-control" id="exampleInputName1" placeholder="Grad" name='grad' required>
              </div>
              <div class="form-group">
                <label>Poštanski broj</label>
                <input type="text" class="form-control" id="exampleInputCompany1" placeholder="Poštanski broj" name='postanskiBroj' required>
              </div>
              <div class="form-group">
                <label>Ulica</label>
                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Ulica" name='ulica' required>
              </div>
              <div class="form-group">
                <label>Kućni broj</label>
                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Kucni broj" name='kucniBroj' required>
              </div>
			  <input type='text' name='id' value='<?php echo $_GET['id']; ?>' hidden>	
              <button type="submit" class="btn btn-default">Dodaj</button>
            </form>
          </div>
		  <div class="col-md-6">
		  <?php
$ducan = getDucan($_GET['id']);
echo 'Postojeće adrese:<br>';
foreach($ducan->adrese as $adresa)
{
	echo"
			<li>
				<h5>
					".$adresa->ulica." ".$adresa->kucniBroj.", ".$adresa->postanskiBroj.", ".$adresa->grad."
				</h5>
			<a href='php/izbrisiAdresu.php?id=".$adresa->id."&ducanId=".$_GET['id']."'>Izbrisi ovu adresu</a></li><br>";
}

?>
		  </div>
        </div>
      </div>
    </section>
	<section id="contact">
		<div class='container'>
			<form action='php/izbrisiDucan.php'>
				<input type='hidden' name='id' value='<?php echo $_GET['id'] ?>'>
				<button class='btn btn-lg btn-danger'>IZBRISI DUĆAN</button>
			</form>
		</div>
	</section>
	<div style='height:100px;'></div>

<?php
include_once 'php/footer.php';
?>
