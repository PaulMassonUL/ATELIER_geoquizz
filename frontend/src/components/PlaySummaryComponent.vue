<script>
export default {
  name: 'PlaySummaryComponent',
  props: {
    game: {
      type: Object,
      required: true
    },
    serie: {
      type: Object,
      required: true
    },
    scores: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      totalScore: this.scores.reduce((acc, score) => acc + score.score, 0),
      totalTime: this.scores.reduce((acc, score) => acc + score.time, 0)
    }
  },
  methods: {
    getFormattedTime(time) {
      const hours = Math.floor(time / 3600)
      const minutes = Math.floor(time / 60)
      const seconds = time % 60

      if (hours > 0) {
        return `${hours} heures, ${minutes} minutes et ${seconds} secondes`
      } else if (minutes > 0) {
        return `${minutes} minutes et ${seconds} secondes`
      } else {
        return `${seconds} secondes`
      }
    }
  }
}
</script>

<template>
  <section class="container mt-3">
    <div class="game-summary mb-4 d-flex flex-column align-items-center">
      <h1>Résumé de la partie</h1>
      <p>Série : {{ serie.name }}</p>
      <p>
        Score <span class="badge bg-info">{{ totalScore }}</span>
      </p>
      <p>
        Durée de la partie <span class="badge bg-info">{{ getFormattedTime(totalTime) }}</span>
      </p>
    </div>
    <hr />
    <div v-for="(score, index) in scores" :key="index">
      <div class="score-counter">#{{ index + 1 }}</div>
      <div class="score-card mb-4 d-flex">
        <img :src="score.image" alt="Image" />
        <div class="score-card-body">
          <p class="score-card-title">{{ score.score }} points</p>
          <p class="score-card-text">Temps : {{ getFormattedTime(score.time) }}</p>
          <p class="score-card-text">Distance: {{ score.distance.toFixed(0) }} m</p>
        </div>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped>
$card-border-radius: 15px;
$card-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);

.game-summary {
  h1 {
    text-decoration: underline;
  }

  p {
    font-size: 1.4em;
    margin-bottom: 0;
  }
}

.score-counter {
  font-size: 1.4em;
}

.score-card {
  border-radius: $card-border-radius;
  box-shadow: $card-shadow;
  height: 200px;
  overflow: hidden;

  .score-card-body {
    padding: 1em;

    .score-card-title {
      font-weight: bold;
      font-size: 1.2em;
    }
  }
}
</style>
