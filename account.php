<?php
session_start();
require 'db.php';
global $db;

if (isset($_SESSION['login'])) {
    $query = $db->prepare('SELECT * FROM users WHERE email = :email');
    $query->bindParam('email', $_SESSION['email']);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);
} else {
    header('Location: login.php');
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<table>
    <tbody>
    <tr>
        <th>Naam</th>
        <td><?= $user['name'] ?></td>
    </tr>
    </tbody>
</table>
</body>
</html>
