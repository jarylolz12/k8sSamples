importScripts('https://www.gstatic.com/firebasejs/9.7.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.7.0/firebase-messaging-compat.js');

firebase.initializeApp({
  apiKey: 'AIzaSyB-P-8y37DTlYBHXWodIJdA2-mvoa2w63U',
  authDomain: 'shifl-55af5.firebaseapp.com',
  projectId: 'shifl-55af5',
  storageBucket: 'shifl-55af5.appspot.com',
  messagingSenderId: '1068169176099',
  appId: '1:1068169176099:web:7b9aae6a5adc150715fd96',
  measurementId: 'G-PJZQ9KXDQM'
})
const messaging = firebase.messaging();
/*
messaging.onBackgroundMessage(payload => {
  console.log(payload)
});*/