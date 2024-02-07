<script>
import GamesList from '@/components/GamesListComponent.vue'

export default {
  components: {
    GamesList
  }
}
</script>

<template>
  <section class="container mt-3">
    <div class="text-end">
      <RouterLink to="/games/new">
        <button class="btn btn-primary btn-lg">Créer une partie</button>
      </RouterLink>
    </div>

    <GamesList/>
  </section>
</template>
