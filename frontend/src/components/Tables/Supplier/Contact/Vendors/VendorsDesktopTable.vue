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
			:items="suppliers"
			class="suppliers-page-table vendor elevation-1"
			v-bind:class="{
                'no-data-table' : (typeof suppliers !== 'undefined' && suppliers.length === 0),
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
					<v-toolbar-title>Vendors</v-toolbar-title>

					<v-spacer></v-spacer>

					<SearchComponent
						placeholder="Search Vendor"
						:searchValue.sync="search"
						:handleSearchComponent="handleSearchComponent"
						@handleSearch="handleSearch"
						@clearSearched="clearSearched" />

					<v-btn
						color="primary"
						class="btn-blue add-supplier ml-2"
						@click.stop="addSupplier">
						+ Add Vendor
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

			<template v-slot:[`item.type`]="{ item }">
				<!-- <p class="mb-0">{{ typeof item.type !== 'undefined' && item.type !== '' ? item.type : '--' }}</p> -->
				<p class="mb-0" :class="item.type">Supplier</p>
			</template>

			<template v-slot:[`item.address`]="{ item }">
				<p class="mb-0">{{ item.address !== "" ? item.address : "--" }}</p>
			</template>

			<template v-slot:[`item.actions`]="{ item }">
				<div class="item-button mr-1">
					<!-- <button class="btn-white invite" @click.stop="inviteVendor(item)">
						<img
							src="@/assets/icons/invite-arrow.svg"
							height="14px"
							width="14px"
						/>
						<p>Invite to Shifl</p>
					</button> -->

					<!-- To dos  -->

					<!-- UNCOMMENT BELOW IF VENDOR IS ALREADY CONNECTED -->
					<!-- <button class="btn-white invite connected">
						<img src="@/assets/icons/checkMark.png" height="14px" width="14px">
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

					<v-menu bottom left offset-y content-class="outbound-lists-menu">
						<template v-slot:activator="{ on, attrs }">
							<v-btn class="btn-white" icon v-bind="attrs" v-on="on">
								<v-icon>mdi-dots-horizontal</v-icon>
							</v-btn>
						</template>

						<v-list class="outbound-lists">
							<v-list-item @click="editSupplier(item)">
								<v-list-item-title style="color: #0171A1;">
									Edit
								</v-list-item-title>
							</v-list-item>

							<!-- ONLY SHOW IF THE VENDOR IS ON PENDING INVITATION ONLY -->
							<!-- <v-list-item @click="cancelInvitation(item)">
								<v-list-item-title class="cancel">
									Cancel Invitation
								</v-list-item-title>
							</v-list-item> -->

							<!-- SHOW IF THE VENDOR HAS ALREADY BEEN INVITED -->
							<!-- <v-list-item @click="disconnectVendor(item)">
								<v-list-item-title class="cancel">
									Disconnect
								</v-list-item-title>
							</v-list-item> -->

							<v-list-item @click="deleteVendor(item)">
								<v-list-item-title class="cancel">
									Delete
								</v-list-item-title>
							</v-list-item>
						</v-list>
					</v-menu>
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

		<InviteModal
			:dialog.sync="dialogEmail"
			:editedItems.sync="editedEmailItem"
			:editedIndex.sync="editedIndexEmail"
			:isMobile="isMobile"
			@close="closeEmail"
			:isVendor="true" />

		<!-- For Deleting Vendor -->
		<ConfirmDialog :dialogData.sync="dialogDeleteVendor">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert">
				</div>
			</template>
			
			<template v-slot:dialog_title>
				<h2>Delete Vendor</h2>
			</template>

			<template v-slot:dialog_content>
				<p>
					Do you want to delete
					<span class="name">
						{{
							currentSelectedVendor !== null ? currentSelectedVendor.name : ""
						}}
					</span>
					as your vendor? Once deleted, you can’t select them in any new
					purchase order. But your previous orders with them will remain
					unchanged.
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn
					class="btn-blue"
					@click="deleteConfirm(currentSelectedVendor.id)"
					text
					:disabled="getDeleteSuppliersLoading">
					<span v-if="!getDeleteSuppliersLoading">Delete</span>
					<span v-if="getDeleteSuppliersLoading">Deleting...</span>
				</v-btn>

				<v-btn class="btn-white" text @click="closeDelete" :disabled="getDeleteSuppliersLoading">
					Cancel
				</v-btn>
			</template>
		</ConfirmDialog>

		<!-- For Disconnecting Vendor -->
		<ConfirmDialog :dialogData.sync="dialogDisconnectVendor">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert">
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Disconnect Vendor</h2>
			</template>

			<template v-slot:dialog_content>
				<p>
					Do you want to disconnect
					<span class="name">
						{{
							currentSelectedVendor !== null ? currentSelectedVendor.name : ""
						}}
					</span>
					as your vendor? Once disconnected, they will no longer receive the
					purchase orders automatically.
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
					<span class="name">
						{{
							currentSelectedVendor !== null ? currentSelectedVendor.name : ""
						}} </span
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
import ConfirmDialog from "@/components/Dialog/GlobalDialog/ConfirmDialog.vue"
import globalMethods from "@/utils/globalMethods"
import PaginationComponent from '../../../../PaginationComponent/PaginationComponent.vue'
import inventoryGlobalMethods from '@/utils/inventoryMethods/inventoryGlobalMethods'

import axios from 'axios'
var cancel
var CancelToken = axios.CancelToken

export default {
	name: "VendorsDesktopTable",
	props: ["items", "isMobile", 'suppliersPagination', 'searchVal'],
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
				// width: "22%",
				width: "25%",
				fixed: true,
			},
			// uncomment all width once api is finalized.
			{
				text: "Type",
				align: "start",
				sortable: false,
				value: "type",
				// width: "8%",
				width: "10%",
				fixed: true,
			},
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
				width: "25%",
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
			return this.editedIndex === -1 ? "Add Supplier" : "Edit Supplier"
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
			return this.items
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
			// console.log(item)
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

<style lang="scss">
@import "../../../../../assets/scss/pages_scss/supplier/vendorTable.scss";
</style>
