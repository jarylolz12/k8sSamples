// Import the functions you need from the SDKs you need
import { initializeApp } from 'firebase/app'

const firebaseConfig = {
  apiKey: 'AIzaSyB-P-8y37DTlYBHXWodIJdA2-mvoa2w63U',
  authDomain: 'shifl-55af5.firebaseapp.com',
  projectId: 'shifl-55af5',
  storageBucket: 'shifl-55af5.appspot.com',
  messagingSenderId: '1068169176099',
  appId: '1:1068169176099:web:7b9aae6a5adc150715fd96',
  measurementId: 'G-PJZQ9KXDQM'
}

// Initialize Firebase
const firebaseApp = initializeApp(firebaseConfig)

export default firebaseApp