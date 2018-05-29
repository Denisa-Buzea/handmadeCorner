<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/handmadeCorner/core/init.php';
  if(!is_logged_in()){
    login_error_redirect();
  }
  if(!has_permission('admin')){
    permission_error_redirect('../index.php');
  }
  include 'includes/head.php';
  include 'includes/navigation.php';

  $sql ="SELECT * FROM categories WHERE parent = 0";
  $result = $db->query($sql);
  $errors = array();
  $categorie = '';
  $post_parent = '';

  //Edit categorie
  if(isset($_GET['edit']) && !empty($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    $edit_sql = "SELECT * FROM categories WHERE id = '$edit_id'";
    $edit_result = $db->query($edit_sql);
    $edit_categorie = mysqli_fetch_assoc($edit_result);
  }


  //Delete categorie
  if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $delete_id = (int)$_GET['delete'];
    $delete_id = sanitize($delete_id);
    $sql = "SELECT * FROM categories WHERE id = '$delete_id'";
    $result = $db->query($sql);
    $categorie = mysqli_fetch_assoc($result);
    if($categorie['parent'] == 0){
      $sql = "DELETE FROM categories WHERE parent = '$delete_id'";
      $db->query($sql);
    }
    $deleteSql = "DELETE FROM categories WHERE id = '$delete_id'";
    $db->query($deleteSql);
    header('Location: categories.php');
  }

  //functionalitate - proceseaza form-ul
  if(isset($_POST) && !empty($_POST)){
    $post_parent = sanitize($_POST['parent']);
    $categorie = sanitize($_POST['category']);
    $sqlform = "SELECT * FROM categories WHERE category = '$categorie' AND parent = '$post_parent'";
    if(isset($_GET['edit'])){
      $id = $edit_categorie['id'];
      $sqlform = "SELECT * FROM categories WHERE category = '$categorie' AND parent = $post_parent AND id != '$id'";
    }
    $formResult = $db->query($sqlform);
    $count = mysqli_num_rows($formResult); //numara cate sunt in db cu stmt.-ul de la $sqlform

    //check if category is blank
    if($categorie == ''){
      $errors[].= 'Categoria nu poate fi necompletata.';
    }
    //if exist in the db
    if($count > 0){
      $errors[] = $categorie.' deja exista. Alege o noua categorie.';
    }
    //afiseaza erorile sau update db
    if(!empty($errors)){
      //display the errors
      $display = display_errors($errors); ?>
      <script>
        jQuery('document').ready(function(){
          jQuery('#errors').html('<?=$display;?>');
        });
      </script>
  <?php  }else{
      //update db
      $updatesql = "INSERT INTO categories (category, parent) VALUES ('$categorie','$post_parent')";
      if(isset($_GET['edit'])){
        $updatesql = "UPDATE categories SET category = '$categorie', parent = '$post_parent' WHERE id = '$edit_id'";
      }
      $db->query($updatesql);
      header('Location: categories.php');
    }
  }

  $valoare_categorie = '';
  $valoare_parent = 0;
  if(isset($_GET['edit'])){
    $valoare_categorie = $edit_categorie['category'];
    $valoare_parent = $edit_categorie['parent'];
  }else{
    if(isset($_POST)){
      $valoare_categorie = $categorie;
      $valoare_parent = $post_parent;
    }
  }

 ?>
<h2 class="text-center">Categories</h2><hr>
<div class="row">
  <!-- form -->
  <div class="col-md-6">
    <form class="form" action="categories.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
      <legend><?=((isset($_GET['edit']))?'Edit a ':'Add a ');?>Category</legend>
      <div id="errors"></div>
      <div class="form-group">
        <label for="parent">Parent</label>
        <select class="form-control" name="parent" id="parent">
          <option value="0"<?=(($valoare_parent == 0)?' selected="selected"':'');?>>Parinte</option>
          <?php while($parent = mysqli_fetch_assoc($result)) : ?>
            <option value="<?=$parent['id'];?>"<?=(($valoare_parent == $parent['id'])?' selected = "selected"':'');?>><?=$parent['category'];?></option>

          <?php endwhile;?>
        </select>
      </div>
      <div class="form-group">
        <label for="category">Category</label>
        <input type="text" class="form-control" id="category" name="category" value = "<?=$valoare_categorie;?>">
      </div>
      <div class="form-group">
        <input type="submit" value="<?=((isset($_GET['edit']))?'Edit ':'Add ');?>category" class="btn btn-success">
      </div>
    </form>
  </div>

  <!-- Tabel categorii -->
  <div class="col-md-6">
    <table class="table table-bordered">
      <thead>
        <th>Category</th><th>Parent</th><th></th>
      </thead>
      <tbody>
        <?php

        $sql ="SELECT * FROM categories WHERE parent = 0";
        $result = $db->query($sql);
        while($parent = mysqli_fetch_assoc($result)):
            $parent_id = (int)$parent['id'];
            $sql2= "SELECT * FROM categories WHERE parent = '$parent_id'";
            $childResult= $db->query($sql2);

        ?>
          <tr class="bg-primary">
            <td><?=$parent['category'];?></td>
            <td>Parent</td>
            <td>
              <a href="categories.php?edit=<?=$parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
              <a href="categories.php?delete=<?=$parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
            </td>
          </tr>
          <?php while($child = mysqli_fetch_assoc($childResult)): ?>
            <tr class="bg-info">
              <td><?=$child['category'];?></td>
              <td><?=$parent['category'];?></td>
              <td>
                <a href="categories.php?edit=<?=$child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                <a href="categories.php?delete=<?=$child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
              </td>
            </tr>
          <?php endwhile; ?>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</div>



<?php
  include 'includes/footer.php';

 ?>
