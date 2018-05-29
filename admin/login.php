<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/handmadeCorner/core/init.php';
include 'includes/head.php';

checkLogin();

$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email); //removes blank spaces
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password); //removes blank spaces
$errors = array();
?>
<style>
  body{
    background-image: url("/handmadeCorner/images/2560m.png");
    background-size: 100vw 100vh; /*view width/ view height-*/
    background-attachment: fixed;
  }

</style>

<!-- folosim email si password pt login pt ca emailu oricum e unic-->
<div id="login-form">
  <div>
    <?php
    //if submit button is pushed
      if($_POST){
        //form validation
        if(empty($_POST['email']) || empty($_POST['password'])){
          $errors[] = 'Completati campurile email si parola.';
        }

        //validate email - constanta - daca nu e un email corect
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
          $errors[] = 'Introduceti o adresa de email valida.';
        }

        //password is more than 6 characters
        if(strlen($password) < 6){
          $errors[] = 'Parola trebuie sa contina minim 6 caractere.';
        }

        //check if email exist in db
        $query = $db->query("SELECT * FROM users WHERE email = '$email'");
        $user = mysqli_fetch_assoc($query);
        $userCount = mysqli_num_rows($query);//daca exista emailul in db atunci e 1 (true)

        if($userCount < 1){
          $errors[] = 'Emailul este incorect.';
        }

        if(!password_verify($password, $user['password'])){
          $errors[] = 'Parola este incorecta. Incearca din nou.';
        }

        //check for errors
        if(!empty($errors)){
          echo display_errors($errors);
        }else{
          //log user in
          $user_id = $user['id'];//pt. ca user e asociativ array

          login($user_id);
        }
      }

    ?>

  </div>
  <h2 class="text-center">Login</h2><hr>
  <form action = "login.php" method="POST">
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="text" name="email" id="email" class="form-control" value="<?=$email;?>">
    </div>
    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
    </div>
    <div class="form-group">
      <input type="submit" value="Login" class="btn btn-primary">
    </div>
  </form>
  <p class=text-right><a href="/handmadeCorner/index.php" alt="home">Viziteaza site-ul</a></p>
</div>


<?php include 'includes/footer.php'; ?>
