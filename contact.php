<?php include_once 'php/header.php'; 

if(isset($_POST['email']))
{
	$email = $_POST['email'];
	$message = 'Pošiljatelj: '.$email;
	$subject = $_POST['naslov'];
	$from = $_POST['ime'];
	$message .= '\nMail od: '.$from;
	
	$message .= "\n".$_POST['message'];
	
	$to = 'vaeos12@gmail.com';
	
	$message = wordwrap($message, 70, "\r\n");
	
	//mail ($to , $subject , $message);
}

?>

    <section id="title-bar">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h1>Kontaktirajte nas</h1>
          </div>
        </div>
      </div>     
    </section>

    <section id="contact">
      <div id="container">
        <div class="row">
          <div class="col-md-8">
            <form action='contact.php' method='post'>
              <div class="form-group">
                <label>Ime i prezime</label>
                <input type="text" class="form-control" id="InputName1" placeholder="Ime i Prezime" name='ime' required>
              </div>
              <div class="form-group">
                <label>Email address</label>
                <input type="email" class="form-control" id="InputEmail1" placeholder="Email" name='email' required>
              </div>
              <div class="form-group">
                <label>Naslov</label>
                <input type="text" class="form-control" id="InputCompany1" placeholder="Ime Tvrtke" name='naslov' required>
              </div>
              <div class="form-group">
                <label>Poruka</label>
                <textarea class="form-control" placeholder="Unesite svoju poruku ovdje" name='message' required></textarea>
              </div>

              <button type="submit" class="btn btn-yellow">Pošalji</button>
            </form>
          </div>
          <div class="col-md-4">
            <img src="img/onama.jpg" class="demo">
            <h2 class="em-text"></h2>
            <h4>Treba li vam programsko rješenje ili aplikacija? Ovdje smo za vas!</h4>
          </div>
        </div>
      </div>
    </section>

    <section id="php">
      
    </section>
    
<?php include_once 'php/footer.php'; ?>
