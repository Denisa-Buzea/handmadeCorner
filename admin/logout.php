<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/handmadeCorner/core/init.php';
unset($_SESSION['SBUser']);
header('Location: ../index.php');

?>
