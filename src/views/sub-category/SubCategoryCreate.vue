<template>
    <div class="modal fade" id="categoryCreateModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Add Sub Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="createSubmit">
                    <div class="modal-body">
                        <span class="text-danger" v-if="error">{{ error }}</span>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" v-model="form.name" placeholder="Sub category name">
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
    name: 'SubCategoryCreate',

    data() {
        return {
            form: {
                name: '',
                parent_category: ''
            },
            errors: null,
            error: null
        }
    },

    computed: {
        ...mapGetters({
            validation_errors: 'validation_errors',
            error_message: 'error_message',
            parentCategories: 'category/categories'
        })
    },

    methods: {
        ...mapActions({
            categoryCreate: 'subCategory/createCategory',
            parentCategoriesList: 'category/getCategoryList',
        }),

        createSubmit() {
            this.categoryCreate(this.form).then(() => {
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
        }
    },
    created() {
        this.parentCategoriesList();
    }
}
</script>

<style scoped>

</style>