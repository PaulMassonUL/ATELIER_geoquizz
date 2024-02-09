<template>
  <div class="banner-container">
    <img alt="Vue logo" class="background" src="@/assets/landscape.jpg" />
    <div class="header-desc-container">
      <div class="header-desc">
        <h1>Explorez le monde</h1>
        <p id="title-descr">
          On vous dépose partout : des rues tumultueuses de New York aux magnifiques plages de Bali.
        </p>
        <RouterLink id="bouton" to="/games">JOUEZ MAINTENANT</RouterLink>
      </div>
    </div>
    <div class="content">
      <section class="container p-5">
        <article class="row align-items-center p-5">
          <div class="col-6">
            <h2>Découvrez le monde</h2>
            <p>
              Découvrez le monde à votre rythme et gagnez des médailles à travers les différents
              niveaux.
            </p>
          </div>
          <div class="col-3 offset-2">
            <img src="@/assets/planete.png" alt="planete" width="200" height="200" />
          </div>
        </article>

        <article class="row align-items-center p-5">
          <div class="col-3 offset-1">
            <img src="@/assets/cercleAmis.png" alt="cercle d'amis" width="200" height="200" />
          </div>
          <div class="col-6 offset-2">
            <h2>Jouez avec vos amis</h2>
            <p>
              Mettez vos compétences à l'épreuve contre vos amis et votre famille. Créez votre
              propre fête privée et jouez ensemble.
            </p>
          </div>
        </article>

        <article class="row align-items-center p-5">
          <div class="col-6">
            <h2>Mesurez-vous aux autres</h2>
            <p>
              Testez vos capacités contre des joueurs du monde entier et essayer de devenir le
              leader !
            </p>
          </div>
          <div class="col-3 offset-2">
            <img src="@/assets/medaille.png" alt="medaille" width="200" height="200" />
          </div>
        </article>
      </section>
    </div>
  </div>
</template>

<style lang="scss" scoped>
#title-descr {
  font-family: 'Comfortaa', cursive;

}
.banner-container {
  .background {
    width: 100%;
    position: absolute;
    z-index: -1;
    height: 700px;
  }

  .content {
    background-color: #393939;

    article {
      border-bottom: 1px solid #fff;

      div {
        h2 {
          margin-bottom: 30px;
        }
      }
    }
  }

  * {
    color: white;
  }

  .header-desc-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 700px;

    .header-desc {
      font-family: 'Knewave', sans-serif;
      letter-spacing: 1px;
      font-weight: 200;
      display: flex;
      flex-direction: column;
      align-items: center;
      position: relative;

      h1 {
        color: white;
        margin-bottom: 30px;
      }

      p {
        color: white;
        margin-bottom: 50px;
      }

      #bouton {
        text-align: center;
        text-decoration: none;
        border: 2px solid white;
        border-radius: 20px;
        color: white;
        background-color: #327cb1;
        text-transform: uppercase;
        padding: 10px 20px;
        font-size: 24px;
        width: 40%;
        min-width: fit-content;
      }
    }
  }
}
</style>
