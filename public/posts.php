<?php

session_start();

require_once('db.php');

if (!isset($_SESSION['auth'])) {
    header('location:login.php');
}

$sql = "SELECT `posts`.`title`, `posts`.`annotation`, `posts`.`content`, `posts`.`email`, `posts`.`views`, `posts`.`date`, `users`.`login` 
 FROM `posts` JOIN `users` ON `users`.`id` = `posts`.`user_id`";
$posts_with_users = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Posts</title>
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

    <ol>
        <?php foreach ($posts_with_users as $post) : ?>
            <li class="item">
                <strong>User: </strong><?= $post['login']; ?>
                <strong>Title: </strong><?= $post['title']; ?>
                <strong>Annotation: </strong><?= $post['annotation']; ?>
            </li>
        <?php endforeach; ?>
    </ol>

</body>
</html>