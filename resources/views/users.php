<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php $title =null;
        echo e($title)?></title>
</head>
<body>
<h1>Listado de Usuarios</h1>
<ul>
    <?php $users =null;
    foreach ($users as $user): ?>
        <li> <?php echo e($user)?> </li>
    <?php endforeach; ?>
</ul>
</body>
</html>