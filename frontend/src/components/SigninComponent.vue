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
        this.error = 'Veiullez remplir tous les champs'
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
          if (error.response.data.error) {
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
  <div id="form-container" class="sm-12 md-6 lg-4 p-2">
    <h1>Hello</h1>
    <h4>Connectez-vous à votre compte</h4>
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
        class="btn btn-primary"
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
</template>

<style lang="scss" scoped>
#form-container {
  border: 1px solid #000;

  & > h1,
  & > h4 {
    text-align: center;
  }

  form {
    margin: 0.5em 0;
  }
}
</style>
