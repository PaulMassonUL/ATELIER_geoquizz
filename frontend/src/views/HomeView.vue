<script>
</script>

<template>
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
                    <img src="@/assets/planete.png" alt="planete" width="200" height="200"/>
                </div>
            </article>

            <article class="row align-items-center p-5">
                <div class="col-3 offset-2">
                    <img src="@/assets/cercleAmis.png" alt="cercle d'amis" width="200" height="200"/>
                </div>
                <div class="col-6 offset-1">
                    <h2>Jouez avec vos amis</h2>
                    <p>
                        Mettez vos compétences à l'épreuve contre vos amis et votre famille. Créez votre propre
                        fête privée et jouez ensemble.
                    </p>
                </div>
            </article>

            <article class="row align-items-center p-5">
                <div class="col-6">
                    <h2>Mesurez-vous aux autres</h2>
                    <p>
                        Testez vos capacités contre des joueurs du monde entier et essayer de devenir le leader !
                    </p>
                </div>
                <div class="col-3 offset-2">
                    <img src="@/assets/medaille.png" alt="medaille" width="200" height="200"/>
                </div>
            </article>
        </section>
    </div>
</template>

<style lang="scss" scoped>
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
</style>
