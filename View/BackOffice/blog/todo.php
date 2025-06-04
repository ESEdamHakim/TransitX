<?php
session_start();
require_once __DIR__ . '/../../../config.php';

$pdo = config::getConnexion();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Suppression d'une tâche
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM taches WHERE id = ? AND id_utilisateur = ?");
    $stmt->execute([(int)$_GET['delete'], $user_id]);
    header("Location: todo.php");
    exit;
}

// Gestion des requêtes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajout d'une tâche
    if (!empty($_POST['contenu'])) {
        $contenu = htmlspecialchars($_POST['contenu']);
        $stmt = $pdo->prepare("INSERT INTO taches (id_utilisateur, contenu, statut) VALUES (?, ?, 'a_faire')");
        $stmt->execute([$user_id, $contenu]);
        header("Location: todo.php");
        exit;
    }

    // Mise à jour du statut (via AJAX)
    if (!empty($_POST['task_id']) && !empty($_POST['new_status'])) {
        $stmt = $pdo->prepare("UPDATE taches SET statut = ? WHERE id = ? AND id_utilisateur = ?");
        $stmt->execute([$_POST['new_status'], $_POST['task_id'], $user_id]);
        exit;
    }

    // Mise à jour du contenu (via AJAX)
    if (!empty($_POST['update_task_id']) && isset($_POST['new_contenu'])) {
        $update_task_id = (int)$_POST['update_task_id'];
        $new_contenu = htmlspecialchars(trim($_POST['new_contenu']));

        if ($new_contenu !== '') {
            $stmt = $pdo->prepare("UPDATE taches SET contenu = ? WHERE id = ? AND id_utilisateur = ?");
            $stmt->execute([$new_contenu, $update_task_id, $user_id]);
        }
        exit;
    }
}

$stmt = $pdo->prepare("SELECT * FROM taches WHERE id_utilisateur = ?");
$stmt->execute([$user_id]);
$taches = $stmt->fetchAll(PDO::FETCH_ASSOC);

$a_faire = array_filter($taches, fn($t) => $t['statut'] === 'a_faire' || $t['statut'] === null);
$en_cours = array_filter($taches, fn($t) => $t['statut'] === 'en_cours');
$terminees = array_filter($taches, fn($t) => $t['statut'] === 'terminee');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>To-Do avec Drag & Drop</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; padding: 20px; }
        .container { display: flex; gap: 20px; justify-content: space-between; }
        .column { background: white; flex: 1; padding: 10px; border-radius: 8px; box-shadow: 0 0 5px #aaa; min-height: 300px; }
        .task { background: #eee; margin: 5px 0; padding: 10px; border-radius: 5px; cursor: move; position: relative; }
        .done { text-decoration: line-through; color: #999; }
        .form input[type="text"] { width: 75%; padding: 8px; }
        .form input[type="submit"] { padding: 8px 12px; }
        .delete-btn { position: absolute; right: 8px; top: 8px; color: red; text-decoration: none; font-weight: bold; }
        /* Styles pour le formulaire d'édition inline */
        .edit-form { display: inline-block; margin-left: 5px; }
        .edit-form input[type="text"] { padding: 4px; font-size: 1em; }
        .edit-form input[type="submit"], .edit-form button { padding: 4px 8px; font-size: 0.9em; margin-left: 5px; }
    </style>
</head>
<body>

<h1 style="text-align:center;">📋 To-Do List</h1>

<form method="POST" class="form">
    <input type="text" name="contenu" placeholder="Ajouter une tâche..." required>
    <input type="submit" value="Ajouter" style="background-color:#003366; color:#fff; border:none; cursor:pointer;">
</form>

<hr>

<div class="container">
    <div class="column" ondrop="drop(event, 'a_faire')" ondragover="allowDrop(event)">
        <h2>📝 À faire</h2>
        <?php foreach ($a_faire as $t): ?>
            <div class="task" draggable="true" ondragstart="drag(event)" id="task-<?= $t['id'] ?>" ondblclick="editTask(<?= $t['id'] ?>)">
                <span id="task-content-<?= $t['id'] ?>"><?= htmlspecialchars($t['contenu']) ?></span>
                <form method="POST" class="edit-form" id="edit-form-<?= $t['id'] ?>" style="display:none;" onsubmit="return submitEdit(event, <?= $t['id'] ?>)">
                    <input type="hidden" name="update_task_id" value="<?= $t['id'] ?>">
                    <input type="text" name="new_contenu" value="<?= htmlspecialchars($t['contenu']) ?>" required>
                    <input type="submit" value="OK">
                    <button type="button" onclick="cancelEdit(<?= $t['id'] ?>)">Annuler</button>
                </form>
                <a href="?delete=<?= $t['id'] ?>" class="delete-btn" onclick="return confirm('Supprimer cette tâche ?')">❌</a>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="column" ondrop="drop(event, 'en_cours')" ondragover="allowDrop(event)">
        <h2>🔧 En cours</h2>
        <?php foreach ($en_cours as $t): ?>
            <div class="task" draggable="true" ondragstart="drag(event)" id="task-<?= $t['id'] ?>" ondblclick="editTask(<?= $t['id'] ?>)">
                <span id="task-content-<?= $t['id'] ?>"><?= htmlspecialchars($t['contenu']) ?></span>
                <form method="POST" class="edit-form" id="edit-form-<?= $t['id'] ?>" style="display:none;" onsubmit="return submitEdit(event, <?= $t['id'] ?>)">
                    <input type="hidden" name="update_task_id" value="<?= $t['id'] ?>">
                    <input type="text" name="new_contenu" value="<?= htmlspecialchars($t['contenu']) ?>" required>
                    <input type="submit" value="OK">
                    <button type="button" onclick="cancelEdit(<?= $t['id'] ?>)">Annuler</button>
                </form>
                <a href="?delete=<?= $t['id'] ?>" class="delete-btn" onclick="return confirm('Supprimer cette tâche ?')">❌</a>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="column" ondrop="drop(event, 'terminee')" ondragover="allowDrop(event)">
        <h2>✅ Terminées</h2>
        <?php foreach ($terminees as $t): ?>
            <div class="task done" draggable="true" ondragstart="drag(event)" id="task-<?= $t['id'] ?>" ondblclick="editTask(<?= $t['id'] ?>)">
                <span id="task-content-<?= $t['id'] ?>"><?= htmlspecialchars($t['contenu']) ?></span>
                <form method="POST" class="edit-form" id="edit-form-<?= $t['id'] ?>" style="display:none;" onsubmit="return submitEdit(event, <?= $t['id'] ?>)">
                    <input type="hidden" name="update_task_id" value="<?= $t['id'] ?>">
                    <input type="text" name="new_contenu" value="<?= htmlspecialchars($t['contenu']) ?>" required>
                    <input type="submit" value="OK">
                    <button type="button" onclick="cancelEdit(<?= $t['id'] ?>)">Annuler</button>
                </form>
                <a href="?delete=<?= $t['id'] ?>" class="delete-btn" onclick="return confirm('Supprimer cette tâche ?')">❌</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    let draggedId;

    function allowDrop(event) {
        event.preventDefault();
    }

    function drag(event) {
        draggedId = event.target.id;
    }

    function drop(event, newStatus) {
        event.preventDefault();
        const taskDiv = document.getElementById(draggedId);
        const column = event.target.closest('.column');
        if (column) {
            column.appendChild(taskDiv);
        }

        const taskId = draggedId.replace('task-', '');

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "todo.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("task_id=" + taskId + "&new_status=" + newStatus);
    }

    // Edition inline du contenu
    function editTask(id) {
        document.getElementById('task-content-' + id).style.display = 'none';
        document.getElementById('edit-form-' + id).style.display = 'inline-block';
    }

    function cancelEdit(id) {
        document.getElementById('edit-form-' + id).style.display = 'none';
        document.getElementById('task-content-' + id).style.display = 'inline';
    }

    function submitEdit(event, id) {
        event.preventDefault();
        const form = event.target;
        const newContent = form.new_contenu.value.trim();
        if (!newContent) return false;

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "todo.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('task-content-' + id).textContent = newContent;
                cancelEdit(id);
            } else {
                alert("Erreur lors de la mise à jour.");
            }
        };

        xhr.send("update_task_id=" + id + "&new_contenu=" + encodeURIComponent(newContent));
        return false;
    }
</script>

</body>
</html>
