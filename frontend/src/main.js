import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap'

const app = createApp(App)

app.use(router)

app.mixin({
  methods: {
    isUserLoggedIn() {
      return !!localStorage.getItem('access_token')
    }
  }
})

app.mount('#app')
