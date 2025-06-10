<?php
$userC = new UserC();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$user_nom = $userC->getNameById($user_id);
$user_image = $userC->getImageById($user_id);
?>
<!-- Messagerie Button -->
<button id="openMessagerieBtn" class="messagerie-btn">
    <i class="fas fa-comments"></i>
</button>
<!-- Messagerie Modal -->
<div id="messagerieModal" class="messagerie-modal" style="display: none;" role="dialog" aria-modal="true">
    <div class="messagerie-modal-content">
        <button class="messagerie-modal-close" id="closeMessagerieModal" aria-label="Fermer">&times;</button>
        <div class="messagerie-modal-body">
            <div class="chat-container">
                <div id="messages" class="messages"></div>
                <div class="chat-inputs">
                    <input type="text" id="message" placeholder="Votre message" maxlength="500">
                    <button id="sendBtn">Envoyer</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Pass PHP user data to JS
    const CURRENT_USER_NOM = <?= json_encode($user_nom) ?>;
    const CURRENT_USER_IMAGE = <?= json_encode($user_image) ?>;
</script>