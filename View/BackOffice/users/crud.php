<?php
require_once __DIR__ . '/../../../Controller/UserC.php';

$userController = new UserC();

// Handle sorting
$sort = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'asc';

// Handle search
$search = $_GET['search'] ?? '';

// Get filtered and sorted users
$users = $userController->listUsers($sort, $order, $search);

// Function to toggle sort order
function getSortOrder($currentSort, $columnName)
{
  $currentOrder = $_GET['order'] ?? 'asc';
  return ($currentSort === $columnName && $currentOrder === 'asc') ? 'desc' : 'asc';
}

// Function to create sort URL
function getSortUrl($columnName)
{
  $params = $_GET;
  $params['sort'] = $columnName;
  $params['order'] = getSortOrder($_GET['sort'] ?? 'id', $columnName);
  return '?' . http_build_query($params);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Gestion des Utilisateurs</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/users.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <!-- Add jsPDF library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
  <!-- Add Jitsi Meet API -->
  <script src='https://meet.jit.si/external_api.js'></script>
  <!-- Add marked.js for markdown rendering -->
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
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

    .export-btn {
      margin-left: 10px;
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .export-btn:hover {
      background-color: #bb2d3b;
    }

    .meeting-btn {
      margin-left: 10px;
      background-color: #28a745;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .meeting-btn:hover {
      background-color: #218838;
    }

    .actions-container {
      display: flex;
      gap: 10px;
    }

    .full-screen {
      width: 95vw !important;
      height: 90vh !important;
      max-width: none !important;
    }

    #meet {
      height: calc(90vh - 60px);
      width: 100%;
      background: #000;
    }

    .meeting-controls {
      position: absolute;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 1000;
      display: flex;
      gap: 10px;
    }

    .meeting-controls button {
      padding: 10px 20px;
      border-radius: 20px;
      border: none;
      background: rgba(255, 255, 255, 0.2);
      color: white;
      cursor: pointer;
      backdrop-filter: blur(5px);
      transition: all 0.3s ease;
    }

    .meeting-controls button:hover {
      background: rgba(255, 255, 255, 0.3);
    }

    .meeting-controls button.end-call {
      background: rgba(255, 0, 0, 0.6);
    }

    .meeting-controls button.end-call:hover {
      background: rgba(255, 0, 0, 0.8);
    }

    .notification {
      position: fixed;
      top: 20px;
      right: 20px;
      background: rgba(0, 0, 0, 0.8);
      color: white;
      padding: 10px 20px;
      border-radius: 5px;
      z-index: 10000;
      animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
      from {
        transform: translateX(100%);
        opacity: 0;
      }

      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
    }

    .modal.active {
      display: block;
    }

    .modal-content {
      background-color: #fff;
      margin: 5% auto;
      padding: 20px;
      border-radius: 5px;
      width: 80%;
      max-width: 800px;
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-bottom: 15px;
      border-bottom: 1px solid #eee;
    }

    .close-modal {
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
    }

    /* AI Assistance Styles */
    .ai-assistance-btn {
      margin-left: 10px;
      background-color: #6f42c1;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .ai-assistance-btn:hover {
      background-color: #5a32a3;
    }

    #ai-modal .modal-content {
      max-width: 800px;
    }

    #ai-prompt {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    #ai-result {
      padding: 15px;
      background-color: #f8f9fa;
      border-radius: 4px;
      min-height: 200px;
      max-height: 400px;
      overflow-y: auto;
    }

    #ai-loading {
      display: none;
      text-align: center;
      padding: 20px;
    }

    .ai-actions {
      display: flex;
      justify-content: space-between;
      margin-top: 15px;
    }

    .error {
      color: #dc3545;
      padding: 10px;
      background-color: #f8d7da;
      border-radius: 4px;
    }
  </style>
</head>

<body>
  <div class="dashboard">
    <aside class="sidebar">
      <div class="sidebar-header">
        <div class="logo">
          <img src="../../assets/images/logo.png" alt="TransitX Logo" class="nav-logo">
          <span>Transit</span><span class="highlight">X</span>
        </div>
        <button class="sidebar-toggle">
          <i class="fas fa-bars"></i>
        </button>
      </div>

      <div class="sidebar-content">
        <nav class="sidebar-menu">
          <ul>
            <li>
              <a href="../index.php">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
              </a>
            </li>
            <li class="active">
              <a href="crud.php">
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
          </ul>
        </nav>
      </div>

      <div class="sidebar-footer">
        <a href="#" class="user-profile">
          <img src="../assets/images/placeholder-admin.png" alt="Admin" class="user-img">
          <div class="user-info">
            <h4>Admin User</h4>
            <p>Administrateur</p>
          </div>
        </a>
        <a href="../../../index.php" class="logout">
          <i class="fas fa-sign-out-alt"></i>
          <span>Déconnexion</span>
        </a>
      </div>
    </aside>

    <main class="main-content">
      <header class="dashboard-header">
        <div class="header-left">
          <h1>Gestion des Utilisateurs</h1>
          <p>Ajoutez, modifiez et supprimez des utilisateurs</p>
        </div>
        <div class="header-right">
          <form class="search-bar" method="GET">
            <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
            <input type="hidden" name="order" value="<?= htmlspecialchars($order) ?>">
            <input type="search" name="search" placeholder="Rechercher un utilisateur..."
              value="<?= htmlspecialchars($search) ?>">
            <button type="submit"><i class="fas fa-search"></i></button>
            <?php if ($search): ?>
              <a href="?" class="btn btn-outline-secondary ms-2">Effacer</a>
            <?php endif; ?>
          </form>
          <div class="actions-container">
            <a href="add_user.php" class="btn primary" id="add-user-btn">
              <i class="fas fa-plus"></i> Ajouter
            </a>
          </div>
        </div>
      </header>

      <div class="dashboard-content">
        <div class="users-container">
          <br>
          <div class="actions-container">
            <button class="btn primary" id="export-pdf-btn">
              <i class="fas fa-file-pdf"></i> PDF
            </button>
            <button class="btn primary" id="start-meeting-btn">
              <i class="fas fa-video"></i> Réunion
            </button>
            <button class="btn primary" id="ai-assistance-btn">
              <i class="fas fa-robot"></i> Assistance IA
            </button>
          </div>
          <br>
          <div class="view-container table-view active">
            <div class="users-table-container">
              <table class="users-table" id="users-table">
                <thead>
                  <tr>
                    <th class="sortable" onclick="window.location.href='<?= getSortUrl('id') ?>'">
                      ID
                      <?php if ($sort === 'id'): ?>
                        <i class="fas fa-sort-<?= $order === 'asc' ? 'up' : 'down' ?> sort-icon"></i>
                      <?php endif; ?>
                    </th>
                    <th class="sortable" onclick="window.location.href='<?= getSortUrl('nom') ?>'">
                      Nom
                      <?php if ($sort === 'nom'): ?>
                        <i class="fas fa-sort-<?= $order === 'asc' ? 'up' : 'down' ?> sort-icon"></i>
                      <?php endif; ?>
                    </th>
                    <th class="sortable" onclick="window.location.href='<?= getSortUrl('prenom') ?>'">
                      Prénom
                      <?php if ($sort === 'prenom'): ?>
                        <i class="fas fa-sort-<?= $order === 'asc' ? 'up' : 'down' ?> sort-icon"></i>
                      <?php endif; ?>
                    </th>
                    <th class="sortable" onclick="window.location.href='<?= getSortUrl('email') ?>'">
                      Email
                      <?php if ($sort === 'email'): ?>
                        <i class="fas fa-sort-<?= $order === 'asc' ? 'up' : 'down' ?> sort-icon"></i>
                      <?php endif; ?>
                    </th>
                    <th>Téléphone</th>
                    <th class="sortable" onclick="window.location.href='<?= getSortUrl('type') ?>'">
                      Type
                      <?php if ($sort === 'type'): ?>
                        <i class="fas fa-sort-<?= $order === 'asc' ? 'up' : 'down' ?> sort-icon"></i>
                      <?php endif; ?>
                    </th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($users)): ?>
                    <tr>
                      <td colspan="7" class="text-center">Aucun utilisateur trouvé</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($users as $user): ?>
                      <tr>
                        <td><?= htmlspecialchars($user->getId()) ?></td>
                        <td><?= htmlspecialchars($user->getNom()) ?></td>
                        <td><?= htmlspecialchars($user->getPrenom()) ?></td>
                        <td><?= htmlspecialchars($user->getEmail()) ?></td>
                        <td><?= htmlspecialchars($user->getTelephone()) ?></td>
                        <td><?= ucfirst(htmlspecialchars($user->getType())) ?></td>
                        <td class="actions">
                          <a href="edit_user.php?id=<?= $user->getId() ?>" class="action-btn edit" title="Modifier">
                            <i class="fas fa-edit"></i>
                          </a>
                          <a href="delete_user.php?id=<?= $user->getId() ?>" class="action-btn delete" title="Supprimer">
                            <i class="fas fa-trash"></i>
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Grid View -->
          <div class="view-container grid-view">
            <div class="users-grid">
              <?php foreach ($users as $user): ?>
                <div class="user-card">
                  <div class="user-avatar">
                    <img src="../assets/images/user-placeholder.png" alt="User Avatar">
                  </div>
                  <div class="user-info">
                    <h3><?= htmlspecialchars($user->getPrenom() . ' ' . $user->getNom()) ?></h3>
                    <p><?= htmlspecialchars($user->getEmail()) ?></p>
                    <span
                      class="user-role <?= $user->getType() ?>"><?= ucfirst(htmlspecialchars($user->getType())) ?></span>
                  </div>
                  <div class="user-actions">
                    <a href="../../edit_user.php?id=<?= $user->getId() ?>" class="action-btn edit" title="Modifier">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="../../delete_user.php?id=<?= $user->getId() ?>" class="action-btn delete" title="Supprimer">
                      <i class="fas fa-trash"></i>
                    </a>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal" id="delete-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Confirmer la suppression</h2>
        <button class="close-modal"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <p>Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.</p>
        <div class="form-actions">
          <button type="button" class="btn secondary cancel-btn">Annuler</button>
          <button type="button" class="btn danger" id="confirm-delete-btn">Supprimer</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Meeting Modal -->
  <div id="meet-container" class="modal">
    <div class="modal-content full-screen">
      <div class="modal-header">
        <h2>Réunion vidéo</h2>
        <button class="close-modal" onclick="closeMeeting()">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div id="meet" class="modal-body"></div>
    </div>
  </div>

  <!-- AI Assistance Modal -->
  <div class="modal" id="ai-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Assistance par IA Gemini</h2>
        <button class="close-modal"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <p>Demandez de l'aide à Gemini pour générer du contenu ou obtenir des conseils :</p>
        <textarea id="ai-prompt" rows="4"
          placeholder="Exemple : Rédige un email professionnel pour informer un utilisateur que son compte a été mis à jour..."></textarea>
        <div id="ai-result"></div>
        <div id="ai-loading" style="display: none;">
          <i class="fas fa-spinner fa-spin"></i> Gemini traite votre demande...
        </div>
        <div class="ai-actions">
          <button class="btn secondary" onclick="document.getElementById('ai-modal').classList.remove('active')">
            Annuler
          </button>
          <button class="btn primary" id="generate-ai-btn">
            <i class="fas fa-magic"></i> Générer
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Initialize jsPDF
    const { jsPDF } = window.jspdf;

    // Gemini API configuration
    const GEMINI_API_KEY = 'AIzaSyC43J6YfEHMLHteuehjvwX73OKU9pR1QSU';
    const GEMINI_ENDPOINT = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${GEMINI_API_KEY}`;

    // Function to generate content with Gemini
    async function generateWithGemini(prompt) {
      try {
        const response = await fetch(GEMINI_ENDPOINT, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            contents: [{
              parts: [{
                text: prompt
              }]
            }]
          })
        });

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        return data.candidates[0].content.parts[0].text;
      } catch (error) {
        console.error('Error:', error);
        throw error;
      }
    }

    // Function to handle AI assistance
    async function getAIAssistance() {
      const prompt = document.getElementById('ai-prompt').value;
      const loadingIndicator = document.getElementById('ai-loading');
      const resultContainer = document.getElementById('ai-result');

      if (!prompt.trim()) {
        resultContainer.innerHTML = '<div class="error">Veuillez entrer une demande valide</div>';
        return;
      }

      try {
        loadingIndicator.style.display = 'block';
        resultContainer.innerHTML = '';

        const response = await generateWithGemini(prompt);

        // Use marked.js to render markdown if needed
        resultContainer.innerHTML = marked.parse(response);
      } catch (error) {
        resultContainer.innerHTML = `<div class="error">Une erreur s'est produite: ${error.message}</div>`;
      } finally {
        loadingIndicator.style.display = 'none';
      }
    }

    // Add event listener for generate button
    document.getElementById('generate-ai-btn').addEventListener('click', getAIAssistance);

    // Add keyboard shortcut (Ctrl+Enter) in prompt textarea
    document.getElementById('ai-prompt').addEventListener('keydown', function (e) {
      if (e.ctrlKey && e.key === 'Enter') {
        getAIAssistance();
      }
    });

    // PDF Export Functionality
    document.getElementById('export-pdf-btn').addEventListener('click', function () {
      // Create new PDF document
      const doc = new jsPDF({
        orientation: 'landscape',
        unit: 'mm'
      });

      // Add title and logo
      doc.setFontSize(18);
      doc.setTextColor(40);
      doc.setFont('helvetica', 'bold');
      doc.text('Liste des Utilisateurs - TransitX', 105, 15, { align: 'center' });

      // Add export date
      const today = new Date();
      const dateStr = today.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
      doc.setFontSize(10);
      doc.setTextColor(100);
      doc.text(`Exporté le: ${dateStr}`, 105, 22, { align: 'center' });

      // Prepare table data
      const tableData = [];
      const headers = [
        "ID",
        "Nom",
        "Prénom",
        "Email",
        "Téléphone",
        "Type"
      ];

      // Add headers
      tableData.push(headers);

      // Add user data
      <?php foreach ($users as $user): ?>
        tableData.push([
          '<?= $user->getId() ?>',
          '<?= htmlspecialchars($user->getNom()) ?>',
          '<?= htmlspecialchars($user->getPrenom()) ?>',
          '<?= htmlspecialchars($user->getEmail()) ?>',
          '<?= htmlspecialchars($user->getTelephone()) ?>',
          '<?= ucfirst(htmlspecialchars($user->getType())) ?>'
        ]);
      <?php endforeach; ?>

      // Add table to PDF
      doc.autoTable({
        head: [tableData[0]],
        body: tableData.slice(1),
        startY: 30,
        margin: { left: 10, right: 10 },
        styles: {
          fontSize: 9,
          cellPadding: 3,
          valign: 'middle',
          textColor: [40, 40, 40]
        },
        headStyles: {
          fillColor: [220, 53, 69],
          textColor: 255,
          fontStyle: 'bold',
          halign: 'center'
        },
        bodyStyles: {
          halign: 'center'
        },
        alternateRowStyles: {
          fillColor: [245, 245, 245]
        },
        columnStyles: {
          0: { cellWidth: 15 }, // ID
          1: { cellWidth: 25 }, // Nom
          2: { cellWidth: 25 }, // Prénom
          3: { cellWidth: 40 }, // Email
          4: { cellWidth: 25 }, // Téléphone
          5: { cellWidth: 25 }  // Type
        }
      });

      // Add page numbers
      const pageCount = doc.internal.getNumberOfPages();
      for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        doc.setFontSize(8);
        doc.setTextColor(150);
        doc.text(`Page ${i} sur ${pageCount}`, 200, 207, { align: 'right' });
      }

      // Save the PDF
      doc.save(`Utilisateurs_TransitX_${today.getFullYear()}${(today.getMonth() + 1).toString().padStart(2, '0')}${today.getDate().toString().padStart(2, '0')}.pdf`);
    });

    // Sidebar Toggle
    document.querySelector('.sidebar-toggle').addEventListener('click', function () {
      document.querySelector('.sidebar').classList.toggle('collapsed');
      document.querySelector('.main-content').classList.toggle('expanded');
    });

    // Tab Switching
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
      button.addEventListener('click', function () {
        tabButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');

        // Filter users based on tab
        const tabName = this.getAttribute('data-tab');
        console.log(`Switching to tab: ${tabName}`);
        // You would need to implement actual filtering here
      });
    });

    // View Switching
    const viewButtons = document.querySelectorAll('.view-btn');
    const viewContainers = document.querySelectorAll('.view-container');
    viewButtons.forEach(button => {
      button.addEventListener('click', function () {
        viewButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');

        const viewType = this.getAttribute('data-view');
        viewContainers.forEach(container => {
          container.classList.remove('active');
          if (container.classList.contains(`${viewType}-view`)) {
            container.classList.add('active');
          }
        });
      });
    });

    // Delete Confirmation Modal
    const deleteModal = document.getElementById('delete-modal');
    const closeButtons = document.querySelectorAll('.close-modal, .cancel-btn');

    // Open Delete Confirmation Modal
    const deleteButtons = document.querySelectorAll('.action-btn.delete');
    deleteButtons.forEach(button => {
      button.addEventListener('click', function (e) {
        e.preventDefault();
        const deleteUrl = this.getAttribute('href');
        document.getElementById('confirm-delete-btn').onclick = function () {
          window.location.href = deleteUrl;
        };
        deleteModal.classList.add('active');
      });
    });

    // Close Modals
    closeButtons.forEach(button => {
      button.addEventListener('click', function () {
        deleteModal.classList.remove('active');
        document.getElementById('ai-modal').classList.remove('active');
      });
    });

    // Delete Confirmation Handler
    document.getElementById('confirm-delete-btn').addEventListener('click', function () {
      deleteModal.classList.remove('active');
    });

    // Jitsi Meet Integration
    let api = null;

    // Start Meeting Button Event Listener
    document.getElementById('start-meeting-btn').addEventListener('click', startMeeting);

    function generateMeetingId() {
      const timestamp = new Date().getTime();
      const random = Math.random().toString(36).substring(7);
      return `TransitX_${timestamp}_${random}`;
    }

    function startMeeting() {
      const domain = 'meet.jit.si';
      const options = {
        roomName: generateMeetingId(),
        width: '100%',
        height: '100%',
        parentNode: document.querySelector('#meet'),
        lang: 'fr',
        userInfo: {
          displayName: '<?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Administrateur"; ?>'
        },
        configOverwrite: {
          startWithAudioMuted: true,
          startWithVideoMuted: true,
          prejoinPageEnabled: false,
          disableDeepLinking: true
        },
        interfaceConfigOverwrite: {
          TOOLBAR_BUTTONS: [
            'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
            'fodeviceselection', 'hangup', 'profile', 'chat', 'recording',
            'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
            'videoquality', 'filmstrip', 'invite', 'feedback', 'stats', 'shortcuts',
            'tileview', 'videobackgroundblur', 'download', 'help', 'mute-everyone',
            'security'
          ],
          SHOW_JITSI_WATERMARK: false,
          SHOW_WATERMARK_FOR_GUESTS: false,
          DEFAULT_BACKGROUND: '#3c3c3c',
          DEFAULT_REMOTE_DISPLAY_NAME: 'Participant',
          TOOLBAR_ALWAYS_VISIBLE: true
        }
      };

      // Show the meeting container
      document.getElementById('meet-container').classList.add('active');

      // Initialize the Jitsi Meet API
      api = new JitsiMeetExternalAPI(domain, options);

      // Add event listeners
      api.addEventListeners({
        readyToClose: closeMeeting,
        participantLeft: handleParticipantLeft,
        participantJoined: handleParticipantJoined,
        videoConferenceJoined: handleVideoConferenceJoined,
        videoConferenceLeft: handleVideoConferenceLeft
      });
    }

    function closeMeeting() {
      if (api) {
        api.dispose();
        api = null;
      }
      document.getElementById('meet-container').classList.remove('active');
    }

    function handleParticipantLeft(participant) {
      console.log('Participant left', participant);
      showNotification('Un participant a quitté la réunion');
    }

    function handleParticipantJoined(participant) {
      console.log('Participant joined', participant);
      showNotification('Un nouveau participant a rejoint la réunion');
    }

    function handleVideoConferenceJoined(participant) {
      console.log('Video conference joined', participant);
      showNotification('Vous avez rejoint la réunion');
    }

    function handleVideoConferenceLeft(participant) {
      console.log('Video conference left', participant);
      closeMeeting();
    }

    function showNotification(message) {
      const notification = document.createElement('div');
      notification.className = 'notification';
      notification.textContent = message;
      document.body.appendChild(notification);

      setTimeout(() => {
        notification.remove();
      }, 3000);
    }
  </script>
  <!-- Add this right before the closing </body> tag -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Get the AI button and modal elements
      const aiButton = document.getElementById('ai-assistance-btn');
      const aiModal = document.getElementById('ai-modal');

      if (!aiButton || !aiModal) {
        console.error('Required elements not found');
        return;
      }

      const closeAiModal = aiModal.querySelector('.close-modal');
      const cancelBtn = aiModal.querySelector('.btn.secondary');

      // Add click event to open modal
      aiButton.addEventListener('click', function () {
        console.log('Opening AI modal');
        aiModal.classList.add('active');
        document.getElementById('ai-prompt').focus();
      });

      // Add click events to close modal
      [closeAiModal, cancelBtn].forEach(btn => {
        btn.addEventListener('click', function () {
          aiModal.classList.remove('active');
        });
      });

      // Close modal when clicking outside content
      aiModal.addEventListener('click', function (e) {
        if (e.target === aiModal) {
          aiModal.classList.remove('active');
        }
      });

      // Ensure modal is properly styled
      const modalStyle = document.createElement('style');
      modalStyle.textContent = `
            .modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.7);
                display: none;
                justify-content: center;
                align-items: center;
                z-index: 1000;
            }
            .modal.active {
                display: flex;
            }
            .modal-content {
                background-color: white;
                padding: 20px;
                border-radius: 5px;
                width: 80%;
                max-width: 800px;
                max-height: 90vh;
                overflow-y: auto;
            }
        `;
      document.head.appendChild(modalStyle);
    });
  </script>
  <!-- Add this right before the closing </body> tag -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Get the AI button and modal elements
      const aiButton = document.getElementById('ai-assistance-btn');
      const aiModal = document.getElementById('ai-modal');
      const closeAiModal = aiModal.querySelector('.close-modal');

      // Add click event to open modal
      aiButton.addEventListener('click', function () {
        aiModal.style.display = 'flex';
        aiModal.classList.add('active');
        document.getElementById('ai-prompt').focus();
      });

      // Add click event to close modal
      closeAiModal.addEventListener('click', function () {
        aiModal.style.display = 'none';
        aiModal.classList.remove('active');
      });

      // Close modal when clicking outside content
      aiModal.addEventListener('click', function (e) {
        if (e.target === aiModal) {
          aiModal.style.display = 'none';
          aiModal.classList.remove('active');
        }
      });
    });

    // Add this CSS to ensure modal displays properly
    const modalStyle = document.createElement('style');
    modalStyle.textContent = `
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
        }
    `;
    document.head.appendChild(modalStyle);
  </script>
</body>

</html>