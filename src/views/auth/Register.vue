<template>
    <div class="login-wrapper">
        <div class="login-box">
            <div class="login-box-header">
                <img src="@/assets/images/logo-english.png" alt="">
            </div>
            <div class="login-box-body">
                <span class="text-danger" v-if="error">{{ error }}</span>
                <form @submit.prevent="registerSubmit">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" placeholder="Name" v-model="form.name">
                        </div>
                        <span class="text-danger" v-if="errors">{{ errors.name ? errors.name[0] : '' }}</span>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                            </div>
                            <input type="email" class="form-control" placeholder="Email" v-model="form.email">
                        </div>
                        <span class="text-danger" v-if="errors">{{ errors.email ? errors.email[0] : '' }}</span>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" placeholder="Username (optional)" v-model="form.username">
                        </div>
                        <span class="text-danger" v-if="errors">{{ errors.username ? errors.username[0] : '' }}</span>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-key"></i>
                                </span>
                            </div>
                            <input type="password" class="form-control" placeholder="Password" v-model="form.password">
                        </div>
                        <span class="text-danger" v-if="errors">{{ errors.password ? errors.password[0] : '' }}</span>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-key"></i>
                                </span>
                            </div>
                            <input type="password" class="form-control" placeholder="Confirm password" v-model="form.password_confirmation">
                        </div>
                        <span class="text-danger" v-if="errors">{{ errors.password_confirmation ? errors.password_confirmation[0] : '' }}</span>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary submit-button">Register</button>
                    </div>
                </form>
            </div>
            <div class="login-box-footer">
                <p>Already have an account? <router-link :to="{name:'Login'}">Login</router-link></p>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    name: 'Register',
    
    components: {
        
    },
    
    data(){
        return {
            form:{
                name: '',
                email: '',
                username: '',
                password: '',
                password_confirmation: '',
            },
            errors: null,
            error: null,
        }
    },
    computed: {
        ...mapGetters({
            validation_errors: 'validation_errors',
            error_message: 'error_message'
        })
    },

    methods: {
        ...mapActions({
            signUp: 'auth/signUp'
        }),

        registerSubmit() {
            this.signUp(this.form).then(() => {
                if (!this.validation_errors && !this.error_message) {
                    this.errors = this.error = null;
                    this.$router.replace({
                        name: 'Home'
                    })
                }else {
                    this.errors = this.validation_errors, 
                    this.error = this.error_message
                }
            }).catch(() => {
                console.log('failed to register');
            })
        },

        phoneValidation(phone) {
            var phoneno = /^\d{11}$/;
            if (phone.match(phoneno)) {
                return true
            }

            return false
        },

        signUpFormValidation(form) {
            let errors = [];
            let flag = 0;
            if (form.name == '') {
                errors['name'] = ['Name field can not be empty'];
                flag = 1;
            }

            if (form.email == '') {
                errors['email'] = ['Email field can not be empty'];
                flag = 1;
            }

            if (form.password == '') {
                errors['password'] = ['Password field can not be empty'];
                flag = 1;
            }

            if (form.password_confirmation == '') {
                errors['password_confirmation'] = ['Password Confirmation field can not be empty'];
                flag = 1;
            }

            if (!flag) {
                errors = null
            }

            return errors;
        }
    },    
    
    created() {
        
    }
}
</script>

<style scoped>
    
</style>