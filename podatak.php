<?php 
include_once 'php/header.php';

//include_once 'php/userClass.php';
//include_once 'php/connectionToDB.php';
include_once 'php/userFunctions.php';
include_once 'php/ducanFunctions.php';
include_once 'php/podatakFunctions.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

//samo ako je logiran moze tu
if(isGuest() /*|| !isset($_GET['id']) || ($_GET['id'] != $_SESSION['userId'])*/)
{
	header("Location: /RWA_ducani/index.php");
}

$ime = getIme($_SESSION['userId']);
$prezime = getPrezime($_SESSION['userId']);
$najDucan = getNajDucan($_SESSION['userId']);

$podatakPostoji = podatakExists($_SESSION['userId']);

if(!$podatakPostoji)
{
	$added = newPodatak($_SESSION['userId'], $ime, $prezime, $najDucan);
	if($added)
	{
		header("Location: /RWA_ducani/podatak.php");
	}
	else
	{
		echo("you're fucked");
	}
}

if(isset($_GET['imeKorisnika']))
{
	$ret = updateIme($_SESSION['userId'], $_GET['imeKorisnika']);
	if($ret)
	{
		header("Location: /RWA_ducani/podatak.php");
	}
	//echo 'ime nije dodano: linija 26, podatak.php';
	
}

if(isset($_GET['prezimeKorisnika']))
{
	$ret = updatePrezime($_SESSION['userId'], $_GET['prezimeKorisnika']);
	if($ret)
	{
		header("Location: /RWA_ducani/podatak.php");
	}
	//echo 'prezime nije dodano: linija 35, podatak.php';
}

if(isset($_GET['najDucan']))
{
	$ret = updateNajDucan($_SESSION['userId'], $_GET['najDucan']);
	if($ret)
	{
		header("Location: /RWA_ducani/podatak.php");
	}
	//echo 'naj ducan nije dodan: linija 44, podatak.php';
}

if(isset($_FILES['image']) && !empty($_FILES['image']['name']))
{
	$user = getUserObject($_SESSION['userId']);
	
	if(strcmp($user->avatar, 'images/users/default/avatar.png'))
	{
		unlink($user->avatar);
	}
	
	$_FILES['image']['name'] = explode(' ', $_FILES['image']['name']);
	$_FILES['image']['name'] = implode('_', $_FILES['image']['name']);
	
	$target = "images/";
	if(!file_exists($target))
	{
		mkdir($target);
	}
	
	$target .= "users/";
	if(!file_exists($target))
	{
		mkdir($target);
	}
	
	$target .= $_SESSION['userId']."/";
	if(!file_exists($target))
	{
		mkdir($target);
	}
	
	if(!file_exists($target.$_FILES['image']['name']))
	{
		move_uploaded_file($_FILES['image']['tmp_name'], $target.$_FILES['image']['name']);
	}
	
	updateAvatar($_SESSION['userId'], $_FILES['image']['name']);
}

?>

<!--<form action='podatak.php' method='get'>
	Ime: <input type='text' name='imeKorisnika' placeholder='Ime' value='<?php echo($_SESSION['user']->ime) ?>'><br>
	Prezime: <input type='text' name='prezimeKorisnika' placeholder='Prezime' value='<?php echo $_SESSION['user']->prezime ?>'><br>
	Najdraži dućan: 
	<select name='najDucan'>
		<?php
		//uzme sve ducane
		//$ducaniArray = getDucaniArray();
		//print options of stores
		/*foreach($ducaniArray as $ducan)
		{
			$isSelected= '';
			if($ducan->ime == $_SESSION['user']->najDucan)
			{
				$isSelected = 'selected';
			}
			echo "<option value='".$ducan->ime."' ".$isSelected.">".$ducan->ime."</option>";
		}*/
		
		?>
	</select><br>
	<input type='submit'>
</form>-->

<section id="middle">
      <div id="container">
        <div class="row">
        	<div class="col-md-3"></div>
          <div class="col-md-4">
            <form action='podatak.php?id=<?php echo $_SESSION['userId']; ?>' method='get'>
			  
              <div class="form-group">
			  <input type='hidden' name='id' value='<?php echo $_SESSION['userId']; ?>'>
                <label><h2>Ime</h2></label><br><input type='text' name='imeKorisnika' class="form-control" placeholder='Ime' value='<?php echo($ime) ?>'>
              </div>
              <div class="form-group">
                <label><h2>Prezime</h2></label><br><input type='text' name='prezimeKorisnika' class="form-control"  placeholder='Prezime' value='<?php echo $prezime ?>'>
              </div>
              <div class="form-group">
                <label><h2>Omiljeni dućan</h2></label><br><select name='najDucan'>
				<?php
				//uzme sve ducane
				$ducaniArray = getDucaniArray();
				//print options of stores
				$user = getUserObject($_SESSION['userId']);
				
				if($user->avatar)
				{
					$path = $user->avatar;
				}
				else
				{
					$path = 'images/users/default/avatar.png';
				}
				
				foreach($ducaniArray as $ducan)
				{
					$isSelected= '';
					if($ducan->ime == $user->najDucan)
					{
						$isSelected = 'selected';
					}
					echo "<option value='".$ducan->ime."' ".$isSelected.">".$ducan->ime."</option>";
				}
				?>
				</select>
				  <br><br><input type="submit" class="btn btn-xxl btn-yellow" value='Spremi'><br>
				  <div style="height: 200px;"></div>
				  <a href='index.php'> Povratak na početnu stranicu</a>
			  </div>
			</form>
		  </div>
		  <div class="col-md-5">
			<h2>Avatar</h2>
			<form action='podatak.php?id=<?php echo $_SESSION['userId']; ?>' enctype="multipart/form-data" method='post'>
					<div style="margin-bottom: 50px;">
						<input type='hidden' name='id' value='<?php echo $_SESSION['userId']; ?>'>
						<?php echo "<img class='store-img' src='" .$path."' >"; ?><br>
					</div>
					<label class="btn btn-xxl btn-yellow" style="margin-bottom: 25px;">
						Uploadaj sliku: <input type='file' 	name='image' style="display: none;">
					</label>

						<br>	
					<input type='submit' value='Ažuriraj sliku' class='btn btn-xxl btn-yellow'>
			</form>
		  </div>
		  <div class="col-md-1">
		  </div>
		</div>
	  </div>
</section>

<?php
include_once 'php/footer.php';
?>