<template>
    <div>
        <div class="box">
            <div class="box-header">
                <h5 class="box-title">Sub Category List</h5>
                <button class="btn btn-primary" data-toggle="modal" data-target="#categoryCreateModal">Add Sub Category</button>
            </div>
            <div class="box-body">
                <table class="table table-bordered" v-if="categories">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Name</th>
                            <th>Parent Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(category, i) in categories" :key="category.id">
                            <td>{{ i+1 }}</td>
                            <td>{{ category.name }}</td>
                            <td>{{ category.parent_category }}</td>
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
        <SubCategoryCreate></SubCategoryCreate>
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
                        {{ form }}
                        <div class="modal-body">
                            <span class="text-danger" v-if="error">{{ error }}</span>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="hidden" v-model="form.sub_category_id">
                                <input type="text" name="name" class="form-control" v-model="form.name" placeholder="Category name">
                                <span class="text-danger" v-if="errors">{{ errors.name ? errors.name[0] : '' }}</span>
                            </div>
                            <div class="form-group">
                                <label>Parent Category</label>
                                <select v-model="form.parent_category" class="form-control">
                                    <option value="">Select Category</option>
                                    <option v-for="(category) in parentCategories" :key="category.id"  :value="category.id" >{{ category.name }}</option>
                                </select>
                                <span class="text-danger" v-if="errors">{{ errors.parent_category ? errors.parent_category[0] : '' }}</span>
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
import SubCategoryCreate from './SubCategoryCreate.vue';
import $ from 'jquery'

export default {
	components: { SubCategoryCreate },

    name: 'SubCategory',

    data () {
        return {
            form: {
                sub_category_id:'',
                name: '',
                parent_category: ''                
            },
            errors: null,
            error: null,
        }
    },

    computed: {
        ...mapGetters({
            categories: 'subCategory/categories',
            validation_errors: 'validation_errors',
            error_message: 'error_message',
            parentCategories: 'category/categories'
        }),
    },

    methods: {
        ...mapActions({
            getCategoryList: 'subCategory/getCategoryList',
            parentCategoriesList: 'category/getCategoryList',
            categoryUpdate: 'subCategory/updateCategory',
            categoryDelete: 'subCategory/deleteCategory'
        }),

        categoryEdit(category) {
            $('#categoryEditModal').modal('show');
            this.form.sub_category_id = category.id
            this.form.name = category.name
            this.form.parent_category = category.category_id
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
            this.resetForm();
        },

        resetForm() {
            let formData = this.form;
            Object.keys(formData).forEach(function (key) {
                formData[key] = '';
            });
        }
    },

    created() {
        this.getCategoryList();
        this.parentCategoriesList();
    }
}
</script>

<style scoped>

</style>