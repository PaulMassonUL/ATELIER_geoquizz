<script>
import axios from 'axios'

export default {
  data() {
    return {
      email: '',
      password: '',
      error: '',
      loading: false
    }
  },
  methods: {
    signin() {
      if (this.loading) return

      this.email = this.email.trim()
      this.password = this.password.trim()
      if (!this.email || !this.password) {
        this.error = 'Veuillez remplir tous les champs.'
        return
      }

      this.error = ''
      this.loading = true

      axios
        .post(
          'http://localhost:2780/api/users/signin',
          {},
          {
            headers: {
              Authorization: 'Basic ' + btoa(this.email + ':' + this.password)
            }
          }
        )
        .then((response) => {
          localStorage.setItem('token', response.data.access_token)
          this.$router.push('/')
        })
        .catch((error) => {
          if (error.response && error.response.data && error.response.data.error) {
            this.error = error.response.data.error
            return
          }
          this.error = "Une erreur s'est produite. Veuillez réessayer."
        })
        .finally(() => {
          this.password = ''
          this.loading = false
        })
    }
  }
}
</script>

<template>
    <div id="form-container" class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card text-center col-12 col-md-10 col-lg-8 col-xl-6">
            <div class="card-body">
                <h1 class="card-title">Hello</h1>
                <h4 class="card-subtitle mb-5 text-muted">Connectez-vous à votre compte</h4>
                <form>
                    <div class="mb-3">
                        <label for="emailInput" class="form-label">Adresse email</label>
                        <input
                                type="email"
                                class="form-control"
                                id="emailInput"
                                aria-describedby="emailHelp"
                                v-model="email"
                                required
                        />
                    </div>
                    <div class="mb-3">
                        <label for="passwordInput" class="form-label">Mot de passe</label>
                        <input
                                type="password"
                                class="form-control"
                                id="passwordInput"
                                v-model="password"
                                required
                        />
                    </div>
                    <button
                            type="submit"
                            class="btn btn-primary mb-3"
                            @click.prevent="signin"
                            v-bind:disabled="loading"
                    >
                        Se connecter
                    </button>
                </form>

                <div v-if="error" class="alert alert-danger" role="alert">
                    {{ error }}
                </div>

                <p>
                    Vous n'avez pas de compte ?
                    <router-link to="/signup">Inscrivez-vous</router-link>
                </p>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
#form-container {
  .card {
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 14px 80px rgba(34, 35, 58, 0.2);
    transition: all .3s ease-in-out;
    overflow: hidden;

    .card-title {
      font-size: 2.5rem;
      color: #327cb1;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 10px;
    }

    .card-subtitle {
      font-size: 1.2rem;
      color: #6c757d;
      margin-bottom: 20px;
    }

    @media (min-width: 768px) { // Medium devices (tablets, 768px and up)
      padding: 40px;
    }

    @media (min-width: 992px) { // Large devices (desktops, 992px and up)
      padding: 60px;
    }

    @media (min-width: 1200px) { // Extra large devices (large desktops, 1200px and up)
      padding: 80px;
    }
  }

  .card-body {
    color: #333;
  }

  .btn-primary {
    background-color: #327cb1;
    border-color: #327cb1;
    color: #fff;
    transition: all .3s ease-in-out;

    &:hover {
      background-color: #84fab0;
      border-color: #84fab0;
    }
  }

  .alert-danger {
    background-color: #ffadad;
    color: #fff;
  }
}
</style>
