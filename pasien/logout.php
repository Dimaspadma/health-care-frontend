<?php

// remove session token pasien
session_start();
unset($_SESSION['pasien']);
unset($_SESSION['pasien_id']);
unset($_SESSION['pasien_nama']);

// redirect to login.php
header("Location: login.php");