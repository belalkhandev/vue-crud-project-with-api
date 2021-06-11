<template>
    <!-- header -->
    <header>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header">
                        <div class="logo">
                            <router-link :to="{'name': 'Home'}">
                                <!-- <h1><span>vue</span>Crud</h1> -->
                                <img src="@/assets/images/logo-english.png" alt="" height="40">
                            </router-link>
                        </div>
                        <Navigation></Navigation>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
import Navigation from '@/components/partials/Navigation.vue'
export default ({
    name: 'Header',
    components: {
        Navigation
    }
})
</script>

<style scoped>

</style>
