<script>
import axios from 'axios'

export default {
  data() {
    return {
      loading: false,
      error: false,
      sending: false,
      sending_error: false,
      series: [],
      game: {
        id_serie: null,
        level: 2,
        isPublic: false
      }
    }
  },
  methods: {
    createGame() {
      if (this.sending) return

      if (!this.game.id_serie) {
        this.sending_error = 'Veuillez sélectionner une série pour votre partie.'
        return
      }

      if (!this.game.level) {
        this.sending_error = 'Veuillez sélectionner une difficulté pour votre partie.'
        return
      }

      if (isNaN(this.game.level)) return
      this.game.level = parseInt(this.game.level)
      if (this.game.level < 1 || this.game.level > 3) return

      this.game.isPublic = this.game.isPublic === true

      this.sending_error = ''
      this.sending = true
      axios
        .post('http://localhost:2080/games/new', this.game, {
          headers: {
            Authorization: 'Bearer ' + localStorage.getItem('access_token')
          }
        })
        .then((response) => {
          localStorage.setItem('game_token', response.data.token)
          this.$router.push('/games/' + response.data.id + '/play')
        })
        .catch(() => {
          this.sending_error = 'Vous devez être connecté pour créer une partie.'
        })
        .finally(() => {
          this.sending = false
        })
    },
    fetchSeries() {
      this.loading = true
      axios
        .get('http://docketu.iutnc.univ-lorraine.fr:11055/items/Serie')
        .then((response) => {
          this.series = response.data.data
          if (this.series.length === 0) this.error = true
        })
        .catch((error) => {
          console.log(error)
          this.error = true
        })
        .finally(() => {
          this.loading = false
        })
    }
  },
  created() {
    this.fetchSeries()
  }
}
</script>
<template>
  <section class="container mt-3">
    <div class="text-center">
      <h1>Nouvelle partie</h1>
    </div>
    <div v-if="loading" class="d-flex justify-content-center mt-5">
      <div id="spinner" class="spinner-border" role="status">
        <span class="visually-hidden">Chargement...</span>
      </div>
    </div>
    <div v-else>
      <div v-if="error" id="message" class="mt-5">
        Une erreur est survenue lors de la récupération des données de création.
      </div>
      <div v-else>
        <div class="mb-3">
          <!--      game avec choix de la série, difficulté et confidentialité (privé public) -->
          <label for="series" class="form-label">Série</label>
          <select class="form-select" id="series" v-model="game.id_serie">
            <option v-for="serie in series" :key="serie.id" :value="serie.id">
              {{ serie.name }}
            </option>
          </select>
        </div>
        <div class="mb-3 d-flex justify-content-around">
          <div class="btn-group" role="group" aria-label="Difficulty">
            <input type="radio" class="btn-check" name="difficulty" id="easy" autocomplete="off" v-model="game.level"
              value="1" />
            <label class="btn btn-outline-success btn-lg" for="easy">Facile</label>

            <input type="radio" class="btn-check" name="difficulty" id="medium" autocomplete="off" v-model="game.level"
              value="2" checked />
            <label class="btn btn-outline-secondary btn-lg" for="medium">Moyen</label>

            <input type="radio" class="btn-check" name="difficulty" id="hard" autocomplete="off" v-model="game.level"
              value="3" />
            <label class="btn btn-outline-danger btn-lg" for="hard">Difficile</label>
          </div>
        </div>
        <div class="form-check form-switch d-flex align-items-center justify-content-center mt-3">
          <input class="form-check-input" type="checkbox" role="switch" id="private" v-model="game.isPublic" />
          <label class="form-check-label ms-2" for="private">Partie publique</label>
        </div>
        <button v-if="sending" class="btn btn-primary" type="button" disabled>
          <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
          <span class="ms-1" role="status">Création...</span>
        </button>
        <button v-else class="btn btn-primary" @click="createGame">Créer</button>

        <div v-if="sending_error" class="alert alert-danger mt-2" role="alert">
          {{ sending_error }}
        </div>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped>
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

#private {
  width: 3em;
  height: 1.5em;
}
</style>
