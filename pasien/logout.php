<?php

// remove session token pasien
session_start();
unset($_SESSION['pasien']);

// redirect to login.php
header("Location: login.php");