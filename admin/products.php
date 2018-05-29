<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/handmadeCorner/core/init.php';
if(!is_logged_in()){
  login_error_redirect();
}
//daca nu are permisiune, redirectioneaza pe pagina de produse
if(!has_permission('admin')){
  permission_error_redirect('administrator.php');
}
include 'includes/head.php';
include 'includes/navigation.php';

//delete produs
if(isset($_GET['delete'])){
  $id = sanitize($_GET['delete']);
  $db->query("UPDATE products SET deleted = 1 WHERE id = '$id'");
  header('Location: products.php');
}

$dbpath = '';
if(isset($_GET['add']) || isset($_GET['edit'])){
$parentQuery = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");
$nume_produs = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
$categorie = ((isset($_POST['child'])) && !empty($_POST['child'])?sanitize($_POST['child']):'');
$pret = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
$descriere = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');
$sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):'');
$sizes = rtrim($sizes,',');
$imagine_salvata = '';


//EDIT FUNCTION
  if(isset($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $rezultatProdus = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
    $produs = mysqli_fetch_assoc($rezultatProdus);
    if(isset($_GET['delete_image'])){
      $image_url = $_SERVER['DOCUMENT_ROOT'].$produs['image'];
      echo $image_url;
      unlink($image_url);//sterge imaginea din atom si db
      $db->query("UPDATE products SET image = '' WHERE id = '$edit_id'");
      header('Location: products.php?edit='.$edit_id);
    }
    $categorie = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):$produs['categories']);
    $nume_produs = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$produs['title']);
    $parentQ = $db->query("SELECT * FROM categories WHERE id = '$categorie'");
    $rezultatParent = mysqli_fetch_assoc($parentQ);
    $parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):$rezultatParent['parent']);
    $pret = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):$produs['price']);
    $descriere = ((isset($_POST['description']))?sanitize($_POST['description']):$produs['description']);
    $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):$produs['sizes']);
    $sizes = rtrim($sizes,',');
    $imagine_salvata = (($produs['image'] != '')?$produs['image']:'');
    $dbpath = $imagine_salvata;


  }

  if(!empty($sizes)){
    $sizeString = sanitize($sizes);
    $sizeString = rtrim($sizeString, ',');
    $sizesArray = explode(',',$sizeString);
    $sArray = array();
    $cArray = array();

    foreach($sizesArray as $ss){
      $s = explode(':', $ss);
      $sArray[] = $s[0];
      $cArray[] = $s[1];

    }
  }else{$sizesArray = array();}


$sizesArray = array();
if($_POST){
  $errors = array();
  $required = array('title','parent','child','price','sizes');
  foreach($required as $field){
    if($_POST[$field] == ''){
      $errors[] = 'Campurile cu * sunt obligatorii';
      break;
    }
  }



  if($_FILES['image']['name'] != ''){
    $imagine = $_FILES['image'];//incearca cu photo
    $name = $imagine['name'];
    $nameArray = explode('.',$name);
    $fileName = $nameArray[0];
    $fileExt = $nameArray[1];
    $mime = explode('/',$imagine['type']);
    $mimeType=$mime[0];
    $mimeExt= $mime[1];
    $tmpLoc = $imagine['tmp_name'];
    $fileSize = $imagine['size'];
    $allowed = array('png','jpg','jpeg','gif');
    $uploadName = md5(microtime()).'.'.$fileExt;
    $uploadPath = BASEURL.'images/'.$uploadName;
    $dbpath = '/handmadeCorner/images/'.$uploadName;
    if($mimeType != 'image'){
      $errors[] = 'Fisierul trebuie sa fie o imagine.';
    }
    if(!in_array($fileExt, $allowed)){
      $errors[] = 'Imaginea trebuie sa fie png, jpg.';
    }

  }

  if(!empty($errors)){
    echo display_errors($errors);
  }else{
    //upload file and insert into db
    if(!empty($_FILES)){
      move_uploaded_file($tmpLoc,$uploadPath);
    }
    $insertSql = "INSERT INTO products (`title`,`price`,`categories`,`image`,`description`,`sizes`)
      VALUES ('$nume_produs','$pret','$categorie','$dbpath','$descriere','$sizes')";
      if(isset($_GET['edit'])){
        $insertSql = "UPDATE products SET title = '$nume_produs', price = '$pret', categories = '$categorie', image = '$dbpath', description = '$descriere', sizes = '$sizes'
        WHERE id = '$edit_id'";
      }

      $db->query($insertSql);
      header('Location: products.php');
  }
}

// name="ceva" se pune si la ->$_GET['ceva']
?>
  <h2 class="text-center"><?=((isset($_GET['edit']))?'Editeaza':'Adauga');?> un produs</h2><hr>
  <form action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="POST" enctype="multipart/form-data">

    <div class="form-group col-md-6">
    </div>

    <div class="form-group col-md-3">
      <label for="title">Product Name:</label>
      <input type="text" name="title" class="form-control" id="title" value="<?=$nume_produs;?>">
    </div>


    <div class="form-group col-md-3">
      <label for="parent">Parent Category*:</label>
      <select class="form-control" id="parent" name="parent">
        <option value=""<?=(($parent == '')?'selected':'');?>></option>
        <?php while($p = mysqli_fetch_assoc($parentQuery)): ?>
          <option value="<?=$p['id'];?>"<?=(($parent == $p['id'])?'selected':'');?>><?=$p['category'];?></option>
        <?php endwhile; ?>
      </select>
    </div>

<div class="form-group col-md-6">
</div>


  <div class = "form-group col-md-3 ">
    <label for="child">Child Category*: </label>
    <select id="child" name="child" class="form-control">
    </select>
  </div>
<div class="form-group col-md-3">
  <label for="price">Price*: </label>
  <input type="text" id="price" name="price" class="form-control" value="<?=$pret;?>"></input>
</div>

<div class="form-group col-md-6">
</div>

<div class="form-group col-md-3">
  <label>Quantity and Sizes*:</label>
  <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle'); return false;">Cantitate si marimi</button>
</div>
<div class="form-group col-md-3">
  <label for="sizes">Sizes and Quantity Preview</label>
  <input type="text" class = "form-control" name="sizes" id="sizes" value="<?=$sizes;?>" readonly>
</div>

<div class="form-group col-md-6">
</div>

<div class="form-group col-md-3">
  <label for="description">Description: </label>
  <textarea id="description" name="description" class="form-control" rows="5" ><?=$descriere;?></textarea>
</div>


<div class="form-group col-md-3">
  <?php if($imagine_salvata != ''): ?>
    <div class= "saved-image">
      <img src="<?=$imagine_salvata;?>" alt="imagine salvata"/><br>
      <a href="products.php?delete_image=1&edit=<?=$edit_id;?>" class="text-danger">Sterge imaginea</a>
    </div>

  <?php else: ?>
    <label for="image">Product Photo: </label>
    <input type="file" id="image" name="image" class="form-control"></input>
  <?php endif; ?>
</div>


<div class="form-group pull-right">
  <a href="products.php" class="btn btn-default">Anulare</a>
  <input type="submit" value="<?=((isset($_GET['edit']))?'Editeaza':'Adauga');?> produs" class=" btn btn-success "></input>
  <div class="clearfix"></div>
</div>

</form>

<!-- Modal -->
<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="sizesModalLabel">Marimi si cantitate disponibile</h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
        <?php for($i=1 ; $i <= 4; $i++): ?>
          <div class="form-group col-md-4">
            <label for="size<?=$i;?>">Marime:</label>
            <input type="text" name="size<?=$i;?>"  id="size<?=$i;?>" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:'');?>" class="form-control"></input>
          </div>

          <div class="form-group col-md-2">
            <label for="quantity<?=$i;?>">Cantitate:</label>
            <input type="number" name="quantity<?=$i;?>"  id="quantity<?=$i;?>" value="<?=((!empty($cArray[$i-1]))?$cArray[$i-1]:'');?>" min="0" class="form-control"></input>
          </div>


        <?php endfor; ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Inchide</button>
        <button type="button" class="btn btn-primary" onclick="updateSizes(); jQuery('#sizesModal').modal('toggle'); return false;">Salveaza modificarile</button>
      </div>
    </div>
  </div>
</div>

<?php }else{
//deleted in bd  = sters
$sql = "SELECT * FROM products WHERE deleted = 0";
$rezultat_produs = $db->query($sql);
if (isset($_GET['featured'])){
   $id = (int)$_GET['id'];
   $featured = (int)$_GET['featured'];
   $recomandatSql = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
   $db->query($recomandatSql);
   header('Location: products.php');
}

 ?>
<h2 class="text-center">Products</h2>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-produs-btn">Add product</a><div class="clearfix"></div>
<hr>

<table class="table table-bordered table-condensed table-striped">
  <thead><th></th><th>Product</th><th>Price</th><th>Category</th><th>Featured</th><th>Sold</th></thead>
  <tbody>
    <?php while($produs = mysqli_fetch_assoc($rezultat_produs)):
      $childID = $produs['categories'];
      $categorieSql = "SELECT * FROM categories WHERE id = '$childID'";
      $rs = $db->query($categorieSql);
      $child = mysqli_fetch_assoc($rs);
      $parentID = $child['parent'];
      $parentSql = "SELECT * FROM categories WHERE id = '$parentID'";
      $parentRs = $db->query($parentSql);
      $parent = mysqli_fetch_assoc($parentRs);
      $categorie = $parent['category'].'~'.$child['category'];


       ?>
      <tr>
        <td>
          <a href="products.php?edit=<?=$produs['id'];?>" class = "btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
          <a href="products.php?delete=<?=$produs['id'];?>" class = "btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
        </td>
        <td><?=$produs['title'];?></td>
        <td><?=money($produs['price']);?></td>
        <td><?=$categorie;?></td>

        <td><a href="products.php?featured=<?=(($produs['featured'] == 0)?'1':'0');?>&id=<?=$produs['id'];?>" class="btn btn-xs btn-default">
          <span class="glyphicon glyphicon-<?=(($produs['featured'] == 1)?'minus':'plus');?>"></span>
        </a>&nbsp<?=(($produs['featured'] == 1)?'Elimina produsul de la recomandate':'Adauga produsul la recomandate');?></td>
        <td>0</td>

      </tr>
    <?php endwhile; ?>
  </tbody>
</table>


<?php } include 'includes/footer.php'; ?>

<script>
  jQuery('document').ready(function(){
    get_child_options('<?=$categorie;?>');
  });


</script>
