import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('@/views/HomeView.vue')
    },
    {
      path: '/signin',
      name: 'signin',
      component: () => import('@/views/SigninView.vue')
    },
    {
      path: '/signup',
      name: 'signup',
      component: () => import('@/views/SignupView.vue')
    },
    {
      path: '/profil',
      name: 'profil',
      component: () => import('@/views/ProfilView.vue')
    },
    {
      path: '/games',
      name: 'games',
      component: () => import('@/views/GamesView.vue')
    },
    {
      path: '/games/new',
      name: 'new-game',
      component: () => import('@/views/NewGameView.vue')
    },
    {
      path: '/games/:id/play',
      name: 'play-game',
      component: () => import('@/views/PlayView.vue')
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      component: () => import('@/views/NotFoundView.vue')
    },
    {
      path: '/socket',
      name: 'socket',
      component: WebSocket
    }
  ]
})

export default router
