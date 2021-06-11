<template>
    <!-- Navigation -->
    <nav class="navigation">
        <ul>
            <li>
                <router-link :to="{name: 'Category'}">Category</router-link>
            </li>
            <li>
                <router-link :to="{name: 'SubCategory'}">Sub Category</router-link>
            </li>
            <li>
                <router-link :to="{name: 'Product'}">Product</router-link>
            </li>
        </ul>
    </nav>
</template>

<script>
export default ({
    name: 'Navigation'
})
</script>

<style scoped>

</style>
