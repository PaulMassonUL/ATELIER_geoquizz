import {createRouter, createWebHistory} from 'vue-router'

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
            // component: () => import('@/views/SignupView.vue')
        },
        {
            path: '/:pathMatch(.*)*',
            name: 'not-found',
            component: () => import('@/views/NotFoundView.vue')
        }
    ]
})

export default router
