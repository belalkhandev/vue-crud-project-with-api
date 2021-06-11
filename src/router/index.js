import { createRouter, createWebHistory } from 'vue-router'
import store from '@/store'

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/views/auth/Login.vue')
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('@/views/auth/Register.vue')
  },
  {
    path: '/',
    name: 'Home',
    component: () => import('@/views/Home.vue'),
    beforeEnter: (to, from, next) => {
      if (!store.getters['auth/authenticated']) {
        return next({
          name: 'Login'
        })
      }

      next();
    }
  },
  {
    path: '/category',
    name: 'Category',
    component: () => import('@/views/category/Category.vue'),
    beforeEnter: (to, from, next) => {
      if (!store.getters['auth/authenticated']) {
        return next({
          name: 'Login'
        })
      }

      next();
    }
  },
  {
    path: '/sub-category',
    name: 'SubCategory',
    component: () => import('@/views/sub-category/SubCategory.vue'),
    beforeEnter: (to, from, next) => {
      if (!store.getters['auth/authenticated']) {
        return next({
          name: 'Login'
        })
      }

      next();
    }
  },
  {
    path: '/product',
    name: 'Product',
    component: () => import('@/views/product/Product.vue'),
    beforeEnter: (to, from, next) => {
      if (!store.getters['auth/authenticated']) {
        return next({
          name: 'Login'
        })
      }

      next();
    }
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
