<template>
    <div>
        <Header></Header>
        <main class="main-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <router-view></router-view>
                    </div>
                </div>
            </div>
        </main>
        <Footer></Footer>
    </div>
</template>

<script>
import Header from '@/components/partials/Header.vue';
import Footer from '@/components/partials/Footer.vue';

export default{
    components: {
        Header, Footer
    },
}
</script>

<style>
</style>