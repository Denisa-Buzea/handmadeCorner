<?php
require_once '../core/init.php';



if(isset($_POST['submit'])){
  $nume = mysqli_real_escape_string($db, $_POST['name']);
  $email= mysqli_real_escape_string($db, $_POST['email']);
  $parola = mysqli_real_escape_string($db, $_POST['parola']);
  $confirm = mysqli_real_escape_string($db, $_POST['confirm']);
  $permisiuni = "Normal user";
  $errors = array();
  if($_POST){
    $emailQuery = $db->query("SELECT * FROM users WHERE email = '$email'");
    $emailCount = mysqli_num_rows($emailQuery);

    if($emailCount != 0){
      $errors[] = 'Acest email exista deja';
    }

    $required = array('name', 'email', 'parola', 'confirm');
    //f = fullfilled
    foreach($required as $f){
      if(empty($_POST[$f])){
        $errors[] = 'Completati toate campurile';
        break;
      }
    }

    if(strlen($parola) < 6){
      $errors[] = 'Parola trebe sa aiba minim 6 caractere';
    }


    if($parola != $confirm){
      $errors[] = 'Parolele nu se potrivesc';
    }

    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
      $errors[] = 'Trebuie sa dati un email valid.';
    }

    if(!empty($errors)){
      echo display_errors($errors);
    }else{
      //add user to db
      $hashed = password_hash($parola, PASSWORD_DEFAULT);
      $db->query("INSERT INTO users (full_name, email, password, permissions) VALUES ('$nume','$email','$hashed','$permisiuni')");
      $_SESSION['success_flash'] = 'Userul a fost adaugat!';
      header('Location: ../index.php');
    }
  }
} else {
      header("Location: ../register.php");
      exit();
}

?>
