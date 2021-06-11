<template>
    <div>
        <div class="box">
            <div class="box-header">
                <h5 class="box-title">Category List</h5>
                <button class="btn btn-primary" data-toggle="modal" data-target="#categoryCreateModal">Add Category</button>
            </div>
            <div class="box-body">
                <table class="table table-bordered" v-if="categories">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(category, i) in categories" :key="category.id">
                            <td>{{ i+1 }}</td>
                            <td>{{ category.name }}</td>
                            <td>
                                <div class="action-group">
                                    <button class="btn btn-sm btn-success" @click="categoryEdit(category)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" @click="deleteCategory(category.id)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="no-data" v-else>
                    <img src="@/assets/images/sad-cloud.png" alt="">
                    <h3>Opps! No Data Found</h3>
                </div>
            </div>
        </div>
        <!-- create category -->
        <CategoryCreate></CategoryCreate>
        <!-- update category -->
        <div class="modal fade" id="categoryEditModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">Update Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="updateSubmit">
                        <div class="modal-body">
                            <span class="text-danger" v-if="error">{{ error }}</span>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="hidden" v-model="form.category_id">
                                <input type="text" name="name" class="form-control" v-model="form.name" placeholder="Category name">
                                <span class="text-danger" v-if="errors">{{ errors.name ? errors.name[0] : '' }}</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="text-right">
                                <button type="submit" class="btn btn-success">Update Category</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'
import CategoryCreate from './CategoryCreate.vue';
import $ from 'jquery'

export default {
	components: { CategoryCreate },

    name: 'Category',

    data () {
        return {
            form: {
                category_id:'',
                name: '',
            },
            errors: null,
            error: null,
        }
    },

    computed: {
        ...mapGetters({
            categories: 'category/categories',
            validation_errors: 'validation_errors',
            error_message: 'error_message'
        }),
    },

    methods: {
        ...mapActions({
            getCategoryList: 'category/getCategoryList',
            categoryUpdate: 'category/updateCategory',
            categoryDelete: 'category/deleteCategory'
        }),

        categoryEdit(category) {
            $('#categoryEditModal').modal('show');
            this.form.category_id = category.id
            this.form.name = category.name
        },

        updateSubmit() {
            this.categoryUpdate(this.form).then(() => {
                if (!this.validation_errors && !this.error_message) {
                    this.errors = null;
                    this.error = null;
                } else {
                    this.errors = this.validation_errors;
                    this.error = this.error_message;
                }
            })
        },

        deleteCategory(category) {
            this.$swal({
                title:"Want to delete?",
                text: "Are you sure? You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#45a800",
                confirmButtonText: "Yes, Delete it!",
                cancelButtonColor: '#c82333',
            }).then((result) => {
                if (result.isConfirmed) {
                   this.categoryDelete(category).then(() => {
                        if (!this.validation_errors && !this.error_message) {
                            this.errors = null;
                            this.error = null;
                        } else {
                            this.errors = this.validation_errors;
                            this.error = this.error_message;
                        }
                    })
                }
            });
        },

        closeModal() {
            this.errors = this.error = null;
        },

        resetForm() {
            let formData = this.form;
            Object.keys(formData).forEach(function (key) {
                formData[key] = '';
            });
        }
    },

    created() {
        this.getCategoryList()
    }
}
</script>

<style scoped>

</style>