import http from 'http';
import { Server as SocketServer } from 'socket.io';

//crée un serveur http utilisé pour écouter les connexions entrantes des clients
const server = http.createServer();

// crée un serveur web socket en utilisant la classe socket serveur
const io = new SocketServer(server, {
    // configuration cors pour autoriser toutes les origines * et les méthodes http get et post
    cors: {
        origin: "*", // Autorise toutes les origines
        methods: ["GET", "POST"] // Autorise les méthodes GET et POST
    }
});

//nombre de personne connecté

// Créez un objet pour stocker les informations sur les utilisateurs connectés et leur série associée
const connectedUsers = {};

// Écoute des connexions WebSocket
io.on('connection', (socket) => {

    console.log('A user connected:' + socket.id);


    socket.on('info', (info) => {
        const {idUser, idSerie } = JSON.parse(info);

        // Stockez les informations sur l'utilisateur connecté dans l'objet connectedUsers
        connectedUsers[socket.id] = { idUser, idSerie, socketId : socket.id };

        // Vérifier si un autre utilisateur avec le même idSerie est déjà connecté
        const usersWithSameSerie = Object.values(connectedUsers).filter(user => user.idSerie === idSerie);

        //si il y a plus d'utilisateur avec le même idSerie
        if (usersWithSameSerie.length > 1) {
            usersWithSameSerie.forEach(user => {
                //le message qu'on envoie en front
                const message = `${user.idUser} joue aussi la même partie que vous.`;
                io.to(user.socketId).emit('notification', message); // Envoyer un message à chaque utilisateur
            });
        }
    });

    // Écoute des messages du client
    socket.on('message', (message) => {
        console.log(`Received: ${message}`);

        //transforme les data en json
        const dataToSend = {
            id : socket.id,
            data : message
        }
        const jsonData = JSON.stringify(dataToSend);

        //notify tout le monde
        notifyAll(jsonData);
    });

    // Gérer la déconnexion du client
    socket.on('disconnect', () => {
        console.log('Client disconnected:', socket.id);
        // Supprimer les informations sur l'utilisateur déconnecté de l'objet connectedUsers
        delete connectedUsers[socket.id];

    });
});

// Envoie un message à tous les clients
const notifyAll = (msg) => {
    io.emit('message', msg);
};


// Démarrage du serveur HTTP sur le port 3000
server.listen(3000, () => {
    console.log('Server running on port 3000');
});