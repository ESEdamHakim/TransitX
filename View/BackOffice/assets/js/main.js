// Sidebar, Tabs, Charts, and Messagerie
document.addEventListener('DOMContentLoaded', function () {
  // Sidebar toggle
  const sidebarToggle = document.querySelector('.sidebar-toggle');
  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', function () {
      document.querySelector('.sidebar').classList.toggle('collapsed');
      document.querySelector('.main-content').classList.toggle('expanded');
    });
  }

  // Report Tabs (if present)
  const reportTabs = document.querySelectorAll('.report-tab');
  const reportContents = document.querySelectorAll('.report-content');
  if (reportTabs.length && reportContents.length) {
    reportTabs.forEach(tab => {
      tab.addEventListener('click', function () {
        reportTabs.forEach(t => t.classList.remove('active'));
        reportContents.forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        const reportType = this.getAttribute('data-report');
        document.getElementById(`${reportType}-report`).classList.add('active');
      });
    });
  }

  // Charts
  const servicesDistribution = document.getElementById('servicesDistribution');
  if (servicesDistribution) {
    const servicesDistributionCtx = servicesDistribution.getContext('2d');
    new Chart(servicesDistributionCtx, {
      type: 'doughnut',
      data: {
        labels: ['Bus', 'Covoiturage', 'Colis'],
        datasets: [{
          data: [
            typeof serviceCounts !== 'undefined' ? serviceCounts.bus : 0,
            typeof serviceCounts !== 'undefined' ? serviceCounts.covoiturage : 0,
            typeof serviceCounts !== 'undefined' ? serviceCounts.colis : 0
          ],
          backgroundColor: ['#1f4f65', '#97c3a2', '#d7dd83'],
          hoverOffset: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
      }
    });
  }

  const topServices = document.getElementById('topServices');
  if (topServices) {
    const topServicesCtx = topServices.getContext('2d');
    new Chart(topServicesCtx, {
      type: 'bar',
      data: {
        labels: ['Bus', 'Covoiturage', 'Colis'],
        datasets: [{
          data: [
            typeof serviceCounts !== 'undefined' ? serviceCounts.bus : 0,
            typeof serviceCounts !== 'undefined' ? serviceCounts.covoiturage : 0,
            typeof serviceCounts !== 'undefined' ? serviceCounts.colis : 0
          ],
          backgroundColor: ['#1f4f65', '#97c3a2', '#d7dd83']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        plugins: {
          legend: { display: false }
        },
        scales: {
          x: { beginAtZero: true }
        }
      }
    });
  }

  // --- Messagerie Section ---

  // Helper: Load users into the receiver select
  function loadUsers() {
    fetch('messages.php?action=users')
      .then(res => res.json())
      .then(users => {
        const select = document.getElementById('receiverSelect');
        if (!select) return;
        select.innerHTML = '';
        users.forEach(user => {
          const option = document.createElement('option');
          option.value = user.id;
          option.textContent = user.nom;
          select.appendChild(option);
        });
      });
  }

  // Helper: Load messages and display sender name and image
function loadMessages() {
  fetch('messages.php?action=list')
    .then(res => {
      if (!res.ok) {
        // Try to get error message from server, else use status text
        return res.text().then(text => {
          let msg = 'Erreur serveur ou non connecté.';
          try {
            const json = JSON.parse(text);
            if (json.error) msg = json.error;
          } catch (e) {
            // Not JSON, keep default
          }
          throw new Error(msg);
        });
      }
      return res.json();
    })
    .then(data => {
      const list = document.getElementById('messagesList');
      list.innerHTML = '';
      if (!Array.isArray(data)) {
        list.innerHTML = '<div style="color:red;">Aucune donnée à afficher.</div>';
        return;
      }
      data.forEach(msg => {
        const div = document.createElement('div');
        div.style.display = 'flex';
        div.style.alignItems = 'center';
        div.style.marginBottom = '10px';

        // Sender image
        const img = document.createElement('img');
        img.src = msg.sender_image && msg.sender_image !== '' ? msg.sender_image : 'assets/images/default-user.png';
        img.alt = msg.sender_name;
        img.style.width = '32px';
        img.style.height = '32px';
        img.style.borderRadius = '50%';
        img.style.marginRight = '10px';

        // Message content
        const content = document.createElement('div');
        content.innerHTML = `<strong>${msg.sender_name}</strong>: ${msg.text}`;

        div.appendChild(img);
        div.appendChild(content);
        list.appendChild(div);
      });
      // Scroll to bottom
      list.scrollTop = list.scrollHeight;
    })
    .catch(err => {
      const list = document.getElementById('messagesList');
      list.innerHTML =
        '<div style="color:red;">Erreur serveur ou non connecté.<br>' + err.message + '</div>';
    });
}
  // Poll for new messages every 2 seconds when modal is open
  let pollInterval = null;
  const openBtn = document.getElementById('openMessagerieButton');
  const closeBtn = document.getElementById('closeMessagerieModal');
  const modal = document.getElementById('messagerieModal');

  if (openBtn && closeBtn && modal) {
    openBtn.onclick = function () {
      modal.style.display = 'flex';
      loadUsers();
      loadMessages();
      if (!pollInterval) {
        pollInterval = setInterval(loadMessages, 2000);
      }
    };
    closeBtn.onclick = function () {
      modal.style.display = 'none';
      if (pollInterval) {
        clearInterval(pollInterval);
        pollInterval = null;
      }
    };
  }

  // Send message
  const sendForm = document.getElementById('sendMessageForm');
  if (sendForm) {
    sendForm.onsubmit = function (e) {
      e.preventDefault();
      const input = document.getElementById('messageInput');
      const receiver = document.getElementById('receiverSelect');
      if (!input || !receiver) return;
      const text = input.value.trim();
      if (!text) return;
      fetch('messages.php?action=send', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ text: text, receiver_id: receiver.value })
      })
        .then(res => res.json())
        .then(() => {
          input.value = '';
          loadMessages();
        });
    };
  }
});