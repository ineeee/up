<?php

session_start();

$_SESSION['logged in'] = false;
header('Location: index.php');
