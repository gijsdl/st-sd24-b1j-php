<?php
session_start();
require 'db.php';
global $db;

const EMAIL_ERROR = 'Vul een email in';
const PASSWORD_ERROR = 'Vul een password in';

if (isset($_POST['submit'])) {
    $errors = [];
    $inputs = [];
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (empty($email)) {
        $errors['email'] = EMAIL_ERROR;
    } else {
        $inputs['email'] = $email;
    }

    if (empty($password)) {
        $errors['password'] = PASSWORD_ERROR;
    }

    if (count($errors) === 0) {
        $query = $db->prepare('SELECT * FROM users WHERE email = :email');
        $query->bindParam('email', $inputs['email']);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user){
            if (password_verify($password, $user['password'])){
                $_SESSION['email'] = $inputs['email'];
                $_SESSION['login'] = true;
                header('Location: index.php');
            }
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
<form method="post">
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
