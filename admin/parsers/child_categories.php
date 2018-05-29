<!--ajax request - cand cnv selecteaza ceva din categoria parinte
vom returna
optiunile pt child category prin ajax request-->

<?php

  require_once $_SERVER['DOCUMENT_ROOT'].'/handmadeCorner/core/init.php';

  $parentID = (int)$_POST['parentID'];
  $selected = sanitize($_POST['selected']);
  $childQuery = $db->query("SELECT * FROM categories WHERE parent = '$parentID' ORDER BY category");

//starts buffering
  ob_start();?>

    <option value=""></option>
    <?php while($child = mysqli_fetch_assoc($childQuery)): ?>
      <option value="<?=$child['id'];?>" <?=(($selected == $child['id'])?' selected':'');?>><?=$child['category'];?></option>
    <?php endwhile ; ?>
<!--releases memory-->
<?php echo ob_get_clean(); ?>
