</div><br><br>
<div class="col-md-12 text-center">&copy; Copyright 2018 Coltisorul Handmade</div>


  <script>

  function updateSizes(){
    var sizeString = '';
    for(var i = 1; i <= 4; i++){
      if(jQuery('#size'+i).val() != ''){
        sizeString += jQuery('#size'+i).val()+':'+jQuery('#quantity'+i).val()+',';
        

        }
      }
      jQuery('#sizes').val(sizeString);
    }


  function get_child_options(selected){
    if(typeof selected === 'undefined'){
      var selected = '';
    }
    var parentID = jQuery('#parent').val();
    jQuery.ajax({
      url: '/handmadeCorner/admin/parsers/child_categories.php',
      type: 'POST',
      // a data object with key and value
      data: {parentID:parentID, selected: selected},
      success: function(data){
        jQuery('#child').html(data);
      },
      error: function(){
        alert("Something went wrong")
      },
    });
  }
  jQuery('select[name="parent"]').change(function(){
    get_child_options();
  });


  </script>

  </body>
</html>
