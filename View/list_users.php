<?php
require_once __DIR__ . '/../Controller/userC.php';

$userController = new UserC();

// Handle sorting
$sort = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'asc';

// Handle search
$search = $_GET['search'] ?? '';

// Get filtered and sorted users
$users = $userController->listUsers($sort, $order, $search);

// Function to toggle sort order
function getSortOrder($currentSort, $columnName) {
    $currentOrder = $_GET['order'] ?? 'asc';
    return ($currentSort === $columnName && $currentOrder === 'asc') ? 'desc' : 'asc';
}

// Function to create sort URL
function getSortUrl($columnName) {
    $params = $_GET;
    $params['sort'] = $columnName;
    $params['order'] = getSortOrder($_GET['sort'] ?? 'id', $columnName);
    return '?' . http_build_query($params);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sort-icon {
            font-size: 0.8em;
            margin-left: 5px;
        }
        .sortable {
            cursor: pointer;
        }
        .sortable:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>User List</h2>
            <form class="d-flex" method="GET">
                <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
                <input type="hidden" name="order" value="<?= htmlspecialchars($order) ?>">
                <input type="search" name="search" class="form-control me-2" placeholder="Search users..." 
                       value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-outline-primary">Search</button>
                <?php if($search): ?>
                    <a href="?" class="btn btn-outline-secondary ms-2">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th class="sortable" onclick="window.location.href='<?= getSortUrl('id') ?>'">
                        ID
                        <?php if($sort === 'id'): ?>
                            <i class="fas fa-sort-<?= $order === 'asc' ? 'up' : 'down' ?> sort-icon"></i>
                        <?php endif; ?>
                    </th>
                    <th class="sortable" onclick="window.location.href='<?= getSortUrl('nom') ?>'">
                        Nom
                        <?php if($sort === 'nom'): ?>
                            <i class="fas fa-sort-<?= $order === 'asc' ? 'up' : 'down' ?> sort-icon"></i>
                        <?php endif; ?>
                    </th>
                    <th class="sortable" onclick="window.location.href='<?= getSortUrl('prenom') ?>'">
                        Prénom
                        <?php if($sort === 'prenom'): ?>
                            <i class="fas fa-sort-<?= $order === 'asc' ? 'up' : 'down' ?> sort-icon"></i>
                        <?php endif; ?>
                    </th>
                    <th class="sortable" onclick="window.location.href='<?= getSortUrl('email') ?>'">
                        Email
                        <?php if($sort === 'email'): ?>
                            <i class="fas fa-sort-<?= $order === 'asc' ? 'up' : 'down' ?> sort-icon"></i>
                        <?php endif; ?>
                    </th>
                    <th class="sortable" onclick="window.location.href='<?= getSortUrl('type') ?>'">
                        Type
                        <?php if($sort === 'type'): ?>
                            <i class="fas fa-sort-<?= $order === 'asc' ? 'up' : 'down' ?> sort-icon"></i>
                        <?php endif; ?>
                    </th>
                    <th>Mot de passe</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($users)): ?>
                    <tr>
                        <td colspan="7" class="text-center">No users found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user->getId()) ?></td>
                        <td><?= htmlspecialchars($user->getNom()) ?></td>
                        <td><?= htmlspecialchars($user->getPrenom()) ?></td>
                        <td><?= htmlspecialchars($user->getEmail()) ?></td>
                        <td><?= htmlspecialchars(ucfirst($user->getType())) ?></td>
                        <td>******</td>
                        <td>
                            <a href="edit_user.php?id=<?= $user->getId() ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="delete_user.php?id=<?= $user->getId() ?>" class="btn btn-danger btn-sm" 
                               onclick="return confirm('Are you sure you want to delete this user?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="add_user.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New User
        </a>
    </div>
</body>
</html>