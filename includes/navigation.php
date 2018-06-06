<?php
$sql = "SELECT * FROM categories WHERE parent = 0";
$parentQuery = $db->query($sql);//use our db object, run a method of query and run our sql statemnt
?>

  <!-- navigation bar top -->
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <a href="index.php" class="navbar-brand">Coltisorul Handmade</a>
    <ul class="nav navbar-nav">
      <!-- loop list item for each parent, face un associative array si stocheaza valorile -->
        <?php while($parent = mysqli_fetch_assoc($parentQuery)) : ?>
          <?php
          $parent_id = $parent['id'];
          $sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
          $childQuery = $db->query($sql2);
          ?>

      <!-- Menu Items -->
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category']; ?><span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
          <?php while($child = mysqli_fetch_assoc($childQuery)) : ?>
            <li><a href="category.php?cat=<?=$child['id'];?>"><?php echo $child['category']; ?></a></li>
          <?php endwhile; ?>
        </ul>
      </li>
    <?php endwhile; ?>

    <li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> My Cart</a></li>
    <?php if(is_logged_in()){ ?>
    <li class="dropdown">
      <a href="#" clas="dropdown-toggle" data-toggle="dropdown">Hello <?=$user_data['full_name'];?>!
        <span class="caret"></span>
      </a>
      <ul class="dropdown-menu" role="menu">
        <li><a href="admin/change_password.php">Change password</a></li>
        <li><a href="admin/logout.php">Log out</a></li>
      </ul>
    </li>
  <?php } ?>
  </ul>


  </div>
</nav>
