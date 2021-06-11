<template>
    <div class="box">
        <div class="box-header">
            <h5 class="box-title">Category Update</h5>
                    <router-link :to="{name: 'Category'}" class="btn btn-primary">View List</router-link>
        </div>
        <form>
            <div class="box-body">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" v-model="form.name" placeholder="Category name">
                    <span class="text-danger"></span>
                </div>
            </div>
            <div class="box-footer">
                <div class="text-right">
                    <button type="submit" class="btn btn-success">Update Category</button>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    name: 'CategoryEdit',

    data() {
        return {
            form: {
                name: '',
            },
            id: this.$route.params.id
        }
    },

    computed: {
        ...mapGetters({
            category: 'category/category',
        }),
        
    },

    methods: {
        ...mapActions({
            getSingleCategory: 'category/getSingleCategory',
        })
    },

    created() {
        this.getSingleCategory(this.id);

    }
}
</script>

<style scoped>

</style>