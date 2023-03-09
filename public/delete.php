<?php

require_once('db.php');

$id = $_GET['id'];

$sql = "DELETE FROM `posts` WHERE id=:id";
$query = $pdo->prepare($sql);
$query->execute(array(':id' => $id));


    if ($query) {
        header("Location: posts.php");
    } else {
        echo "Failed to delete post";
    }

?>
