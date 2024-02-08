<script>
import axios from 'axios'

export default {
  data() {
    return {
      loading: false,
      game: {
        id: "zefzefzefzef",
        token: "zefzefzefezf",
        id_serie: "ID",
        sequence: [
          {
            url: "805c06d9-0af6-4b5c-9ef0-f9d07d52dd0c",
            location: {
              coordinates: [48.692054, 6.184417],
              type: "Point"
            }
          },
          {
            url: "805c06d9-0af6-4b5c-9ef0-f9d07d52dd0c",
            location: {
              coordinates: [48.692054, 6.184417],
              type: "Point"
            }
          },
          {
            url: "805c06d9-0af6-4b5c-9ef0-f9d07d52dd0c",
            location: {
              coordinates: [48.692054, 6.184417],
              type: "Point"
            }
          }
        ],
        isPublic: true,
        level: 2,
        state: 2,
        id_user: "ID",
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
      console.log(location)
    },
    validate() {
      location = null
      current_image = (current_image + 1) % game.sequence.length
    }
  },
  created() {
    this.fetchGame()
  }
}
</script>

<template>
  <div class="game-page">
    <GameInfoComponent :game="game" />
    <div class="game-content">
      <div class="image-component">
        <img :src="'http://docketu.iutnc.univ-lorraine.fr:11055/assets/' + game.sequence[current_image].url"
          alt="Si cette image ne s'affiche pas, rafraichissez la page." />
      </div>
      <MapComponent @location-selected="handleLocationSelected" :default-center="game.sequence[current_image].location.coordinates" />
      <button v-if="location" @click="validate">Valider</button>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.game-page {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.game-content {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  width: 100%;
}
</style>