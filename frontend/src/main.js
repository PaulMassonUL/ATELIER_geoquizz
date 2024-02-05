import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import('@/scss/main.css')
import('bootstrap/dist/css/bootstrap-grid.min.css')


const app = createApp(App)

app.use(router)

app.mount('#app')
