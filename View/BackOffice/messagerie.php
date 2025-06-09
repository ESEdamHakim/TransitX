<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Application</title>
<link rel="stylesheet" href="assets/firebase-messaging-app/public/css/styles.css">
    <!-- Use Firebase v8 for browser compatibility -->
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>
    <script>
      // Firebase config for TransitX Messenger
      var firebaseConfig = {
        apiKey: "AIzaSyA8aYuFvX_wTxJUzG1Ai2H37B1xTramYYQ",
        authDomain: "transitxmessenger.firebaseapp.com",
        databaseURL: "https://transitxmessenger-default-rtdb.firebaseio.com",
        projectId: "transitxmessenger",
        storageBucket: "transitxmessenger.appspot.com",
        messagingSenderId: "273624305132",
        appId: "1:273624305132:web:09865d1255f1232a9444c9"
      };
      firebase.initializeApp(firebaseConfig);

      var messagesRef = firebase.database().ref('messages');

      function sendMessage() {
        var input = document.getElementById('messageInput');
        var text = input.value.trim();
        if (text !== "") {
          messagesRef.push({
            user: "User_1",
            text: text,
            timestamp: Date.now()
          });
          input.value = "";
        }
      }

      document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('sendMessageBtn').onclick = sendMessage;
        messagesRef.on('child_added', function(snapshot) {
          var msg = snapshot.val();
          var msgDiv = document.createElement('div');
          msgDiv.textContent = msg.user + ": " + msg.text;
          document.getElementById('messages').appendChild(msgDiv);
          // Auto-scroll to bottom
          document.getElementById('messages').scrollTop = document.getElementById('messages').scrollHeight;
        });
      });
    </script>
    <style>
      .chat-container {
        max-width: 500px;
        margin: 30px auto;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 20px;
      }
      .messages {
        height: 300px;
        overflow-y: auto;
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
        background: #fafafa;
      }
      #messageInput {
        width: 75%;
        padding: 8px;
        margin-right: 5px;
      }
      #sendMessageBtn {
        padding: 8px 16px;
      }
    </style>
</head>
<body>
    <div class="chat-container">
        <h1>Chat Room</h1>
        <div id="messages" class="messages"></div>
        <input type="text" id="messageInput" placeholder="Type your message here..." autocomplete="off" />
        <button id="sendMessageBtn">Send</button>
    </div>
</body>
</html>