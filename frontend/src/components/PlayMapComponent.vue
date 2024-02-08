<script>
import "leaflet/dist/leaflet.css"
import {LMap, LTileLayer, LMarker} from "@vue-leaflet/vue-leaflet"

export default {
  name: "PlayMapComponent",
  components: {
    LMap,
    LTileLayer,
    LMarker, // Importer LMarker
  },
  props: {
    default_center: Array,
  },
  data() {
    return {
      zoom: 13,
      url: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
      marker: null,
    }
  },
  methods: {
    onMapClick(e) {
      this.marker = e.latlng;
      this.$emit('location-selected', e.latlng)
    },
    removeMarker() {
      this.marker = null;
    },
  },
}
</script>

<template>
  <div id="map">
    <l-map ref="map" v-model:zoom="zoom" :center="default_center" :useGlobalLeaflet="false" @click="onMapClick">
      <l-tile-layer url="https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png"
                    layer-type="base"></l-tile-layer>
      <l-marker v-if="marker" :lat-lng="marker"></l-marker>
    </l-map>
  </div>
</template>

<style lang="scss" scoped>
#map {
  height: 100%;
}
</style>