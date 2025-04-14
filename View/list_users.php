<?php
require_once __DIR__ . '/../Controller/userC.php';

$userController = new UserC();
$users = $userController->listUsers();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>User List</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Mot de passe</th> <!-- New column, but masked -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user->getId() ?></td>
                    <td><?= $user->getNom() ?></td>
                    <td><?= $user->getPrenom() ?></td>
                    <td><?= $user->getEmail() ?></td>
                    <td><?= ucfirst($user->getType()) ?></td>
                    <td>******</td> <!-- Masked password for security -->
                    <td>
                        <a href="edit_user.php?id=<?= $user->getId() ?>" class="btn btn-warning">Edit</a>
                        <a href="delete_user.php?id=<?= $user->getId() ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="add_user.php" class="btn btn-primary">Add New User</a>
    </div>
</body>
</html>
