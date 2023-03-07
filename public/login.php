<?php
session_start();

require_once('db.php');

if (isset($_POST['submit'])) {
	if (isset($_POST['email'], $_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])) {
		$email = trim($_POST['email']);
		$password = trim($_POST['password']);

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
					header('location:post.php');
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
	} else {
		$errors[] = "Email and Password are required";
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login page</title>
	<link rel="stylesheet" href="../css/style.css">
</head>

<body>

	<header class="header">
		<nav>
			<ul class="nav-menu">

				<li>
					<a href="post.php" class="nav-link">Post</a>
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
		
		<?php
		if (isset($errors) && count($errors) > 0) {
			foreach ($errors as $error_msg) {
				echo '<div class="form-error-msg">' . $error_msg . '</div>';
			}
		}
		?>

		<form class="form-register" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<h1 class="form-title">Login now</h1>
			<input class="input" type="text" name="email" placeholder="Enter Email" class="form-control">
			<input class="input" type="password" name="password" placeholder="Enter Password" class="form-control">
			<button type="submit" name="submit" class="form-btn">Submit</button>
			<p class="form-text"> Back to <a href="register.php" class="form-link">Register</a></p>
		</form>
	</div>

</body>
</html>