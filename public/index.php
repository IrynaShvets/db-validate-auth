<?php

session_start();

if(isset($_SESSION['auth']) ){
    header('location:post.php');
} else {
    session_destroy();
    header('location:login.php');
    exit();
}

