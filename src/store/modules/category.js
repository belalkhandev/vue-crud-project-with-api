import axios from 'axios'

export default {
    namespaced: true,
    state: {
        categories: null,
        category: null
    },

    getters: {
        categories(state) {
            return state.categories
        },

        category(state) {
            return state.category
        },
    },

    mutations: {
        SET_CATEGORIES (state, categories) {
            state.categories = categories
        },

        SET_CATEGORY (state, category) {
            if (state.categories) {
                state.categories.unshift(category)
            }
            else {
                state.categories = [category]
            }
        },

        UPDATE_CATEGORY (state, category) {
            const item = state.categories.find(item => {
                return item.id === category.id
            });
            Object.assign(item, category);
        },

        DELETE_CATEGORY (state, category_id) {
            const item = state.categories.find(item => {
                return item.id === category_id
            });
            state.categories.splice(item, 1)
            
        }
    },

    actions: {
        async getCategoryList({ commit }) {
            const res = await axios.get('category/list')

            commit('SET_CATEGORIES', res.data.categories)
        },

        
        async createCategory({ commit }, formdata) {
            const res = await axios.post('category/create', formdata)
            if (res.data.status) {
                commit('SET_CATEGORY', res.data.category)
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

        async updateCategory({ commit }, formdata) {
            const res = await axios.put('category/edit/'+formdata.category_id, formdata)
            if (res.data.status) {
                commit('UPDATE_CATEGORY', res.data.category)
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

        async deleteCategory({ commit }, category_id) {
            const res = await axios.delete('category/delete/'+category_id)
            if (res.data.status) {
                commit('DELETE_CATEGORY', category_id)
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
    }
}