<script>
import axios from 'axios'
import { format, parseISO } from 'date-fns'
import { fr } from 'date-fns/locale'

export default {
  data() {
    return {
      games: [],
      series: [],
      games_loading: false,
      series_loading: false,
      message: '',
      user: ''
    }
  },
  methods: {
    fetchGames() {
      this.games_loading = true
      axios
        .get('http://localhost:2080/games')
        .then((response) => {
          this.games = response.data
          if (this.games.length === 0) this.message = 'Aucune partie trouvée. Créez-en une !'
        })
        .catch((error) => {
          console.error(error)
          this.message = 'Impossible de charger les parties. Veuillez réessayer plus tard.'
        })
        .finally(() => {
          this.games_loading = false
        })
    },
    fetchSerie() {
      this.series_loading = true
      axios
        .get(`http://docketu.iutnc.univ-lorraine.fr:11055/items/Serie`)
        .then((response) => {
          this.series = response.data.data
        })
        .catch(() => {
          this.message = 'Impossible de charger la série. Veuillez réessayer plus tard.'
        })
        .finally(() => {
          this.series_loading = false
        })
    },
    getSeriesById(game) {
      const serie = this.series.find((serie) => serie.id == game.id_serie)
      if (!serie) {
        console.error(`Serie avec l'id ${game.id_serie} non trouvée`)
        return null
      }

      return serie
    },
    checkDifficulty(value) {
      if (value === 1) {
        return '<span class="text-success">Facile</span>'
      } else if (value === 2) {
        return '<span class="text-warning">Moyen</span>'
      } else {
        return '<span class="text-danger">Difficile</span>'
      }
    },
    formatDate(dateString) {
      const date = parseISO(dateString)
      return format(date, "dd/MM/yyyy 'à' HH:mm:ss", { locale: fr })
    }
  },
  created() {
    this.fetchGames()
    this.fetchSerie()
  }
}
</script>

<template>
  <div>
    <h1 id="title">Liste des parties</h1>
    <div v-if="games_loading || series_loading" class="d-flex justify-content-center mt-5">
      <div id="spinner" class="spinner-border" role="status">
        <span class="visually-hidden">Chargement...</span>
      </div>
    </div>
    <div v-else>
      <div v-if="message" id="message" class="mt-5">{{ message }}</div>
      <div id="games-list" v-else>
        <div class="row">
          <div v-for="game in games" :key="game.id" class="col-md-3 col-sm-6">
            <div class="card mb-3">
              <div class="card-body">
                <h2 class="card-title">{{ getSeriesById(game).name }}</h2>
                <p class="card-text">Niveau : <span v-html="checkDifficulty(game.level)"></span></p>
                <p class="card-text">Auteur : {{ game.username }}</p>
                <p class="card-text">Créé le {{ formatDate(game.created_at) }}</p>
                <RouterLink id="bouton" :to="'/games/' + game.id + '/play'" class="btn btn-success"
                  >Lancer la partie ►
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
