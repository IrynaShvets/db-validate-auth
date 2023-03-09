<?php
session_start();

require_once('db.php');

$loginErr = $emailErr = $passwordErr = $cpasswordErr = $cityErr = $sexErr = $phoneErr = "";

if (isset($_POST['submit'])) {

    if (!empty($_POST['login']) && 
    !empty($_POST['email']) && 
    !empty($_POST['city']) && 
    !empty($_POST['phone']) && 
    !empty($_POST['sex']) && 
    !empty($_POST['password']) && 
    !empty($_POST['cpassword']) && 
   
    $_POST['password'] === $_POST['cpassword']) 
{

        $login = trim($_POST['login']);
        $email = trim($_POST['email']);
        $city = trim($_POST['city']);
        $phone = trim($_POST['phone']);
        $sex = trim($_POST['sex']);
        $password = trim($_POST['password']);
        $cpassword = trim($_POST['cpassword']);

        $options = array("test" => 4);
        $hashPassword = password_hash($password, PASSWORD_BCRYPT, $options);
        $date = date('Y-m-d H:i:s');

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $sql = 'SELECT * FROM users WHERE email = :email';
            $stmt = $pdo->prepare($sql);
            $p = ['email' => $email];
            $stmt->execute($p);

            if ($stmt->rowCount() == 0) {
                $sql = "INSERT INTO users (`login`, email, `password`, created_at, updated_at, city, sex, phone) VALUES(:vlogin,:email,:pass,:created_at,:updated_at,:city,:sex,:phone)";

                try {
                    $handle = $pdo->prepare($sql);
                    $params = [
                        ':vlogin' => $login,
                        ':email' => $email,
                        ':pass' => $hashPassword,
                        ':created_at' => $date,
                        ':updated_at' => $date,
                        ':city' => $city,
                        ':sex' => $sex,
                        ':phone' => $phone,
                    ];

                    $getRow = $handle->execute($params); 

                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $sql = "SELECT * FROM users WHERE email = :email";
                        $handle = $pdo->prepare($sql);
                        $params = ['email' => $email];
                        $handle->execute($params);
                        if ($handle->rowCount() > 0) {
                            $getRow = $handle->fetch(PDO::FETCH_ASSOC);
                            if (password_verify($password, $getRow['password'])) {
                                unset($getRow['password']);
                                $_SESSION['auth'] = $getRow;
                                header('location:post-form.php');
                                exit();
                            } else {
                                $errors[] = "Wrong Email or Password";
                            }
                        } else {
                            $errors[] = "Wrong Email or Password";
                        }
                    } else {
                        $errors[] = "Email address is not valid";
                    }
                   
                } catch (PDOException $e) {
                    $errors[] = $e->getMessage();
                }
            } else {
                $valLogin = $login;
                $valCity = $city;
                $valSex = $sex;
                $valPhone = $phone;
                $valEmail = '';

                $emailErr = 'Email address already registered';
            }
        } else {
            $emailErr = "Email address is not valid";
        }
    } else {
        if (empty($_POST['login'])) {
            $loginErr = 'Login is required';
        } else {
            $valLogin = $_POST['login'];
        }

        if (empty($_POST['email'])) {
            $emailErr = 'Email is required';
        } else {
            $valEmail = $_POST['email'];
        }

        if (empty($_POST['city'])) {
            $cityErr = 'City is required';
        } else {
            $valCity = $_POST['city'];
        }

        if (empty($_POST['phone'])) {
            echo $_POST['phone'];
            $phoneErr = 'Phone is required';
        } else {
            $valPhone = $_POST['phone'];
        }

        if (trim($_POST['sex']) == "") {
            $sexErr = 'You must select a sex';
        } else {
            $valSex = $_POST['sex'];
        }

        if (empty($_POST['password'])) {
            $passwordErr = 'Password is required or must not be less than 6 characters';
        } else {
            $valPassword = $_POST['password'];
        }

        if (empty($_POST['cpassword'])) {
            $cpasswordErr = 'Confirm password is required';
        } else {
            $valConfirmPassword = $_POST['cpassword'];
        }

        if ($_POST['password'] !== $_POST['cpassword']) {
            $cpasswordErr = "Passwords did not match";
        } else {
            $valConfirmPassword = $_POST['cpassword'];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register page</title>
    <link rel="stylesheet" href="../css/style.css">
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

    </header>

    <div class="form-container">

        <form class="form-register" method="POST">
            <h1 class="form-title">Register now</h1>
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
            <p><span class="error">* required field</span></p>

            <label class="label" for="login">Enter your login<span class="error-star">*</span>:</label>
            <input type="text" name="login" id='login' placeholder="Login" class="input" value="<?php echo ($valLogin ?? '') ?>">
            <span class="error"><?php echo ($loginErr ?? '') ?></span>

            <label class="label" for="email">Enter your email<span class="error-star">*</span>:</label>
            <input type="text" name="email" id='email' placeholder="Email" class="input" value="<?php echo ($valEmail ?? '') ?>">
            <span class="error"><?php echo ($emailErr ?? '') ?></span>

            <label class="label" for="city">Enter your city<span class="error-star">*</span>:</label>
            <input type="text" name="city" id='city' placeholder="City" class="input" value="<?php echo ($valCity ?? '') ?>">
            <span class="error"><?php echo ($cityErr ?? '') ?></span>

            <label class="label" for="phone">Enter your phone number<span class="error-star">*</span>:</label>
            <input type="" name="phone" id='phone' placeholder="Phone number" class="input" value="<?php echo ($valPhone ?? '') ?>">
            <span class="error"><?php echo ($phoneErr ?? '') ?></span>

            <label class="label" for="sex">Select your sex<span class="error-star">*</span>:</label>
            <select class="select" name="sex" for='id'>
                <option value=""></option>
                <option class="option" value="Male">Male</option>
                <option class="option" value="Female">Female</option>
            </select>
            <span class="error"><?php echo ($sexErr ?? '') ?></span>

            <label class="label" for="password">Enter your password<span class="error-star">*</span>:</label>
            <input type="password" name="password" id='password' placeholder="Password" class="input" value="<?php echo ($valPassword ?? '') ?>">
            <span class="error"><?php echo ($passwordErr ?? '') ?></span>

            <label class="label" for="cpassword">Confirm your password<span class="error-star">*</span>:</label>
            <input type="password" name="cpassword" id="cpassword" placeholder="Confirm password" class="input" value="<?php echo ($valPassword ?? '') ?>">
            <span class="error"><?php echo ($cpasswordErr ?? '') ?></span>

            <button type="submit" name="submit" class="form-btn">Submit</button>
            <p class="form-text"> Back to <a href="login.php" class="form-link">Login</a></p>

        </form>
    </div>
    <footer></footer>

</body>
</html>