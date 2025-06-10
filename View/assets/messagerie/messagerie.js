document.addEventListener('DOMContentLoaded', function () {
    const messagesDiv = document.getElementById('messages');
    const messageInput = document.getElementById('message');
    const sendBtn = document.getElementById('sendBtn');

    function escapeHTML(str) {
        return str.replace(/[&<>"']/g, function (m) {
            return ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            })[m];
        });
    }

    function fetchMessages() {
        fetch('../assets/messagerie/get_messages.php')
            .then(res => res.json())
            .then(data => {
                messagesDiv.innerHTML = '';
                data.forEach(msg => {
                    // If your backend returns image, use it; otherwise, fallback to a default
                    const imgSrc = msg.image && msg.image !== ''
                        ? '../assets/uploads/profiles/' + escapeHTML(msg.image)
                        : '../assets/images/user-placeholder.png';
                    const row = document.createElement('div');
                    row.className = 'message-row';
                    row.innerHTML = `
                        <img src="${imgSrc}" alt="user" class="message-avatar" style="width:32px;height:32px;border-radius:50%;vertical-align:middle;margin-right:8px;">
                        <span class="message-username">${escapeHTML(msg.username)}</span>
                        <span>${escapeHTML(msg.message)}</span>
                        <span class="message-time">${new Date(msg.timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</span>
                    `;
                    messagesDiv.appendChild(row);
                });
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            });
    }

    function sendMessage() {
        const username = typeof CURRENT_USER_NOM !== 'undefined' ? CURRENT_USER_NOM : 'Utilisateur';
        const image = typeof CURRENT_USER_IMAGE !== 'undefined' ? CURRENT_USER_IMAGE : '../assets/images/user-placeholder.png';
        const message = messageInput.value.trim();
        if (!username || !message) return;

        fetch('../assets/messagerie/send_messages.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, image, message })
        })
            .then(res => res.json())
            .then(() => {
                messageInput.value = '';
                fetchMessages();
            });
    }

    if (sendBtn && messageInput) {
        sendBtn.addEventListener('click', sendMessage);
        messageInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') sendMessage();
        });
    }

    setInterval(fetchMessages, 3000);
    fetchMessages();

    // Modal logic (no iframe)
    const openBtn = document.getElementById('openMessagerieBtn');
    const modal = document.getElementById('messagerieModal');
    const closeBtn = document.getElementById('closeMessagerieModal');

    if (openBtn && modal && closeBtn) {
        // Open Modal
        openBtn.addEventListener('click', function () {
            modal.style.display = 'flex';
        });

        // Close Modal
        function closeModal() {
            modal.style.display = 'none';
        }

        closeBtn.addEventListener('click', closeModal);

        // Close when clicking outside modal content
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Close on ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                closeModal();
            }
        });
    }
});