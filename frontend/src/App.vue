<script setup>
import { RouterLink, RouterView } from 'vue-router'

import('@/scss/bouton.css')
import('./scss/header.css')

</script>

<template>
  <header>
    <div class="menu">
      <img alt="Vue logo" class="logo" src="@/assets/landscape.jpg" />
      <div class="wrapper">
        <nav>
          <RouterLink class="nav-accueil" to="/">GeoQuizz</RouterLink>
          <RouterLink to="/connexion">Connexion</RouterLink>
        </nav>
      </div>
    </div>
    <div class="header-desc">
      <h1>Explorez le monde</h1>
      <p>On vous dépose partout : des rues tumultueuses de New York aux magnifiques plages de Bali.</p>
      <button class="bouton">JOUEZ MAINTENANT</button>
    </div>
  </header>

  <RouterView />
</template>