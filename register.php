<?php
require_once 'core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
checkLogin("index.php");
?>
<h2 class="text-center">Sign up</h2><hr>
<form action="includes/register.inc.php" method="post">
  <div class="form-group col-md-6">
    <label for="name">Nume:</labe>
      <input type="text" name="name" id="name" class="form-control">
    </div>

    <div class="form-group col-md-6">
      <label for="email">Email:</labe>
        <input type="email" name="email" id="email" class="form-control">
      </div>

      <div class="form-group col-md-6">
        <label for="nume">Parola:</labe>
          <input type="password" name="parola" id="password" class="form-control">
        </div>

        <div class="form-group col-md-6">
          <label for="confirm">Confirma parola:</labe>
            <input type="password" name="confirm" id="confirm" class="form-control">
          </div>

            <div class="form-group col-md-6 text-right">
              <input type="submit" value="Sign up" name="submit" class="btn btn-primary">
            </div>

</form>
<?php  include 'includes/footer.php'; ?>
<a href="/handmadeCorner/index.php" class="btn btn-info" role="button">Inapoi la pagina principala</a>
