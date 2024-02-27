<template>
    <div class="manage-category-wrapper" v-resize="onResize">
        <div class="details-breadcrumbs">
            <router-link to="/products" class="product-link">
                Products
            </router-link>

            <span class="right-chevron">
                <img src="../assets/images/right_chevron.svg" alt="" srcset="" width="7px">
            </span>

            <p class="manage-category">
                Manage Categories
            </p>
        </div>

        <div class="manage-category-table-wrapper">
            <!-- Categories Table Desktop View -->
            <CategoryDesktopTable 
                :searchVal.sync="search"
                :items="categories"
                @addCategory="addCategory"
                @editCategory="editCategory"
                @deleteCategory="deleteCategory"
                :isMobile="isMobile"
                v-if="!isMobile"
                @handlePageChange="handlePageChange"
                :categoriesNextPageLoading="categoriesNextPageLoading" />

            <!-- Categories Table Mobile View -->
            <CategoryMobileTable
                :searchVal.sync="search"
                :items="categories"
                @addCategory="addCategory"
                @editCategory="editCategory"
                @deleteCategory="deleteCategory"
                :isMobile="isMobile"
                v-if="isMobile"
                @handlePageChange="handlePageChange"
                :categoriesNextPageLoading="categoriesNextPageLoading" />

            <CreateDialog
                :dialogData.sync="dialogCategoryCreate"
                :editedItemData.sync="editedCategoryItem"
                :editedIndexData.sync="editedCategoryIndex"
                @close="closeCategory"
                :isMobile="isMobile"
                :searchVal="search"
                :categoriesData="categories" />

            <!-- Dialog Delete -->
            <ConfirmDialog :dialogData.sync="dialogDeleteCategory">
                <template v-slot:dialog_icon>
                    <div class="header-wrapper-close">
                        <img src="@/assets/icons/icon-delete.svg" alt="alert">
                    </div>
                </template>
                
                <template v-slot:dialog_title>
                    <h2> Delete Category </h2>
                </template>

                <template v-slot:dialog_content>
                    <p> 
                        Do you want to delete the category
                        <span class="name">"{{ currentItemToDelete !== null ? currentItemToDelete.name : '' }}"</span>?
                        Once deleted, this cannot be undone.
                    </p>
                </template>

                <template v-slot:dialog_actions>
                    <v-btn class="btn-blue" @click="deleteCategoryConfirm" text :disabled="gettDeleteCatLoading">
                        <span v-if="!gettDeleteCatLoading">Delete</span>
                        <span v-if="gettDeleteCatLoading">Deleting...</span>
                    </v-btn>

                    <v-btn class="btn-white" text @click="closeDelete" :disabled="gettDeleteCatLoading">
                        Cancel
                    </v-btn>
                </template>
            </ConfirmDialog> 

            <!-- Dialog Failed Delete -->
            <ConfirmDialog :dialogData.sync="dialogDeleteFailed">
                <template v-slot:dialog_icon>
                    <div class="header-wrapper-close">
                        <img src="@/assets/icons/icon-delete.svg" alt="alert">
                    </div>
                </template>
                
                <template v-slot:dialog_title>
                    <h2> Deletion Failed </h2>
                </template>

                <template v-slot:dialog_content>
                    <p> You cannot delete a category that has an associated product. </p>
                </template>

                <template v-slot:dialog_actions>
                    <v-btn class="btn-blue" text @click="closeFailedDelete">
                        Understood
                    </v-btn>
                </template>
            </ConfirmDialog> 
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import CategoryDesktopTable from '../components/Tables/Categories/CategoryDesktopTable.vue'
import CategoryMobileTable from '../components/Tables/Categories/CategoryMobileTable.vue'
import CreateDialog from '../components/ProductComponents/Categories/CreateDialog.vue'
import ConfirmDialog from '../components/Dialog/GlobalDialog/ConfirmDialog.vue'
import globalMethods from '../utils/globalMethods'

export default {
    name: 'ManageCategories',
    components: {
        CategoryDesktopTable,
        CategoryMobileTable,
        CreateDialog,
        ConfirmDialog,
    },
    data: () => ({
        search: '',
        dialogCategoryCreate: false,
        editedCategoryIndex: -1,
        editedCategoryItem: {
            name: '',
            description: '',
        },
        defaultCategoryItem: {
            name: '',
            description: '',
        },
        dialogDeleteCategory: false,
        dialogDeleteFailed: false,
        currentItemToDelete: null,
        isMobile: false,
        categoriesNextPageLoading: false
    }),
    watch: { },
    computed: {
        ...mapGetters({
            getCategories: 'category/getCategories',
            getCategoriesLoading: 'category/getCategoriesLoading',
            getCreateCatLoading: 'category/getCreateCatLoading',
            getUpdateCatLoading: 'category/getUpdateCatLoading',
            gettDeleteCatLoading: 'category/gettDeleteCatLoading',
            getUser: 'getUser',
            getSearchedCategories: 'category/getSearchedCategories',
            getAllCategoriesDropdown: 'category/getAllCategoriesDropdown'
        }),
        categories() {
            let categoryListsItems = []

			// if (typeof this.getCategories !== 'undefined' && this.getCategories !== null) {
			// 	if (typeof this.getCategories.categories !== 'undefined' && 
            //         Array.isArray(this.getCategories.categories) && 
            //         this.getCategories.categories.length > 0) {

			// 		categoryListsItems = this.getCategories.categories
			// 	}
			// } else {
			// 	categoryListsItems = []
			// }

            if (typeof this.getSearchedCategories !== 'undefined' && this.getSearchedCategories !== null) {
                if (this.search !== '' && this.getSearchedCategories.tab === 'category') {
                    categoryListsItems = this.getSearchedCategories.categories
                } else {
                    if (typeof this.getCategories !== 'undefined' && this.getCategories !== null) {
                        if (typeof this.getCategories.categories !== 'undefined' && 
                            Array.isArray(this.getCategories.categories) && 
                            this.getCategories.categories.length > 0) {

                            categoryListsItems = this.getCategories.categories
                        }
                    }
                }
            } else {
                if (typeof this.getCategories !== 'undefined' && this.getCategories !== null) {
                    if (typeof this.getCategories.categories !== 'undefined' && 
                        Array.isArray(this.getCategories.categories) && 
                        this.getCategories.categories.length > 0) {

                        categoryListsItems = this.getCategories.categories
                    }
                }
            }

			return categoryListsItems
        },
    },
    methods: {
        ...mapActions({
            fetchCategories: 'category/fetchCategories',
            createCategories: 'category/createCategories',
            updateCategories: 'category/updateCategories',
            deleteCategories: 'category/deleteCategories',
            fetchUser: 'fetchUser',
            setAllCategoriesDropdown: 'category/setAllCategoriesDropdown',
            fetchSearchedCategories: 'category/fetchSearchedCategories',
            setSearchedCategoriesLoading: 'category/setSearchedCategoriesLoading',
        }),
        ...globalMethods,
        onResize() {
			if (window.innerWidth < 1023) {
				this.isMobile = true
			} else {
				this.isMobile = false;
			}
		},
        addCategory() {
            this.dialogCategoryCreate = true
        },
        editCategory(category) {
            this.editedCategoryIndex = this.categories.indexOf(category)
            this.editedCategoryItem = Object.assign({}, category)
            this.dialogCategoryCreate = true
        },
        deleteCategory(category) {
            this.editedCategoryIndex = this.categories.indexOf(category)
            this.editedItem = Object.assign({}, category)
            this.dialogDeleteCategory = true

            this.currentItemToDelete = {
                name: category.name,
                id: category.id,
                no_of_prod: category.no_of_products
            }
        },
        async deleteCategoryConfirm() {
            if (this.currentItemToDelete !== null) {
                try {
                    let storePagination = this.$store.state.category.categories
                    let page = typeof storePagination.current_page !== 'undefined' ? storePagination.current_page : 1

                    await this.deleteCategories(this.currentItemToDelete.id)
                    
                    if (this.search !== '') {
                        let searchedPage = typeof this.getSearchedCategories !== 'undefined' ? this.getSearchedCategories.current_page : 1

                        if (this.categories.length === 1 && this.getSearchedCategories.current_page !== 1) {
							searchedPage = searchedPage - 1
						}

                        let passedData = {
                            method: "get",
                            url: 'https://po.shifl.com/api/categories',
                            params: {
                                search: this.search,
                                page: searchedPage
                            }
                        }

                        try {
                            passedData.tab = 'category'
                            await this.fetchSearchedCategories(passedData)
                            this.notificationCustom('Category has been deleted.') 
                            this.closeDelete() 

                            page = 1
                            await this.fetchCategories(page)
                        } catch(e) {
                            this.notificationError(e)
                            this.setSearchedCategoriesLoading(false)
                            console.log(e, 'Search error')
                        }
                    } else {
                        if (this.categories.length === 1 && storePagination.current_page !== 1) {
                            page = page - 1
                        } 

                        this.notificationCustom('Category has been deleted.') 
                        this.closeDelete() 
                        await this.fetchCategories(page)
                    }

                    if (typeof this.getAllCategoriesDropdown !== 'undefined' && this.getAllCategoriesDropdown.length > 0) {
                        this.setAllCategoriesDropdown([])
                    }
                } catch (e) {
                    this.closeDelete()

                    if (this.currentItemToDelete.no_of_prod !== 0) {
                        this.dialogDeleteFailed = true
                    } else {
                        this.notificationError(e)
                    }
                }
            }
        },
        closeDelete() {
            this.dialogDeleteCategory = false

            this.$nextTick(() => {
                this.editedCategoryItem = Object.assign({}, this.defaultCategoryItem)
                this.editedCategoryIndex = -1
            })
        },
        closeFailedDelete() {
            this.dialogDeleteFailed = false
        },
        closeCategory() {
            this.$nextTick(() => {
                this.editedCategoryItem = Object.assign({}, this.defaultCategoryItem)
                this.editedCategoryIndex = -1
            })

            this.dialogCategoryCreate = false
        },
        async handlePageChange(page) {
			if (page !== null) {
				let storePagination = this.$store.state.category.categories

				try {
					if (storePagination.old_page !== page) {
						this.categoriesNextPageLoading = true
						await this.fetchCategories(page)
						storePagination.old_page = page
						this.categoriesNextPageLoading = false
					}
				} catch(e) {
					this.notificationError(e)
					console.log(e);
				}
			}
        },
    },
    async mounted() {
        this.$store.dispatch("page/setPage","products")
        let storePagination = this.$store.state.category.categories
        
        try {
            let page = typeof storePagination.current_page !== 'undefined' ? storePagination.current_page : 1
            await this.fetchCategories(page)
        } catch (e) {
            this.notificationError(e)
        }
    },
    updated() {}
}
</script>

<style lang="scss">
@import '../assets/scss/pages_scss/category/categories.scss';
@import '../assets/scss/pages_scss/category/categoriesDialog.scss';
@import '../assets/scss/buttons.scss';
@import '../assets/scss/pages_scss/dialog/globalDialog.scss';
</style>
