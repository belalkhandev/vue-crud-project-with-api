<template>
    <div class="login-wrapper">
        <div class="login-box">
            <div class="login-box-header">
                <img src="@/assets/images/logo-english.png" alt="">
            </div>
            <div class="login-box-body">
                <span class="text-danger" v-if="error">{{ error }}</span>
                <form @submit.prevent="loginSubmit">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" placeholder="Email/Username" name="email" v-model="form.email">
                        </div>
                        <span class="text-danger" v-if="errors">{{ errors.email ? errors.email[0] : '' }}</span>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-key"></i>
                                </span>
                            </div>
                            <input type="password" class="form-control" placeholder="Passwrod" name="password" v-model="form.password">
                        </div>
                        <span class="text-danger" v-if="errors">{{ errors.password ? errors.password[0] : '' }}</span>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary submit-button">Login</button>
                    </div>
                </form>
            </div>
            <div class="login-box-footer">
                <p>Don't have an account? <router-link :to="{name:'Register'}">Create an Account</router-link></p>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    name: 'Login',
    components: {
        
    },

    data () {
        return {
            form: {
                email: '',
                password: ''
            },
            errors: null,
            error: null,
        }
    },

    computed: {
        ...mapGetters({
            validation_errors: 'validation_errors',
            error_message: 'error_message',
            user: 'auth/user'
        })
    },

    methods: {
        ...mapActions({
            signIn: 'auth/signIn'
        }),

        loginSubmit() {
            this.is_loading = true
            this.signIn(this.form).then(() => {
                this.is_loading = false
                if (!this.validation_errors && !this.error_message) {
                    this.errors = this.error = null;
                    if (this.user) {
                        this.$router.replace({
                            name: 'Home'
                        })
                    }
                }else {
                    this.errors = this.validation_errors, 
                    this.error = this.error_message
                }
            }).catch(() => {
                console.log('failed to login');
            })
        }
    }
}
</script>

<style scoped>

</style>