<?php
require_once 'core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

?>
<!-- I updated -->
<!-- Acum suntem pe parallax -->
<body id="main_page">
<div>
  <div>
  <?php
  if(has_permission('admin')): ?>
  <a href="/handmadeCorner/admin/administrator.php" class="btn btn-warning" role="button">Admin</a>
  <?php endif
  ?>
  <a href="shop.php" class="btn btn-info" role="button">Featured Products</a>
  <?php
      if(!is_logged_in()) {
  ?>
    <a href="admin/login.php" class="btn btn-warning" role="button">Log in</a>
  <a href="register.php" class="btn btn-success" role="button">Sign Up</a>
<?php } ?>
  <p><?php
echo 'Current PHP version: ' . phpversion();
?></p>
</div>
</div>
</body>

<?php

  include 'includes/footer.php';

 ?>
