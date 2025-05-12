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

            // STEP 3: Create the system message content with the data
            const systemContent = `You are speaking to a dear user of TransitX. Please assist them professionally and helpfully.`;

            // STEP 4: Send data to GPT API
            const response = await axios.post('https://api.zukijourney.com/v1/chat/completions', {
                model: 'gpt-4o-mini',
                messages: [
                    { role: 'system', content: systemContent },
                    { role: 'user', content: userMessage }
                ]
            }, {
                headers: {
                    'Authorization': 'Bearer zu-b4c25e58e0925cd7a87fc34aae4d4dbf',
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

        container.appendChild(avatar); // bot avatar on the left
        container.appendChild(messageDiv);
        chatBox.appendChild(container);
        chatBox.scrollTop = chatBox.scrollHeight;
    }
});