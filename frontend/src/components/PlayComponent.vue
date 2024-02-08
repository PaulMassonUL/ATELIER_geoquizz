<script>
import axios from 'axios'
import PlayInfoComponent from './PlayInfoComponent.vue'
import PlayMapComponent from './PlayMapComponent.vue'
import PlaySummaryComponent from './PlaySummaryComponent.vue'

export default {
  name: 'PlayComponent',
  components: {
    PlayInfoComponent,
    PlayMapComponent,
    PlaySummaryComponent
  },
  data() {
    return {
      loading: true,
      game: null,
      scores: [],
      current_image: 0,
      location: null,
      score: 0,
      timer: null,
      time: 0,
      ended: false
    }
  },
  methods: {
    fetchGame() {
      this.loading = true

      axios
        .get('http://localhost:2080/games/' + this.$route.params.id)
        .then((response) => {
          this.game = response.data
          this.game.sequence.forEach((image) => {
            image.location.coordinates = image.location.coordinates.reverse()
          })
        })
        .catch(() => {
          this.message = 'Impossible de charger la partie. Essayez de rafraichir la page.'
        })
        .finally(() => {
          this.loading = false
        })
    },
    handleLocationSelected(location) {
      this.location = location
    },
    handleImageLoaded() {
      clearInterval(this.timer)
      this.time = 0
      this.timer = setInterval(() => {
        this.time = this.time + 1
      }, 1000)
    },
    validate() {
      this.calculateScore()
      if (this.current_image === this.game.sequence.length - 1) {
        this.endGame()
      } else {

        this.location = null
        this.$refs.mapComponent.removeMarker()
        this.current_image = this.current_image + 1
      }
    },
    calculateScore() {
      //  Règles de calcul des points :
      // Placement des réponses :
      // • pour 1 réponse placée à une distance < D : 5pts
      // • pour 1 réponse placée à une distance < 2D : 3pts
      // • pour 1 réponse placée à une distance < 3D : 1pts
      // ( la distance D est une valeur à choisir, qui peut être un paramètre de l'application ou du
      // niveau de jeu)
      // Prise en compte de la rapidité :
      // • les pts sont multipliés par 4 pour une réponse en moins de 5s
      // • les points sont multipliés par 2 pour 1 réponse en moins de 10s
      // • les points ne sont pas acquis pour 1 réponse en plus de 20s

      const D = (() => {
        switch (this.game.level) {
          case 1:
            return 2000;
          case 2:
            return 1000;
          case 3:
            return 500;
          default:
            return 0;
        }
      })();

      const distance = this.location.distanceTo(this.game.sequence[this.current_image].location.coordinates)
      let score = 0
      if (distance < D) {
        score = 5
      } else if (distance < 2 * D) {
        score = 3
      } else if (distance < 3 * D) {
        score = 1
      }
      if (this.time < 5) {
        score = score * 4
      } else if (this.time < 10) {
        score = score * 2
      } else if (this.time > 20) {
        score = 0
      }

      this.score += score

      this.scores.push({
        image: 'http://docketu.iutnc.univ-lorraine.fr:11055/assets/' + this.game.sequence[this.current_image].url,
        score: score,
        time: this.time,
        distance: distance
      });
    },
    endGame() {
      clearInterval(this.timer)
      this.ended = true
    }
  },
  created() {
    this.fetchGame()
  }
}
</script>

<template>
  <div v-if="!ended" class="game-page">
    <div v-if="loading" class="d-flex justify-content-center mt-5">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Chargement...</span>
      </div>
    </div>
    <div v-else>
      <PlayInfoComponent :game="game" :score="score" :time="time" />
      <div class="game-content">
        <div class="image-component">
          <img :src="'http://docketu.iutnc.univ-lorraine.fr:11055/assets/' + game.sequence[current_image].url
            " alt="Si cette image ne s'affiche pas, rafraichissez la page." @load="handleImageLoaded" />
        </div>
        <div class="map-component">
          <PlayMapComponent ref="mapComponent" @location-selected="handleLocationSelected"
            :default_center="game.sequence[current_image].location.coordinates" />
        </div>
      </div>
      <button class="btn btn-primary btn-lg validate-button" v-if="location" @click="validate">
        Valider
      </button>
    </div>
  </div>
  <div v-else class="summary-page">
    <PlaySummaryComponent :scores="scores" />
  </div>
</template>

<style lang="scss" scoped>
.game-page {
  position: relative;
  height: 100%;

  & > div {
    height: 100%;
  }

  .game-content {
    display: flex;
    flex-direction: row;
    /* Par défaut, les éléments sont disposés côte à côte */
    flex-wrap: wrap;
    justify-content: center;
    width: 100%;
    height: 100%;

    >* {
      width: 50%;
      /* Par défaut, la largeur est de 50% */
    }

    @media (max-width: 1100px) {
      flex-direction: column;
      /* Change la direction lorsque la largeur de la fenêtre est inférieure à 1100px */

      >* {
        width: 100%;
        /* En colonne, la largeur est de 100% */
        height: 50%;
        /* En colonne, la hauteur est de 50% */
      }
    }

    .image-component {
      img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        /* Ajouté pour faire en sorte que l'image s'adapte à la taille de la div */
      }
    }

    .map-component {
      z-index: 1;
    }
  }

  .validate-button {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    border-radius: 0;
    z-index: 200;

    animation: slide-up 0.2s ease-out forwards;
  }

  @keyframes slide-up {
    0% {
      transform: translateY(100%);
    }

    100% {
      transform: translateY(0);
    }
  }
}
</style>
