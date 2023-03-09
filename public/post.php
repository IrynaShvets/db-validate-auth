<?php

session_start();

require_once('db.php');

if (!isset($_SESSION['auth'])) {
    header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <div class="container mt-5">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Post View Details 
                            <a href="posts.php" class="btn btn-danger float-end">BACK TO ALL POSTS</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <?php
                        if(isset($_GET['id']))
                        {
                            $id = $_GET['id'];
                            $stmt = $pdo->query("SELECT * FROM `posts` WHERE `id`=$id")->fetchAll(PDO::FETCH_ASSOC);
                      
                            if($stmt)
                            { 
                                ?>
                                    <div class="mb-3">
                                        <label>Post Title</label>
                                        <p class="form-control">
                                            <?=$stmt[0]['title'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Post Annotation</label>
                                        <p class="form-control">
                                            <?=$stmt[0]['annotation'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Post Content</label>
                                        <p class="form-control">
                                            <?=$stmt[0]['content'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Post View</label>
                                        <p class="form-control">
                                            <?=$stmt[0]['views'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Post Date</label>
                                        <p class="form-control">
                                            <?=$stmt[0]['date'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Post Email</label>
                                        <p class="form-control">
                                            <?=$stmt[0]['email'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Post Publish in index</label>
                                        <p class="form-control">
                                            <?=$stmt[0]['publish_in_index'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Post Category</label>
                                        <p class="form-control">
                                            <?=$stmt[0]['category'] ;?>
                                        </p>
                                    </div>
                                <?php
                            }
                            else
                            {
                                echo "<h4>No Such Id Found</h4>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>