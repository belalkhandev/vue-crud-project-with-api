import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'
import vueSweetAlert from 'vue-sweetalert2'

// vendors
import 'bootstrap'
import '@fortawesome/fontawesome-free'
import 'bootstrap/dist/css/bootstrap.min.css'
import '@fortawesome/fontawesome-free/css/all.min.css'
import 'sweetalert2/dist/sweetalert2.min.css'

//styles
require('@/assets/css/style.css')

axios.defaults.baseURL = 'http://127.0.0.1:8000/api/'
axios.defaults.headers.common = {
    'Authorization': 'Bearer '+ 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC91c2VyXC9sb2dpbiIsImlhdCI6MTYyMzQxNzgwOSwiZXhwIjoxNjIzNjMzODA5LCJuYmYiOjE2MjM0MTc4MDksImp0aSI6IjJ1cW5RZThWY0IwWXdUTXoiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.vF1PdbRB54xdPac_ouB0vxpmIKFY49ztHZRpsvMOQcM'
}

const app = createApp(App)

app.use(router);
app.use(store);
app.use(vueSweetAlert)
app.mount('#app');
