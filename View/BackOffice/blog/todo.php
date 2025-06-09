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
    $stmt->execute([(int) $_GET['delete'], $user_id]);
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
        $update_task_id = (int) $_POST['update_task_id'];
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do avec Drag & Drop</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/crud.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .header-left h1 {
            color: #1f4f65;
            /* Blue color for the title */
        }

        .dashboard-content {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .todo-columns {
            display: flex;
            flex-direction: row;
            gap: 20px;
            width: 100%;
        }

        .column {
            background: rgb(229, 245, 247);
            flex: 1;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 5px #aaa;
            min-height: 300px;
            margin-bottom: 0;
        }

        .task {
            background: rgb(215, 246, 223);
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
            cursor: move;
            position: relative;
        }

        .done {
            text-decoration: line-through;
            color: #999;
        }

        .form input[type="text"] {
            width: 75%;
            padding: 8px;
            border-radius: 25px;
            border: 1px solid #e0e0e0;
            outline: none;
            font-size: 1em;
        }

        .form input[type="submit"] {
            background-color: #9bcbad;
            color: #fff;
            border: none;
            border-radius: 25px;
            padding: 8px 28px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .form input[type="submit"]:hover {
            background-color: #7ebd97;
        }

        .delete-btn {
            position: absolute;
            right: 8px;
            top: 8px;
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        /* Styles pour le formulaire d'édition inline */
        .edit-form {
            display: inline-block;
            margin-left: 5px;
        }

        .edit-form input[type="text"] {
            padding: 4px;
            font-size: 1em;
        }

        .edit-form input[type="submit"],
        .edit-form button {
            padding: 4px 8px;
            font-size: 0.9em;
            margin-left: 5px;
        }

        .todo-badges {
            display: flex;
            flex-direction: row;
            gap: 20px;
            width: 100%;
            margin-bottom: 0;
            /* Reduce space below badges */
            justify-content: space-between;
        }

        .column-badge {
            display: block;
            margin: 0 auto 8px auto;
            /* Add only a small space below badge */
            padding: 8px 28px;
            border-radius: 20px;
            background: #97c3a2;
            color: #fff;
            font-weight: 600;
            font-size: 1em;
            letter-spacing: 1px;
            box-shadow: 0 2px 6px rgba(31, 79, 101, 0.08);
            border: none;
            pointer-events: none;
        }
    </style>
</head>

<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="../../FrontOffice/index.php" class="logo-link">
                    <div class="logoback">
                        <img src="../../assets/images/logo.png" alt="TransitX Logoback" class="nav-logoback">
                        <span>Transit</span><span class="highlight">X</span>
                    </div>
                </a>
                <button class="sidebar-toggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div class="sidebar-content">
                <nav class="sidebar-menu">
                    <ul>
                        <li class="active">
                            <a href="../index.php">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="../users/crud.php">
                                <i class="fas fa-users"></i>
                                <span>Utilisateurs</span>
                            </a>
                        </li>
                        <li>
                            <a href="../bus/crud.php">
                                <i class="fas fa-bus"></i>
                                <span>Bus</span>
                            </a>
                        </li>
                        <li><a href="../trajets/crud.php"><i class="fas fa-road"></i><span>Trajets</span></a></li>

                        <li>
                            <a href="../colis/crud.php">
                                <i class="fas fa-box"></i>
                                <span>Colis</span>
                            </a>
                        </li>
                        <li>
                            <a href="../reclamations/crud.php">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>Réclamations</span>
                            </a>
                        </li>
                        <a href="crud.php">
                            <i class="fas fa-car-side"></i>
                            <span>Covoiturage</span>
                        </a>

                        <li>
                            <a href="../blog/crud.php">
                                <i class="fas fa-blog"></i>
                                <span>Blog</span>
                            </a>
                        </li>
                        <li>
                            <a href="../vehicule/crud.php">
                                <i class="fas fa-car"></i>
                                <span>Véhicules</span>
                            </a>
                        </li>


                    </ul>
                    <a href="../../../index.php" class="logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Déconnexion</span>
                    </a>
                </nav>
            </div>
        </aside>
        <main class="main-content">
            <header class="dashboard-header">
                <div class="header-left">
                    <h1>To Do List</h1>
                    <p>Ajoutez, modifiez et supprimez des tâches</p>
                </div>
            </header>
            <div class="dashboard-content">
                <form method="POST" class="form">
                    <input type="text" name="contenu" placeholder="Ajouter une tâche..." required>
                    <input type="submit" value="Ajouter"
                        style="background-color:#97c3a2; color:#fff; border:none; cursor:pointer;">
                </form>
                <!-- BADGES ROW -->
                <div class="todo-badges">
                    <span class="column-badge">En attente</span>
                    <span class="column-badge">en cours</span>
                    <span class="column-badge">terminee</span>
                </div>
                <!-- COLUMNS ROW -->
                <div class="todo-columns">

                    <div class="column" ondrop="drop(event, 'a_faire')" ondragover="allowDrop(event)">
                        <?php foreach ($a_faire as $t): ?>
                            <div class="task" draggable="true" ondragstart="drag(event)" id="task-<?= $t['id'] ?>"
                                ondblclick="editTask(<?= $t['id'] ?>)">
                                <span id="task-content-<?= $t['id'] ?>"><?= htmlspecialchars($t['contenu']) ?></span>
                                <form method="POST" class="edit-form" id="edit-form-<?= $t['id'] ?>" style="display:none;"
                                    onsubmit="return submitEdit(event, <?= $t['id'] ?>)">
                                    <input type="hidden" name="update_task_id" value="<?= $t['id'] ?>">
                                    <input type="text" name="new_contenu" value="<?= htmlspecialchars($t['contenu']) ?>"
                                        required>
                                    <input type="submit" value="OK">
                                    <button type="button" onclick="cancelEdit(<?= $t['id'] ?>)">Annuler</button>
                                </form>
                                <a href="?delete=<?= $t['id'] ?>" class="delete-btn"
                                    onclick="return confirm('Supprimer cette tâche ?')">
                                    <i class="fa-solid fa-xmark" style="color:#1f4f65;"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>

                    </div>
                    <div class="column" ondrop="drop(event, 'en_cours')" ondragover="allowDrop(event)">
                        <?php foreach ($en_cours as $t): ?>
                            <div class="task" draggable="true" ondragstart="drag(event)" id="task-<?= $t['id'] ?>"
                                ondblclick="editTask(<?= $t['id'] ?>)">
                                <span id="task-content-<?= $t['id'] ?>"><?= htmlspecialchars($t['contenu']) ?></span>
                                <form method="POST" class="edit-form" id="edit-form-<?= $t['id'] ?>" style="display:none;"
                                    onsubmit="return submitEdit(event, <?= $t['id'] ?>)">
                                    <input type="hidden" name="update_task_id" value="<?= $t['id'] ?>">
                                    <input type="text" name="new_contenu" value="<?= htmlspecialchars($t['contenu']) ?>"
                                        required>
                                    <input type="submit" value="OK">
                                    <button type="button" onclick="cancelEdit(<?= $t['id'] ?>)">Annuler</button>
                                </form>
                                <a href="?delete=<?= $t['id'] ?>" class="delete-btn"
                                    onclick="return confirm('Supprimer cette tâche ?')">
                                    <i class="fa-solid fa-xmark" style="color:#1f4f65;"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="column" ondrop="drop(event, 'terminee')" ondragover="allowDrop(event)">
                        <?php foreach ($terminees as $t): ?>
                            <div class="task done" draggable="true" ondragstart="drag(event)" id="task-<?= $t['id'] ?>"
                                ondblclick="editTask(<?= $t['id'] ?>)">
                                <span id="task-content-<?= $t['id'] ?>"><?= htmlspecialchars($t['contenu']) ?></span>
                                <form method="POST" class="edit-form" id="edit-form-<?= $t['id'] ?>" style="display:none;"
                                    onsubmit="return submitEdit(event, <?= $t['id'] ?>)">
                                    <input type="hidden" name="update_task_id" value="<?= $t['id'] ?>">
                                    <input type="text" name="new_contenu" value="<?= htmlspecialchars($t['contenu']) ?>"
                                        required>
                                    <input type="submit" value="OK">
                                    <button type="button" onclick="cancelEdit(<?= $t['id'] ?>)">Annuler</button>
                                </form>
                                <a href="?delete=<?= $t['id'] ?>" class="delete-btn"
                                    onclick="return confirm('Supprimer cette tâche ?')">
                                    <i class="fa-solid fa-xmark" style="color:#1f4f65;"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
        </main>
    </div>
</body>


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

        xhr.onload = function () {
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