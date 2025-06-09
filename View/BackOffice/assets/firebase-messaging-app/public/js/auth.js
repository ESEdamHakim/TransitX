// This file handles user authentication, including functions for signing in and signing out users. It may also manage user sessions.

const firebaseConfig = {
    apiKey: "YOUR_API_KEY",
    authDomain: "YOUR_PROJECT_ID.firebaseapp.com",
    projectId: "YOUR_PROJECT_ID",
    storageBucket: "YOUR_PROJECT_ID.appspot.com",
    messagingSenderId: "YOUR_SENDER_ID",
    appId: "YOUR_APP_ID"
};

firebase.initializeApp(firebaseConfig);

const auth = firebase.auth();

function signIn(email, password) {
    return auth.signInWithEmailAndPassword(email, password)
        .then(userCredential => {
            // Signed in 
            const user = userCredential.user;
            console.log("User signed in:", user);
            return user;
        })
        .catch(error => {
            console.error("Error signing in:", error);
            throw error;
        });
}

function signOut() {
    return auth.signOut()
        .then(() => {
            console.log("User signed out");
        })
        .catch(error => {
            console.error("Error signing out:", error);
            throw error;
        });
}

auth.onAuthStateChanged(user => {
    if (user) {
        console.log("User is signed in:", user);
    } else {
        console.log("No user is signed in.");
    }
});