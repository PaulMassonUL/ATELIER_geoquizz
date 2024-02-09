<script>
import io from 'socket.io-client';

export default {
  data() {
    return {
      socket: null,
      messages: [],
      inputText: '',
      idSerie : 1,
      idUser : 2
    };
  },

  //mounted appelé lorsque la composante est montée dans le dom
  mounted() {
    // initialise la connection
    this.socket = io('http://localhost:3000');

    //évenement émis dès que la connection est établie

    this.socket.on('connect', () => {
      const info = {
        idUser : this.idUser,
        idSerie : this.idSerie
      }
      const jsonData = JSON.stringify(info);
      this.socket.emit('info', jsonData);
    });

    this.socket.on('notification', message => {
      console.log(message);
    });

    //écouteurs d'événements sur la connexion web socket
    // évenement open, message, close, error
    this.socket.addEventListener('open', () => {
      console.log('Connected to server');
    });

    this.socket.addEventListener('partieEnCours', event => {
      const messageDate = JSON.parse(event);
    });

    this.socket.addEventListener('message', event => {
      const messageData = JSON.parse(event);
      this.messages.push({ id: messageData.id, text: messageData.data });
    });
    this.socket.addEventListener("close", (event) => {
      console.log('disconnected from ws://localhost:3000');
    });
    this.socket.addEventListener("error", (event) => {
      console.log('error on ws://localhost:3000');
    });

  },
  methods: {
    sendMessage() {
      if (this.inputText.trim() !== '') {
        this.socket.emit('message' ,this.inputText);
        this.inputText = ''; // Clear input field after sending message
      }
    }
  }
}
</script>

<template>
  <div class="content">
    <ul>
      <li v-for="message in messages" :key="message.id">
        {{ message.text }}
      </li>
    </ul>
      <input type="text" v-model="inputText" />
      <p> {{ inputText }} </p>
      <button @click="sendMessage">Send</button>
  </div>
</template>