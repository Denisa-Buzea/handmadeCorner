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
      <?php endif  ?>
      <a href="shop.php" class="btn btn-info" role="button">Featured Products</a>
      <?php if(!is_logged_in()) {  ?>
        <a href="admin/login.php" class="btn btn-warning" role="button">Log in</a>
        <a href="register.php" class="btn btn-success" role="button">Sign Up</a>
      <?php } ?>
      <p><?php
    echo 'Current PHP version: ' . phpversion(); ?>
      </p>
    </div>
  </div>
  <div class="parallax-bg">
    <div class="container12">
      <ul id="scene">
        <li class="layer" data-depth="0.2">My first Layer!</li>
        <li class="layer" data-depth="0.6">My second Layer!</li>
        <li class="layer" data-depth="0.4">My first Layer!</li>
        <li class="layer" data-depth="0.8">My second Layer!</li>
      </div>
    </ul>
  </div>
  <script src="js/parallax.min.js"></script>
  <script>
    var scene = document.getElementById('scene');
    var parallax = new Parallax(scene, {
      relativeInput : true
    });
    parallax.friction(0.2, 0.2);
  </script>
</body>
<?php
  include 'includes/footer.php';
 ?>
