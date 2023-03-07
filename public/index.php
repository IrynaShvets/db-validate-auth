<?php

session_start();

if(isset($_SESSION) ){
    session_destroy();
    header('location:post.php');
    exit();
    
} else {
    session_destroy();
    header('location:login.php');
    exit();
}

