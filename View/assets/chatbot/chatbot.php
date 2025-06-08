<button id="openChatbotButton">
  <!-- Robot head icon, extra large and centered -->
  <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 48 48" style="display:block; margin:auto;">
    <rect x="9" y="15" width="30" height="21" rx="10" fill="#97c3a2" stroke="#1f4f65"/>
    <circle cx="18" cy="26" r="3" fill="#1f4f65"/>
    <circle cx="30" cy="26" r="3" fill="#1f4f65"/>
    <rect x="20" y="36" width="8" height="3" rx="1.5" fill="#1f4f65"/>
    <rect x="22" y="8" width="4" height="6" rx="2" fill="#d7dd83" stroke="#1f4f65"/>
    <line x1="9" y1="19" x2="3" y2="19" stroke="#1f4f65" stroke-width="3"/>
    <line x1="39" y1="19" x2="45" y2="19" stroke="#1f4f65" stroke-width="3"/>
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
                <!-- Microphone icon (looks like a classic mic) -->
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="9" y="2" width="6" height="12" rx="3" />
                    <path d="M12 18v2M8 22h8M19 10a7 7 0 0 1-14 0" />
                </svg>
            </button>
            <select id="chatLangSelect" style="margin-left:8px; border-radius:8px; padding:4px;">
                <option value="en-US">English</option>
                <option value="fr-FR">Français</option>
            </select>
        </div>
    </div>
</div>