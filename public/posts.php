<?php

session_start();

require_once('db.php');

if (!isset($_SESSION['auth'])) {
    header('location:login.php');
}

$sql = "SELECT `posts`.`title`, `posts`.`annotation`, `posts`.`content`, `posts`.`email`, `posts`.`id`, `posts`.`date`, `users`.`login` 
 FROM `posts` JOIN `users` ON `users`.`id` = `posts`.`user_id`";
$result = $pdo->query($sql);

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

    <table width='80%' border=0>

	<tr bgcolor='#CCCCCC'>
		<td>Title</td>
		<td>Annotation</td>
        <td>Content</td>
        <td>Date</td>
		<td>Email</td>
        <td>View</td>
		<td>Update</td>
        <td>Delete</td>
	</tr>

    <?php 	
	while($row = $result->fetch(PDO::FETCH_ASSOC)) { 	
		echo "<tr>";
		echo "<td>".$row['title']."</td>";
		echo "<td>".$row['annotation']."</td>";
        echo "<td>".$row['content']."</td>";
        echo "<td>".$row['date']."</td>";
		echo "<td>".$row['email']."</td>";	
        echo "<td><a href=\"post.php?id=$row[id]\">View</a></td>";
		echo "<td><a href=\"edit.php?id=$row[id]\">Edit</a></td>"; 
        echo "<td><a href=\"delete.php?id=$row[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a></td>";		
	}
	?>
</table>
</body>
</html>