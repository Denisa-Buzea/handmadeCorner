<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/handmadeCorner/core/init.php';
  $name = sanitize($_POST['full_name']);
  $email = sanitize($_POST['email']);
  $address = sanitize($_POST['address']);
  $city = sanitize($_POST['city']);
  $state = sanitize($_POST['state']);
  $zip_code = sanitize($_POST['zip_code']);
  $country = sanitize($_POST['country']);

  $errors = array();
  $required = array(
    //required fields - key si value - id si display name
    'full_name' => 'Fullname',
    'email' => 'Email',
    'address' => 'Address',
    'city' => 'City',
    'state' => 'State',
    'zip_code' => 'Zip Code',
    'country' => 'Country',
  );

  //check if all required fields are filled out
  foreach($required as $f => $d){
    if(empty($_POST[$f]) || $_POST[$f] == ''){
      $errors[] = $d.' este obligatoriu.';
    }
  }

  //CHECK IF EMAIL is valid
  if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    $errors[] = 'Please enter a valid email.';
  }


  if(!empty($errors)){
    echo display_errors($errors);
  }else{
    echo 'passed';
  }






 ?>
