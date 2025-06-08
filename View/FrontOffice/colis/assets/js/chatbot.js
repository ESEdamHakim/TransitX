// Updated chatbot script that fetches and includes colis table data in the system message

document.addEventListener('DOMContentLoaded', function () {
    const openChatbotButton = document.getElementById('openChatbotButton');
    const chatbotContainer = document.getElementById('chatbotContainer');
    const chatBox = document.getElementById('chatBox');
    const inputMessage = document.getElementById('inputMessage');
    const sendMessageButton = document.getElementById('sendMessageButton');
    let colisText = 'Loading colis data...';

    // Fetch colis data from PHP backend
    async function loadColisData() {
        try {
            const response = await axios.get('getColis.php');
            const colis = response.data.list; // or adjust depending on your structure
            colisText = colis.map(c => 
                `ID: ${c.id_colis}, Client ID: ${c.id_client}, Covoiturage ID: ${c.id_covoit ?? 'N/A'}, Statut: ${c.statut}, ` +
    `Date: ${c.date_colis}, Dimensions (LxWxH): ${c.longueur}x${c.largeur}x${c.hauteur}, Poids: ${c.poids}kg, ` +
    `Lieu Ramassage: ${c.lieu_ram}, Lieu Destination: ${c.lieu_dest}, ` +
    `Coord. Ramassage: (${c.latitude_ram}, ${c.longitude_ram}), Coord. Destination: (${c.latitude_dest}, ${c.longitude_dest}), ` +
    `Prix: ${c.prix} TND`
            ).join('\n');            
        } catch (error) {
            console.error('Failed to load colis data:', error);
            colisText = 'Unable to fetch colis data.';
        }
    }    
    loadColisData();

    inputMessage.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
        this.style.overflowY = this.scrollHeight > 120 ? 'auto' : 'hidden';
    });

    openChatbotButton.addEventListener('click', function () {
        if (chatbotContainer.style.display === 'none' || chatbotContainer.style.display === '') {
            chatbotContainer.style.display = 'flex';
            if (chatBox.children.length <= 1) {
                setTimeout(() => {
                    addBotMessage("Hello. How can I be of service to you today?");
                }, 500);
            }
        } else {
            chatbotContainer.style.display = 'none';
        }
    });

    document.querySelector('.header-icon:last-child').addEventListener('click', function () {
        chatbotContainer.style.display = 'none';
    });

    sendMessageButton.addEventListener('click', sendMessage);

    inputMessage.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    async function sendMessage() {
        const userMessage = inputMessage.value.trim();
        if (!userMessage) return;

        addUserMessage(userMessage);
        inputMessage.value = '';
        inputMessage.style.height = 'auto';

        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'loading';
        for (let i = 0; i < 3; i++) loadingDiv.appendChild(document.createElement('span'));
        chatBox.appendChild(loadingDiv);
        chatBox.scrollTop = chatBox.scrollHeight;

        try {
            const response = await axios.post('https://api.zukijourney.com/v1/chat/completions', {
                model: 'gpt-4o-mini',
                messages: [
                    { role: 'system', content: `Here is the current Colis data:\n${colisText}` },
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
        return `${hours}:${minutes} ${ampm}`;
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

        const timeDiv = document.createElement('div');
        timeDiv.className = 'message-time';
        timeDiv.textContent = getCurrentTime();
        messageDiv.appendChild(timeDiv);

        container.appendChild(avatar);
        container.appendChild(messageDiv);
        chatBox.appendChild(container);
        chatBox.scrollTop = chatBox.scrollHeight;
         // Text-to-Speech
       if ('speechSynthesis' in window) {
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = currentLang;
            window.speechSynthesis.speak(utterance);
        }
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
