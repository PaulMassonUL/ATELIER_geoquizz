<script>
export default {
  name: 'PlaySummaryComponent',
  props: {
    scores: {
      type: Array,
      required: true
    },
  }
}
</script>

<template>
  <section class="container mt-3">
    <div class="row">
      <div class="col-md-4" v-for="(score, index) in scores" :key="index">
        <div class="card mb-4">
          <img :src="score.image" class="card-img-top" alt="Image">
          <div class="card-body">
            <h5 class="card-title">{{ score.score }} points</h5>
            <p class="card-text">Temps : {{ score.time }} secondes</p>
            <p class="card-text">Distance: {{ score.distance.toFixed(1) }} m</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>