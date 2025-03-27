<?php
require 'db.php';
global $db;

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
        $query = $db->prepare('INSERT INTO category (name) VALUES (:name)');
        $query->bindParam('name', $inputs['name']);
        $query->execute();
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
    <input type="text" id="name" name="name" value="<?= $inputs['name'] ?? '' ?>"><br>
    <div><?= $errors['name'] ?? '' ?></div>
    <button name="submit">Verzenden</button>
</form>
</body>
</html>
