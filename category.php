<?php
  require_once 'core/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';
  include 'includes/leftbar.php';


if(isset($_GET['cat'])){
  $cat_id = sanitize($_GET['cat']);
}else{
  $cat_id = '';
}

  $sql = "SELECT * FROM products WHERE categories = '$cat_id'";
  //$recomandat = $featured in tutorial
  $productQ= $db->query($sql);
  $categorie = get_category($cat_id);

   ?>

  <!-- Main content-->
  <div class="col-md-8">
    <div class="row">
      <h2 class="text-center"><?=$categorie['parent']. ' - ' . $categorie['child'];?></h2>
        <?php
        //$produs = $product in tutorial
          while($produs = mysqli_fetch_assoc($productQ)) : ?>
          <div class="col-sm-3 text-center">
            <!--echo se mai scrie si ?= -->
            <h4><?= $produs['title']; ?></h4>
            <img src="<?= $produs['image']; ?>" alt="<?= $produs['title'];?>" class="img-size"/>
            <p class="price">Pret: <?= $produs['price'];?> lei</p>
            <button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?= $produs['id']; ?>)">Detalii</button>
          </div>
        <?php endwhile; ?>
   </div>
  </div>
<a href="/handmadeCorner/index.php" class="btn btn-info" role="button">Inapoi la pagina principala</a>
<?php
  include 'includes/rightbar.php';
  include 'includes/footer.php';

?>
