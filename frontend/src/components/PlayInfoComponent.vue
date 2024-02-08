<script>
export default {
  name: 'PlayInfoComponent',
  props: {
    game: {
      type: Object,
      required: true
    },
    score: {
      type: Number,
      required: true
    },
    time: {
      type: Number,
      required: true
    }
  },
  computed: {
    getFormattedTime() {
      const hours = Math.floor(this.time / 3600)
      const minutes = Math.floor(this.time / 60)
      const seconds = this.time % 60
      if (hours === 0) {
        return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`
      }
      return `${hours}:${minutes < 10 ? '0' : ''}${minutes}:${seconds < 10 ? '0' : ''}${seconds}`
    }
  }
}
</script>

<template>
  <div id="play-info-container">
    <div class="wrapper">
      <div class="info">
        <h4>{{ game.id_serie }}</h4>
      </div>
      <div class="info">
        <span>Créé par :</span>
        <span>{{ game.username }}</span>
      </div>
      <div class="info">
        <span>Score :</span>
        <span>{{ score }}</span>
      </div>
      <div class="info">
        <span>Temps écoulé :</span>
        <span>{{ getFormattedTime }}</span>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
#play-info-container {
  position: absolute;
  top: 0;
  right: 0;
  z-index: 10;
  background: rgba(255, 255, 255, 0.75);
  border-bottom: 1px solid black;
  border-left: 1px solid black;
  border-bottom-left-radius: 10px;
  cursor: default;
  padding: 5px;

  .wrapper {
    display: flex;
    justify-content: center;
    align-items: center;

    .info {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin: 0 5px;
    }
  }
}
</style>
