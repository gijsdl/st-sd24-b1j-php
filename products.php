<?php

    require 'db.php';
    global $db;

    $id =  filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!empty($id)){
        $query = $db->prepare('SELECT * FROM products WHERE category_id = :id');
        $query->bindParam('id', $id);
        $query->execute();

        $products = $query->fetchAll(PDO::FETCH_ASSOC);
    }else{
        die('ERROR 404: Item not found');
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
    <thead>
    <tr>
        <th scope="col">Naam</th>
        <th scope="col">Prijs</th>
        <th scope="col">Land van herkomst</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($products as $product):?>
        <tr>
            <td><?= $product['name'] ?></td>
            <td><?= number_format($product['price'], 2, ',', '.') ?></td>
            <td><?= $product['origin'] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
