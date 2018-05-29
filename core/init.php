<?php
//host,username of the db,password,db
//data base object care e available pentru toate celelalte fisiere daca-l incluzi in ele
$db = mysqli_connect('127.0.0.1','root','','handmade_corner');

//verify if there are errors and kill the application if so
if(mysqli_connect_errno()) {
  echo 'Database connection failed with following errors: '.mysqli_connect_error();
  die(); //kills the page
}

session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/handmadeCorner/config.php';
require_once BASEURL.'helpers/helpers.php';

//incarca toate clasele de care avem nevoie ca sa nu le mai includem de fiecare data
require BASEURL.'vendor/autoload.php';

$cart_id = '';
//$domain = false;
if(isset($_COOKIE[CART_COOKIE])){
  $cart_id = sanitize($_COOKIE[CART_COOKIE]);//if this cookie exists, it will set it equal to cos id

}

if(isset($_SESSION['SBUser'])){
  $user_id = $_SESSION['SBUser'];
  $query = $db->query("SELECT * FROM users WHERE id = '$user_id'");
  $user_data = mysqli_fetch_assoc($query);
  //$fn = full name in tutorial - 22
//  $nume = explode(' ', $user_data['nume']);
//  $user_data['prenume'] = $nume[1];
}

if(isset($_SESSION['success_flash'])){
  echo '<div class="bg-success"><p class ="text-success text-center">'.$_SESSION['success_flash'].'</p></div>';
  unset($_SESSION['success_flash']);
}

if(isset($_SESSION['error_flash'])){
  echo '<div class="bg-danger"><p class ="text-danger text-center">'.$_SESSION['error_flash'].'</p></div>';
  unset($_SESSION['error_flash']);
}
//session_destroy();
