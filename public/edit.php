<?php

session_start();

require_once('db.php');

if (!isset($_SESSION['auth'])) {
    header('location:login.php');
}

$titleErr = $annotationErr = $contentErr = $emailErr = $viewsErr = $dateErr = $publishInIndexErr = $categoryErr = "";
$valid = true;

if (isset($_POST['update'])) {

    if (empty($_POST['title'])) {
        $valid = false;
        $titleErr = "Поле заголовок не повинно бути порожнім";
    } else {
        $title = $_POST["title"];
        if (strlen($title) < 3) {
            $valid = false;
            $titleErr = "Поле заголовок повинно мати не менше трьох символів";
        }
        if (strlen($title) > 255) {
            $valid = false;
            $titleErr = "Поле заголовок повинно мати не більше ніж 255 символів";
        }
    }

    if (isset($_POST["annotation"])) {
        $annotation = $_POST["annotation"];
        if (strlen($annotation) > 500) {
            $valid = false;
            $annotationErr = "Поле анотація не повинне перевищувати 500 символів";
        }
    }

    if (isset($_POST["content"])) {
        $content = $_POST["content"];
        if (strlen($content) > 30000) {
            $valid = false;
            $contentErr = "Поле контенту не повинно перевищувати 30 000 символів";
        }
    }

    if (empty($_POST["email"])) {
        $valid = false;
        $emailErr = "Поле email не повинно бути порожнім";
    } else {
        $email = $_POST["email"];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $valid = false;
            $emailErr = "Поле email має бути валідним email";
        }
    }

    if (isset($_POST["views"])) {
        $views = $_POST["views"];
        if (!filter_var($views, FILTER_VALIDATE_INT, ["options" => ["min_range" => 0, "max_range" => 2147483647]]) !== false) {
            $valid = false;
            $viewsErr = "Кількість переглядів має бути числом, не повинно бути негативним і в межах розміру UNSIGNED INT";
        }
    }

    if (isset($_POST["date"])) {
        $valid = false;
        $date1 = new DateTime("now");
        $date2 = new DateTime($_POST["date"]);
        $diff = $date1 < $date2;
        if ((bool)($diff === true)) {
            $dateErr = "Дата публікації не повинна бути раніше поточної дати";
        }
    }

    if (empty($_POST["publish_in_index"])) {
        $valid = false;
        $publishInIndexErr = "Поле публікувати на головній має бути обов'язковим";
    }
} else {

    try {

        $stmt = $pdo->prepare("UPDATE `posts` SET `title`=:title, `annotation`=:annotation, `content`=:content, `email`=:email, views=:views, `date`=':date', `publish_in_index`=:publish_in_index, `category`=:category, `user_id`=:user_id, `id`=:id WHERE `id`=:id");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':annotation', $annotation);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':views', $views);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':publish_in_index', $publish_in_index);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':id', $id);

        if ($stmt) {
            $valid = true;
            $stmt->execute();
            // $success = "Post updated successfully";
            //header("Location: posts.php");
        }
    } catch (PDOException $e) {
        $errors[] = $e->getMessage();
    }
}

?>

<?php

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->query("SELECT * FROM `posts` WHERE `id`=$id")->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Post page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .error {
            color: red;
        }
    </style>
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

    <?php
    if (isset($errors) && count($errors) > 0) {
        foreach ($errors as $error_msg) {
            echo '<div class="form-error-msg">' . $error_msg . '</div>';
        }
    }

    if (isset($success)) {
        echo '<div class="form-success-msg">' . $success . '</div>';
    }
    ?>

    <div class="container">

        <div class="row">
            <p><span class="error">* required field</span></p>
            <form style="width: 100%" method="post" action="edit.php">
                <div class="form-group row">
                    <label for="title" class="col-md-2 col-form-label">Заголовок</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo $stmt[0]['title']; ?>">
                        <div class="invalid-feedback">

                        </div>
                        <span class="error">* <?php echo $titleErr; ?></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="annotation" class="col-md-2 col-form-label">Аннотация</label>
                    <div class="col-md-10">
                        <textarea name="annotation" id="annotation" class="form-control" cols="30" rows="10"><?php echo $stmt[0]['annotation']; ?></textarea>
                        <div class="invalid-feedback">
                        </div>
                        <span class="error"> <?php echo $annotationErr; ?></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="content" class="col-md-2 col-form-label">Текст новости</label>
                    <div class="col-md-10">
                        <textarea name="content" id="content" class="form-control" cols="30" rows="10"><?php echo $stmt[0]['content']; ?></textarea>
                        <div class="invalid-feedback">
                        </div>
                        <span class="error"> <?php echo $contentErr; ?></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-2 col-form-label">Email автора для связи</label>
                    <div class="col-md-10">
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $stmt[0]['email']; ?>">
                        <div class="invalid-feedback">
                        </div>
                        <span class="error">*<?php echo $emailErr; ?></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="views" class="col-md-2 col-form-label">Кол-во просмотров</label>
                    <div class="col-md-10">
                        <input type="number" class="form-control" id="views" name="views" value="<?php echo $stmt[0]['views']; ?>">
                        <div class="invalid-feedback">
                        </div>
                        <span class="error"> <?php echo $viewsErr; ?></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="date" class="col-md-2 col-form-label">Дата публикации</label>
                    <div class="col-md-10">
                        <input type="date" class="form-control" id="date" name="date" value="<?php echo $stmt[0]['date']; ?>">
                        <div class="invalid-feedback">
                        </div>
                        <span class="error"> <?php echo $dateErr; ?></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="is_publish" class="col-md-2 col-form-label">Публичная новость</label>
                    <div class="col-md-10">
                        <input type="checkbox" class="form-control" id="is_publish" name="is_publish">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Публиковать на главной</label>
                    <div class="col-md-10">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="publish_in_index" id="publish_in_index_yes" value="<?php echo $stmt[0]['publish_in_index']; ?>" checked>
                            <label class="form-check-label" for="publish_in_index_yes">
                                Да
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="publish_in_index" id="publish_in_index_no" value="<?php echo $stmt[0]['publish_in_index']; ?>">
                            <label class="form-check-label" for="publish_in_index_no">
                                Нет
                            </label>
                        </div>
                        <div class="invalid-feedback">
                        </div>
                        <span class="error">* <?php echo $publishInIndexErr; ?></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="category" class="col-md-2 col-form-label">Публичная новость</label>
                    <div class="col-md-10">
                        <select id="category" class="form-control" name="category">
                            <option value="" selected>Оберіть категорію</option>
                            <option value="<?php echo $stmt[0]['category'] ?? $stmt[0]['category']; ?>">Спорт</option>
                            <option value="<?php echo $stmt[0]['category']  ?? $stmt[0]['category']; ?>">Культура</option>
                            <option value="<?php echo $stmt[0]['category']  ?? $stmt[0]['category']; ?>">Политика</option>
                        </select>
                        <div class="invalid-feedback">
                        </div>
                        <span class="error"><?php echo $categoryErr; ?></span>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-9">
                        <button type="submit" name="update" class="btn btn-primary">Оновити пост</button>

                        <!-- <input type="submit" name="update" class="btn btn-primary" value="Update"> -->
                    </div>
                    <div class="col-md-3">
                        <?php if ($valid === true && isset($_POST['update'])) { ?>
                            <div class="alert alert-success">
                                Форма валидна
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>