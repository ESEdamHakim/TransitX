document.addEventListener('DOMContentLoaded', function () {
    const openChatbotButton = document.getElementById('openChatbotButton');
    const chatbotContainer = document.getElementById('chatbotContainer');
    const chatBox = document.getElementById('chatBox');
    const inputMessage = document.getElementById('inputMessage');
    const sendMessageButton = document.getElementById('sendMessageButton');

    // Auto-resize textarea
    inputMessage.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
        if (this.scrollHeight > 120) {
            this.style.overflowY = 'auto';
        } else {
            this.style.overflowY = 'hidden';
        }
    });

    // Toggle chatbot visibility
    openChatbotButton.addEventListener('click', function () {
        if (chatbotContainer.style.display === 'none' || chatbotContainer.style.display === '') {
            chatbotContainer.style.display = 'flex';
            if (chatBox.children.length <= 1) {
                setTimeout(() => {
                    addBotMessage("Greetings! How can I support you?");
                }, 500);
            }
        } else {
            chatbotContainer.style.display = 'none';
        }
    });

    // Close button in header
    document.querySelector('.header-icon:last-child').addEventListener('click', function () {
        chatbotContainer.style.display = 'none';
    });

    // Send message on button click
    sendMessageButton.addEventListener('click', sendMessage);

    // Send message on Enter key (but allow Shift+Enter for new lines)
    inputMessage.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    async function sendMessage() {
        const userMessage = inputMessage.value.trim();
        if (!userMessage) return; // Ignore empty messages
    
        // Add user message to chat
        addUserMessage(userMessage);
        inputMessage.value = '';
        inputMessage.style.height = 'auto';
    
        // Show loading animation
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'loading';
        for (let i = 0; i < 3; i++) {
            const span = document.createElement('span');
            loadingDiv.appendChild(span);
        }
        chatBox.appendChild(loadingDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
    
        try {
            // STEP 1: Fetch data from your PHP backend
            const fetchResponse = await axios.get('systemprompt.php');
            const data = fetchResponse.data;
    
            // STEP 2: Create a summarized string
            const trajets = Array.isArray(data.trajets) ? data.trajets : [];
            const buses = Array.isArray(data.buses) ? data.buses : [];
            
            const trajetData = trajets.length > 0
                ? trajets.map(t => 
                    `- Départ: ${t.place_depart}, Arrivée: ${t.place_arrivee}, Heure départ: ${t.heure_depart}, Durée: ${t.duree}, Distance: ${t.distance_km} km, Prix: ${t.prix} DT`
                ).join('\n')
                : 'Aucun trajet disponible.';
            
                const busData = buses.length > 0
                ? buses.map(b => 
                    `- Numéro: ${b.num_bus}, Marque: ${b.marque}, Modèle: ${b.modele}, Matricule: ${b.matricule}, Capacité: ${b.capacite} places, Disponibles: ${b.nb_places_dispo} places, Date mise en service: ${b.date_mise_en_service}`
                ).join('\n')
                : 'Aucun bus disponible.';
            
            
            // STEP 3: Create the system message content with the data
            const systemContent = `I am Edam Hakim.\n\n📍 Trajets:\n${trajetData}\n\n🚌 Bus:\n${busData}`;
    
            // STEP 4: Send data to GPT API
            const response = await axios.post('https://api.zukijourney.com/v1/chat/completions', {
                model: 'gpt-4o-mini',
                messages: [
                    { role: 'system', content: systemContent },
                    { role: 'user', content: userMessage }
                ]
            }, {
                headers: {
                    'Authorization': 'Bearer zu-c3b9ff6938b69d9d959f0aaf722415c8',
                    'Content-Type': 'application/json'
                }
            });
    
            chatBox.removeChild(loadingDiv);
            const botMessage = response.data.choices?.[0]?.message?.content || 'No response from API';
            addBotMessage(botMessage);
        } catch (error) {
            chatBox.removeChild(loadingDiv);
            console.error('Error:', error);
            addBotMessage('Sorry, I encountered an error. Please try again.');
        }
    }
    

    function getCurrentTime() {
        const now = new Date();
        let hours = now.getHours();
        let minutes = now.getMinutes();
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12 || 12;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        return hours + ':' + minutes + ' ' + ampm;
    }

    function addUserMessage(text) {
            const container = document.createElement('div');
            container.className = 'message-container user-container';
            const avatar = document.createElement('div');
            avatar.className = 'avatar user-avatar';
            avatar.textContent = 'Me';
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message user-message';
            messageDiv.textContent = text;
            const timeDiv = document.createElement('div');
            timeDiv.className = 'message-time';
            timeDiv.textContent = getCurrentTime();
            messageDiv.appendChild(timeDiv);
            container.appendChild(messageDiv);
            container.appendChild(avatar);
            chatBox.appendChild(container);
            chatBox.scrollTop = chatBox.scrollHeight;
        }
        

             // === Voice and Language Selection ===
    const voiceInputButton = document.getElementById('voiceInputButton');
    const langSelect = document.getElementById('chatLangSelect');
    let currentLang = langSelect ? langSelect.value : 'fr-FR';

    // Update language when user changes dropdown
    if (langSelect) {
        langSelect.addEventListener('change', function () {
            currentLang = this.value;
            if (recognition) recognition.lang = currentLang;
        });
    }

function addBotMessage(text) {
    const container = document.createElement('div');
    container.className = 'message-container bot-container';

    const avatar = document.createElement('div');
    avatar.className = 'avatar bot-avatar';
    const img = document.createElement('img');
    img.src = '../../assets/images/logo.png';
    img.alt = 'Bot';
    img.className = 'avatar-img';
    avatar.appendChild(img);

    const messageDiv = document.createElement('div');
    messageDiv.className = 'message bot-message';
    messageDiv.textContent = text;

    // Read Aloud button
    const readBtn = document.createElement('button');
    readBtn.className = 'read-aloud-btn';
    readBtn.title = 'Read aloud';
    readBtn.innerHTML = `
      <svg viewBox="0 0 24 24">
        <polygon points="3,9 9,9 13,5 13,19 9,15 3,15" />
        <path d="M16 8.82a5 5 0 0 1 0 6.36" />
        <path d="M19 5a9 9 0 0 1 0 14" />
      </svg>
    `;
    readBtn.onclick = function() {
        if ('speechSynthesis' in window) {
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = currentLang;
            window.speechSynthesis.speak(utterance);
        }
    };

    // Time
    const timeDiv = document.createElement('div');
    timeDiv.className = 'message-time';
    timeDiv.textContent = getCurrentTime();
    messageDiv.appendChild(timeDiv);

    // Add read button after message
    messageDiv.appendChild(readBtn);

    container.appendChild(avatar); // bot avatar on the left
    container.appendChild(messageDiv);
    chatBox.appendChild(container);
    chatBox.scrollTop = chatBox.scrollHeight;
}


    // Speech-to-Text
    let recognition;
    if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        recognition = new SpeechRecognition();
        recognition.lang = currentLang;
        recognition.maxAlternatives = 1;

        voiceInputButton.addEventListener('click', function () {
            recognition.lang = currentLang; // Always use latest selected lang
            recognition.start();
            voiceInputButton.disabled = true;
            voiceInputButton.classList.add('listening');
        });

        recognition.onresult = function (event) {
            const transcript = event.results[0][0].transcript;
            inputMessage.value = transcript;
            inputMessage.focus();
            voiceInputButton.disabled = false;
            voiceInputButton.classList.remove('listening');
        };

        recognition.onerror = function () {
            voiceInputButton.disabled = false;
            voiceInputButton.classList.remove('listening');
        };

        recognition.onend = function () {
            voiceInputButton.disabled = false;
            voiceInputButton.classList.remove('listening');
        };
    } else if (voiceInputButton) {
        voiceInputButton.style.display = 'none'; // Hide if not supported
    }
});