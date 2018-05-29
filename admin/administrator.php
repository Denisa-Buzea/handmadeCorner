<!-- administrator  = index.php in tutorial - index pt. admin page-->
<?php
  require_once '../core/init.php';//for acces to db
  //daca nu e logat nu poate accesa pagina
  if(!is_logged_in()){
    header('Location: login.php');
  }
  if(!has_permission('admin')){
    permission_error_redirect('../index.php');
  }
  include 'includes/head.php';
  include 'includes/navigation.php';
  //session_destroy();
  ?>
Administrator Page


<!-- orders  to fill -->
<?php
  $txnQuery = "SELECT t.id, t.cart_id, t.full_name, t.description, t.txn_date, t.grand_total, c.items, c.paid,c.shipped
              FROM transactions t
              LEFT JOIN cart c ON t.cart_id = c.id
              WHERE c.paid = 1 AND c.shipped = 0
              ORDER BY t.txn_date";

  $txnResults = $db->query($txnQuery);

 ?>
<div class="col-md-12">
  <h3 class="text-center">Orders to Ship</h3>
  <table class="table table-condensed table-bordered table-striped">
    <thead>
      <th></th>
      <th>Name</th>
      <th>Description</th>
      <th>Total</th>
      <th>Date</th>
    </thead>

    <tbody>
      <?php while($order = mysqli_fetch_assoc($txnResults)): ?>
      <tr>
        <td><a href="orders.php?txn_id=<?=$order['id'];?>"class="btn btn-xs btn-info">Details</a></td>
        <td><?=$order['full_name'];?></td>
        <td><?=$order['description'];?></td>
        <td><?=money($order['grand_total']);?></td>
        <td><?=pretty_date($order['txn_date']);?></td>
      </tr>
    <?php endwhile; ?>
    </tbody>

  </table>

</div>

<div clas="row">
  <!--Sales By Month-->
  <?php
  $thisYr = date("Y");
  $lastYr = $thisYr - 1;
  $thisYrQ = $db->query("SELECT grand_total, txn_date FROM transactions WHERE YEAR(txn_date)= '{$thisYr}'");
  $lastYrQ = $db->query("SELECT grand_total, txn_date FROM transactions WHERE YEAR(txn_date)= '{$lastYr}'");
  $current = array();
  $last = array();
  $currentTotal = 0;
  $lastTotal = 0;
  while($x = mysqli_fetch_assoc($thisYrQ)){
    $month = date("m",strtotime($x['txn_date']));
    if(!array_key_exists($month,$current)){
      $current[(int)$month] = $x['grand_total'];
    }else{
      $current[(int)$month] += $x['grand_total'];
    }
    $currentTotal += $x['grand_total'];

  }
 ?>
<div class="col-md-4">
<h3 class="text-center">Sales By Month</h3>
<table class="table table-striped table-bordered table-condensed">
  <thead>
    <th></th>
    <th><?=$lastYr;?></th>
    <th><?=$thisYr;?></th>
  </thead>
  <tbody>
    <?php
    for($i = 1; $i <= 12; $i++):
      $dt = DateTime::createFromFormat('!m',$i);
     ?>
    <tr<?=(date("m") == $i)?' class="info"':'';?>>
      <td><?=$dt->format("F");?></td>
      <td><?=(array_key_exists($i,$last))?money($last[$i]):money(0);?></td>
      <td><?=(array_key_exists($i,$current))?money($current[$i]):money(0);?></td>
    </tr>
  <?php endfor; ?>
  <tr>
    <td>Total</td>
    <td><?=money($lastTotal);?></td>
    <td><?=money($currentTotal);?></td>

  </tr>

  </tbody>
</table>
</div>




<?php include 'includes/footer.php'; ?>

<a href="/handmadeCorner/index.php" class="btn btn-info" role="button">Inapoi la pagina principala</a>
