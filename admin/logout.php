<?php

// remove session token
session_start();
unset($_SESSION['admin']);
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);                

// redirect to login.php
header("Location: login.php");