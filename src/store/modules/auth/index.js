import axios from "axios"

export default {
    namespaced: true,

    state: {
        token: null,
        user:null,
        business_types: null
    },

    getters: {
        authenticated (state) {
            return state.token && state.user
        },

        user (state) {
            return state.user
        },

        business_types(state) {
            return state.business_types
        }
    },

    mutations: {
        SET_TOKEN (state, token) {
            state.token = token
        },
        
        SET_USER (state, user) {
            state.user = user
        },
        
        SET_BUSINESS_TYPES (state, business_types) {
            state.business_types = business_types
        },

        SET_USER_OTP (state, value) {
            state.user.is_verified = value
        }

    },

    actions: {
        async signIn({ dispatch, commit }, credentials) {
            const res = await axios.post('user/login', credentials)
            
            if (res.data.status) {
                commit('SET_VALIDATION_ERRORS', null,  { root:true })
                commit('SET_ERROR_MESSAGE', null,  { root:true })
                return dispatch('attempt', res.data.token.access_token)
            }else {
                commit('SET_TOKEN', null)
                commit('SET_USER', null)
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

        async attempt({ commit, state}, token) {
            if (token) {
                commit('SET_TOKEN', token)
            }

            if (!state.token) {
                return
            }

            try{
                const resuser = await axios.post('user/me')
                commit('SET_USER', resuser.data.user)
                
            }catch (e) {
                commit('SET_TOKEN', null)
                commit('SET_USER', null)
            }
        },

        // register/signup user
        async signUp({dispatch, commit }, formData) {
            const res = await axios.post('user/signup', formData)
            if (res.data.status) {
                commit('SET_VALIDATION_ERRORS', null,  { root:true })
                commit('SET_ERROR_MESSAGE', null,  { root:true })
                return dispatch('attempt', res.data.token.access_token)
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

        async otpVerify({ commit }, formData) {
            const res = await axios.post('user/otp/verify', formData)
            if (res.data.status) {
                commit('SET_VALIDATION_ERRORS', null,  { root:true })
                commit('SET_ERROR_MESSAGE', null,  { root:true })
                commit('SET_USER_OTP', 1)
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

        async otpResend({ commit }, formData) {
            const res = await axios.post('user/otp/resend', formData)
            if (res.data.status) {
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

        async subscriptionCreate({ commit }, formData) {
            const res = await axios.post('user/subscription/create', formData)
            if (res.data.status) {
                commit('SET_VALIDATION_ERRORS', null,  { root:true })
                commit('SET_ERROR_MESSAGE', null,  { root:true })
                commit('SET_USER', res.data.user)
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

        async signOut({ commit }) {
            return axios.post('user/logout').then(() => {
                commit('SET_TOKEN', null)
                commit('SET_USER', null)
            })
        },

        async getBusinessTypes({ commit }) {
            const res = await axios.get('business/type/list')            
            commit('SET_BUSINESS_TYPES', res.data.business_types)
        },
    }

}