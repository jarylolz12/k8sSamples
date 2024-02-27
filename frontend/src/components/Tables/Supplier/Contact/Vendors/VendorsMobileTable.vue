<template>
	<div class="supplier-content-wrapper">
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
			:items="suppliers"
			mobile-breakpoint="769"
			class="suppliers-table-mobile elevation-1"
			v-bind:class="{
                'no-data-table' : (typeof suppliers !== 'undefined' && suppliers.length === 0),
                'no-current-pagination' : (getTotalPage <= 1),
            }"
			:page.sync="page"
			:items-per-page="itemsPerPage"
			@page-count="pageCount = $event"
			hide-default-footer
			ref="my-table">
			<!-- :search="search" -->

			<template v-slot:top>
				<v-toolbar flat>
					<v-toolbar-title>Vendor</v-toolbar-title>

					<v-spacer></v-spacer>

					<v-btn
						color="primary"
						class="btn-blue add-supplier"
						@click.stop="addSupplier">
						+ Add Vendor
					</v-btn>
				</v-toolbar>

				<div class="search-component" v-if="handleSearchComponent">
					<SearchComponent
						placeholder="Search Vendor"
						:searchValue.sync="search"
						:handleSearchComponent="handleSearchComponent"
						@handleSearch="handleSearch"
						@clearSearched="clearSearched" />
				</div>
			</template>

			<template v-slot:[`item.name`]="{ item }">
				<div class="supplier-row">
					<p class="text-start mb-0" style="font-weight: 600; color: #4a4a4a;">
						{{ item.name }}
					</p>

					<div class="item-button" @click="editSupplier(item)">
						<img src="@/assets/icons/edit-blue.svg" alt="" />
						<span>Edit</span>
					</div>
				</div>
			</template>

			<template v-slot:[`item.phone`]="{ item }">
				<div class="supplier-row">
					<p class="p-gray mb-0">{{ item.address }}</p>
				</div>

				<div class="supplier-row">
					<div class="d-flex align-items-center mt-1 mb-2">
						<img src="@/assets/icons/phone.svg" class="mr-1" alt="" />
						<p class="p-gray mb-0">{{ item.phone }}</p>
					</div>
				</div>
			</template>

			<template v-slot:[`item.address`]="{ item }">
				<div class="mb-4 email-mobile">
					<img src="@/assets/icons/email.svg" class="mt-1 mr-1" alt="" />

					<div v-if="item.emails !== ''">
						<div
							class="supplier-row"
							v-for="(email, index) in item.emails"
							:key="index">
							<p v-if="!(email instanceof Object)" class="mb-0 text-start" style="color: #0171A1;">{{ email }}</p>
							<div v-else
								v-for="(emailValue, emailKey) in email"
								:key="emailKey">
								<p class="mb-0 text-start" style="color: #0171A1;">{{ emailValue }}</p>
							</div>
						</div>
					</div>

					<div v-else>
						<p class="mb-0" style="color: #4a4a4a;">--</p>
					</div>
				</div>
			</template>

			<template v-slot:no-data>
				<div class="no-data-preloader mt-4" v-if="getSuppliersLoading">
					<v-progress-circular
						:size="40"
						color="#0171a1"
						indeterminate
						v-if="getSuppliersLoading">
					</v-progress-circular>
				</div>

				<div class="no-data-wrapper" v-if="!getSuppliersLoading && suppliers.length == 0 && search === ''">
                    <div class="no-data-heading">
                        <img
							src="@/assets/icons/empty-supplier-icon.svg"
							width="40px"
							height="42px"
							alt=""
						/>

                        <h3>Add Vendor</h3>
						<p>
							You have not added any vendor yet. Let’s add your first vendor to Shifl platform.
						</p>

                        <div class="supplier-button-action-wrapper">
							<v-btn
								color="primary"
								class="btn-white add-supplier"
								@click.stop="addSupplier">
								Add Vendor
							</v-btn>
						</div>
                    </div>
                </div>

                <div class="no-data-wrapper" v-if="!getSuppliersLoading && suppliers.length == 0 && search !== ''">
                    <div class="no-data-heading">
                        <img
							src="@/assets/icons/empty-supplier-icon.svg"
							width="40px"
							height="42px"
							alt=""
						/>

                        <div>                           
                            <h3>No matching result </h3>
                            <p> Sorry! We could not find any vendor that matches your search term. </p>
                        </div>
                    </div>
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
import { mapActions, mapGetters } from "vuex";
import SearchComponent from '../../../../SearchComponent/SearchComponent.vue'
// import InviteModal from "@/components/SupplierComponents/InviteModal.vue";
// import ConfirmDialog from "@/components/Dialog/GlobalDialog/ConfirmDialog.vue";
import globalMethods from "@/utils/globalMethods";
import PaginationComponent from '../../../../PaginationComponent/PaginationComponent.vue'
import inventoryGlobalMethods from '@/utils/inventoryMethods/inventoryGlobalMethods'

import axios from 'axios'
var cancel;
var CancelToken = axios.CancelToken;
export default {
	name: "VendorsMobileTable",
	props: ["items", "isMobile", 'suppliersPagination', 'searchVal'],
	components: {
		SearchComponent,
		// InviteModal,
		// ConfirmDialog,
		PaginationComponent
	},
	data: () => ({
		page: 1,
		pageCount: 0,
		itemsPerPage: 35,
		headers: [
			{
				text: "Name",
				align: "start",
				sortable: false,
				value: "name",
				// width: "22%",
				width: "25%",
				fixed: true,
			},
			// uncomment all width once api is finalized.
			// {
			// 	text: "Type",
			// 	align: "start",
			// 	sortable: false,
			// 	value: "type",
			// 	width: "8%",
			// 	fixed: true,
			// },
			{
				text: "Phone",
				align: "start",
				value: "phone",
				sortable: false,
				// width: "14%",
				width: "15%",
				fixed: true,
			},
			{
				text: "Address",
				align: "start",
				value: "address",
				sortable: false,
				// width: "20%",
				width: "25%",
				fixed: true,
			},
			{
				text: "Email",
				align: "start",
				value: "emails",
				sortable: false,
				// width: "20%",
				width: "22%",
				fixed: true,
			},
			{
				text: "",
				align: "end",
				value: "actions",
				sortable: false,
				// width: "23%",
				width: "10%",
				fixed: true,
			},
		],
		dialogEmail: false,
		editedIndexEmail: -1,
		editedEmailItem: {
			emails: [],
			info: {},
			supplier_id: "",
			customer_admin_id: "",
			isSupplier: true,
		},
		defaultEmailItem: {
			emails: [],
			info: {},
			supplier_id: "",
			customer_admin_id: "",
			isSupplier: true,
		},
		currentSelectedVendor: null,
		dialogDeleteVendor: false,
		dialogDisconnectVendor: false,
		dialogCancelInvitation: false,
		typingTimeout: 0,
		vendorsNextPageLoading: false
	}),
	computed: {
		...mapGetters({
			getSuppliers: "suppliers/getSuppliers",
			getUser: "getUser",
			getSuppliersLoading: "suppliers/getSuppliersLoading",
			getDeleteSuppliersLoading: 'suppliers/getDeleteSuppliersLoading',
			getSearchedSuppliers: "suppliers/getSearchedSuppliers",
			getSearchedSuppliersLoading: "suppliers/getSearchedSuppliersLoading"
		}),
		formTitle() {
			return this.editedIndex === -1 ? "Add Supplier" : "Edit Supplier";
		},
		search: {
            get() {
                return this.searchVal
            },
            set(value) {
                this.$emit('update:searchVal', value)
            }
        },
		suppliers() {
			return this.items;
		},
		getTotalPage: {
            get() {
				let totalPage = 1

                if (typeof this.getSearchedSuppliers !== 'undefined' && this.getSearchedSuppliers !== null) {
                    if (this.search !== '' && this.getSearchedSuppliers.tab === 'vendors') {
                        if (typeof this.getSearchedSuppliers.last_page !== 'undefined') {
                            totalPage = this.getSearchedSuppliers.last_page
                        }
                    } else {
                        if (typeof this.getSuppliers !== 'undefined' && this.getSuppliers !== null) {
                            if (typeof this.getSuppliers.last_page !== 'undefined') {
                                totalPage = this.getSuppliers.last_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getSuppliers !== 'undefined' && this.getSuppliers !== null) {
                        if (typeof this.getSuppliers.last_page !== 'undefined') {
                            totalPage = this.getSuppliers.last_page
                        }
                    }
                }

				return totalPage
            }
        },
        getCurrentPage: {
            get() {
                let currentPage = 1

                // if (typeof this.getSearchedProducts !== 'undefined' && this.getSearchedProducts !== null) {
                //     if (this.search !== '' && this.getSearchedProducts.tab === 'products') {
                //         if (typeof this.getSearchedProducts.current_page !== 'undefined') {
                //             currentPage = this.getSearchedProducts.current_page
                //         }
                //     } else {
                //         if (typeof this.getProducts !== 'undefined' && this.getProducts !== null) {
                //             if (typeof this.getProducts.current_page !== 'undefined') {
                //                 currentPage = this.getProducts.current_page
                //             }
                //         }
                //     }
                // } else {
                //     if (typeof this.getProducts !== 'undefined' && this.getProducts !== null) {
                //         if (typeof this.getProducts.current_page !== 'undefined') {
                //             currentPage = this.getProducts.current_page
                //         }
                //     }
                // }

				if (typeof this.getSearchedSuppliers !== 'undefined' && this.getSearchedSuppliers !== null) {
                    if (this.search !== '' && this.getSearchedSuppliers.tab === 'vendors') {
                        if (typeof this.getSearchedSuppliers.current_page !== 'undefined') {
                            currentPage = this.getSearchedSuppliers.current_page
                        }
                    } else {
                        if (typeof this.getSuppliers !== 'undefined' && this.getSuppliers !== null) {
                            if (typeof this.getSuppliers.current_page !== 'undefined') {
                                currentPage = this.getSuppliers.current_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getSuppliers !== 'undefined' && this.getSuppliers !== null) {
                        if (typeof this.getSuppliers.current_page !== 'undefined') {
                            currentPage = this.getSuppliers.current_page
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

				if (typeof this.getSearchedSuppliers !== 'undefined' && this.getSearchedSuppliers !== null) {
                    if (this.search !== '' && this.getSearchedSuppliers.tab === 'vendors') {
                        if (typeof this.getSearchedSuppliers.per_page !== 'undefined') {
                            itemsPerPage = this.getSearchedSuppliers.per_page
                        }
                    } else {
                        if (typeof this.getSuppliers !== 'undefined' && this.getSuppliers !== null) {
                            if (typeof this.getSuppliers.per_page !== 'undefined') {
                                itemsPerPage = this.getSuppliers.per_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getSuppliers !== 'undefined' && this.getSuppliers !== null) {
                        if (typeof this.getSuppliers.per_page !== 'undefined') {
                            itemsPerPage = this.getSuppliers.per_page
                        }
                    }
                }

				return itemsPerPage
            }
        },
		handleLoadingShow() {
            if (this.search === '') {
                return this.vendorsNextPageLoading
            } else {
                return this.getSearchedSuppliersLoading
            }
        },
		handleSearchComponent() {
            let isShow = true

            if (this.search == '' && this.suppliers.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.suppliers.length === 0) {
                isShow = true
            }

            return isShow
        }
	},
	watch: {},
	created() {},
	methods: {
		...mapActions({
			fetchSuppliers: "suppliers/fetchSuppliers",
			deleteSuppliers: "suppliers/deleteSuppliers",
			fetchSearchedVendors: "suppliers/fetchSearchedVendors",
			setSearchedVendorsLoading: "suppliers/setSearchedVendorsLoading",
			setSearchedVendorsVal: "suppliers/setSearchedVendorsVal"
		}),
		...globalMethods,
		...inventoryGlobalMethods,
		addSupplier() {
			this.$emit("addSupplier")
		},
		editSupplier(item) {
			this.$emit("editSupplier", item)
		},
		inviteVendor(vendor) {
			this.dialogEmail = true
			this.editedPoIndex = -1
			this.editedEmailItem.info = vendor
			this.editedEmailItem.supplier_id = vendor.id

			if (Array.isArray(vendor.emails) && vendor.emails.length > 0) {
				this.editedEmailItem.emails = vendor.emails
			}
		},
		closeEmail() {
			this.dialogEmail = false
			this.editedPoIndex = -1
			this.editedEmailItem = {
				emails: [],
				info: {},
			}
		},
		viewDetails(vendor) {
			console.log(vendor)
		},
		// deleting
		deleteVendor(item) {
			this.dialogDeleteVendor = true
			this.currentSelectedVendor = item
			console.log(item)
		},
		async deleteConfirm(id) {
			try {
				await this.deleteSuppliers(id)

				let parmsSuppliers = {
					itemsPerPage: this.suppliersPagination.per_page,
					pageNumber: this.suppliersPagination.current_page,
				}

				if (this.suppliers.length === 1 && this.getCurrentPage !== 1) {
					parmsSuppliers.pageNumber = this.getCurrentPage - 1
				}
			
				if (this.search !== '') {
					let passedData = {
						method: "get",
						url: 'https://beta.shifl.com/api/v2/suppliers',
						params: {
							qry: this.search,
							page: parmsSuppliers.pageNumber
						}
					}

					try {
						passedData.tab = 'vendors'
						await this.fetchSearchedVendors(passedData)
						this.notificationCustom("Vendor has been deleted.")
						this.dialogDeleteVendor = false

						parmsSuppliers.pageNumber = 1
						await this.fetchSuppliers(parmsSuppliers)
					} catch(e) {
						this.notificationError(e)
						this.setSearchedVendorsLoading(false)
						console.log(e, 'Search error')
					}
				} else {
					await this.fetchSuppliers(parmsSuppliers)
					this.notificationCustom("Vendor has been deleted.")
					this.dialogDeleteVendor = false
				}
			} catch(e) {
				this.notificationError(e)
				this.dialogDeleteVendor = false
			}
		},
		closeDelete() {
			this.dialogDeleteVendor = false
			this.currentSelectedVendor = null
		},
		// disconnecting
		disconnectVendor(item) {
			this.dialogDisconnectVendor = true
			this.currentSelectedVendor = item
			console.log(item)
		},
		disconnectConfirm() {
			console.log("confirm disconnection")
			// this.notificationCustom('Vendor has been disconnected')
		},
		closeDisconnect() {
			this.dialogDisconnectVendor = false
			this.currentSelectedVendor = null
		},
		// cancelling
		cancelInvitation(item) {
			this.dialogCancelInvitation = true
			this.currentSelectedVendor = item
			console.log(item)
		},
		cancelInvitationConfirm() {
			console.log("confirm cancel invitation")
			// this.notificationCustom('Invitation has been cancelled')
		},
		closeCancelInvitation() {
			this.dialogCancelInvitation = false
			this.currentSelectedVendor = null
		},
		async handlePageChange(page) {	
			this.handleScrollToTop()
			if (this.search == '') {
                let parms = {
					itemsPerPage: this.suppliersPagination.per_page,
					pageNumber: page,
				}

				try {
					if (page !== this.getCurrentPage) {
						this.vendorsNextPageLoading = true
						await this.fetchSuppliers(parms)
						this.vendorsNextPageLoading = false
					}
				} catch (e) {
					this.notificationError(e)
				}
            } else {
                let data = {
                    search: this.search,
                    page
                }

                this.handlePageSearched(data)
            }
		},
		clearSearched() {
			this.search = ''
			this.setSearchedVendorsVal([])
		},
		handleSearch() {
			if (cancel !== undefined) {
                cancel()
            }
            clearTimeout(this.typingTimeout)
            this.typingTimeout = setTimeout(() => {
                let data = { 
                    search: this.search
                }  

                this.setSearchedVendorsLoading(true)
                this.apiCall(data)
            }, 500)
		},
		apiCall(data) {
            if (data !== null && this.search !== '') {
                let passedData = {
                    method: "get",
                    url: 'https://beta.shifl.com/api/v2/suppliers',
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: {
                        qry: this.search,
                        page: 1
                    }
                }

                try {
                    passedData.tab = 'vendors'
                    this.fetchSearchedVendors(passedData)
                } catch(e) {
                    this.notificationError(e)
                    this.setSearchedVendorsLoading(false)
                    console.log(e, 'Search error')
                }
            } else {
                this.setSearchedVendorsVal([])
            }
        },
		async handlePageSearched(data) {
			this.handleScrollToTop()
            if (data !== null && this.search !== '') {
                let passedData = {
					method: "get",
					url: 'https://beta.shifl.com/api/v2/suppliers',
					cancelToken: new CancelToken(function executor(c) {
						cancel = c
					}),
					params: {
						qry: this.search,
						page: data.page
					}
				}

				try {
					passedData.tab = 'vendors'

					if (data.page !== this.getCurrentPage) {
						this.fetchSearchedVendors(passedData)
					}					
				} catch(e) {
					this.notificationError(e)
					this.setSearchedVendorsLoading(false)
					console.log(e, 'Search error')
				}               
            } else {
                this.setSearchedVendorsVal([])
            }
        },
		handleScrollToTop() {
            this.scrollTableToTop()
        }
	},
	async mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "contact")
	},
	updated() {},
}
</script>

<style lang="scss"></style>
