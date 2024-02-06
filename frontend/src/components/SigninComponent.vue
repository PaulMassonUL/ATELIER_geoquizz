<script>
import axios from 'axios'

export default {
  components: {
    axios
  },
  data() {
    return {
      email: '',
      password: ''
    }
  },
  methods: {
    signin() {
      axios
        .post('http://localhost:3000/api/auth/signin', {
          email: this.email,
          password: this.password
        })
        .then((response) => {
          console.log(response)
        })
        .catch((error) => {
          console.log(error)
        })
    }
  }
}
</script>

<template>
  <div>
    <h1>Hello</h1>
    <p>Connectez-vous à votre compte</p>
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
      <button type="submit" class="btn btn-primary" @click.prevent="signin">Se connecter</button>
    </form>
  </div>
</template>
