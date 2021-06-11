import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'
import vueSweetAlert from 'vue-sweetalert2'

require('./store/subscriber')

// vendors
import 'bootstrap'
import '@fortawesome/fontawesome-free'
import 'bootstrap/dist/css/bootstrap.min.css'
import '@fortawesome/fontawesome-free/css/all.min.css'
import 'sweetalert2/dist/sweetalert2.min.css'

//styles
require('@/assets/css/style.css')

axios.defaults.baseURL = 'http://127.0.0.1:8000/api/'
axios.defaults.headers.common['Authorization'] = "Bearer "+ localStorage.getItem('token')

const app = createApp(App)
store.dispatch('auth/attempt', localStorage.getItem('token')).then(() => {
    app.use(router);
    app.use(store);
    app.use(vueSweetAlert)
    app.mount('#app');
})

