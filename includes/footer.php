</div><br><br>
<div class="col-md-12 text-center">&copy; Copyright 2018 Coltisorul Handmade</div>


  <script>
  function detailsmodal(id){
  //  alert(id);
  //data = un json string
  var data = {"id" : id};
  //pass a json object
  //the page doesn't need to reload for this to happend
  //ajax=asyncronous javascript..and xml
  jQuery.ajax({
    url : '/handmadeCorner/includes/detailsmodal.php',
    method : "post",
    data : data,
    success : function(data){
    jQuery('body').prepend(data); //add the data to the body
    jQuery('#details-modal').modal('toggle');
    },
    error : function(){
      alert("Failed");
    }
  });
  }

  function update_cart(mode, edit_id, edit_size){
    var data = {"mode": mode, "edit_id": edit_id, "edit_size": edit_size};
    jQuery.ajax({
      url : '/handmadeCorner/admin/parsers/update_cart.php',
      method : "post",
      data : data,
      success : function(){location.reload();},
      error : function(){alert("Ceva merge prost");},
    });
}


  function add_to_cart(){
    jQuery('#modal_errors').html("");
    var size = jQuery('#size').val();
    var quantity = jQuery('#quantity').val();
    var available = jQuery('#available').val();
    var error = '';
    var data = jQuery('#add_product_form').serialize();//takes the values of the form
    //and serialize those parameters like get values
    if(size =='' || quantity == '' || quantity == 0){
      error += '<p class="text-danger text-center">Trebuie sa alegeti o marime si o cantitate.</p>';
      jQuery('#modal_errors').html(error);
      return;
    }else if(quantity > available){
      error += '<p class="text-danger text-center">Sunt disponibile doar '+available+' bucati din acest produs.</p>';
      jQuery('#modal_errors').html(error);
      return;
    }else{
      jQuery.ajax({
        url : '/handmadeCorner/admin/parsers/add_cart.php',
        method : 'post',
        data : data,
        success : function(){
          location.reload();//refresh the page
        },
        error : function(){alert("Something went wrong.");}
      });
    }

  }



    </script>
  </body>
</html>
