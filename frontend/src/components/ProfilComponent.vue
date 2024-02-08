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
    }
  }
}
</script>

<template>
  <div class="mt-5">
    <h1 id="title" class="text-center">Profil</h1>
    <div id="profil" class="d-flex justify-content-center align-items-center vh-50 mt-3">
      <div
        class="card profil-card mb-5 d-flex justify-content-center align-items-center col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4"
      >
        <div class="card-body">
          <h2 class="card-title">{{ profil.username }}</h2>
        </div>
      </div>
    </div>

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
  box-shadow:
    0 4px 8px 0 rgba(0, 0, 0, 0.2),
    0 6px 20px 0 rgba(0, 0, 0, 0.19);
  transition: 0.3s;

  &:hover {
    box-shadow:
      0 8px 16px 0 rgba(0, 0, 0, 0.2),
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
  box-shadow:
    0 4px 8px 0 rgba(0, 0, 0, 0.2),
    0 6px 20px 0 rgba(0, 0, 0, 0.19);
}
</style>
