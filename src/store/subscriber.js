import store from '@/store'
import axios from 'axios';

store.subscribe((mutation) => {
    switch (mutation.type) {
        case 'auth/SET_TOKEN':
            if (mutation.payload) {
                axios.defaults.params['token'] = mutation.payload
                localStorage.setItem('token', mutation.payload)
            }else {
                axios.defaults.params['token'] = null
                localStorage.removeItem('token')
            }

            break;
    }
})