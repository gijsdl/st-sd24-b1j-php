<?php
session_start();
require 'db.php';
global $db;

const NAME_ERROR = 'Vul een naam in';
const EMAIL_ERROR = 'Vul een email in';
const PASSWORD_ERROR = 'Vul een password in';
const USED_ERROR = 'Deze email is al gebruikt.';


if (isset($_POST['submit'])) {

    $errors = [];
    $inputs = [];

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (empty($name)) {
        $errors['name'] = NAME_ERROR;
    } else {
        $inputs['name'] = $name;
    }

    if (empty($email)) {
        $errors['email'] = EMAIL_ERROR;
    } else {
        $inputs['email'] = $email;
    }

    if (empty($password)) {
        $errors['password'] = PASSWORD_ERROR;
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT);
    }

    if (count($errors) === 0) {

        $emailQuery = $db->prepare('SELECT email FROM users WHERE email = :email');
        $emailQuery->bindParam('email', $inputs['email']);
        $emailQuery->execute();
        $users = $emailQuery->fetchAll(PDO::FETCH_ASSOC);

        if (count($users) === 0) {
            $query = $db->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
            $query->bindParam('name', $inputs['name']);
            $query->bindParam('email', $inputs['email']);
            $query->bindParam('password', $password);
            $query->execute();
            header('Location: login.php');
        } else {
            $errors['used'] = USED_ERROR;
        }
    }
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
<nav>
    <?php if (!isset($_SESSION['login'])): ?>
        <a href="login.php">Login</a>
        <a href="index.php">Registreren</a>
    <?php else: ?>
        <a href="account.php">Account</a>
    <?php endif; ?>
</nav>
<form method="post">
    <label for="name">Naam:</label>
    <input type="text" id="name" name="name" value="<?= $inputs['name'] ?? '' ?>">
    <div><?= $errors['name'] ?? '' ?> <?= $errors['used'] ?? '' ?></div>
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" value="<?= $inputs['email'] ?? '' ?>">
    <div><?= $errors['email'] ?? '' ?></div>
    <label for="password">Wachtwoord:</label>
    <input type="password" id="password" name="password">
    <div><?= $errors['password'] ?? '' ?></div>
    <button name="submit">Verzenden</button>
</form>
</body>
</html>
