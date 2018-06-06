<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/handmadeCorner/core/init.php';
if(!is_logged_in()){
  login_error_redirect();
}
include 'includes/head.php';

$hashed = $user_data['password'];
$old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
$old_password = trim($old_password); //removes blank spaces
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password); //removes blank spaces
$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$confirm = trim($confirm); //removes blank spaces
$new_hashed = password_hash($password, PASSWORD_DEFAULT);
$user_id = $user_data['id'];
$errors = array();

?>

<!-- folosim email si password pt login pt ca emailu oricum e unic-->
<div id="login-form">
  <div>
    <?php
    //if submit button is pushed
      if($_POST){
        //form validation - daca toate sunt goale -> mesaj eroare
        if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])){
          $errors[] = 'Completati toate cele 3 campuri.';
        }

        //password is more than 6 characters
        if(strlen($password) < 6){
          $errors[] = 'Parola trebuie sa contina minim 6 caractere.';
        }

        //if new pass matched confirmed one
        if($password != $confirm){
          $errors[] = 'Parola noua si confirmarea noii parole nu sunt identice.';
        }

        if(!password_verify($old_password, $hashed)){
          $errors[] = 'Parola veche este incorecta. Incearca din nou.';
        }

        //check for errors
        if(!empty($errors)){
          echo display_errors($errors);
        }else{
          //change password_verify
          $db->query("UPDATE users SET password = '$new_hashed' WHERE id = '$user_id'");
          $_SESSION['success_flash'] = 'Parola ta a fost modificata cu succes!';
          header('Location: ../index.php');
        }
      }

    ?>

  </div>
  <h2 class="text-center">Modify password</h2><hr>
  <form action = "change_password.php" method="POST">
    <div class="form-group">
      <label for="old_password">Old Password:</label>
      <input type="password" name="old_password" id="old_password" class="form-control" value="<?=$old_password;?>">
    </div>
    <div class="form-group">
      <label for="password">New Password:</label>
      <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
    </div>
    <div class="form-group">
      <label for="confirm">Confirm New Password:</label>
      <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
    </div>
    <div class="form-group">
      <a href="administrator.php" class="btn btn-default">Cancel</a>
      <input type="submit" value="Login" class="btn btn-primary">
    </div>
  </form>
  <p class=text-right><a href="/handmadeCorner/index.php" alt="home">Visit Site</a></p>
</div>


<?php include 'includes/footer.php'; ?>
