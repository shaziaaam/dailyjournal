<?php
include "session_init.php";
session_destroy();
header("location:login.php");
?>
