
<?php
//check if a user has permissions to be there
  require_once '../core/init.php';//for acces to db
  //daca nu e logat nu poate accesa pagina
  if(!is_logged_in()){
    login_error_redirect();
  }
  //daca nu are permisiune, redirectioneaza pe pagina de produse
  if(!has_permission('admin')){
    permission_error_redirect('administrator.php');
  }

  include 'includes/head.php';
  include 'includes/navigation.php';
  if(isset($_GET['delete'])){
    $delete_id = sanitize($_GET['delete']);
    $db->query("DELETE FROM users WHERE id = '$delete_id'");
    $_SESSION['success_flash'] = 'Userul a fost sters!';
    header('Location: users.php');
  }

  if(isset($_GET['add'])){
    $nume = ((isset($_POST['name']))?sanitize($_POST['name']):'');
    $email= ((isset($_POST['email']))?sanitize($_POST['email']):'');
    $parola = ((isset($_POST['password']))?sanitize($_POST['password']):'');
    $confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
    $permisiuni = ((isset($_POST['permissions']))?sanitize($_POST['permissions']):'');
    $errors = array();
    if($_POST){
      $emailQuery = $db->query("SELECT * FROM users WHERE email = '$email'");
      $emailCount = mysqli_num_rows($emailQuery);

      if($emailCount != 0){
        $errors[] = 'Acest email exista deja';
      }

      $required = array('name', 'email', 'password', 'confirm','permissions');
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
        header('Location: users.php');
      }
    }

    ?>
    <h2 class="text-center">Adauga un user nou</h2><hr>
    <form action="users.php?add=1" method="post">
      <div class="form-group col-md-6">
        <label for="name">Nume:</labe>
          <input type="text" name="name" id="name" class="form-control" value="<?=$nume;?>">
        </div>

        <div class="form-group col-md-6">
          <label for="email">Email:</labe>
            <input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
          </div>

          <div class="form-group col-md-6">
            <label for="nume">Parola:</labe>
              <input type="password" name="parola" id="password" class="form-control" value="<?=$parola;?>">
            </div>

            <div class="form-group col-md-6">
              <label for="confirm">Confirma parola:</labe>
                <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
              </div>

              <div class="form-group col-md-6">
                <label for="nume">Permisiuni:</labe>
                <select class="form-control" name="permissions">
                  <option value=""<?=(($permisiuni == '')?' selected':'');?>></option>
                  <option value="editor"<?=(($permisiuni == 'editor')?'selected':'');?>>Editor</option>
                  <option value="admin,editor"<?=(($permisiuni == 'admin,editor')?' selected':'');?>>Admin</option>
                </select>
                </div>

                <div class="form-group col-md-6 text-right">
                  <a href="users.php" class="btn btn-default">Cancel</a>
                  <input type="submit" value="Adauga user" class="btn btn-primary">
                </div>

    </form>
    <?php
  }else{

  $userQuery = $db->query("SELECT * FROM users ORDER BY full_name");

?>
<h2>Users</h2>
<a href="users.php?add=1" class="btn btn-success">Add a user</a>
<hr>
<table class="table table-bordered table-striped table-condensed">
  <thead>
    <th></th>
    <th>Name</th>
    <th>Email</th>
    <th>Register Date</th>
    <th>Last Login</th>
    <th>Permissions</th>

  </thead>
  <tbody>
    <?php while($user = mysqli_fetch_assoc($userQuery)): ?>
      <tr>
        <td>
          <?php if($user['id'] != $user_data['id']): ?>
            <a href="users.php?delete=<?=$user['id'];?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove-sign"></span></a>

          <?php endif; ?>

        </td>
        <td><?=$user['full_name'];?></td>
        <td><?=$user['email'];?></td>
        <td><?=pretty_date($user['join_date']);?></td>
        <td><?=(($user['last_login'] == '0000-00-00 00:00:00')?'Niciodata':pretty_date($user['last_login']));?></td>
        <td><?=$user['permissions'];?></td>

      </tr>
    <?php endwhile; ?>
    <tbody>



</table>
<?php } include 'includes/footer.php'; ?>

<a href="/handmadeCorner/index.php" class="btn btn-info" role="button">Inapoi la pagina principala</a>
