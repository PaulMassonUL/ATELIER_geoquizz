import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import('bootstrap/dist/css/bootstrap-grid.min.css')
import ('./css/main.css')

const app = createApp(App)

app.use(router)

app.mount('#app')
