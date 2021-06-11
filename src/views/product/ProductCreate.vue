<template>
    <div class="modal fade" id="productCreateModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Create Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="createSubmit">
                    <div class="modal-body">
                        <span class="text-danger" v-if="error">{{ error }}</span>
                        <div class="form-group">
                            <label>Category</label>
                            <select v-model="form.category" class="form-control" @change="categoryChange">
                                <option value="">Select Category</option>
                                <option v-for="(category) in categories" :key="category.id"  :value="category.id" >{{ category.name }}</option>
                            </select>
                            <span class="text-danger" v-if="errors">{{ errors.category ? errors.category[0] : '' }}</span>
                        </div>
                        <div class="form-group">
                            <label>Sub Category</label>
                            <select v-model="form.sub_category" class="form-control">
                                <option value="">Select Category</option>
                                <option v-for="(category) in filterSubCategories" :key="category.id"  :value="category.id" >{{ category.name }}</option>
                            </select>
                            <span class="text-danger" v-if="errors">{{ errors.category ? errors.category[0] : '' }}</span>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" v-model="form.name" placeholder="Category name">
                            <span class="text-danger" v-if="errors">{{ errors.name ? errors.name[0] : '' }}</span>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control form-image-control">
                            <span class="text-danger" v-if="errors">{{ errors.image ? errors.image[0] : '' }}</span>
                        </div>
                        <div class="img-prev"></div>
                    </div>
                    <div class="modal-footer">
                        <div class="text-right">
                            <button type="submit" class="btn btn-success">Save Category</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    name: 'ProductCreate',

    data() {
        return {
            form: {
                name: '',
                image: '',
                category: '',
                sub_category: ''
            },
            errors: null,
            error: null
        }
    },

    computed: {
        ...mapGetters({
            validation_errors: 'validation_errors',
            error_message: 'error_message',
            categories: 'category/categories',
            sub_categories: 'subCategory/categories',
        }),

        filterSubCategories: function (){
            if (this.form.category) {
                var self = this;
                return this.sub_categories.filter(item => {
                    if (item.category_id == self.form.category) {
                        return item
                    }
                })
            } else {
                return this.sub_categories
            }
        }
    },

    methods: {
        ...mapActions({
            getCategoryList: 'category/getCategoryList',
            getSubCategoryList: 'subCategory/getCategoryList',
            createProduct: 'product/createProduct',
        }),

        createSubmit() {
            this.createProduct(this.form).then(() => {
                if (!this.validation_errors && !this.error_message) {
                    this.errors = null;
                    this.error = null;
                    this.resetForm();
                } else {
                    this.errors = this.validation_errors;
                    this.error = this.error_message;
                }
            })
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
        },

        categoryChange(event){
            let category_id = event.target.value;
            if (category_id) {
                this.filterSubCategories
            }
        }
    },

    created() {
        this.getCategoryList();
        this.getSubCategoryList();
    }
}
</script>

<style scoped>

</style>