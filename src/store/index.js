import { createStore } from 'vuex'

import auth from '@/store/modules/auth/'
import category from '@/store/modules/category.js'
import subCategory from '@/store/modules/sub-category.js'
import product from '@/store/modules/product.js'

export default createStore({
  state: {
  },
  getters: {
    validation_errors (state) {
        return state.validation_errors
    },

    error_message (state) {
        return state.error_message
    }
  },
  mutations: {
    SET_VALIDATION_ERRORS (state, errors) {
      state.validation_errors = errors
    },
    
    SET_ERROR_MESSAGE (state, error) {
      state.error_message = error
    }
  },
  actions: {
  },
  modules: {
    auth,
    category, subCategory, 
    product
  }
})
