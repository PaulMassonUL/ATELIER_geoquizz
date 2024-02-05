<script setup>
import { RouterLink, RouterView } from 'vue-router'
import('./scss/header.css')
</script>

<template>
  <header>
    <img alt="Vue logo" class="logo" src="@/assets/landscape.jpg" />
    <div class="wrapper">
      <nav>
        <RouterLink class="nav-accueil" to="/">GeoQuizz</RouterLink>
        <RouterLink to="/connection">Connexion</RouterLink>
      </nav>
    </div>

    <h1>Explorez le monde</h1>
    <p>On vous dépose partout : des rues tumultueuses de New York aux magnifiques plages de Bali.</p>
    <button>JOUEZ MAINTENANT</button>
  </header>

  <RouterView />
</template>