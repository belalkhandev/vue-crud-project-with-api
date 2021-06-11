import axios from 'axios'

export default {
    namespaced: true,
    state: {
        products: null
    },

    getters: {
        products(state) {
            return state.products
        }
    },

    mutations: {
        SET_PRODUCTS (state, products) {
            state.products = products
        },

        SET_PRODUCT (state, product) {
            if (state.products) {
                state.products.unshift(product)
            }
            else {
                state.products = [product]
            }
        },

        UPDATE_PRODUCT (state, product) {
            const item = state.products.find(item => {
                return item.id === product.id
            });
            Object.assign(item, product);
        },

        DELETE_PRODUCT (state, product_id) {
            const item = state.products.find(item => {
                return item.id === product_id
            });
            state.products.splice(item, 1)

            if (state.products.length < 1) {
                state.products = null;
            }
        }
    },

    actions: {
        async getProductList({ commit }) {
            const res = await axios.get('product/list')

            commit('SET_PRODUCTS', res.data.products)
        },

        async createProduct({ commit }, formdata) {
            const res = await axios.post('product/create', formdata)
            if (res.data.status) {
                commit('SET_PRODUCT', res.data.product)
                commit('SET_VALIDATION_ERRORS', null,  { root:true })
                commit('SET_ERROR_MESSAGE', null,  { root:true })
            }else {
                if (res.data.errors) {
                    commit('SET_VALIDATION_ERRORS', res.data.errors, { root:true })
                    commit('SET_ERROR_MESSAGE', null, { root:true })
                } else if (res.data.message) {
                    commit('SET_ERROR_MESSAGE', res.data.message, { root:true })
                    commit('SET_VALIDATION_ERRORS', null, { root:true })
                } else {
                    console.log('someting went wrong');
                }
            }
        },

        async updateProduct({ commit }, formdata) {
            const res = await axios.put('product/edit/'+formdata.product_id, formdata)
            if (res.data.status) {
                commit('UPDATE_PRODUCT', res.data.product)
                commit('SET_VALIDATION_ERRORS', null,  { root:true })
                commit('SET_ERROR_MESSAGE', null,  { root:true })
            }else {
                if (res.data.errors) {
                    commit('SET_VALIDATION_ERRORS', res.data.errors, { root:true })
                    commit('SET_ERROR_MESSAGE', null, { root:true })
                } else if (res.data.message) {
                    commit('SET_ERROR_MESSAGE', res.data.message, { root:true })
                    commit('SET_VALIDATION_ERRORS', null, { root:true })
                } else {
                    console.log('someting went wrong');
                }
            }
        },

        async deleteProduct({ commit }, product_id) {
            const res = await axios.delete('product/delete/'+product_id)
            if (res.data.status) {
                commit('DELETE_PRODUCT', product_id)
                commit('SET_VALIDATION_ERRORS', null,  { root:true })
                commit('SET_ERROR_MESSAGE', null,  { root:true })
            }else {
                if (res.data.errors) {
                    commit('SET_VALIDATION_ERRORS', res.data.errors, { root:true })
                    commit('SET_ERROR_MESSAGE', null, { root:true })
                } else if (res.data.message) {
                    commit('SET_ERROR_MESSAGE', res.data.message, { root:true })
                    commit('SET_VALIDATION_ERRORS', null, { root:true })
                } else {
                    console.log('someting went wrong');
                }
            }
        },
    },

    
}