<template>
    <div>
        <div class="box">
            <div class="box-header">
                <h5 class="box-title">Product List</h5>
                <button class="btn btn-primary" data-toggle="modal" data-target="#productCreateModal">Add Product</button>
            </div>
            <div class="box-body">
                <div class="row"  v-if="products">
                    <div class="col-lg-4">
                        <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" placeholder="Search name" name="search_key" v-model="search_key" @keyup="filterItems">
                        </div>
                    </div>
                    </div>
                </div>
                <table class="table table-bordered" v-if="filterProducts">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(product, i) in filterProducts" :key="product.id">
                            <td>{{ i+1 }}</td>
                            <td>{{ product.image }}</td>
                            <td>{{ product.name }}</td>
                            <td>{{ product.category_name }}</td>
                            <td>{{ product.sub_category_name }}</td>
                            <td>
                                <div class="action-group">
                                    <button class="btn btn-sm btn-success" @click="productEdit(product)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" @click="deleteProduct(product.id)">
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
        <ProductCreate></ProductCreate>
        <div class="modal fade" id="productEditModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Update Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="updateSubmit">
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
    </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'
import ProductCreate from './ProductCreate.vue';
import $ from 'jquery'

export default {
    name: 'Product',
    components: { 
        ProductCreate 
    },
    data () {
        return {
            form: {
                product_id:'',
                category_id:'',
                sub_category_id:'',
                name: '',
            },
            errors: null,
            error: null,
            search_key: ''
        }
    },

    computed: {
        ...mapGetters({
            products: 'product/products',
            categories: 'category/categories',
            sub_categories: 'subCategory/categories',
            validation_errors: 'validation_errors',
            error_message: 'error_message'
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
        },

        filterProducts: function (){
            if (this.search_key.length > 3) {
                var self = this;
                return this.products.filter(item => {
                    if (item.name == self.search_key || item.category_name == self.search_key) {
                        return item;
                    }
                })
            } else {
                return this.products
            }
        }

    },

    methods: {
        ...mapActions({
            getProductList: 'product/getProductList',
            getCategoryList: 'category/getCategoryList',
            getSubCategoryList: 'subCategory/getCategoryList',
            productUpdate: 'product/updateProduct',
            productDelete: 'product/deleteProduct'
        }),

        productEdit(product) {
            $('#productEditModal').modal('show');
            this.form.product_id = product.id
            this.form.name = product.name
            this.form.category = product.category_id
            this.form.sub_category = product.sub_category_id
        },

        updateSubmit() {
            this.productUpdate(this.form).then(() => {
                if (!this.validation_errors && !this.error_message) {
                    this.errors = null;
                    this.error = null;
                } else {
                    this.errors = this.validation_errors;
                    this.error = this.error_message;
                }
            })
        },

        deleteProduct(product) {
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
                   this.productDelete(product).then(() => {
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


        filterItems() {
            this.filterProducts
        }
    },

    getCategory: function (id) {
        return id;
    },

    getSubCategory: function (id) {
        return id;
    },

    created() {
        this.getProductList();
        this.getCategoryList();
        this.getSubCategoryList();
    }

}
</script>

<style scoped>

</style>