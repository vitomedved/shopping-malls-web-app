<?php

include_once 'php/userClass.php';
include_once 'php/connectionToDB.php';
include_once 'php/userFunctions.php';
include_once 'php/ducanFunctions.php';


$ime = 'Error with name';
$ocjena = 0;

//ako nije postavljen id ducana, preusmjeri na index
if(isset($_GET['id']) == false)
{
	header("Location: /RWA_ducani/index.php");
}
//ako je postavljen index ducana, provjeri ako taj id postoji u bazi
else
{
	$ducanId = $_GET['id'];
	$exist = doesDucanExist($_GET['id']);
	if(!$exist)
	{
		header("Location: /RWA_ducani/index.php");
	}
}

//ako smo tu ducan postoji pa parsiraj njegovo ime i rating
$ime = getDucanName($_GET['id']);


$ducan = getDucan($_GET['id']);
if(isset($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == true))
{
	$user = getUserObject($_SESSION['userId']);
	
	if($ducan->ime == $user->najDucan)
	{
		$_SESSION['newNotifications'] = 0;
	}
}

//sprema ocjenu korisnika za odredeni ducan ili ako je vec glasao, updejta mu ocjenu
if(isset($_POST['ocjena']))
{
	$canRate = ratedOnThisStore($_SESSION['userId'], $_GET['id']);
	$novaOcjena = $_POST['ocjena'];
	$updated = false;
	$added = false;
	
	if($canRate)
	{
		//Dodaje rating (userId, ducanId, $ocjena)
		$added = newRating($_SESSION['userId'], $_GET['id'], $novaOcjena);
	}
	else
		//inace znaci da je vec ocjenio pa updejtaj vrijednost
	{
		$updated = updateRating($_SESSION['userId'], $_GET['id'], $novaOcjena);
	}
	
	//ako je ocjena dodana ili updejtana, preusmjeri na stranicu ducana
	if($added || $updated)
	{
		header("Location: /RWA_ducani/ducan.php?id=".$_GET['id']);
	}
}

//sprema komentar u bazu
if(isset($_POST['sadrzaj']))
{
	$commentAdded = false;
	$sadrzaj = $_POST['sadrzaj'];
	if(isset($_POST['naslov']))
	{
		$naslov = $_POST['naslov'];
	}
	else
	{
		$naslov = '';
	}
	
	$commentAdded = addComment($_SESSION['userId'], $_GET['id'], $naslov, $sadrzaj);
	if($commentAdded)
	{
		header("Location: /RWA_ducani/ducan.php?id=".$_GET['id']);
	}
	else
	{
		echo("Ne mogu dodati komentar, linija 81, ducan.php");
	}
}

$ocjena = getRating($_GET['id']);
?>


<?php include_once 'php/header.php'; ?>

<div class="store-header">
      <div class="container">
        <p></p>
        <div class="row">
          <div class="col-md-6">
            <h1><span class="em-text"><?php echo $GLOBALS['ime'] ?></span></h1>
            <h3>Srednja ocjena: <?php echo $GLOBALS['ocjena']."/5 (".getNumOfComments($ducan->id).")"; ?></h3>
          </div>
          <div class="col-md-6">
          </div>
        </div>
      </div>
    </div>

    <section id="middle">
      <div id="container">
        <div class="row">
          <div class="col-md-8">
            <h2 class="em-text">Komentari korisnika</h2><hr><br>
            
			<?php
			
			$page = 0;
			$max = 3;
			
			//ukupno zapisa
			$total = getNumOfCommentsForDucan($_GET['id']);
			
			//koliko bude to ukupno stranica
			$pages = ceil($total / $max);
			
			if(!isset($_GET['page']) || $_GET['page'] == 1)
			{
				$page = 0;
			}
			else if($_GET['page'] <= $pages)
			{
				$page = ($_GET['page'] * $max) - $max;
			}
			else
			{
				header("Location: /RWA_ducani/ducan.php?id=".$_GET['id']);
			}
			
			listComments($_GET['id'], $page, $max);
			
			//echo "PAGING:";
			
			echo '<ul class="pagination pagination-lg">';
			for($i = 1; $i <= $pages; $i++)
			{
				echo "<li class='page-item'><a class='page-link' href='ducan.php?id=".$_GET['id']."&page=".$i."'>".$i."</a></li> ";
			}
			echo '</ul>';
			?>
          </div>
          <div class="col-md-4">
		  
            <img src="img/demo3.jpg" class="demo">
            <h3 class="em-text">
				
				<form action='ducan.php?id=<?php echo($_GET['id']) ?>' method='post'>
				Ocjeni:
				<select name = 'ocjena' class="selectpicker" <?php if(isGuest()) echo 'disabled'; ?>>
				  <option value='1'>1</option>
				  <option value='2'>2</option>
				  <option value='3'>3</option>
				  <option value='4'>4</option>
				  <option value='5'>5</option>
				</select>
				<input type='submit' value='Prihvati' <?php if(isGuest()) echo 'disabled'; ?>>
				</form>
			</h3> <br><h4> Adrese dućana:</h4>
			<?php
			echo "<br><ul>";
			foreach($ducan->adrese as $adresa)
			echo"
			<li>
				<h5>
					".$adresa->ulica." ".$adresa->kucniBroj.", ".$adresa->postanskiBroj.", ".$adresa->grad."
				</h5>
			</li>";
			
			echo "</ul>";
			?>
            
          </div>
			<form action='ducan.php?id=<?php echo($_GET['id']) ?>' method='post'>
				<div class="col-md-8">
					<label>Naslov</label>
					<input name='naslov' type="text" class="form-control" id="exampleInputName1" placeholder="Naslov Komentara" <?php if(isGuest()) echo 'disabled'; ?>>
					<label>Komentar</label>
					<textarea name='sadrzaj' class="form-control" rows="5" id="comment" placeholder="Komentar" <?php if(isGuest()) echo 'disabled'; ?>></textarea><br>
					<input type='submit' class="btn btn-xxl btn-yellow" <?php if(isGuest()) echo 'disabled'; ?> value='Komentiraj'>
				</div>
			</form>
        </div>
      </div>
    </section>

    <section id="php">
      
    </section>
	
<?php include_once 'php/footer.php'; ?>