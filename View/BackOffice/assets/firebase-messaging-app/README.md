# Firebase Messaging App

This project is a real-time messaging application built using Firebase. It allows users to send and receive messages in real-time, providing a seamless chat experience.

## Project Structure

```
firebase-messaging-app
├── public
│   ├── index.html          # Main entry point for the application
│   ├── chat.html           # Chat interface for messaging
│   ├── css
│   │   └── styles.css      # CSS styles for the chat application
│   ├── js
│   │   ├── firebase-config.js # Firebase configuration
│   │   ├── auth.js         # User authentication logic
│   │   └── chat.js         # Logic for sending and receiving messages
├── .gitignore              # Files and directories to ignore by Git
├── package.json            # npm configuration file
└── README.md               # Project documentation
```

## Setup Instructions

1. **Clone the repository:**
   ```
   git clone <repository-url>
   cd firebase-messaging-app
   ```

2. **Install dependencies:**
   Make sure you have Node.js installed. Then run:
   ```
   npm install
   ```

3. **Firebase Configuration:**
   - Create a Firebase project at [Firebase Console](https://console.firebase.google.com/).
   - Obtain your Firebase configuration object and add it to `public/js/firebase-config.js`.

4. **Run the application:**
   You can use a local server to serve the `public` directory. For example, you can use `live-server` or any other static server.

5. **Access the application:**
   Open your browser and navigate to `http://localhost:PORT/index.html` to start using the chat application.

## Usage Guidelines

- Users can sign in to the application using the authentication methods provided in `auth.js`.
- Once signed in, users can navigate to the chat interface to send and receive messages in real-time.
- The chat interface updates automatically as new messages are sent.

## Contributing

Contributions are welcome! Please feel free to submit a pull request or open an issue for any suggestions or improvements.

## License

This project is licensed under the MIT License. See the LICENSE file for more details.