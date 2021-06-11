import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/category',
    name: 'Category',
    component: () => import('@/views/category/Category.vue')
  },
  {
    path: '/category/create',
    name: 'CreateCategory',
    component: () => import('@/views/category/CategoryCreate.vue')
  },
  {
    path: '/category/:id/edit',
    name: 'EditCategory',
    component: () => import('@/views/category/CategoryEdit.vue')
  },
  {
    path: '/sub-category',
    name: 'SubCategory',
    component: () => import('@/views/sub-category/SubCategory.vue')
  },
  {
    path: '/product',
    name: 'Product',
    component: () => import('@/views/product/Product.vue')
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
