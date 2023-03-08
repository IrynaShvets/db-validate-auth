<?php

require('db.php');

$id=$_GET['id'];

$pdo->prepare("DELETE FROM `posts` WHERE `id` = $id")->execute([$id]);

header("Location: posts.php"); 
exit();

?>