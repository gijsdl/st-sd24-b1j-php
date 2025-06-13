<?php
require 'db.php';
global $db;

if (isset($_SESSION['login']) && $_SESSION['login']) {
    $userQuery = $db->prepare('SELECT * FROM users WHERE email = :email');
    $userQuery->bindParam('email', $_SESSION['email']);
    $userQuery->execute();
    $user = $userQuery->fetch(PDO::FETCH_ASSOC);
}
if (!(isset($user) && $user['role'] === 'ROLE_ADMIN')){
    header('Location: index.php');
}

$id = $_GET['id'];
$selectQuery = $db->prepare('SELECT * FROM category WHERE id = :id');
$selectQuery->bindParam('id', $id);
$selectQuery->execute();

$category = $selectQuery->fetch(PDO::FETCH_ASSOC);

const NAME_ERROR = "Vul een naam in";

if (isset($_POST['submit'])){

    $errors = [];
    $inputs = [];

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);


    if (empty($name)){
        $errors['name'] = NAME_ERROR;
    } else{
        $inputs['name'] = $name;
    }

    if (count($errors) === 0){
        $updateQuery = $db->prepare('UPDATE category SET name = :name WHERE id = :id');
        $updateQuery->bindParam('name', $inputs['name']);
        $updateQuery->bindParam('id', $id);
        $updateQuery->execute();

        header('Location: index.php');
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
    <label for="name">Naam: </label>

    <input type="text" id="name" name="name" value="<?= $category['name'] ?? '' ?>"><br>


    <div><?= $errors['name'] ?? '' ?></div>
    <button name="submit">Verzenden</button>
</form>
</body>
</html>
