<?php

session_start();

if (isset($_SESSION['login'])) {
    $login = $_SESSION['login'];
    echo $login;
}  

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
}

if (isset($_SESSION['city'])) {
    $city = $_SESSION['city'];
}

if (isset($_SESSION['phone'])) {
    $phone = $_SESSION['phone'];
}

if (isset($_SESSION['sex'])) {
    $sex = $_SESSION['sex'];
}

if (isset($_SESSION['password'])) {
    $password = $_SESSION['password'];
}

if (isset($_POST['submit'])) {
    print_r($_POST['submit']);

    $login = $_POST['login'];
    $email = $_POST['email'];
    $city = $_POST['city'];
    $phone = $_POST['phone'];
    $sex = $_POST['sex'];
    $password = $_POST['password'];
    
    $_SESSION['login'] = $_POST['login'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['city'] = $_POST['city'];
    $_SESSION['phone'] = $_POST['phone'];
    $_SESSION['sex'] = $_POST['sex'];
    $_SESSION['password'] = $_POST['password'];
   
}

$loginError = $emailError = $cityError = $phoneError = $sexError = $passwordError = "";
$login = $email = $city = $phone = $sex = $password = "";
$valid = true;
    if (empty($_SESSION['login'])) {
        $valid = false;
        $loginError = "Login не повинний бути порожнім";
    } else {
        $login = $_SESSION['login'];
        if (strlen($login) < 3) {
            $valid = false;
            $loginError = "Поле login повинно мати не менше трьох символів";
        }
    }

    if (empty($_SESSION['email'])) {
        $valid = false;
        $emailError = "Поле email не повинно бути порожнім";
    } else {
        $email = $_SESSION['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $valid = false;
            $emailError = "Поле email має бути валідним email";
        }
    }

    if (empty($_SESSION['city'])) {
        $valid = false;
        $cityError = "Поле city не повинне бути порожнім";
    } else {
        $city = $_SESSION['city'];
        if (strlen($city) < 2) {
            $valid = false;
            $cityError = "Поле city повинно мати не менше двох символів";
        }
    }


    $errorsArr = [
       1 => ['valid' => $valid,],
       2 => ['login' => $loginError, 'city' => $cityError, 'phone' => $phoneError, 'email' => $emailError, 'sex' => $sexError, 'password' => $passwordError],
    ];

    print_r($errorsArr);