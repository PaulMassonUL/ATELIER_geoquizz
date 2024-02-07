<script>
import axios from 'axios'

export default {
  data() {
    return {
      email: '',
      password: '',
      passwordConfirmation: '',
      username: '',
      error: '',
      loading: false
    }
  },
  methods: {
    signup() {
      if (this.loading) return

      this.email = this.email.trim()
      this.password = this.password.trim()
      this.passwordConfirmation = this.passwordConfirmation.trim()
      this.username = this.username.trim()
      if (!this.email || !this.password || !this.passwordConfirmation || !this.username) {
        this.error = 'Veuillez remplir tous les champs.'
        return
      }

      if (this.password !== this.passwordConfirmation) {
        this.error = 'Les mots de passe ne correspondent pas.'
        return
      }

      this.error = ''
      this.loading = true

      axios
        .post('http://localhost:2780/api/users/signup', {
          email: this.email,
          password: this.password,
          username: this.username
        })
        .then(() => {
          this.$router.push('/signin')
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
          this.passwordConfirmation = ''
          this.loading = false
        })
    }
  }
}
</script>

<template>
  <div id="form-container" class="sm-12 md-6 lg-4 p-2">
    <h1>Créer un compte</h1>
    <form>
      <div class="mb-3">
        <label for="emailInput" class="form-label">Adresse email</label>
        <input type="email" class="form-control" id="emailInput" v-model="email" required />
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
      <div class="mb-3">
        <label for="passwordConfirmationInput" class="form-label">Confirmer le mot de passe</label>
        <input
          type="password"
          class="form-control"
          id="passwordConfirmationInput"
          v-model="passwordConfirmation"
          required
        />
      </div>
      <div class="mb-3">
        <label for="usernameInput" class="form-label">Nom d'utilisateur</label>
        <input type="text" class="form-control" id="usernameInput" v-model="username" required />
      </div>
      <button
        type="submit"
        class="btn btn-primary"
        @click.prevent="signup"
        v-bind:disabled="loading"
      >
        Se connecter
      </button>
    </form>

    <div v-if="error" class="alert alert-danger" role="alert">
      {{ error }}
    </div>

    <p>
      Vous avez déjà un compte ?
      <router-link to="/signin">Connexion</router-link>
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
