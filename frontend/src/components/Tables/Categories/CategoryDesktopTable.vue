<template>
    <div class="category-desktop-table-wrapper">
        <div class="overlay" :class="handleLoadingShow ? 'show' : ''">  
            <div class="preloader" v-if="(handleLoadingShow)">
                <v-progress-circular
                    :size="40"
                    color="#0171a1"
                    indeterminate>
                </v-progress-circular>
            </div>       
        </div>

        <v-data-table
            :headers="headers"
            :items="getCategoriesItems"
            :page.sync="getCurrentPage"
            :items-per-page="getItemsPerPage"
            mobile-breakpoint="1023"
            @page-count="pageCount = $event"
            class="manage-category-table elevation-1"
            v-bind:class="{
                'no-data-table' : (typeof getCategoriesItems !== 'undefined' && getCategoriesItems.length === 0),
                'no-current-pagination' : (getTotalPage <= 1),
            }"
            hide-default-footer
            fixed-header
            ref="my-table">

            <template v-slot:no-data>
                <div class="no-data-preloader mt-4" v-if="getCategoriesLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate
                        v-if="getCategoriesLoading">
                    </v-progress-circular>
                </div>
                                
                <div class="no-data-wrapper" v-if="!getCategoriesLoading && getCategoriesItems.length == 0 && search == ''">
                    <div class="no-data-heading">
                        <h3> Add Category </h3>
                        <p>
                            Let’s add the first product category on Shifl. You can use categories to organize your products and managing Inventory.
                        </p>

                        <div class="category-table-button-wrapper">                                
                            <v-btn color="primary" dark class="btn-blue add-category" @click.stop="addCategory">
                                Add Category
                            </v-btn>
                        </div>
                    </div>
                </div>

                <div class="no-data-wrapper" v-if="!getCategoriesLoading && getCategoriesItems.length == 0 && search !== ''">
                    <div class="no-data-heading">
                        <img src="../../../assets/icons/empty-product-icon.svg" class="mb-2" width="40px" height="42px" alt="">

                        <div>                           
                            <h3> No matching result </h3>
                            <p> We couldn’t find any category that matches your search term. 
                                <br/>Have you spelled it correctly?
                            </p>
                        </div>
                    </div>
                </div>
            </template>

            <template v-slot:top>
                <v-toolbar flat >
                    <v-toolbar-title>Categories</v-toolbar-title>
                    
                    <v-spacer></v-spacer>

                    <div class="search-component mr-2">
                        <SearchComponent
                            placeholder="Search Category"
                            :searchValue.sync="search"
                            :handleSearchComponent="handleSearchComponent"
                            @handleSearch="handleSearch"
                            @clearSearched="clearSearched" />
                    </div>

                    <v-btn color="primary" dark class="btn-blue add-category" @click.stop="addCategory">
                        Add Category
                    </v-btn>
                </v-toolbar>
            </template>

            <template v-slot:[`item.name`]="{ item }">
                <p class="mb-0 item-name">{{ item.name }}</p>
            </template>

            <template v-slot:[`item.description`]="{ item }">
                <div class="item-unit">{{ (item.description !== null && item.description !== "") ? item.description : '--' }}</div>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="actions">
                    <button class="btn-white mr-2" @click.stop="editCategory(item)">
                        <img src="../../../assets/icons/edit-inventory.svg" alt="">
                    </button>

                    <button class="btn-white" @click.stop="deleteCategory(item)">                    
                        <img src="../../../assets/icons/delete-blue.svg" alt="">
                    </button>
                </div>
            </template>
        </v-data-table>

        <PaginationComponent 
            :totalPage.sync="getTotalPage"
            :currentPage.sync="getCurrentPage"
            @handlePageChange="handlePageChange"
            :isMobile="isMobile" />
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import SearchComponent from '../../SearchComponent/SearchComponent.vue'
import PaginationComponent from '../../PaginationComponent/PaginationComponent.vue'

import axios from 'axios'
var cancel;
var CancelToken = axios.CancelToken;

export default {
    name: 'CategoryDesktopTable',
    props: ['items', 'isMobile', 'categoriesNextPageLoading', 'searchVal'],
    components: { 
        SearchComponent,
        PaginationComponent
    },
    data: () => ({
        page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        headers: [
            {
                text: 'CATEGORY NAME',
                align: 'start',
                sortable: false,
                value: 'name',
                fixed: true,
                width: '25%'
            },
            { 
                text: 'DESCRIPTION', 
                value: 'description',
                sortable: false,
                fixed: true,
                width: '35%'
            },
            { 
                text: 'CATEGORY CODE', 
                value: 'id',
                sortable: false,
                fixed: true,
                width: '15%'
            },
            { 
                text: 'NO OF PRODUCT', 
                align: 'start',
                value: 'no_of_products', 
                sortable: false,
                fixed: true,
                width: '15%'
            },
            { 
                text: '', 
                align: 'end',
                value: 'actions', 
                sortable: false,
                fixed: true,
                width: '10%'
            },
        ],
        typingTimeout: 0
    }),
    computed: {
        ...mapGetters({
            getCategories: 'category/getCategories',
            getCategoriesLoading: 'category/getCategoriesLoading',
            getCreateCatLoading: 'category/getCreateCatLoading',
            getUpdateCatLoading: 'category/getUpdateCatLoading',
            gettDeleteCatLoading: 'category/gettDeleteCatLoading',
            getDeleteCat: 'category/getDeleteCat',
            getUser: 'getUser',
            getSearchedCategories: 'category/getSearchedCategories',
            getSearchedCategoriesLoading: 'category/getSearchedCategoriesLoading',
        }),
        search: {
            get() {
                return this.searchVal
            },
            set(value) {
                this.$emit('update:searchVal', value)
            }
        },
        getCategoriesItems() {
            // if (typeof this.getSearchedCategories !== 'undefined' && this.getSearchedCategories !== null) {
            //     if (this.search !== '' && this.getSearchedCategories.tab === 'category') {
            //         return this.getSearchedCategories.categories
            //     } else {
            //         return this.items
            //     }
            // } else {
            //     return this.items
            // }

            return this.items
        },
        getTotalPage: {
            get() {
				let totalPage = 1

                if (typeof this.getSearchedCategories !== 'undefined' && this.getSearchedCategories !== null) {
                    if (this.search !== '' && this.getSearchedCategories.tab === 'category') {
                        if (typeof this.getSearchedCategories.last_page !== 'undefined') {
                            totalPage = this.getSearchedCategories.last_page
                        }
                    } else {
                        if (typeof this.getCategories !== 'undefined' && this.getCategories !== null) {
                            if (typeof this.getCategories.last_page !== 'undefined') {
                                totalPage = this.getCategories.last_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getCategories !== 'undefined' && this.getCategories !== null) {
                        if (typeof this.getCategories.last_page !== 'undefined') {
                            totalPage = this.getCategories.last_page
                        }
                    }
                }

				return totalPage
            }
        },
        getCurrentPage: {
            get() {
                let currentPage = 1

                if (typeof this.getSearchedCategories !== 'undefined' && this.getSearchedCategories !== null) {
                    if (this.search !== '' && this.getSearchedCategories.tab === 'category') {
                        if (typeof this.getSearchedCategories.current_page !== 'undefined') {
                            currentPage = this.getSearchedCategories.current_page
                        }
                    } else {
                        if (typeof this.getCategories !== 'undefined' && this.getCategories !== null) {
                            if (typeof this.getCategories.current_page !== 'undefined') {
                                currentPage = this.getCategories.current_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getCategories !== 'undefined' && this.getCategories !== null) {
                        if (typeof this.getCategories.current_page !== 'undefined') {
                            currentPage = this.getCategories.current_page
                        }
                    }
                }

				return currentPage
            },
            set() {
                return {}
            }
        }, 
		getItemsPerPage: {
            get() {
				let itemsPerPage = 1

                if (typeof this.getSearchedCategories !== 'undefined' && this.getSearchedCategories !== null) {
                    if (this.search !== '' && this.getSearchedCategories.tab === 'category') {
                        if (typeof this.getSearchedCategories.per_page !== 'undefined') {
                            itemsPerPage = this.getSearchedCategories.per_page
                        }
                    } else {
                        if (typeof this.getCategories !== 'undefined' && this.getCategories !== null) {
                            if (typeof this.getCategories.per_page !== 'undefined') {
                                itemsPerPage = this.getCategories.per_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getCategories !== 'undefined' && this.getCategories !== null) {
                        if (typeof this.getCategories.per_page !== 'undefined') {
                            itemsPerPage = this.getCategories.per_page
                        }
                    }
                }

				return itemsPerPage
            }
        },
        handleLoadingShow() {
            if (this.search === '') {
                return this.categoriesNextPageLoading
            } else {
                return this.getSearchedCategoriesLoading
            }
        },
        handleSearchComponent() {
            let isShow = true

            if (this.search == '' && this.getCategoriesItems.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.getCategoriesItems.length === 0) {
                isShow = true
            }

            return isShow
        }
    },
    methods: {
        ...mapActions({
            fetchCategories: 'category/fetchCategories',
            createCategories: 'category/createCategories',
            updateCategories: 'category/updateCategories',
            deleteCategories: 'category/deleteCategories',
            fetchUser: 'fetchUser',
            fetchSearchedCategories: 'category/fetchSearchedCategories',
            setSearchedCategoriesLoading: 'category/setSearchedCategoriesLoading',
            setSearchedCategoriesVal: 'category/setSearchedCategoriesVal'
        }),
        addCategory() {
            this.$emit('addCategory')
        },
        editCategory(category) {
            this.$emit('editCategory', category)
        },
        deleteCategory(category) {
            this.$emit('deleteCategory', category)
        },
        async handlePageChange(page) {
            if (this.search == '') {
                this.$emit('handlePageChange', page)
            } else {
                let data = {
                    search: this.search,
                    page
                }

                this.handlePageSearched(data)
            }

            this.handleScrollToTop()
        },
        clearSearched() {
            this.search = ''
            this.setSearchedCategoriesVal([])
            // document.getElementById("search-input").focus();
        },
        handleSearch() {
            if (cancel !== undefined) {
                cancel()
            }
            // this.setSearchedCategoriesLoading(false)
            clearTimeout(this.typingTimeout)
            this.typingTimeout = setTimeout(() => {
                let data = { 
                    search: this.search
                }  

                this.setSearchedCategoriesLoading(true)
                this.apiCall(data)
            }, 500)
        },
        apiCall(data) {
            if (data !== null && this.search !== '') {
                let passedData = {
                    method: "get",
                    url: 'https://po.shifl.com/api/categories',
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: {
                        search: this.search,
                        page: 1
                    }
                }

                try {
                    passedData.tab = 'category'
                    this.fetchSearchedCategories(passedData)
                } catch(e) {
                    this.notificationError(e)
                    this.setSearchedCategoriesLoading(false)
                    console.log(e, 'Search error')
                }
            } else {
                this.setSearchedCategoriesVal([])
            }
        },
        async handlePageSearched(data) {
            let storePagination = this.$store.state.category.searchedCategories

            if (data !== null && this.search !== '') {
                if (storePagination.old_page !== data.page) {
                    storePagination.old_page = data.page

                    let passedData = {
                        method: "get",
                        url: 'https://po.shifl.com/api/categories',
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: {
                            search: this.search,
                            page: data.page
                        }
                    }

                    try {
                        passedData.tab = 'category'
                        this.fetchSearchedCategories(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setSearchedCategoriesLoading(false)
                        console.log(e, 'Search error')
                    }
                }                
            } else {
                this.setSearchedCategoriesVal([])
            }

            this.handleScrollToTop()
        },
        handleScrollToTop() {
            var table = this.$refs['my-table'];
            var wrapper = table.$el.querySelector('div.v-data-table__wrapper');
            
            this.$vuetify.goTo(table); // to table
            this.$vuetify.goTo(table, {container: wrapper}); // to header
        }
    },
    mounted() {}
}
</script>

<style lang="scss">
.category-desktop-table-wrapper {
    position: relative;

    .overlay {
        &.show {
            position: absolute;
            top: 64px;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #fff !important;
            z-index: 100;
            cursor: auto;
            border: 1px solid #EBF2F5 !important;

            .preloader {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-top: 3%;
            }
        }
    }
}
</style>
