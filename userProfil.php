<?php 

include_once 'php/header.php';

//include_once 'php/connectionToDB.php';
include_once 'php/userFunctions.php';
include_once 'php/komentarFunctions.php';

if(!isset($_GET['id']) || empty($_GET['id']))
{
	header("Location: /RWA_ducani/index.php");
}

$user = getUserObject($_GET['id']);

$me = new user();
$me->razinaOvlasti = 0;

if(!isGuest())
{
	$me = getUserObject($_SESSION['userId']);
}
?>

<div class="store-header">
      <div class="container">
        <p></p>
        <div class="row">
          <div class="col-md-6">
            <h1><span class="em-text"><?php echo $user->ime." ".$user->prezime; if($user->razinaOvlasti == 0) echo "<br>PLEB"; else echo "<br>ADMIN"; ?></span></h1>
			
			<?php
			
			if(($user->razinaOvlasti == 0) && ($me->razinaOvlasti == 1))
			{
				echo "<h4><a href='php/giveAdmin.php?userId=".$user->id."'>Dodijeli admina</a></h4>";
			}
			
			?>
			
			<h4><a href='mailto:<?php echo $user->email ?>'>pošalji e-mail (<?php echo $user->email ?>)</a></h4>
          </div>
		  <div class="col-md-6">
            <h2><span class="em-text"><?php echo "Najdraži dućan: ".$user->najDucan; ?></span></h2>
			<h3>Avatar:<br></h3>
			<img class='store-img' src='<?php echo $user->avatar ?>'></img>
          </div>
        </div>
      </div>
    </div>

    <section id="middle">
      <div id="container">
        <div class="row">
			<div class='col-md-8 offset-md-4'>
				<h2 class='em-text'>Komentari korisnika</h2><hr>
				<?php
				
				$page = 0;
				$max = 3;
				
				//ukupno zapisa
				$total = getNumOfCommentsForUser($_GET['id']);
				
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
				
				listUserComments($user->id, $page, $max);
				
				//echo "PAGING:";
				echo '<ul class="pagination pagination-lg">';
				for($i = 1; $i <= $pages; $i++)
				{
					echo "<li class='page-item'><a class='page-link' href='userProfil.php?id=".$_GET['id']."&page=".$i."'>".$i."</a></li> ";
				}
				echo '</ul>';
				
				?>
          </div>

        </div>
      </div>
    </section>

    <section id="php">
      
    </section>

<?php



if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

//ako nije postavljen id ducana, preusmjeri na index
if(isset($_GET['id']) == false)
{
	header("Location: /RWA_ducani/index.php");
}
//ako je postavljen index ducana, provjeri ako taj id postoji u bazi
else
{
	$ducanId = $_GET['id'];
	$exist = doesUserExist($_GET['id']);
	if(!$exist)
	{
		header("Location: /RWA_ducani/index.php");
	}
}

?>

<?php include_once 'php/footer.php';?>