<!DOCTYPE html>
<html lang="fr">
    
<head>
    <meta charset="UTF-8">
    <title>To-Do avec Drag & Drop</title>
    <style>
        body { font-family: Arial; background:rgb(255, 255, 255); padding: 20px; }
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

<h1 style="text-align:center;">To-Do List</h1>

<form method="POST" class="form">
    <input type="text" name="contenu" placeholder="Ajouter une tâche..." required>
    <input type="submit" value="Ajouter" style="background-color:#003366; color:#fff; border:none; cursor:pointer;">
</form>

<hr>

<div class="container">
    <div class="column" ondrop="drop(event, 'a_faire')" ondragover="allowDrop(event)">
        <h2>À faire</h2>
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
        <h2>En cours</h2>
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
        <h2>Terminées</h2>
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