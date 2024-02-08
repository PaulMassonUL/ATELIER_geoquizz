<script>
import axios from 'axios'
import PlayInfoComponent from './PlayInfoComponent.vue'
import PlayMapComponent from './PlayMapComponent.vue'

export default {
  name: 'PlayComponent',
  components: {
    PlayInfoComponent,
    PlayMapComponent
  },
  data() {
    return {
      loading: false,
      game: {
        id: "zefzefzefzef",
        token: "zefzefzefezf",
        id_serie: "Nancy",
        sequence: [
          {
            url: "805c06d9-0af6-4b5c-9ef0-f9d07d52dd0c",
            location: {
              coordinates: [48.692054, 6.184417],
              type: "Point"
            }
          },
          {
            url: "4846760d-cc26-476e-a13f-828ec0b78f16",
            location: {
              coordinates: [48.692054, 6.184417],
              type: "Point"
            }
          },
          {
            url: "2e6542a1-201c-4721-b04c-2e789618495d",
            location: {
              coordinates: [48.692054, 6.184417],
              type: "Point"
            }
          }
        ],
        isPublic: true,
        level: 2,
        state: 2,
        id_user: "mail@example.com",
      },
      current_image: 0,
      location: null,
    };
  },
  methods: {
    fetchGame() {
      this.loading = true

      axios
          .get('http://localhost:2080/games/' + this.$route.params.id)
          .then((response) => {
            this.game = response.data
            this.game.sequence = JSON.parse(this.game.sequence)
          })
          .catch((error) => {
            console.error(error)
            this.message = 'Impossible de charger la partie. Essayez de rafraichir la page.'
          })
          .finally(() => {
            this.loading = false
          })
    },
    handleLocationSelected(location) {
      this.location = location
    },
    validate() {
      // send location to backend

      // Si c'est fini aller à la page de résumé
      if (this.current_image === this.game.sequence.length - 1) {
        this.$router.push('/games/' + this.$route.params.id + '/summary')
      } else {
        this.location = null
        this.$refs.mapComponent.removeMarker()
        this.current_image = this.current_image + 1
      }
    }
  },
  created() {
    this.fetchGame()
  }
}
</script>

<template>
  <div class="game-page">
    <PlayInfoComponent :game="game"/>
    <div class="game-content">
      <div class="image-component">
        <img :src="'http://docketu.iutnc.univ-lorraine.fr:11055/assets/' + game.sequence[current_image].url"
             alt="Si cette image ne s'affiche pas, rafraichissez la page."/>
      </div>
      <div class="map-component">
        <PlayMapComponent ref="mapComponent" @location-selected="handleLocationSelected"
                          :default_center="game.sequence[current_image].location.coordinates"/>
      </div>
    </div>
    <button class="btn btn-primary btn-lg validate-button" v-if="location" @click="validate">Valider</button>
  </div>
</template>

<style lang="scss" scoped>
.game-page {
  position: relative;
  height: 100%;

  .game-content {
    display: flex;
    flex-direction: row; /* Par défaut, les éléments sont disposés côte à côte */
    flex-wrap: wrap;
    justify-content: center;
    width: 100%;
    height: 100%;

    > * {
      width: 50%; /* Par défaut, la largeur est de 50% */
    }

    @media (max-width: 1100px) {
      flex-direction: column; /* Change la direction lorsque la largeur de la fenêtre est inférieure à 1100px */

      > * {
        width: 100%; /* En colonne, la largeur est de 100% */
        height: 50%; /* En colonne, la hauteur est de 50% */
      }
    }

    .image-component {
      img {
        width: 100%;
        height: 100%;
        object-fit: contain; /* Ajouté pour faire en sorte que l'image s'adapte à la taille de la div */
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