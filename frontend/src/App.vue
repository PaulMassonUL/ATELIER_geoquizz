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
      <div class="wrapper">
        <nav>
          <RouterLink id="nav-accueil" to="/">GeoQuizz</RouterLink>
          <RouterLink id="nav-action" to="/signin">Connexion</RouterLink>
        </nav>
      </div>
    </div>
  </header>

  <main>
    <RouterView />
  </main>
</template>

<style lang="scss" scoped>
@import url('https://fonts.googleapis.com/css2?family=Knewave&display=swap');

header {
  box-shadow: 0px 0px 10px 0px black;

  .menu {
    height: fit-content;
    background: rgb(98, 98, 98);
    background: radial-gradient(
      circle,
      rgba(98, 98, 98, 1) 0%,
      rgba(80, 80, 80, 1) 50%,
      rgba(54, 54, 54, 1) 100%
    );

    .wrapper {
      width: 90%;
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

        #nav-action {
          font-size: 1.2rem;
          border: 3px solid white;
          border-radius: 30px;
          background-color: transparent;
          padding: 7px 12px;

          &:hover {
            background-color: white;
            color: black;
          }
        }

        #nav-accueil {
          font-size: 60px;
        }
      }
    }
  }
}
</style>
