<?php
 require_once 'core/init.php';
 include 'includes/head.php';
 include 'includes/navigation.php';

 if($cart_id != ''){
   //returns the row of the id
   $cartQ = $db->query("SELECT * FROM cart WHERE id='{$cart_id}'");
   $result = mysqli_fetch_assoc($cartQ);
   //force this to decode as an associative array rather than returning an object
   $items = json_decode($result['items'],true);
   $i = 1;
   $sub_total = 0;
   $item_count = 0;
 }
 ?>

<div class="col-md-12">
  <div class="row">
    <h2 class="text-center">Cos de cumparaturi</h2><hr>
    <?php if($cart_id == ''): ?>
      <div class="bg-danger">
        <p class="text-center text-danger">Cosul de cumparaturi este gol.</p>
      </div>
    <?php else: ?>
      <table class="table table-bordered table-condensed table-striped">
        <thead>
          <th>#</th>
          <th>Articol</th>
          <th>Pret</th>
          <th>Cantitate</th>
          <th>Marime</th>
          <th>Sub Total</th>
        </thead>
        <tbody>
          <?php
          //$items = array();
            foreach($items as $item){
              $product_id = $item['id'];
              $productQ=$db->query("SELECT * FROM products WHERE id = '{$product_id}'");
              $product = mysqli_fetch_assoc($productQ);
              //size array
              $sArray = explode(',',$product['sizes']);
              foreach($sArray as $sizeString){
                $s = explode(':',$sizeString);
                if($s[0] == $item['size']){
                  $available = $s[1];
                }
              }
              ?>
              <tr>
                <td><?=$i;?></td>
                <td><?=$product['title'];?></td>
                <td><?=money($product['price']);?></td>
                <td>
                  <button class="btn btn-xs btn-default" type="button" onclick="update_cart('removeone','<?=$product['id'];?>','<?=$item['size'];?>');">-</button>
                    <?=$item['quantity'];?>

                    <?php if($item['quantity'] < $available): ?>
                  <button class="btn btn-xs btn-default" type="button" onclick="update_cart('addone','<?=$product['id'];?>','<?=$item['size'];?>');">+</button>
                    <?php else: ?>
                    <span class="text-danger">Maxim - Stoc epuizat</span>
                    <?php endif;?>
                </td>

                <td><?=$item['size'];?></td>
                <td><?=money($item['quantity'] * $product['price']);?></td>

              </tr>

            <?php
              $i++;
              $item_count += $item['quantity'];
              $sub_total += ($product['price'] * $item['quantity']);

          }
              $tax = TAXRATE * $sub_total;
              $tax = number_format($tax, 2);
              $grand_total = $tax + $sub_total;


           ?>

        </tbody>

      </table>

        <table class="table table-bordered table-condensed text-right">
          <legend>Total</legend>
          <thead class="totals-table-header">
            <th>Total articole</th>
            <th>Subtotal</th>
            <th>Taxa</th>
            <th>Grand Total</th>


          </thead>
          <tbody>
            <tr>
              <td><?=$item_count;?></td>
              <td><?=money($sub_total);?></td>
              <td><?=money($tax);?></td>
              <td class="bg-success"><?=money($grand_total);?></td>

            </tr>


          </tbody>
        </table>

        <!-- Check out button -->
<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#checkoutModal">
<span class="glyphicon glyphicon-shopping-cart" ></span> Check out >>
</button>

<!-- Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="checkoutModalLabel">Shipping Address</h4>
      </div>
      <div class="modal-body">
        <div class="row">
        <form action="multumesc.php" method="post" id="payment-form">
          <span class="bg-danger" id="payment-errors"></span>
          <input type="hidden" name="tax" value="<?=$tax;?>">
          <input type="hidden" name="sub_total" value="<?=$sub_total;?>">
          <input type="hidden" name="grand_total" value="<?=$grand_total;?>">
          <input type="hidden" name="cart_id" value="<?=$cart_id;?>">
          <input type="hidden" name="description" value="<?=$item_count.' item'.(($item_count>1)?'s':''). 'from handmadeCorner';?>">


          <div id="step1" style="display:block;">
            <div class="form-group col-md-6">
              <label for="full_name">Fullname:</label>
              <input class="form-control" id="full_name" name="full_name" type="text">
            </div>
            <div class="form-group col-md-6">
              <label for="email">Email:</label>
              <input class="form-control" id="email" name="email" type="email">
            </div>
            <div class="form-group col-md-6">
              <label for="address">Address:</label>
              <input class="form-control" id="address" name="address" type="text" data-stripe="address_line1">
            </div>
            <div class="form-group col-md-6">
              <label for="city">City:</label>
              <input class="form-control" id="city" name="city" type="text" data-stripe="address_city">
            </div>
            <div class="form-group col-md-6">
              <label for="state">State:</label>
              <input class="form-control" id="state" name="state" type="text" data-stripe="address_state">
            </div>
            <div class="form-group col-md-6">
              <label for="zip_code">Zip Code:</label>
              <input class="form-control" id="zip_code" name="zip_code" type="text" data-stripe="address_zip">
            </div>
            <div class="form-group col-md-6">
              <label for="country">Country:</label>
              <input class="form-control" id="country" name="country" type="text" data-stripe="address_country">
            </div>
          </div>
          <div id="step2" style="display:none;">
            <div class="form-group col-md-3">
              <label for="name">Name on Card:</label>
              <!--nu punem name pt. ca altfel s-ar retine in server info. despre card -->
              <input type="text" id="name" class="form-control" data-stripe="name">
            </div>
            <div class="form-group col-md-3">
              <label for="number">Card Number:</label>
              <!--nu punem name pt. ca altfel s-ar retine in server info. despre card -->
              <input type="text" id="number" class="form-control" data-stripe="number">
            </div>
            <div class="form-group col-md-2">
              <label for="cvc">CVC:</label>
              <input type="text" id="cvc" class="form-control" data-stripe="cvc">
            </div>
            <div class="form-group col-md-2">
              <label for="exp-month">Expire Month:</label>
              <select id="exp-month" class="form-control" data-stripe="exp_month">
                <option value=""></option>
                <?php for($i = 1; $i <13; $i++): ?>
                  <option value="<?=$i;?>"><?=$i;?></option>

                <?php endfor; ?>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label for="exp-year">Expire Year:</label>
              <select id="exp-year" class="form-control" data-stripe="exp_year">
                  <option value=""></option>
                  <?php $yr = date("Y"); ?>
                    <?php  for($i = 0; $i < 11; $i++): ?>
                      <option value="<?=$yr + $i;?>"><?=$yr + $i;?></option>
                  <?php endfor; ?>
              </select>
            </div>
          </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Inchide</button>

        <button type="button" class="btn btn-primary" onclick="check_address();" id="next_button">Inainte</button>
        <button type="button" class="btn btn-primary" onclick="back_address();" id="back_button" style="display:none;">Inapoi</button>
        <button type="submit" class="btn btn-primary" id="check_out_button" style="display:none;">Confirma</button>
        </form>
      </div>
    </div>
  </div>
</div>

    <?php endif; ?>

  </div>
</div>
<script>

  function back_address(){
    jQuery('#payment-errors').html("");
    jQuery('#step1').css("display","block");
    jQuery('#step2').css("display","none");
    jQuery('#next_button').css("display","inline-block");
    jQuery('#back_button').css("display","none");
    jQuery('#check_out_button').css("display","none");
    jQuery('#checkoutModalLabel').html("Shipping Address");
  }


  function check_address(){
    var data =
    {'full_name' : jQuery('#full_name').val(),
      'email' : jQuery('#email').val(),
      'address' : jQuery('#address').val(),
      'city': jQuery('#city').val(),
      'state' : jQuery('#state').val(),
      'zip_code' : jQuery('#zip_code').val(),
      'country' : jQuery('#country').val()

  };

  jQuery.ajax({
    url : '/handmadeCorner/admin/parsers/check_address.php',
    method : 'POST',
    data : data,
    success : function(data){
      if(data != 'passed'){
        jQuery('#payment-errors').html(data);
      }

      if(data == 'passed'){
        jQuery('#payment-errors').html("");
        jQuery('#step1').css("display","none");
        jQuery('#step2').css("display","block");
        jQuery('#next_button').css("display","none");
        jQuery('#back_button').css("display","inline-block");
        jQuery('#check_out_button').css("display","inline-block");
        jQuery('#checkoutModalLabel').html("Enter Your Card Details");
      }

    },//data care vine din check_address.php - diferit de data : data
    error : function(){alert("Eroare");},
  });

  }

Stripe.setPublishableKey('<?=STRIPE_PUBLIC;?>');

function stripeResponseHandler(status, response){
  var $form = $('#payment-form');

  if (response.error){
    //Show the errors on the form
    $form.find('#payment-errors').text(response.error.message);
    $form.find('button').prop('disabled',false);

  }else{
    //response contains id and card, which contains aditional card Details
    var token = response.id;
    //insert the token into the fom so it gets submitted to the server
    $form.append($('<input type="hidden" name="stripeToken" />').val(token));
    //and submit
    $form.get(0).submit();
  }
};

jQuery(function($) {
  $('#payment-form').submit(function(event){
    var $form = $(this);

    //Disable the submit button to prevent repeated clicks
    $form.find('button').prop('disabled',true);
    Stripe.card.createToken($form, stripeResponseHandler);

    //Prevent the form from submitting with the default action
    return false;
  });
});

</script>

 <?php
  include 'includes/footer.php';

 ?>
