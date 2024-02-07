<script>
import axios from 'axios'

export default {
  data() {
    return {
      games: [],
      loading: false,
      message: ''
    }
  },
  methods: {
    fetchGames() {
      this.loading = true
      axios.get('http://localhost:2080/games')
          .then(response => {
            this.games = response.data
            if (this.games.length === 0) this.message = 'Aucune partie trouvée. Créez-en une !'
          })
          .catch(error => {
            console.error(error)
            this.message = 'Impossible de charger les parties. Veuillez réessayer plus tard.'
          })
          .finally(() => {
            this.loading = false
          })
    }
  },
  created() {
    this.fetchGames()
  }
}
</script>

<template>
  <div>
    <h1 id="title">Liste des parties</h1>
    <div v-if="loading" class="d-flex justify-content-center mt-5">
      <div id="spinner" class="spinner-border" role="status">
        <span class="visually-hidden">Chargement...</span>
      </div>
    </div>
    <div v-else>
      <div v-if="message" id="message" class="mt-5">{{ message }}</div>
      <div id="games-list" v-else>
        <div v-for="game in games" :key="game.id" class="card mb-3">
          <div class="card-body">
            <h2 class="card-title">{{ game.name }}</h2>
            <p class="card-text">{{ game.description }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
#title {
  text-decoration: underline;
}

#spinner {
  width: 3rem;
  height: 3rem;
}

#message {
  color: gray;
  font-weight: bold;
  font-size: 1.5rem;
  text-align: center;
}
</style>
