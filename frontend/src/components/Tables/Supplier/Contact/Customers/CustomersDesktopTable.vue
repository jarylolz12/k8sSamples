<!-- @format -->

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
			:items="customers"
			class="suppliers-page-table customer elevation-1"
			v-bind:class="{
                'no-data-table' : (typeof customers !== 'undefined' && customers.length === 0),
                'no-current-pagination' : (getTotalPage <= 1),
            }"
			:page.sync="page"
			:items-per-page="itemsPerPage"
			@page-count="pageCount = $event"
			hide-default-footer
			fixed-header
			ref="my-table">
			<!-- @click:row="viewDetails" -->

			<template v-slot:top>
				<v-toolbar flat>
					<v-toolbar-title>Buyers</v-toolbar-title>

					<v-spacer></v-spacer>

					<SearchComponent
						placeholder="Search Buyers"
						:searchValue.sync="search"
						:handleSearchComponent="handleSearchComponent"
						@handleSearch="handleSearch"
						@clearSearched="clearSearched" />

					<v-btn
						color="primary"
						class="btn-blue add-supplier ml-2"
						@click.stop="addCustomer">
						+ Add Buyers
					</v-btn>
				</v-toolbar>
			</template>

			<template v-slot:[`item.name`]="{ item }">
				<p class="mb-0" style="color: #0171A1;">
					{{ item.name !== "" ? item.name : "--" }}
				</p>
			</template>

			<template v-slot:[`item.emails`]="{ item }">
				<div class="email-wrapper" v-if="item.emails !== ''">
					<div
						class="email-container"
						v-for="(email, index) in item.emails"
						:key="index">
						<p class="mb-0" v-if="!(email instanceof Object)">{{ email }}</p>
						<div v-else
							v-for="(emailValue, emailKey) in email"
							:key="emailKey">
							<p class="mb-0">{{ emailValue }}</p>
						</div>
					</div>
				</div>

				<div v-if="item.emails == ''">
					<p class="mb-0">--</p>
				</div>
			</template>

			<template v-slot:[`item.phone`]="{ item }">
				<p class="mb-0">{{ item.phone !== "" ? item.phone : "--" }}</p>
			</template>

			<template v-slot:[`item.address`]="{ item }">
				<p class="mb-0">{{ item.address !== "" ? item.address : "--" }}</p>
			</template>

			<template v-slot:[`item.actions`]="{ item }">
				<div class="item-button mr-1">
					<!-- <button class="btn-white invite" @click.stop="inviteCustomer(item)">
						<img
							src="@/assets/icons/invite-arrow.svg"
							height="14px"
							width="14px"
						/>
						<p>Invite to Shifl</p>
					</button> -->

					<!-- To dos  -->

					<!-- UNCOMMENT BELOW IF VENDOR IS ALREADY CONNECTED -->
					<!-- <button class="btn-white invite connected" >
						<img
							src="@/assets/icons/checkMark.png"
							height="14px"
							width="14px"
						/>
						<p>Connected</p>
					</button> -->

					<!-- UNCOMMENT BELOW IF VENDOR IS ALREADY INVITED -->
					<!-- <v-menu offset-y content-class="outbound-lists-menu">
						<template v-slot:activator="{ on, attrs }">
							<v-btn text class=" invite btn-invited"  v-bind="attrs" v-on="on">
								<img src="@/assets/icons/clock.svg" >
								<p class="btn-text">Invited</p>
							</v-btn>
						</template>

						<v-list class="outbound-lists">
							<v-list-item @click="cancelInvitation(item)">
								<v-list-item-title style="color: #0171A1;">
									Cancel Invitation
								</v-list-item-title>
							</v-list-item>
						</v-list>
					</v-menu> -->

					<v-menu bottom offset-y left content-class="outbound-lists-menu">
						<template v-slot:activator="{ on, attrs }">
							<v-btn class="btn-white" icon v-bind="attrs" v-on="on">
								<v-icon>mdi-dots-horizontal</v-icon>
							</v-btn>
						</template>

						<v-list class="outbound-lists">
							<v-list-item @click="editCustomer(item)">
								<v-list-item-title style="color: #0171A1;">
									Edit
								</v-list-item-title>
							</v-list-item>

							<!-- ONLY SHOW IF THE CUSTOMER IS ON PENDING INVITATION ONLY -->
							<!-- <v-list-item @click="cancelInvitation(item)">
								<v-list-item-title class="cancel">
									Cancel Invitation
								</v-list-item-title>
							</v-list-item> -->

							<!-- SHOW IF THE CUSTOMER HAS ALREADY BEEN INVITED -->
							<!-- <v-list-item @click="disconnectCustomer(item)">
								<v-list-item-title class="cancel">
									Disconnect
								</v-list-item-title>
							</v-list-item> -->

							<v-list-item @click="deleteCustomer(item)">
								<v-list-item-title class="cancel">
									Delete
								</v-list-item-title>
							</v-list-item>
						</v-list>
					</v-menu>
				</div>
			</template>

			<template v-slot:no-data>
				<div class="no-data-preloader mt-4" v-if="getCustomersLoading">
					<v-progress-circular
						:size="40"
						color="#0171a1"
						indeterminate
						v-if="getCustomersLoading">
					</v-progress-circular>
				</div>

				<div class="no-data-wrapper" v-if="!getCustomersLoading && customers.length == 0 && search === ''">
                    <div class="no-data-heading">
                        <img
							src="@/assets/icons/empty-supplier-icon.svg"
							width="40px"
							height="42px"
							alt=""
						/>

                        <h3>Add Buyers</h3>
						<p>
							You have not added any buyer yet. Let’s add your first buyer to Shifl platform.
						</p>

                        <div class="supplier-button-action-wrapper">
							<v-btn
								color="primary"
								class="btn-white add-supplier"
								@click.stop="addCustomer">
								Add Buyer
							</v-btn>
						</div>
                    </div>
                </div>

                <div class="no-data-wrapper" v-if="!getCustomersLoading && customers.length == 0 && search !== ''">
                    <div class="no-data-heading">
                        <img
							src="@/assets/icons/empty-supplier-icon.svg"
							width="40px"
							height="42px"
							alt=""
						/>

                        <div>                           
                            <h3>No matching result </h3>
                            <p> Sorry! We could not find any buyers that matches your search term. </p>
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
			:isVendor="false" />

		<PaginationComponent 
            :totalPage.sync="getTotalPage"
            :currentPage.sync="getCurrentPage"
            @handlePageChange="handlePageChange"
            :isMobile="isMobile" />
		
		<!-- For Deleting Customer -->
		<ConfirmDialog :dialogData.sync="dialogDeleteCustomer">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
                    <img src="@/assets/icons/icon-delete.svg" alt="alert">
                </div>
			</template>

			<template v-slot:dialog_title>
				<h2>Delete Buyer</h2>
			</template>

			<template v-slot:dialog_content>
				<p>
					Do you want to delete
					<span class="name">
						{{
							currentSelectedCustomer !== null
							? currentSelectedCustomer.name
							: ""
						}}
					</span>
					as your buyer? Once deleted, you can’t select them in any new sales
					order. But your previous orders with them will remain unchanged.
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn
					class="btn-blue"
					@click="deleteConfirm(currentSelectedCustomer.id)"
					text
					:disabled="getDeleteCustomersLoading">
					<span v-if="!getDeleteCustomersLoading">Delete</span>
					<span v-if="getDeleteCustomersLoading">Deleting...</span>
				</v-btn>

				<v-btn class="btn-white" text @click="closeDelete" :disabled="getDeleteCustomersLoading">
					Cancel
				</v-btn>
			</template>
		</ConfirmDialog>

		<!-- For Disconnecting Customer -->
		<ConfirmDialog :dialogData.sync="dialogDisconnectCustomer">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
                    <img src="@/assets/icons/icon-delete.svg" alt="alert">
                </div>
			</template>

			<template v-slot:dialog_title>
				<h2>Disconnect Buyer</h2>
			</template>

			<template v-slot:dialog_content>
				<p>
					Do you want to disconnect
					<span class="name">
						{{
							currentSelectedCustomer !== null
							? currentSelectedCustomer.name
							: ""
						}}
					</span>
					as your buyer? Once disconnected, they will no longer receive the
					sales orders automatically.
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="disconnectConfirm" text>
					<span>Disconnect</span>
				</v-btn>

				<v-btn class="btn-white" text @click="closeDisconnect">
					Cancel
				</v-btn>
			</template>
		</ConfirmDialog>

		<!-- For Cancelling Invitation -->
		<ConfirmDialog :dialogData.sync="dialogCancelInvitation">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
                    <img src="@/assets/icons/icon-delete.svg" alt="alert">
                </div>
			</template>

			<template v-slot:dialog_title>
				<h2>Cancel Invitation</h2>
			</template>

			<template v-slot:dialog_content>
				<p>
					Do you want to cancel the invitation sent to
					<span class="name">{{
						currentSelectedCustomer !== null ? currentSelectedCustomer.name : ""
					}}</span
					>?
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="cancelInvitationConfirm" text>
					<span>Cancel Invitation</span>
				</v-btn>

				<v-btn class="btn-white" text @click="closeCancelInvitation">
					Cancel
				</v-btn>
			</template>
		</ConfirmDialog>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import SearchComponent from '../../../../SearchComponent/SearchComponent.vue'
import InviteModal from "@/components/SupplierComponents/InviteModal.vue";
import globalMethods from "@/utils/globalMethods";
import ConfirmDialog from "@/components/Dialog/GlobalDialog/ConfirmDialog.vue";
import PaginationComponent from '../../../../PaginationComponent/PaginationComponent.vue'
import inventoryGlobalMethods from '@/utils/inventoryMethods/inventoryGlobalMethods'

import axios from 'axios'
var cancel;
var CancelToken = axios.CancelToken;

export default {
	name: "CustomersDesktopTable",
	props: ["items", "isMobile", "customerPagination", 'searchVal'],
	components: {
		SearchComponent,
		InviteModal,
		ConfirmDialog,
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
			isSupplier: true,
		},
		defaultEmailItem: {
			emails: [],
			info: {},
			customer_id: "",
			customer_admin_id: "",
			isSupplier: true,
		},
		currentSelectedCustomer: null,
		dialogDeleteCustomer: false,
		dialogDisconnectCustomer: false,
		dialogCancelInvitation: false,
		typingTimeout: 0,
		customersNextPageLoading: false
	}),
	computed: {
		...mapGetters({
			getCustomersLoading: "customers/getCustomersLoading",
			getDeleteCustomersLoading: 'customers/getDeleteCustomersLoading',
			getSearchedCustomer: 'customers/getSearchedCustomer',
			getSearchedCustomerLoading: 'customers/getSearchedCustomerLoading',
			getCustomers: 'customers/getCustomers'
		}),
		formTitle() {
			return this.editedIndex === -1 ? "Add Customer" : "Edit Customer";
		},
		search: {
            get() {
                return this.searchVal
            },
            set(value) {
                this.$emit('update:searchVal', value)
            }
        },
		customers() {
			return this.items;
		},
		getTotalPage: {
            get() {
				let totalPage = 1

                if (typeof this.getSearchedCustomer !== 'undefined' && this.getSearchedCustomer !== null) {
                    if (this.search !== '' && this.getSearchedCustomer.tab === 'customers') {
                        if (typeof this.getSearchedCustomer.last_page !== 'undefined') {
                            totalPage = this.getSearchedCustomer.last_page
                        }
                    } else {
                        if (typeof this.getCustomers !== 'undefined' && this.getCustomers !== null) {
                            if (typeof this.getCustomers.last_page !== 'undefined') {
                                totalPage = this.getCustomers.last_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getCustomers !== 'undefined' && this.getCustomers !== null) {
                        if (typeof this.getCustomers.last_page !== 'undefined') {
                            totalPage = this.getCustomers.last_page
                        }
                    }
                }

				return totalPage
            }
        },
        getCurrentPage: {
            get() {
                let currentPage = 1

                if (typeof this.getSearchedCustomer !== 'undefined' && this.getSearchedCustomer !== null) {
                    if (this.search !== '' && this.getSearchedCustomer.tab === 'customers') {
                        if (typeof this.getSearchedCustomer.current_page !== 'undefined') {
                            currentPage = this.getSearchedCustomer.current_page
                        }
                    } else {
                        if (typeof this.getCustomers !== 'undefined' && this.getCustomers !== null) {
                            if (typeof this.getCustomers.current_page !== 'undefined') {
                                currentPage = this.getCustomers.current_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getCustomers !== 'undefined' && this.getCustomers !== null) {
                        if (typeof this.getCustomers.current_page !== 'undefined') {
                            currentPage = this.getCustomers.current_page
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

                if (typeof this.getSearchedCustomer !== 'undefined' && this.getSearchedCustomer !== null) {
                    if (this.search !== '' && this.getSearchedCustomer.tab === 'customers') {
                        if (typeof this.getSearchedCustomer.per_page !== 'undefined') {
                            itemsPerPage = this.getSearchedCustomer.per_page
                        }
                    } else {
                        if (typeof this.getCustomers !== 'undefined' && this.getCustomers !== null) {
                            if (typeof this.getCustomers.per_page !== 'undefined') {
                                itemsPerPage = this.getCustomers.per_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getCustomers !== 'undefined' && this.getCustomers !== null) {
                        if (typeof this.getCustomers.per_page !== 'undefined') {
                            itemsPerPage = this.getCustomers.per_page
                        }
                    }
                }

				return itemsPerPage
            }
        },
		handleLoadingShow() {
            if (this.search === '') {
                return this.customersNextPageLoading
            } else {
                return this.getSearchedCustomerLoading
            }
        },
		handleSearchComponent() {
            let isShow = true

            if (this.search == '' && this.customers.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.customers.length === 0) {
                isShow = true
            }

            return isShow
        }
	},
	methods: {
		...mapActions({
			fetchCustomers: "customers/fetchCustomers",
			deleteCustomers: "customers/deleteCustomers",
			fetchSearchedCustomers: 'customers/fetchSearchedCustomers',
			setSearchedCustomerVal: 'customers/setSearchedCustomerVal',
			setSearchedCustomerLoading: 'customers/setSearchedCustomerLoading'
		}),
		...globalMethods,
		...inventoryGlobalMethods,
		addCustomer() {
			this.$emit("addCustomer")
		},
		editCustomer(item) {
			this.$emit("editCustomer", item)
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
			if (this.search === '') {
				let parms = {
					itemsPerPage: this.customerPagination.per_page,
					pageNumber: page,
				}

				try {
					if (page !== this.getCurrentPage) {
						this.customersNextPageLoading = true
						await this.fetchCustomers(parms)
						this.customersNextPageLoading = false
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
			this.setSearchedCustomerVal([])
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

                this.setSearchedCustomerLoading(true)
                this.apiCall(data)
            }, 500)
		},
		apiCall(data) {
            if (data !== null && this.search !== '') {
                let passedData = {
                    method: "get",
                    url: 'https://beta.shifl.com/api/v2/buyers',
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: {
                        qry: this.search,
                        page: 1,
                    }
                }

                try {
                    passedData.tab = 'customers'
                    this.fetchSearchedCustomers(passedData)
                } catch(e) {
                    this.notificationError(e)
                    this.setSearchedCustomerLoading(false)
                    console.log(e, 'Search error')
                }
            } else {
                this.setSearchedCustomerVal([])
            }
        },
		async handlePageSearched(data) {
			this.handleScrollToTop()
            if (data !== null && this.search !== '') {
                let passedData = {
					method: "get",
					url: 'https://beta.shifl.com/api/v2/buyers',
					cancelToken: new CancelToken(function executor(c) {
						cancel = c
					}),
					params: {
						qry: this.search,
						page: data.page,
					}
				}

				try {
					passedData.tab = 'customers'

					if (data.page !== this.getCurrentPage) {
						this.fetchSearchedCustomers(passedData)
					}					
				} catch(e) {
					this.notificationError(e)
					this.setSearchedCustomerLoading(false)
					console.log(e, 'Search error')
				}               
            } else {
                this.setSearchedCustomerVal([])
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
				let parms = {
					itemsPerPage: this.customerPagination.per_page,
					pageNumber: this.customerPagination.current_page,
				}

				await this.deleteCustomers(id)

				if (this.customers.length === 1 && this.getCurrentPage !== 1) {
					parms.pageNumber = this.getCurrentPage - 1
				}

				if (this.search !== '') {
					let passedData = {
						method: "get",
						url: 'https://beta.shifl.com/api/v2/buyers',
						params: {
							qry: this.search,
							page: parms.pageNumber
						}
					}

					try {
						passedData.tab = 'customers'
						await this.fetchSearchedCustomers(passedData)
						this.notificationCustom("Buyer has been deleted.")
						this.dialogDeleteCustomer = false

						parms.pageNumber = 1
						await this.fetchCustomers(parms)
					} catch(e) {
						this.notificationError(e)
						this.setSearchedCustomerLoading(false)
						console.log(e, 'Search error')
					}
				} else {
					await this.fetchCustomers(parms)
					this.notificationCustom("Buyer has been deleted.")
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
			// this.notificationCustom('Buyer has been disconnected')
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
