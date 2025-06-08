<button id="openChatbotButton">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
    </svg>
</button>

<div id="chatbotContainer">
    <div class="chat-wrapper">
        <div class="chat-header">
            <div class="chat-header-left">
                <div class="status-indicator"></div>
                <div>
                    <div class="chat-header-title">TransitX AI Assistant</div>
                    <div class="chat-header-subtitle">Online</div>
                </div>
            </div>
            <div class="chat-header-right">
                <svg class="header-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="1"></circle>
                    <circle cx="19" cy="12" r="1"></circle>
                    <circle cx="5" cy="12" r="1"></circle>
                </svg>
                <svg class="header-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </div>
        </div>

        <div class="chat-box" id="chatBox">
            <div class="date-separator">Today</div>
            <!-- Messages will appear here -->
        </div>

        <div class="chat-input">
            <div class="input-wrapper">
                <div class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                </div>
                <textarea id="inputMessage" placeholder="Type your message here..." rows="1"></textarea>
            </div>
            <button id="sendMessageButton">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
            <button id="voiceInputButton" title="Parler">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="11" r="4"></circle>
                    <path d="M19 11a7 7 0 0 1-14 0"></path>
                    <line x1="12" y1="15" x2="12" y2="19"></line>
                    <line x1="8" y1="19" x2="16" y2="19"></line>
                </svg>
            </button>
            <select id="chatLangSelect" style="margin-left:8px; border-radius:8px; padding:4px;">
                <option value="en-US">English</option>
                <option value="fr-FR">Français</option>
            </select>
        </div>
    </div>
</div>
