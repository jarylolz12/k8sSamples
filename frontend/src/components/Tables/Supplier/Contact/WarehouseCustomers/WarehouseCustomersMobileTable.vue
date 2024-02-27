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
			mobile-breakpoint="769"
			:items="warehouseCustomers"
			class="suppliers-table-mobile customer elevation-1"
			v-bind:class="{
                'no-data-table' : (typeof warehouseCustomers !== 'undefined' && warehouseCustomers.length === 0),
                'no-current-pagination' : (getTotalPage <= 1),
            }"
			:page.sync="page"
			:items-per-page="itemsPerPage"
			@page-count="pageCount = $event"
			hide-default-footer
			fixed-header
			ref="my-table">

			<template v-slot:top>
				<v-toolbar flat>
					<v-toolbar-title>Warehouse Customers</v-toolbar-title>

					<v-spacer></v-spacer>

					<v-btn
						color="primary"
						class="btn-blue add-supplier"
						@click.stop="addWarehouseCustomer">
						<span v-if="!isMobile">+ Add Warehouse Customers</span>
						<span v-if="isMobile">+ Add</span>
					</v-btn>
				</v-toolbar>

				<div class="search-component" v-if="handleSearchComponent">
					<SearchComponent
						placeholder="Search Customer"
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

					<div class="item-button" @click="editWarehouseCustomer(item)">
						<img src="@/assets/icons/edit-blue.svg" alt="" />
						<span>Edit</span>
					</div>
				</div>
			</template>

			<template v-slot:[`item.phone`]="{ item }">
				<div class="supplier-row">
					<p class="p-gray mb-0">{{ item.address !== "" ? item.address : "No address indicated." }}</p>
				</div>

				<div class="supplier-row">
					<div class="d-flex align-items-center mt-1 mb-2">
						<img src="@/assets/icons/phone.svg" class="mr-1" alt="" />
						<p class="p-gray mb-0">{{ item.phone !== "" ? item.phone : "N/A" }}</p>
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

					<div v-if="item.emails == ''">
						<p class="p-gray mb-0">N/A</p>
					</div>
				</div>
			</template>

			<template v-slot:no-data>
				<div class="no-data-preloader mt-4" v-if="getWarehouseCustomersLoading">
					<v-progress-circular
						:size="40"
						color="#0171a1"
						indeterminate
						v-if="getWarehouseCustomersLoading">
					</v-progress-circular>
				</div>

				<div class="no-data-wrapper" v-if="!getWarehouseCustomersLoading && warehouseCustomers.length == 0 && search === ''">
                    <div class="no-data-heading">
                        <img
							src="@/assets/icons/empty-supplier-icon.svg"
							width="40px"
							height="42px"
							alt=""
						/>

                        <h3>Add Warehouse Customers</h3>
						<p>
							You have not added any warehouse customers yet. Let’s add your first warehouse customer to Shifl platform.
						</p>

                        <div class="supplier-button-action-wrapper">
							<v-btn
								color="primary"
								class="btn-white add-supplier"
								@click.stop="addWarehouseCustomer">
								Add Warehouse Customer
							</v-btn>
						</div>
                    </div>
                </div>

                <div class="no-data-wrapper" v-if="!getWarehouseCustomersLoading && warehouseCustomers.length == 0 && search !== ''">
                    <div class="no-data-heading">
                        <img
							src="@/assets/icons/empty-supplier-icon.svg"
							width="40px"
							height="42px"
							alt=""
						/>

                        <div>                           
                            <h3>No matching result </h3>
                            <p> Sorry! We could not find any warehouse customers that matches your search term. </p>
                        </div>
                    </div>
                </div>
			</template>
		</v-data-table>

		<InviteModal
			:dialog.sync="dialogEmail"
			:editedItems.sync="editedEmailItem"
			:editedIndex.sync="editedIndexEmail"
			:isMobile="isMobile"
			@close="closeEmail"
		/>

		<PaginationComponent 
            :totalPage.sync="getTotalPage"
            :currentPage.sync="getCurrentPage"
            @handlePageChange="handlePageChange"
            :isMobile="isMobile" />
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import SearchComponent from '../../../../SearchComponent/SearchComponent.vue'
import InviteModal from "@/components/SupplierComponents/InviteModal.vue"
import globalMethods from "@/utils/globalMethods"
// import ConfirmDialog from "@/components/Dialog/GlobalDialog/ConfirmDialog.vue"
import PaginationComponent from '../../../../PaginationComponent/PaginationComponent.vue'
import inventoryGlobalMethods from '@/utils/inventoryMethods/inventoryGlobalMethods'

import axios from 'axios'
var cancel
var CancelToken = axios.CancelToken

export default {
	name: "WarehouseCustomersMobileTable",
	props: ["items", "isMobile", "warehouseCustomersPagination", 'searchVal'],
	components: {
		SearchComponent,
		InviteModal,
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
				width: "25%",
				fixed: true,
			},
			{
				text: "Phone",
				align: "start",
				value: "phone",
				sortable: false,
				// width: "12%",
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
				width: "20%",
				fixed: true,
			},
			{
				text: "",
				align: "end",
				value: "actions",
				sortable: false,
				width: "15%",
				fixed: true,
			},
		],
		dialogEmail: false,
		editedIndexEmail: -1,
		editedEmailItem: {
			emails: [],
			info: {},
			customer_id: "",
			customer_admin_id: "",
			company_key: ""
		},
		defaultEmailItem: {
			emails: [],
			info: {},
			customer_id: "",
			customer_admin_id: "",
			company_key: ""
		},
		currentSelectedCustomer: null,
		dialogDeleteCustomer: false,
		dialogDisconnectCustomer: false,
		dialogCancelInvitation: false,
		typingTimeout: 0,
		warehouseCustomersNextPageLoading: false
	}),
	computed: {
		...mapGetters({
			getUser: "getUser",
			getWarehouseCustomersLoading: "warehouseCustomers/getWarehouseCustomersLoading",
			getWarehouseDeleteCustomersLoading: 'warehouseCustomers/getWarehouseDeleteCustomersLoading',
			getSearchedWarehouseCustomers: 'warehouseCustomers/getSearchedWarehouseCustomers',
			getSearchedWarehouseCustomersLoading: 'warehouseCustomers/getSearchedWarehouseCustomersLoading',
			getWarehouseCustomers: 'warehouseCustomers/getWarehouseCustomers',
		}),
		search: {
            get() {
                return this.searchVal
            },
            set(value) {
                this.$emit('update:searchVal', value)
            }
        },
		warehouseCustomers() {
			return this.items
		},
		getTotalPage: {
            get() {
				let totalPage = 1

                if (typeof this.getSearchedWarehouseCustomers !== 'undefined' && this.getSearchedWarehouseCustomers !== null) {
                    if (this.search !== '' && this.getSearchedWarehouseCustomers.tab === 'warehouse_customers') {
                        if (typeof this.getSearchedWarehouseCustomers.last_page !== 'undefined') {
                            totalPage = this.getSearchedWarehouseCustomers.last_page
                        }
                    } else {
                        if (typeof this.getWarehouseCustomers !== 'undefined' && this.getWarehouseCustomers !== null) {
                            if (typeof this.getWarehouseCustomers.last_page !== 'undefined') {
                                totalPage = this.getWarehouseCustomers.last_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getWarehouseCustomers !== 'undefined' && this.getWarehouseCustomers !== null) {
                        if (typeof this.getWarehouseCustomers.last_page !== 'undefined') {
                            totalPage = this.getWarehouseCustomers.last_page
                        }
                    }
                }

				return totalPage
            }
        },
        getCurrentPage: {
            get() {
                let currentPage = 1

                if (typeof this.getSearchedWarehouseCustomers !== 'undefined' && this.getSearchedWarehouseCustomers !== null) {
                    if (this.search !== '' && this.getSearchedWarehouseCustomers.tab === 'warehouse_customers') {
                        if (typeof this.getSearchedWarehouseCustomers.current_page !== 'undefined') {
                            currentPage = this.getSearchedWarehouseCustomers.current_page
                        }
                    } else {
                        if (typeof this.getWarehouseCustomers !== 'undefined' && this.getWarehouseCustomers !== null) {
                            if (typeof this.getWarehouseCustomers.current_page !== 'undefined') {
                                currentPage = this.getWarehouseCustomers.current_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getWarehouseCustomers !== 'undefined' && this.getWarehouseCustomers !== null) {
                        if (typeof this.getWarehouseCustomers.current_page !== 'undefined') {
                            currentPage = this.getWarehouseCustomers.current_page
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

                if (typeof this.getSearchedWarehouseCustomers !== 'undefined' && this.getSearchedWarehouseCustomers !== null) {
                    if (this.search !== '' && this.getSearchedWarehouseCustomers.tab === 'warehouse_customers') {
                        if (typeof this.getSearchedWarehouseCustomers.per_page !== 'undefined') {
                            itemsPerPage = this.getSearchedWarehouseCustomers.per_page
                        }
                    } else {
                        if (typeof this.getWarehouseCustomers !== 'undefined' && this.getWarehouseCustomers !== null) {
                            if (typeof this.getWarehouseCustomers.per_page !== 'undefined') {
                                itemsPerPage = this.getWarehouseCustomers.per_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getWarehouseCustomers !== 'undefined' && this.getWarehouseCustomers !== null) {
                        if (typeof this.getWarehouseCustomers.per_page !== 'undefined') {
                            itemsPerPage = this.getWarehouseCustomers.per_page
                        }
                    }
                }

				return itemsPerPage
            }
        },
		handleLoadingShow() {
            if (this.search === '') {
                return this.warehouseCustomersNextPageLoading
            } else {
                return this.getSearchedWarehouseCustomersLoading
            }
        },
		handleSearchComponent() {
            let isShow = true

            if (this.search == '' && this.warehouseCustomers.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.warehouseCustomers.length === 0) {
                isShow = true
            }

            return isShow
        }
	},
	methods: {
		...mapActions({
			fetchWarehouseCustomers: "warehouseCustomers/fetchWarehouseCustomers",
			deleteWarehouseCustomers: "warehouseCustomers/deleteWarehouseCustomers",
			fetchWarehouseCustomersSearched: 'warehouseCustomers/fetchWarehouseCustomersSearched',
			setSearchedWarehouseCustomerVal: 'warehouseCustomers/setSearchedWarehouseCustomerVal',
			setSearchedWarehouseCustomerLoading: 'warehouseCustomers/setSearchedWarehouseCustomerLoading',
		}),
		...globalMethods,
		...inventoryGlobalMethods,
		addWarehouseCustomer() {
			this.$emit("addWarehouseCustomer")
		},
		editWarehouseCustomer(item) {
			this.$emit("editWarehouseCustomer", item)
		},
		inviteCustomer(vendor) {
			this.dialogEmail = true
			this.editedPoIndex = -1
			this.editedEmailItem.info = vendor
			this.editedEmailItem.customer_id = vendor.id

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
		viewDetails(customer) {
			console.log(customer)
			// this.$router.push(`/contact/details?customer=${customer.id}`)
		},
		async handlePageChange(page) {
			this.handleScrollToTop()
			let parms = {
				id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id,
				page
			}

			if (this.search === '') {
				try {
					if (page !== this.getCurrentPage) {
						this.warehouseCustomersNextPageLoading = true
						await this.fetchWarehouseCustomers(parms)
						this.warehouseCustomersNextPageLoading = false
					}				
				} catch (e) {
					this.notificationError(e)
				}
			} else {
				let data = {
                    search: this.search,
                    page,
					cid: parms.id
                }

                this.handlePageSearched(data)
			}
		},
		clearSearched() {
			this.search = ''
			this.setSearchedWarehouseCustomerVal([])
		},
		handleSearch() {
			if (cancel !== undefined) {
                cancel()
            }
            clearTimeout(this.typingTimeout)
            this.typingTimeout = setTimeout(() => {
                let data = { 
                    search: this.search,
					cid: (typeof this.getUser=='string') ? JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id
                }  

                this.setSearchedWarehouseCustomerLoading(true)
                this.apiCall(data)
            }, 500)
		},
		apiCall(data) {
            if (data !== null && this.search !== '') {
                let passedData = {
                    method: "get",
                    url: `https://po.shifl.com/api/customer/${data.cid}/warehouse-customers`,
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: {
                        search: this.search,
                        page: 1
                    }
                }

                try {
                    passedData.tab = 'warehouse_customers'
                    this.fetchWarehouseCustomersSearched(passedData)
                } catch(e) {
                    this.notificationError(e)
                    this.setSearchedWarehouseCustomerLoading(false)
                    console.log(e, 'Search error')
                }
            } else {
                this.setSearchedWarehouseCustomerVal([])
            }
        },
		async handlePageSearched(data) {
			this.handleScrollToTop()
            if (data !== null && this.search !== '') {
                let passedData = {
					method: "get",
					url: `https://po.shifl.com/api/customer/${data.cid}/warehouse-customers`,
					cancelToken: new CancelToken(function executor(c) {
						cancel = c
					}),
					params: {
						search: this.search,
						page: data.page
					}
				}

				try {
					passedData.tab = 'warehouse_customers'

					if (data.page !== this.getCurrentPage) {
						this.fetchWarehouseCustomersSearched(passedData)
					}					
				} catch(e) {
					this.notificationError(e)
					this.setSearchedWarehouseCustomerLoading(false)
					console.log(e, 'Search error')
				}               
            } else {
                this.setSearchedWarehouseCustomerVal([])
            }
        },
		handleScrollToTop() {
            this.scrollTableToTop()
        },
		// deleting
		deleteCustomer(item) {
			this.dialogDeleteCustomer = true
			this.currentSelectedCustomer = item
			// console.log(item)
		},
		async deleteConfirm(id) {
			try {
				let parmsWarehouseCustomers = {
					id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id,
					page: this.warehouseCustomersPagination.current_page,
				}

				await this.deleteWarehouseCustomers(id)

				if (this.warehouseCustomers.length === 1 && this.getCurrentPage !== 1) {
					parmsWarehouseCustomers.page = this.getCurrentPage - 1
				}

				if (this.search !== '') {
					let passedData = {
						method: "get",
						url: `https://po.shifl.com/api/customer/${parmsWarehouseCustomers.id}/warehouse-customers`,
						params: {
							search: this.search,
							page: parmsWarehouseCustomers.page
						}
					}

					try {
						passedData.tab = 'warehouse_customers'
						await this.fetchWarehouseCustomersSearched(passedData)
						this.notificationCustom("Warehouse Customer has been deleted.")
						this.dialogDeleteCustomer = false

						parmsWarehouseCustomers.page = 1
						await this.fetchWarehouseCustomers(parmsWarehouseCustomers)
					} catch(e) {
						this.notificationError(e)
						this.setSearchedWarehouseCustomerLoading(false)
						console.log(e, 'Search error')
					}
				} else {
					await this.fetchWarehouseCustomers(parmsWarehouseCustomers)
					this.notificationCustom("Warehouse Customer has been deleted.")
					this.dialogDeleteCustomer = false
				}
			} catch(e) {
				this.notificationError(e)
				this.dialogDeleteCustomer = false
			}
		},
		closeDelete() {
			this.dialogDeleteCustomer = false
			this.currentSelectedCustomer = null
		},
		// disconnecting
		disconnectCustomer(item) {
			this.dialogDisconnectCustomer = true
			this.currentSelectedCustomer = item
			console.log(item)
		},
		disconnectConfirm() {
			console.log("confirm disconnection")
			// this.notificationCustom('Warehouse Customer has been disconnected')
		},
		closeDisconnect() {
			this.dialogDisconnectCustomer = false
			this.currentSelectedCustomer = null
		},
		// cancelling
		cancelInvitation(item) {
			this.dialogCancelInvitation = true
			this.currentSelectedCustomer = item
			console.log(item)
		},
		cancelInvitationConfirm() {
			console.log("confirm cancel invitation")
			// this.notificationCustom('Invitation has been cancelled')
		},
		closeCancelInvitation() {
			this.dialogCancelInvitation = false
			this.currentSelectedCustomer = null
		},
	},
	async mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "contact")
	},
}
</script>

<style lang="scss"></style>
