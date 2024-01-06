<?php

// remove session token
session_start();
unset($_SESSION['dokter']);
unset($_SESSION['dokter_id']);
unset($_SESSION['dokter_nama']);                

// redirect to login.php
header("Location: login.php");

?>