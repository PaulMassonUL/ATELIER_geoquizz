<script>
export default {
  name: 'PlayHintComponent',
  props: {
    image: {
      type: Object,
      required: true
    },
  },
  data() {
    return {
      showHint: false
    }
  },
}
</script>

<template>
  <div id="play-info-container">
    <div class="wrapper">
      <button @click="showHint = !showHint">
        Indice
      </button>
      <span v-if="showHint">{{ image.hint }}</span>
    </div>
  </div>
</template>

<style lang="scss" scoped>
#play-info-container {
  position: absolute;
  top: 0;
  left: 0;
  z-index: 20;
  background: rgba(255, 255, 255, 0.75);
  border-bottom: 1px solid black;
  border-right: 1px solid black;
  border-bottom-right-radius: 10px;
  padding: 5px;

  .wrapper {
    display: flex;
    justify-content: center;
    align-items: center;

    >button {
      // round button
      border: none;
      border-radius: 40%;
      height: 50px;
      cursor: pointer;

      &:hover {
        background-color: lightgrey;
      }
    }

    >span {
      margin-left: 10px;
    }
  }
}
</style>
