<script>
import { RouterLink, RouterView } from 'vue-router'

export default {
  name: 'App',
  components: {
    RouterLink,
    RouterView
  }
}
</script>

<template>
  <header>
    <div class="menu">
      <img alt="Vue logo" class="logo" src="@/assets/landscape.jpg" />
      <div class="wrapper">
        <nav>
          <RouterLink class="nav-accueil" to="/">GeoQuizz</RouterLink>
          <RouterLink to="/signin">Connexion</RouterLink>
        </nav>
      </div>
    </div>
    <div class="header-desc">
      <h1>Explorez le monde</h1>
      <p>
        On vous dépose partout : des rues tumultueuses de New York aux magnifiques plages de Bali.
      </p>
      <button class="bouton">JOUEZ MAINTENANT</button>
    </div>
  </header>

  <main>
    <RouterView />
  </main>
</template>

<style lang="scss" scoped>
@import url('https://fonts.googleapis.com/css2?family=Knewave&display=swap');
header {
  height: 700px;
  .menu {
    margin-bottom: 200px;

    .logo {
      width: 100%;
      position: absolute;
      z-index: -1;
      height: 700px;
    }

    .wrapper {
      width: 80%;
      margin: auto;

      nav {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;

        a {
          text-decoration: none;
          color: white;
        }

        a:not(a:first-child) {
          font-size: 1.2rem;
          border: 3px solid white;
          border-radius: 30px;
          background-color: transparent;
          padding: 7px 12px;
        }

        .nav-accueil {
          font-size: 60px;
        }
      }
    }
  }

  .header-desc {
    font-family: 'Knewave', sans-serif;
    letter-spacing: 1px;
    font-weight: 200;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 1OOpx;
    position: relative;

    h1 {
      color: white;
      margin-bottom: 30px;
    }

    p {
      color: white;
      margin-bottom: 50px;
    }
  }
}
.bouton {
  border: 2px solid white;
  border-radius: 20px;
  color: white;
  background-color: #327cb1;
  text-transform: uppercase;
  padding: 10px 20px;
  font-weight: bold;
  width: 20%;
}
</style>
