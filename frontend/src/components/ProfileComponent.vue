<script>
import axios from 'axios'

export default {
    data() {
        return {
            loading: true,
            user: null,
            history: [],
            error: null
        }
    },
    methods: {
        fetchProfil() {
            axios.get('http://docketu.iutnc.univ-lorraine.fr:11111/profile', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                }
            })
                .then((response) => {
                    this.user = response.data.user
                    this.history = response.data.games_played
                    // créer des objets Date à partir des dates de jeu pour les trier
                    this.history.forEach(game => {
                        game.date = new Date(game.date)
                        game.level = function (level) {
                            switch (level) {
                                case 1:
                                    return "Facile"
                                case 2:
                                    return "Moyen"
                                case 3:
                                    return "Difficile"
                                default:
                                    return "Inconnu"
                            }
                        }(game.level)
                    })
                    // trier les jeux par date
                    this.history.sort((a, b) => b.date - a.date)
                })
                .catch(() => {
                    this.error = "Impossible de charger le profil."
                })
                .finally(() => {
                    this.loading = false
                })
        },
        logout() {
            localStorage.removeItem('access_token'); // supprimez le token d'accès du local storage
            this.$router.push('/'); // redirigez l'utilisateur vers la page de connexion
        },
    },
    created() {
        this.$root.isUserSignedIn().then(signedIn => {
            if (!signedIn) {
                this.$router.push('/signin')
                return
            }
            this.fetchProfil()
        })
    }
}
</script>

<template>
    <div class="mt-5">
        <div v-if="$root.$data.signedIn">
            <div v-if="loading" class="d-flex justify-content-center mt-5">
                <div id="spinner" class="spinner-border" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
            </div>
            <div v-else-if="error" class="alert alert-danger" role="alert">
                {{ error }}
            </div>
            <div v-else>
                <h1 class="fw-bold mb-3 text-center">Profil</h1>
                <div class="profil-card">
                    <h2 class="profil-title fw-bold mb-5">Vos informations</h2>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Adresse email</span>
                        <input type="email" class="form-control" id="email" v-model="user.email" disabled>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Nom d'utilisateur</span>
                        <input type="text" class="form-control" id="username" v-model="user.username" disabled>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-outline-danger" type="button" @click="logout">Se déconnecter</button>
                    </div>
                </div>

                <div class="profil-card">
                    <h2 class="profil-title fw-bold mb-5">Historique des parties</h2>
                    <div v-if="history.length === 0" class="alert alert-info" role="alert">
                        Vous n'avez pas encore joué de parties. <RouterLink to="/games">Commencer à jouer.</RouterLink>
                    </div>
                    <div v-else>
                        <div class="list-group list-group-flush">
                            <div v-for="game in history" :key="game.id">
                                <RouterLink :to="'/games/' + game.id_game + '/play'" class="">
                                <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Partie du {{ game.date.toLocaleDateString() }} à {{ game.date.toLocaleTimeString() }}</h5>
                                    <small>{{ game.score }} points</small>
                                </div>
                                <div>Série : {{ game.name_serie }}</div>
                                <small>Difficulté : {{ game.level }}</small>
                                </div>
                                </RouterLink>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
#spinner {
    width: 3rem;
    height: 3rem;
}

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

    a {
        color: #343a40;
        text-decoration: none;

    }

}

.profil-title {
    font-size: 2em;
    color: #343a40;
    text-align: center;
}
</style>
