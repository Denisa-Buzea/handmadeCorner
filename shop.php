<?php
  require_once 'core/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';
  include 'includes/leftbar.php';


  $sql = "SELECT * FROM products WHERE featured = 1";
  //$recomandat = $featured in tutorial
  $featured = $db->query($sql);

   ?>

  <!-- Main content-->
  <div class="col-md-8">
    <div class="row">
      <h2 class="text-center">Featured Products</h2><br>
        <?php
        //$produs = $product in tutorial
          while($product = mysqli_fetch_assoc($featured)) : ?>
          <div class="col-sm-3 text-center">
            <!--echo se mai scrie si ?= -->
            <h4><?= $product['title']; ?></h4>
            <img src="<?= $product['image']; ?>" alt="<?= $product['title'];?>" class="img-size"/>
            <p class="price">Price: <?= $product['price'];?> RON</p>
            <button type="button" class="btn btn-sm btn-info" onclick="detailsmodal(<?= $product['id']; ?>)">Buy</button>
          </div>
        <?php endwhile; ?>
   </div>
  </div>
<a href="/handmadeCorner/index.php" class="btn btn-info" role="button">Back to the main page</a>
<?php
  include 'includes/rightbar.php';
  include 'includes/footer.php';

?>
