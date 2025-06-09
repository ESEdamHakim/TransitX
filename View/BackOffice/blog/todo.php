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
    <link rel="stylesheet" href="assets/css/todo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


</head>

<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="../../FrontOffice/index.php" class="logoback-link">
                    <div class="logoback">
                        <img src="../../assets/images/logo.png" alt="TransitX Logoback" class="nav-logo">
                        <span class="logo-text">Transit</span><span class="highlight logo-text">X</span>
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
                        <li><a href="../trajets/crud.php">
                                <i class="fas fa-road"></i>
                                <span>Trajets</span>
                            </a>
                        </li>
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
                        <li>
                            <a href="../covoiturage/crud.php">
                                <i class="fas fa-car-side"></i>
                                <span>Covoiturage</span>
                            </a>
                        </li>
                        <li>
                            <a href="../blog/crud.php">
                                <i class="fas fa-blog"></i>
                                <span>Blog</span>
                            </a>
                        </li>
                        <li><a href="../vehicule/crud.php"><i class="fas fa-car"></i><span>Véhicules</span></a></li>

                    </ul>
                    <a href="../../../index.php" class="logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Déconnexion</span>
                    </a>
                </nav>
            </div>
        </aside>
        <main class="main-content">
            <div class="container todo-header-container">
                <div class="section-header todo-section-header">
                    <h2>
                        <i class="fas fa-list-check" style="color:#86b391;"></i>
                        To Do List
                    </h2>
                    <span class="todo-subtitle">Organisez vos tâches par glisser-déposer</span>
                </div>

                <form method="POST" class="form">
                    <input type="text" name="contenu" placeholder="Ajouter une tâche..." required>
                    <input type="submit" value="Ajouter">
                </form>
                <?php
                $total = count($taches);
                $done = count($terminees);
                $percent = $total > 0 ? round($done / $total * 100) : 0;
                ?>
                <div class="todo-progress-bar">
                    <div class="todo-progress-bar-inner" style="width:<?= $percent ?>%"></div>
                </div>
                <!-- BADGES ROW -->
                <div class="todo-badges">
                    <span class="column-badge">
                        <i class="fas fa-hourglass-start"></i> En attente
                    </span>
                    <span class="column-badge">
                        <i class="fas fa-spinner"></i> En cours
                    </span>
                    <span class="column-badge">
                        <i class="fas fa-check-circle"></i> Terminée
                    </span>
                </div>
                <!-- COLUMNS ROW -->
                <div class="todo-columns">

                    <div class="column" ondrop="drop(event, 'a_faire')" ondragover="allowDrop(event)">
                        <?php foreach ($a_faire as $t): ?>
                            <div class="task" draggable="true" ondragstart="drag(event)" id="task-<?= $t['id'] ?>"
                                ondblclick="editTask(<?= $t['id'] ?>)">
                                <span id="task-content-<?= $t['id'] ?>"><?= htmlspecialchars($t['contenu']) ?></span>
                                <span class="task-status-dot"></span>
                                <form method="POST" class="edit-form" id="edit-form-<?= $t['id'] ?>" style="display:none;"
                                    onsubmit="return submitEdit(event, <?= $t['id'] ?>)">
                                    <input type="hidden" name="update_task_id" value="<?= $t['id'] ?>">
                                    <input type="text" name="new_contenu" value="<?= htmlspecialchars($t['contenu']) ?>"
                                        required>
                                    <input type="submit" value="OK">
                                    <button type="button" onclick="cancelEdit(<?= $t['id'] ?>)">Annuler</button>
                                </form>
                                <a href="#" class="delete-btn" data-task-id="<?= $t['id'] ?>" title="Supprimer cette tâche">
                                    <i class="fa-solid fa-xmark"></i>
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
                                <a href="#" class="delete-btn" data-task-id="<?= $t['id'] ?>" title="Supprimer cette tâche">
                                    <i class="fa-solid fa-xmark"></i>
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
                                <a href="#" class="delete-btn" data-task-id="<?= $t['id'] ?>" title="Supprimer cette tâche">
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </main>
    </div>
    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Supprimer la tâche</h3>
                <button class="close-delete-modal" aria-label="Fermer"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette tâche ? Cette action est irréversible.</p>
            </div>
            <div class="modal-buttons">
                <button class="btn btn-secondary cancel-delete-btn" type="button">Annuler</button>
                <form id="delete-form" method="GET" action="todo.php" style="display:inline;">
                    <input type="hidden" name="delete" id="delete-task-id">
                    <button type="submit" class="btn btn-primary">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</body>
<script>
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const taskId = this.getAttribute('data-task-id');
            document.getElementById('delete-task-id').value = taskId;
            document.getElementById('delete-modal').classList.add('active');
        });
    });

    document.querySelector('.close-delete-modal').onclick =
        document.querySelector('.cancel-delete-btn').onclick = function () {
            document.getElementById('delete-modal').classList.remove('active');
            document.getElementById('delete-task-id').value = '';
        };

    // Optional: close modal when clicking outside modal-content
    window.addEventListener('click', function (e) {
        const modal = document.getElementById('delete-modal');
        if (e.target === modal) {
            modal.classList.remove('active');
            document.getElementById('delete-task-id').value = '';
        }
    });
</script>

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
        window.location.reload();
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
    function updateTodoProgressBar() {
        // Count all tasks and done tasks
        const allTasks = document.querySelectorAll('.task');
        const doneTasks = document.querySelectorAll('.task.done');
        const percent = allTasks.length > 0 ? Math.round((doneTasks.length / allTasks.length) * 100) : 0;
        document.getElementById('todo-progress-bar-inner').style.width = percent + '%';
    }
</script>

</body>

</html>