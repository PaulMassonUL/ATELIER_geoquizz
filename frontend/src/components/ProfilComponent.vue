<script>
import axios from 'axios'

export default {
    data() {
        return {
            profil: {
                id: 1,
                username: 'Régis'
            },
            scores: [
                {
                    id: 1,
                    score: 100
                },
                {
                    id: 2,
                    score: 200
                },
                {
                    id: 3,
                    score: 300
                },
                {
                    id: 4,
                    score: 400
                },
                {
                    id: 5,
                    score: 500
                }
            ],
            games: [
                {
                    id: 1,
                    game: 'game1'
                },
                {
                    id: 2,
                    game: 'game2'
                },
                {
                    id: 3,
                    game: 'game3'
                },
                {
                    id: 4,
                    game: 'game4'
                },
                {
                    id: 5,
                    game: 'game5'
                }
            ]
        }
    },
    methods: {
        // méthode qui va permettre de retourner les scores et les parties de l'utilisateur connecté
        fetchProfil() {
            axios
                .get('http://localhost:2080/profil')
                .then((response) => {
                    this.profil = response.data
                })
                .catch((error) => {
                    console.error(error)
                })
        },
        logout() {
            localStorage.removeItem('access_token'); // supprimez le token d'accès du local storage
            this.$router.push('/'); // redirigez l'utilisateur vers la page de connexion
        },
    }
}
</script>

<template>
    <div class="mt-5">
        <nav class="navbar navbar-light bg-light">
            <div class="container-fluid">
                <h1 class="profil-title fw-bold mb-5">Profil de {{ profil.username }}</h1>
                <button class="btn btn-outline-danger" type="button" @click="logout">Se déconnecter</button>
            </div>
        </nav>

        <h2 class="fw-bold">Mes Scores</h2>
        <div id="scores" class="d-flex flex-wrap mb-5">
            <div
                    v-for="score in scores"
                    :key="score.id"
                    class="card square-card m-3 d-flex align-items-center justify-content-center col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2"
            >
                <div class="card-body d-flex align-items-center justify-content-center">
                    <h2 class="card-title m-0">{{ score.score }}</h2>
                </div>
            </div>
        </div>

        <h2 class="fw-bold">Mes Parties</h2>
        <div id="games" class="d-flex flex-wrap mb-5">
            <div
                    v-for="game in games"
                    :key="game.id"
                    class="card square-card m-3 d-flex align-items-center justify-content-center col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2"
            >
                <div class="card-body d-flex align-items-center justify-content-center">
                    <h2 class="card-title m-0">{{ game.game }}</h2>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.square-card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2),
  0 6px 20px 0 rgba(0, 0, 0, 0.19);
  transition: 0.3s;

  &:hover {
    box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2),
    0 12px 40px 0 rgba(0, 0, 0, 0.19);
    cursor: pointer;
  }
}

.profil-card {
  background-color: #f8f9fa;
  color: #343a40;
  padding: 20px;
  margin-bottom: 20px;
  border-radius: 5px;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2),
  0 6px 20px 0 rgba(0, 0, 0, 0.19);
}

.profil-title {
  font-size: 2em; /* Augmenter la taille de la police */
  color: #343a40; /* Changer la couleur du texte */
  text-align: center; /* Aligner le texte à gauche */
}
</style>
