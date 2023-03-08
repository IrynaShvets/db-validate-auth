<?php

session_start();

require_once('db.php');

if (!isset($_SESSION['auth'])) {
    header('location:login.php');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
echo $id;

// $stmt = $pdo->query("SELECT * FROM `posts` WHERE `id`=$id");

// print_r($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Post</title>
</head>

<body>
    <header class="header">
        <nav>
            <ul class="nav-menu">

                <li>
                    <a href="post-form.php" class="nav-link">Post form</a>
                </li>
                <li>
                    <a href="posts.php" class="nav-link">Posts</a>
                </li>
                <li>
                    <a href="register.php" class="nav-link">Register</a>
                </li>
                <li>
                    <a href="login.php" class="nav-link">Login</a>
                </li>
            </ul>
        </nav>

        <div class="container-user">
            <p class="user-title">Welcome: <span class="user-text"><?php echo $_SESSION['auth']['login']; ?></span></p>
            <a class="logout" href="logout.php?logout=true">Logout</a>
        </div>

    </header>

    <a href="edit.php?id=">Edit</a>

    <a href="delete.php?id=">Delete</a>

</body>
</html>