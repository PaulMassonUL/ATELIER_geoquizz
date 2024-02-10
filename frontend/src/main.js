import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap'
import axios from 'axios'

const app = createApp(App)

app.use(router)

app.mixin({
  data() {
    return {
      signedIn: localStorage.getItem('signedIn') === 'true' ? true : false
    }
  },
  methods: {
    async isUserSignedIn() {
      if (!localStorage.getItem('access_token')) {
        this.signedIn = false
        localStorage.setItem('signedIn', this.signedIn)
        return false
      }

      try {
        await axios.get('http://docketu.iutnc.univ-lorraine.fr:11114/api/users/validate', {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('access_token')}`
          }
        })
        this.signedIn = true
        localStorage.setItem('signedIn', this.signedIn)
      } catch (error) {
        this.signedIn = false
        localStorage.setItem('signedIn', this.signedIn)
      }

      return this.signedIn
    }
  }
})

app.mount('#app')
