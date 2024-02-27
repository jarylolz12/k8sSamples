<template>
	<div class="Outbounds-desktop-wrapper">
		<div class="overlay" :class="getCurrentLoadingToDisplay ? 'show' : ''">  
            <div class="preloader" v-if="(getCurrentLoadingToDisplay)">
                <v-progress-circular
                    :size="40"
                    color="#0171a1"
                    indeterminate>
                </v-progress-circular>
            </div>       
        </div>
		<v-data-table
			:headers="_header"
			:items="currentWarehouseOutbounds.data"
			:page.sync="page"
			:items-per-page="itemsPerPage"
			@page-count="pageCount = $event"
			item-key="order_id"
			mobile-breakpoint="769"
			class="inventory-table outbound-table elevation-1"
			v-bind:class="{
                'no-data-table': (typeof currentWarehouseOutbounds.data !== 'undefined' && currentWarehouseOutbounds.data.length === 0),
                'no-current-pagination' : (getTotalPage <= 1),
                'type-3pl' : currentWarehouseSelected.warehouse_type_id === 3,
				'type-3pl-service-provider-connected' : isWarehouse3PLProvider == true
            }"
			hide-default-footer
			fixed-header
			show-select
			ref="my-table"
			@click:row="viewOutbound"
			v-model="selectedCancelled">
			<template v-slot:top>
				<div class="inventory-search-wrapper outbound-search">
					<v-tabs
						class="inventory-inner-tab"
						@change="onTabChange"
						v-model="currentTab">

						<v-tab
							v-for="(tab, index) in tabsComputed"
							:key="index"
							:class="conditionForFloorTab(tab) ? `${tab}` : `paddingZero ${tab}`"
							@click="onClickTab(index)">
							
							<span v-if="conditionForFloorTab(tab)">{{ tab }}</span>
							<span v-else>{{ tab }}</span>
							<span v-if="isWarehouseConnected || isWarehouse3PLProvider">
								<small v-if="(index == 0 || index == 1 || index == 2) && getTabCount(index) !== '0'">{{ getTabCount(index) }}</small>
							</span>	
							<span v-if="!isWarehouseConnected && !isWarehouse3PLProvider">	
								<small v-if="(index == 0 || index == 1) && getTabCount(index) !== '0'">{{ getTabCount(index) }}</small>
							</span>
							
						</v-tab>
					</v-tabs>

					<v-spacer></v-spacer>
					<!-- v-click-outside="onClickOutsideWithConditional" -->
					<div :class="isWarehouse3PLProvider ==true && isWarehouseConnected == false ? 'search-and-filter d-flex align-center' : 'search-and-filter'" 
						v-if="creareSearchAndFilterButtonShow">
						<div class="filter-tags-input"  
                            v-if="(isWarehouse3PLProvider ==true && isWarehouseConnected == false) && handleFilterComponent">
							<FilterCustomers 
                                @onClickFilter="onClickFilter"
                                @filterAllWarehouseCustomers="filtermultipleCustomer"
                                @cancelSelectingWarehouseCustomers="cancelfiltermultipleCustomer"
                                @searchWarehouseCustomers="searchWarehouseCustomers"
                                @removeCustomerLists="removeCustomerLists"
                                @removeCustomerListsEmptyOnChange="removeCustomerListsEmptyOnChange"
                                :searchCustomerData.sync="searchCustomerData"
                                :menu.sync="filterMenu"
                                :selectedWhCustomersCopy.sync="SearchCustomerFinalArrayCopy"
								:selectedWhCustomers.sync="SearchCustomerFinalArray"
                                :warehouseCustomerLists.sync="warehouseCustomerLists"
                                :loading="fetchWarehouseCustomersLoading"
								@clickOutside="clickOutsideFilter"
                                :isActiveClicked.sync="isActiveClicked"
								@clearAllFilter="clearAllFilter"
                            />
                        </div>
							<SearchComponent
                            placeholder="Search Outbound"
                            :searchValue.sync="search"
                            :handleSearchComponent="handleSearchComponent"
                            @handleSearch="handleSearch"
                            @clearSearched="clearSearched" />

						<v-btn
							color="primary"
							dark
							class="btn-blue ml-2"
							@click.stop="createOutbound">
							Create Outbound
						</v-btn>
					</div>

					<div class="search-and-filter"
						v-if="CLearAndCancelledButtonShow">

						<button class="mx-2">
							<span class="btn-clear clearSelection" @click="clearCancelation">
								Clear Selection
							</span>
						</button>

						<v-btn
							small
							@click="cancelMultipleOrder"
							class="Cancelled-btn ml-2">
							Cancel Order
						</v-btn>
					</div>
				</div>
			</template>

			<template v-slot:[`item.name`]="{ item }">
				<div class="inventory-wrapper">
					<div>
						{{
							typeof item.name !== "undefined" &&
							item.name !== null &&
							item.name !== "null"
							? item.name
							: "--"
						}}
					</div>
				</div>
			</template>
			
			<template v-slot:[`item.created_at`]="{ item }">
				<div class="inventory-wrapper">
					<div>
						{{
							formatCreatedAt(item.created_at)
						}}
					</div>
				</div>
			</template>
			// 3pl 
			<template v-slot:[`item.updated_at`]="{ item }">
				<div class="inventory-wrapper">
					<div>
						{{
							formatCreatedAt(item.updated_at)
						}}
					</div>
				</div>
			</template>
			
			<template v-slot:[`item.customer`]="{ item }">
				<div class="inventory-wrapper">
					<div>
						{{
							typeof item.customer !== "undefined" &&
							item.customer !== null &&
							item.customer !== "null"
							? item.customer
							: "--"
						}}
					</div>
				</div>
			</template>

			<template v-slot:[`item.ref_no`]="{ item }">
				<div class="inventory-wrapper">
					<div>
						{{
							typeof item.ref_no !== "undefined" &&
							item.ref_no !== null &&
							item.ref_no !== "null"
							? item.ref_no
							: "--"
						}}
					</div>
				</div>
			</template>
			

			<template  v-slot:[`item.outbound_status`]="{ item }">
				<div class="status">
					<span :class="item.outbound_status">{{ item.outbound_status }}</span>
				</div>
			</template>
			
			<template  v-slot:[`item.status`]="{ item }">
				<div v-if="currentTab !== 4" class="status">
					<span :class="item.status">{{ item.status !== null && item.status == 'pending-pick' ? 'Pending Pick' : item.status  }}</span>
				</div>
				<div v-else class="status">
					<span style="background-color: #FFF2F2 !important;color:#F93131 !important" :class="item.outbound_status">{{ item.outbound_status  }}</span>
				</div>
			</template>

			<template v-slot:[`item.estimated_time`]="{ item }">
				<div class="estimated_time">
					<span>{{ formatNewDate(item.estimated_date, item.estimated_time) }}</span>
				</div>
			</template>
			<template v-slot:[`item.warehouse_customer`]="{ item }">
                <span>
                    {{ item.warehouse_customer !== null && item.warehouse_customer !==undefined && item.warehouse_customer !=='undefined'  ? item.warehouse_customer.company_name : '--' }}
                </span>
            </template>
			<template v-slot:[`item.customer_admin_name`]="{ item }">
				<div>
					<span>
                    {{ item.customer_admin_name !== null && item.customer_admin_name !==undefined && item.customer_admin_name !=='undefined'  ? item.customer_admin_name : '--' }}
                </span>
				</div>
					<span style="color: #6D858F;">{{ formatCreatedAt(item.created_at) }} </span>
            </template>
			<template v-slot:[`item.updated_customer_admin_name`]="{ item }">
				<div>
					<span>
                    {{ item.updated_customer_admin_name !== null && item.updated_customer_admin_name !==undefined && item.updated_customer_admin_name !=='undefined'  ? item.updated_customer_admin_name : '--' }}
                </span>
				</div>
					<span style="color: #6D858F;">{{ formatCreatedAt(item.created_at) }} </span>
            </template>

			<template v-slot:[`item.actions`]="{ item }">
				<div class="actions" v-if="!isWarehouseConnected">
					<button class="btn-edit inventory-btn-edit mr-2"
						style="border: 1px solid #16B442;color: #16B442;" 
                        @click.stop="acceptConnectedWarehouseOrder(item)" v-if="currentTab === 0 && isWarehouse3PLProvider && item.is_from_connected_3pl === 1">
						Accept
                    </button>
					<button class="btn-edit inventory-btn-edit mr-2" 
                        @click.stop="rejectConnectedWarehouseOrder(item)" v-if="currentTab === 0 && isWarehouse3PLProvider && item.is_from_connected_3pl === 1">
						<v-icon color="red" >mdi-close</v-icon>
                    </button>
					<v-menu bottom left content-class="outbound-lists-menu" offset-y>
						<template v-slot:activator="{ on, attrs }">
							<v-btn
								v-show="ActionButtonShow(item.order_id)"
								icon
								v-bind="attrs"
								v-on="on" >
								<v-icon>mdi-dots-horizontal</v-icon>
							</v-btn>
						</template>

						<v-list class="outbound-lists" v-if="currentTab == 0">
							<v-list-item class="" v-if="isWarehouse3PL ==true && item.outbound_status =='pending'" @click="loadAllProducts3PL(item)">
								<v-list-item-title class="green--text">
									<img src="@/assets/icons/checkMark.png" height="16px" width="16px" class="mr-2">
									All Loaded </v-list-item-title>
							</v-list-item>
							<v-list-item @click="viewOutbound(item)">
								<v-list-item-title> View </v-list-item-title>
							</v-list-item>

							<v-list-item @click="editOrder(item, 'edit')" v-if="item.is_from_connected_3pl !== 1">
								<v-list-item-title> Edit </v-list-item-title>
							</v-list-item>

							<!-- <v-list-item @click="editOrder(item)">
								<v-list-item-title> Create Work Order </v-list-item-title>
							</v-list-item> -->

							<!-- <v-list-item @click="arrangeTracking(item)">
								<v-list-item-title class="arrange-trucking">
								Arrange Tracking
								</v-list-item-title>
							</v-list-item> -->

							<v-list-item @click="editOrder(item, 'copy')" v-if="item.is_from_connected_3pl !== 1">
								<v-list-item-title> Duplicate </v-list-item-title>
							</v-list-item>

							<v-list-item @click="printOrder(item)">
								<v-list-item-title> Print Order </v-list-item-title>
							</v-list-item>

							<v-list-item v-if="showCancelButton(item) && item.is_from_connected_3pl !== 1"  @click="cancel(item)">
								<v-list-item-title class="cancel">
								Cancel Order
								</v-list-item-title>
							</v-list-item>

							<!-- <v-list-item @click="canceldelete(item)">
								<v-list-item-title class="cancel">
								Delete Order
								</v-list-item-title>
							</v-list-item> -->
						</v-list>

						<v-list class="outbound-lists" v-if="currentTab == 1">
							<v-list-item @click="viewOutbound(item)">
								<v-list-item-title> View </v-list-item-title>
							</v-list-item>

							<!-- <v-list-item @click="viewOutbound(item)">
								<v-list-item-title> Create Work Order </v-list-item-title>
							</v-list-item> -->
							<v-list-item @click="editOrder(item, 'edit')" v-if="item.is_from_connected_3pl !== 1">
								<v-list-item-title> Edit </v-list-item-title>
							</v-list-item>

							<v-list-item @click="editOrder(item, 'copy')" v-if="item.is_from_connected_3pl !== 1">
								<v-list-item-title> Duplicate </v-list-item-title>
							</v-list-item>

							<v-list-item @click="printOrder(item)">
								<v-list-item-title> Print Order </v-list-item-title>
							</v-list-item>

							<v-list-item v-if="showCancelButton(item) && item.is_from_connected_3pl !== 1" @click="cancel(item)">
								<v-list-item-title class="cancel"> Cancel Order </v-list-item-title>
							</v-list-item>
						</v-list>

						<v-list class="outbound-lists" v-if="currentTab == 2">
							<v-list-item @click="viewOutbound(item)">
								<v-list-item-title> View </v-list-item-title>
							</v-list-item>
							<v-list-item v-if="isWarehouse3PL ==true" @click="checkEditInbound(item, 'edit')">
								<v-list-item-title> Edit </v-list-item-title>
							</v-list-item>
							<v-list-item @click="editOrder(item, 'copy')" v-if="item.is_from_connected_3pl !== 1">
								<v-list-item-title> Duplicate </v-list-item-title>
							</v-list-item>

							<v-list-item @click="printOrder(item)">
								<v-list-item-title> Print Order </v-list-item-title>
							</v-list-item>
							<v-list-item v-if="showCancelButton(item) && isWarehouse3PLProvider == true && item.is_from_connected_3pl !== 1" @click="cancel(item)">
								<v-list-item-title class="cancel"> Cancel Order </v-list-item-title>
							</v-list-item>
						</v-list>

						<v-list class="outbound-lists" v-if="currentTab == 3">
							<v-list-item @click="viewOutbound(item)">
								<v-list-item-title> View </v-list-item-title>
							</v-list-item>

							<v-list-item @click="editOrder(item, 'copy')" v-if="item.is_from_connected_3pl !== 1">
								<v-list-item-title> Duplicate </v-list-item-title>
							</v-list-item>

							<v-list-item @click="printOrder(item)">
								<v-list-item-title> Print Order </v-list-item-title>
							</v-list-item>
						</v-list>
						<v-list class="outbound-lists" v-if="currentTab == 4 && isWarehouse3PLProvider && !isWarehouseConnected">
							<v-list-item @click="viewOutbound(item)">
								<v-list-item-title> View </v-list-item-title>
							</v-list-item>

							<v-list-item @click="editOrder(item, 'copy')" v-if="item.is_from_connected_3pl !== 1">
								<v-list-item-title> Duplicate </v-list-item-title>
							</v-list-item>

							<v-list-item @click="printOrder(item)">
								<v-list-item-title> Print Order </v-list-item-title>
							</v-list-item>
						</v-list>
					</v-menu>
				</div>
				<div class="actions mr-2" v-if="isWarehouseConnected">
                    <button class="btn-edit inventory-btn-edit mr-2" 
                        @click.stop="editOrder(item, 'edit')" v-if="currentTab === 0">
                        <img src="@/assets/icons/edit-inventory.svg" alt=""> Edit
                    </button>

                    <div v-if="currentTab !== 2 && currentTab !== 3">
                        <v-menu bottom offset-y left content-class="outbound-lists-menu">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn icon v-bind="attrs" v-on="on">
                                    <v-icon>mdi-dots-horizontal</v-icon>
                                </v-btn>
                            </template>

                            <v-list class="outbound-lists">
								<v-list-item @click="viewOutbound(item)">
									<v-list-item-title> View </v-list-item-title>
								</v-list-item>
                                <v-list-item v-if="currentTab === 0" @click="submitDraft(item)">
                                    <v-list-item-title> Submit </v-list-item-title>
                                </v-list-item>

                                <v-list-item v-if="currentTab === 0" @click="canceldelete(item)">
                                    <v-list-item-title class="cancel"> Delete </v-list-item-title>
                                </v-list-item>

								<v-list-item v-if="showCancelButton(item) && currentTab === 1" @click="cancel(item)">
								<v-list-item-title class="cancel"> Cancel</v-list-item-title>
							</v-list-item>
                            </v-list>
                        </v-menu>
                    </div>                    
                </div>
			</template>

			<template v-slot:no-data>
				<div class="loading-wrapper" v-if="currentWarehouseLoading">
					<v-progress-circular :size="40" color="#0171a1" indeterminate></v-progress-circular>
				</div>

				<div class="no-data-wrapper" v-if="!currentWarehouseLoading && currentWarehouseOutbounds.data.length == 0">
					<div v-if="search ==='' && SearchCustomerFinalArray.length ===0">
						<div class="no-data-heading">
							<img
								src="@/assets/icons/empty-inventory-icon.svg"
								width="40px"
								height="42px"
								alt="" />

							<div v-if="currentTab === 0">
								<h3>Create Outbound</h3>
								<p>Let's add outbound to this warehouse.</p>

								<div class="d-flex justify-center align-center mt-4">
									<v-btn
										color="primary"
										dark
										class="btn-blue"
										@click.stop="createOutbound">
										Create Outbound
									</v-btn>
								</div>
							</div>
							<div v-for="(tab, index) in tabsComputed" :key="index">
                            	<span v-if="(currentTab === index && index !== 0)">
                            	    <h3> No {{ tab }} Outbounds </h3>
                            	    <p> There are no <span class="text-lowercase">{{ tab }}</span> outbounds listed. </p>
                            	</span>                            
                        	</div>

							<!-- <div v-if="currentTab === 1 && isWarehouse3PL ==false">
								<h3>No Floor Outbounds</h3>
								<p>There are no floor outbounds listed.</p>
							</div>
							<div v-if="currentTab === 1 && isWarehouse3PL ==true">
								<h3>Create Outbound</h3>
								<p>Let's add outbound to this warehouse.</p>

								<div class="d-flex justify-center align-center mt-4">
									<v-btn
										color="primary"
										dark
										class="btn-blue"
										@click.stop="createOutbound">
										Create Outbound
									</v-btn>
								</div>
							</div>

							<div v-if="currentTab === 2">
								<h3>No Completed Outbounds</h3>
								<p>There are no completed outbounds listed.</p>
							</div>

							<div v-if="currentTab === 3">
								<h3>No Cancelled Outbounds</h3>
								<p>There are no cancelled outbounds listed.</p>
							</div> -->
						</div>
					</div>
					<div v-if="search !=='' && SearchCustomerFinalArray.length ===0">
						<img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">

                        <div>
                            <h3> No Outbounds found. </h3>
                            <p> There are no outbounds listed. Try searching another keyword.</p>
                        </div>
					</div>
					<div v-if="SearchCustomerFinalArray.length > 0">
						<img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">

                        <div>
                            <h3> No Outbounds found. </h3>
                            <p> There are no outbounds listed. Change another Customer.</p>
                        </div>
					</div>
				</div>
			</template>
		</v-data-table>

		<CreateOutboundDialog
			:dialogCreate.sync="dialogCreate"
			:editedOutboundIndex.sync="editedOutboundIndex"
			:editedOutboundItems.sync="editedOutboundItems"
			:defaultOutboundItems.sync="defaultOutboundItems"
			:currentWarehouseSelected="currentWarehouseSelected"
			:isFetchSingleOutboundbound="false"
			@close="closeCreate"
			:status="outbound_Status_for_3pl"
			:outboundProducts_clone="outboundProducts_clone" 
			:fetchProductLoading ="fetchProductLoading"
			:productsListsData.sync="productsListsData"
			@Warehouse6PL_ProductsOnchange="Warehouse6PL_ProductsOnchange"
			@fetchOutboundProductsAPiFunction="fetchOutboundProductsAPiFunction"
			:isWarehouseConnected="isWarehouseConnected"
			:fetchWarehouseCustomersLoading="fetchWarehouseCustomersLoading"/>

		<CancelOutbound
			:dialogData.sync="dialogCancel"
			:editedItemData.sync="cancelOrderData"
			@cancel="cancelConfirm"
			@close="closeCancel"
			:loading="getCancelOutboundLoading" />

		<CancelMultipleOutbound
			:dialogData.sync="dialogCancelMultiple"
			:editedItemData.sync="CancelOrderCondition"
			@cancel="cancelMultipleConfirm"
			@close="closeCancelMultiple"
			:loading="getcancelMultipleOutboundLoading" />

		<DeleteOutboundDialog
			:dialogData.sync="dialogCanceldelete"
			:editedItemData.sync="deleteOrderData"
			@cancel="deleteConfirm"
			@close="closeDelete"
			:loading="getDeleteOutboundLoading"
			:isWarehouseConnected="isWarehouseConnected" />

		<!-- <Pagination 
			v-if="currentWarehouseOutbounds !== 'undefined' &&
			currentWarehouseOutbounds.length > 0 "
			:pageData.sync="page"
			:lengthData.sync="pageCount"
			:isMobile="isMobile" /> -->
		<PaginationComponent 
            :totalPage.sync="getTotalPage"
            :currentPage.sync="getCurrentPage"
            @handlePageChange="handlePageChange"
            :isMobile="isMobile" />

		<ConfirmDialog :dialogData.sync="showWarningEditInboundDialog">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert">
				</div>
			</template>
			
			<template v-slot:dialog_title>
				<h2> Edit Completed Order? </h2>
			</template>

			<template v-slot:dialog_content>
				<p> 
					This order has already been completed. Do you still want to edit this outbound order?
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="confirmEditOrder" text>
                    Edit Order
				</v-btn>

				<v-btn class="btn-white" text @click="closeWarning">
					Close
				</v-btn>
			</template>
		</ConfirmDialog>

		<ConfirmDialog :dialogData.sync="confirmDraftSubmit">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/question.svg" alt="alert">
				</div>
			</template>
			
			<template v-slot:dialog_title>
				<h2>Submit Outbound Order?</h2>
			</template>

			<template v-slot:dialog_content>
				<p> 
					Do you want to submit the draft outbound order to <br/>
					<span v-if="draftOutboundData" style="font-family: 'Inter-SemiBold', sans-serif !important;">
                        '{{typeof draftOutboundData !== 'undefined' && draftOutboundData !== undefined && draftOutboundData.warehouse !== undefined &&
						draftOutboundData.warehouse !== 'undefined' ? draftOutboundData.warehouse.name :'--'  }}'
                    </span>?
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="submitDraftConfirm" :disabled="getDraftSbmitLoading" text>
					{{ getDraftSbmitLoading ? 'Submitting...' : 'Submit' }}
				</v-btn>

				<v-btn class="btn-white" text @click="closeDraft" :disabled="getDraftSbmitLoading">
					Close
				</v-btn>
			</template>
		</ConfirmDialog>

		<ConfirmDialog :dialogData.sync="AcceptConnectedWarehouseOutboundDialog">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/question.svg" alt="alert">
				</div>
			</template>
			
			<template v-slot:dialog_title>
				<h2> Accept Outbound Order? </h2>
			</template>

			<template v-slot:dialog_content>
				<p v-if="acceptConnectedWarehouseOrderData"> 
					Do you want to accept outbound order 
					'<span style="font-family: 'Inter-SemiBold', sans-serif !important;">
						{{acceptConnectedWarehouseOrderData !== null ? acceptConnectedWarehouseOrderData.order_id : 'N/A'}}
					</span>'
					from 
					<span style="font-family: 'Inter-SemiBold', sans-serif !important;">
						'{{acceptConnectedWarehouseOrderData !== null && acceptConnectedWarehouseOrderData.warehouse_customer !== null ? 
						acceptConnectedWarehouseOrderData.warehouse_customer.company_name :'N/A'}}
					'</span>? 
					Once accepted you can't cancel it.
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="AcceptConnectedOutboundConfirm" :disabled="getAcceptOrderByProviderOutboundLoading" text>
					{{ getAcceptOrderByProviderOutboundLoading ? 'Accepting...' : 'Accept'}}
				</v-btn>

				<v-btn class="btn-white" text @click="closeConnectedWarehouseOrder" :disabled="getAcceptOrderByProviderOutboundLoading">
					Close
				</v-btn>
			</template>
		</ConfirmDialog>

		<ConfirmDialog :dialogData.sync="RejectConnectedWarehouseOutboundDialog">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert">
				</div>
			</template>
			
			<template v-slot:dialog_title>
				<h2> Reject Outbound Order? </h2>
			</template>

			<template v-slot:dialog_content>
				<p v-if="rejectConnectedWarehouseOrderData !== null"> 
					Do you want to reject order 
					<span style="font-family: 'Inter-SemiBold', sans-serif !important;">
						‘{{rejectConnectedWarehouseOrderData.order_id}}’
					</span> 
					with the refence number
					<span style="font-family: 'Inter-SemiBold', sans-serif !important;">
						‘{{rejectConnectedWarehouseOrderData.ref_no !==null ? rejectConnectedWarehouseOrderData.ref_no : "N/A" }}’
					</span>?
				</p>
				<div class="mt-5">
					<p class="outbound-title" style="color: #819FB2;font-size:10px;font-weight: 600;"> NOTE <span style="font-weight: 400;">(Optional)</span></p>
					<v-textarea
					class="deliver-address"
					outlined
					v-model="reasonForRejectionOutboundOrder"
					row-height="35"
					rows="3"
					placeholder="Type reason for rejection"
					hide-details="auto">
				  </v-textarea>
				</div>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="RejectConnectedOutboundConfirm" :disabled="getRejectOrderByProviderOutboundLoading" text>
					{{ getRejectOrderByProviderOutboundLoading ? 'Rejecting...' : 'Reject' }}
				</v-btn>

				<v-btn class="btn-white" text @click="closeRejectConnectedWarehouseOrder" :disabled="getRejectOrderByProviderOutboundLoading">
					Close
				</v-btn>
			</template>
		</ConfirmDialog>
		
		<v-dialog v-model="cancelOrder_StorableLoaded" max-width="500px" content-class="item-delete-dialog">
			<v-card class="delete-dialog">
				<v-card-title class="headline">
					<div class="delete-icon mt-3 mb-1">
						<img src="@/assets/icons/icon-delete.svg" alt="">
					</div>
				</v-card-title>

				<v-card-text style="padding-bottom: 15px;">
					<h2>Cancellation Failed</h2>
					<p v-if="selectedCancelled.length>0">You can not cancel Outbound order <span class="mr-1" v-for="cancelOdr in selectedCancelled" :key="cancelOdr.id">{{cancelOdr.order_id}}</span> at this moemnt,This has already been loaded
					</p>   
				</v-card-text>
				
				<v-card-actions class="delete-btn-wrapper">
					<v-btn class="cancel-btn" text @click="cancelOrder_StorableLoaded = !cancelOrder_StorableLoaded">Back</v-btn>
				</v-card-actions>
			</v-card>
    	</v-dialog>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
// import Search from "../../../Search.vue";
import SearchComponent from '../../../SearchComponent/SearchComponent.vue'
import CreateOutboundDialog from "../../../InventoryComponents/OutboundComponents/CreateOutboundDialog.vue";
import CancelOutbound from "../../../InventoryComponents/OutboundComponents/CancelOutbound.vue";
import CancelMultipleOutbound from "../../../InventoryComponents/OutboundComponents/CancelMultipleOutbound.vue";
import DeleteOutboundDialog from "../../../InventoryComponents/OutboundComponents/DeleteOutboundDialog.vue";
import ConfirmDialog from '../../../Dialog/GlobalDialog/ConfirmDialog.vue'
// import Pagination from "../../../Pagination";
import PaginationComponent from "../../..//PaginationComponent/PaginationComponent.vue"
import moment from "moment";
import globalMethods from "../../../../utils/globalMethods";
import FilterCustomers from '../../../InventoryComponents/FilterCustomers/FilterCustomers'
import _ from 'lodash'

// search OUtbound
import axios from 'axios'
var cancel;
var CancelToken = axios.CancelToken;

export default {
	name: "InventoryOutboundDesktopTable",
	props: ["currentWarehouseSelected", "isMobile","isWarehouseConnected","isWarehouse3PL","isWarehouse3PLProvider"],
	components: {
		// Search,
		SearchComponent,
		CreateOutboundDialog,
		CancelOutbound,
		CancelMultipleOutbound,
		DeleteOutboundDialog,
		ConfirmDialog,
		// Pagination,
		PaginationComponent,
		FilterCustomers
	},
	data: () => ({
		showWarningEditInboundDialog:false,
		currentEditInboundData: null,
		selectedCancelled: [],
		sendDataforSelectedCanceled: [],
		CancelOrderCondition:[],
		cancelOrder_StorableLoaded:false,
		page: 1,
		pageCount: 0,
		itemsPerPage: 35,
		search: "",
		headers: [
		{
			text: "Order ID",
			align: "start",
			sortable: false,
			value: "order_id",
			fixed: true,
			width: "12%",
		},
		{
			text: "Truck",
			align: "start",
			sortable: false,
			value: "name",
			fixed: true,
			width: "20%",
		},
		{
			text: "Customer",
			align: "start",
			sortable: false,
			value: "customer",
			fixed: true,
			width: "20%",
		},
		{
			text: "Reference",
			align: "start",
			sortable: false,
			value: "ref_no",
			fixed: true,
			width: "22%",
		},
		{
			text: "ETA/Arrival Time",
			align: "start",
			sortable: false,
			value: "estimated_time",
			fixed: true,
			width: "20%",
		},
		{
			text: "Status",
			align: "center",
			sortable: false,
			value: "outbound_status",
			fixed: true,
			width: "13%",
		},
		{
			text: "",
			align: "end",
			value: "actions",
			sortable: false,
			fixed: true,
			width: "5%",
		},
		],
		header3PLServiceProviderConnectedCompleted:[
			{
			text: "Order ID",
			align: "start",
			sortable: false,
			value: "order_id",
			fixed: true,
			width: "8%",
		},
		{
			text: "Created At",
			align: "start",
			sortable: false,
			value: "created_at",
			fixed: true,
			width: "20%",
		},
		{
			text: "Customer",
			align: "start",
			sortable: false,
			value: "warehouse_customer",
			fixed: true,
			width: "20%",
		},
			{
			text: "Reference",
			align: "start",
			sortable: false,
			value: "ref_no",
			fixed: true,
			width: "20%",
		},
		
		{
			text: "Updated At",
			align: "start",
			sortable: false,
			value: "updated_at",
			fixed: true,
			width: "32%",
		},
		],
		header3PLServiceProviderConnected:[
			{
			text: "Order ID",
			align: "start",
			sortable: false,
			value: "order_id",
			fixed: true,
			width: "8%",
		},
		{
			text: "Created By & At",
			align: "start",
			sortable: false,
			value: "customer_admin_name",
			fixed: true,
			width: "180px",
		},
		{
			text: "Truck",
			align: "start",
			sortable: false,
			value: "name",
			fixed: true,
			width: "16%",
		},
			{
			text: "Reference",
			align: "start",
			sortable: false,
			value: "ref_no",
			fixed: true,
			width: "16%",
		},
		{
			text: "Customer",
			align: "start",
			sortable: false,
			value: "warehouse_customer",
			fixed: true,
			width: "16%",
		},
		{
			text: "Departure Time",
			align: "start",
			sortable: false,
			value: "estimated_time",
			fixed: true,
			width: "16%",
		},
		{
			text: "ETA/Arrival Time",
			align: "start",
			sortable: false,
			value: "estimated_time",
			fixed: true,
			width: "25%",
		},
		{
			text: "Status",
			align: "center",
			sortable: false,
			value: "status",
			fixed: true,
			width: "13%",
		},
		{
			text: "",
			align: "end",
			value: "actions",
			sortable: false,
			fixed: true,
			width: "5%",
		},
		],
		header6PL:[
			{
			text: "Order ID",
			align: "start",
			sortable: false,
			value: "order_id",
			fixed: true,
			width: "8%",
		},
		{
			text: "Created By & At",
			align: "start",
			sortable: false,
			value: "customer_admin_name",
			fixed: true,
			width: "180px",
		},
		{
			text: "Updated By & At",
			align: "start",
			sortable: false,
			value: "updated_customer_admin_name",
			fixed: true,
			width: "180px",
		},
		{
			text: "Truck",
			align: "start",
			sortable: false,
			value: "name",
			fixed: true,
			width: "16%",
		},
			{
			text: "Reference",
			align: "start",
			sortable: false,
			value: "ref_no",
			fixed: true,
			width: "16%",
		},
		{
			text: "Customer",
			align: "start",
			sortable: false,
			value: "warehouse_customer",
			fixed: true,
			width: "20%",
		},
		{
			text: "ETA/Arrival Time",
			align: "start",
			sortable: false,
			value: "estimated_time",
			fixed: true,
			width: "170px",
		},
		{
			text: "Status",
			align: "center",
			sortable: false,
			value: "outbound_status",
			fixed: true,
			width: "13%",
		},
		{
			text: "",
			align: "end",
			value: "actions",
			sortable: false,
			fixed: true,
			width: "5%",
		},
		],
		header3PL: [
		{
			text: "Order ID",
			align: "start",
			sortable: false,
			value: "order_id",
			fixed: true,
			width: "12%",
		},
		{
			text: "Created At",
			align: "start",
			sortable: false,
			value:'created_at',
			fixed: true,
			width: "20%",

		},
		{
			text: "Customer",
			align: "start",
			sortable: false,
			value: "customer",
			fixed: true,
			width: "20%",
		},
		{
			text: "Reference",
			align: "start",
			sortable: false,
			value: "ref_no",
			fixed: true,
			width: "20%",
		},
		{
			text: "ETA",
			align: "start",
			sortable: false,
			value: "estimated_time",
			fixed: true,
			width: "35%",
		},
		{
			text: "Updated At",
			align: "start",
			sortable: false,
			value: "updated_at",
			fixed: true,
			width: "35%",
		},
		{
			text: "",
			align: "end",
			value: "actions",
			sortable: false,
			fixed: true,
			width: "5%",
		},
		],
		completed_heder: [
		{
			text: "Order ID",
			align: "start",
			sortable: false,
			value: "order_id",
			fixed: true,
			width: "12%",
		},
		{
			text: "Created At",
			align: "start",
			sortable: false,
			value:'created_at',
			fixed: true,
			width: "18%",

		},
		{
			text: "Customer",
			align: "start",
			sortable: false,
			value: "customer",
			fixed: true,
			width: "18%",
		},
		{
			text: "Reference",
			align: "start",
			sortable: false,
			value: "ref_no",
			fixed: true,
			width: "18%",
		},
		{
			text: "Updated At",
			align: "start",
			sortable: false,
			value: "updated_at",
			fixed: true,
			width: "15%",
		},
		{
			text: "Status",
			align: "center",
			sortable: false,
			value: "outbound_status",
			fixed: true,
			width: "15%",
		},
		{
			text: "",
			align: "end",
			value: "actions",
			sortable: false,
			fixed: true,
			width: "10%",
		},
		],
		dialogCreate: false,
		editedOutboundIndex: -1,
		editedOutboundItems: {
		order_id: "",
		customer: "",
		ref_no: "",
		deliver_to: "",
		name: "",
		notes:"",
		estimated_date: null,
		estimated_time: null,
		warehouse_customer_id: '',
		outbound_products: [
			{
				product: {
					product_id: "",
				},
				product_id: "",
				quantity: "",
				shipping_unit: "",
				total_unit: "",
				outbound_product_id:null,
				instructions:''
			},
		],
		pallets: [],
		outbound_documents: [],
		bol_number: "",
		outbound_storable_units: [
			{
				id: null,
				outbound_id: null,
				warehouse_id: null,
				customer_id: null,
				action: null,
				label: null,
				type: "",
				created_at: "",
				updated_at: "",
				dimension: {
					l: "",
					w: "",
					h: "",
					uom: "",
				},
				weight: {
					value: "",
					unit: "",
				},
				products: null,
				status: "",
				no_of_sku: null,
				deleted_at: null,
				storable_unit_id: null,
				outbound_storable_unit_products: [
					{
						id: null,
						outbound_product_id: null,
						outbound_storable_unit_id: null,
						carton_count: "",
						total_unit: "",
						in_each_carton: "",
						storable_unit_product_id: null,
						created_at: "",
						updated_at: "",
					},
				],
			},
		],
		},
		defaultOutboundItems: {
		order_id: "",
		customer: "",
		ref_no: "",
		deliver_to: "",
		name: "",
		notes:"",
		estimated_date: null,
		estimated_time: null,
		warehouse_customer_id: '',
		outbound_products: [
			{
				product: {
					product_id: "",
				},
				product_id: "",
				quantity: "",
				shipping_unit: "",
				total_unit: "",
				outbound_product_id:null,
				instructions:''
			},
		],
		pallets: [],
		outbound_documents: [],
		bol_number: "",
		outbound_storable_units: [],
		},
		editedPoItems: {
			products: [
				{
					id: null,
					quantity: 0,
					unit_price: 0,
					amount: 0,
				},
			],
			po_number: "",
			is_system_generated: 1,
			supplier_id: "",
			customer_id: "",
			notes: "",
			created_by: "",
			tax: 0,
			warehouse_id: "",
			sub_total: "",
			shipping: 0,
			total: "",
			discount: 0,
		},
		defaultPoItems: {
			products: [
				{
					id: null,
					quantity: 0,
					unit_price: 0,
					amount: 0,
				},
			],
			po_number: "",
			is_system_generated: 1,
			supplier_id: "",
			customer_id: "",
			notes: "",
			created_by: "",
			tax: 0,
			warehouse_id: "",
			sub_total: "",
			shipping: 0,
			total: "",
			discount: 0,
		},
		tabsoutbound: ["Pending", "Floor", "Completed", "Cancelled"],
		tabsoutbound3plProvider:["Waiting Approval", "Pending Shipping","floor", "Completed", "Cancelled"],
		tabsoutbound3plServiceProviderConnected:["Draft","Waiting Approval", "Pending Shipping", "Completed", "Archived"],
		tabCount: [],
		currentTab: 0,
		cancelOrderData: null,
		deleteOrderData: null,
		dialogCancel: false,
		dialogCancelMultiple: false,
		dialogCanceldelete: false,
		outbound_Status_for_3pl:'',
		outboundProducts_clone:[],
		// Products Drop Down 
		lastDataCheck: [],
		productsListsData: [],
		current_page_is: 1,
		fetchProductLoading:false,
		// search Outbound
		pendingOutboundLoadingPage: false,
        floorOutboundLoadingPage: false,
        completedOutboundLoadingPage: false,
        cancelledOutboundLoadingPage: false,
		draftOutboundLoadingPage: false,
		pendingShippedOutboundLoadingPage: false,
		pendingShippingProviderOutboundLoadingPage: false,
		SearchCustomerFinalArray:[],
		SearchCustomerFinalArrayCopy:[],
		// wareHouse 6PL
		current_page_6pl_products:1,
		searchCustomerData: '',
		// warehouse customers data
		current_page_is_whcustomers: 1,
		warehouseCustomerListsData: [],
		warehouseCustomerLists: [],
        warehouseCustomerListsCopy: [],
		lastCheckWHData: [],
        fetchWarehouseCustomersLoading: false,
		filterMenu: false,
		isActiveClicked: false,
		selectedWhCustomer:[],
		// connected warehouse 3pl provider
		confirmDraftSubmit: false,
		draftOutboundData: null,
		AcceptConnectedWarehouseOutboundDialog:false,
		acceptConnectedWarehouseOrderData : null,
		RejectConnectedWarehouseOutboundDialog:false,
		rejectConnectedWarehouseOrderData:null,
		reasonForRejectionOutboundOrder:''
	}),
	watch: {
		selectedCancelled: function () {
			if(!this.isWarehouseConnected && !this.isWarehouse3PLProvider){
				if ((this.currentTab == 2 || this.currentTab == 3) &&
					this.selectedCancelled.length > 0) {
					this.selectedCancelled = [];
					this.CancelOrderCondition=[];
				}
			}else if(!this.isWarehouseConnected && this.isWarehouse3PLProvider){
				if ((this.currentTab == 0 || this.currentTab == 4 || this.currentTab == 3) &&
					this.selectedCancelled.length > 0) {
					this.selectedCancelled = [];
					this.CancelOrderCondition=[];
				}
			} else{
				if ((this.currentTab !== 1) &&
					this.selectedCancelled.length > 0) {
					this.selectedCancelled = [];
					this.CancelOrderCondition=[];
				}
			}
			
		}
	},
	computed: {
		...mapGetters({
			getUser: 'getUser',
			getOutboundInventories: "outbound/getOutboundInventories",
			getOutboundInventoriesLoading: "outbound/getOutboundInventoriesLoading",
			getCancelOutboundLoading: "outbound/getCancelOutboundLoading",
			getcancelMultipleOutboundLoading:
				"outbound/getcancelMultipleOutboundLoading",
			getDeleteOutboundLoading: "outbound/getDeleteOutboundLoading",
			getPrintOutboundOrderLoading: "outbound/getPrintOutboundOrderLoading",
			getCurrentOutboundTab: "outbound/getCurrentOutboundTab",
			//
			getPendingOutbounds: "outbound/getPendingOutbounds",
			getFloorOutbounds: "outbound/getFloorOutbounds",
			getCompletedOutbounds: "outbound/getCompletedOutbounds",
			getCancelledOutbounds: "outbound/getCancelledOutbounds",
			getPendingOutboundsLoading: "outbound/getPendingOutboundsLoading",
			getFloorOutboundsLoading: "outbound/getFloorOutboundsLoading",
			getCompletedOutboundsLoading: "outbound/getCompletedOutboundsLoading",
			getCancelledOutboundsLoading: "outbound/getCancelledOutboundsLoading",
			// Products DropDown
			getAllOutboundProductsListsDropdownData:'outbound/getAllOutboundProductsListsDropdownData',
			getallOutboundProductsLists:'outbound/getallOutboundProductsLists',
			// Search Outbounds
			poBaseUrlState: 'products/poBaseUrlState',
			getsearchedOutboundsLoading:'outbound/getsearchedOutboundsLoading',
			getSearchedOutbounds:'outbound/getSearchedOutbounds',
			// filter outbound customer
			getfilteredOutbounds:'outbound/getfilteredOutbounds',
			getfilteredOutboundsLoading:'outbound/getfilteredOutboundsLoading',
			//6PL
			getWarehouseCustomersDropdown: 'warehouseCustomers/getWarehouseCustomersDropdown',
			getallProducts6PLists: 'outbound/getallProducts6PLists',
			getAllWarehouseCustomerListsData: 'warehouseCustomers/getAllWarehouseCustomerListsData',
			// pagination
			getPendingOutboundPagination:"outbound/getPendingOutboundPagination",
			getFloorOutboundPagination:"outbound/getFloorOutboundPagination",
			getCompletedOutboundPagination:"outbound/getCompletedOutboundPagination",
			getCancelledOutboundPagination:"outbound/getCancelledOutboundPagination",
			// connected 3pl Provider
			getDraftOutboundPagination: "outbound/getDraftOutboundPagination",
			getPendingShippedOutboundPagination: "outbound/getPendingShippedOutboundPagination",
			getDraftOutboundsLoading : "outbound/getDraftOutboundsLoading",
			getPendingShippedOutboundsLoading : "outbound/getPendingShippedOutboundsLoading",
			getDraftSbmitLoading : "outbound/getDraftSbmitLoading",
			getPendingShippingProviderOutboundPagination:"outbound/getPendingShippingProviderOutboundPagination",
			getPendingShippingProviderOutboundsLoading:"outbound/getPendingShippingProviderOutboundsLoading",
			getAcceptOrderByProviderOutboundLoading:"outbound/getAcceptOrderByProviderOutboundLoading",
			getRejectOrderByProviderOutboundLoading:"outbound/getRejectOrderByProviderOutboundLoading"
		}),
		tabsComputed() {
            if (this.isWarehouseConnected) {
                return this.tabsoutbound3plServiceProviderConnected
            }else if(!this.isWarehouseConnected && this.isWarehouse3PLProvider){
				return this.tabsoutbound3plProvider
			} else {
                return this.tabsoutbound
            }
        },
		_header(){
			let final_header = []
			if(this.isWarehouse3PL == true){
				if(this.currentTab ==2){
					return final_header = this.completed_heder
				}
				else{
					final_header = this.header3PL
				return final_header.filter(val =>{
					if(this.currentTab == 3){
						return val.text !=='ETA'
					}
					else if(this.currentTab ==0){
						return val.text !== 'Updated At'
					}
				})
				}
			}
			else if(this.isWarehouse3PLProvider == true && this.isWarehouseConnected == false){
				final_header = this.header6PL
				return final_header.filter(val => {
					if(this.currentTab == 0){
						if(val.text == 'Order ID'){
							val.width = '10%'
						}
						if(val.text == 'Updated By & At'){
							val.width = '20%'
						}
						if(val.text == 'Truck'){
							val.width = '13%'
						}

						if(val.text == 'Reference'){
							val.width = '13%'
						}
						if(val.text == 'Customer'){
							val.width = '24%'
						}
						if(val.text == 'ETA/Arrival Time'){
							val.width = '24%'
						}
						if(val.text == ''){
							val.width = '10%'
						}

						return val.text !== 'Status' && val.text !== 'Created By & At'
					}else if(this.currentTab == 1){
						return val.text !== 'Status' && val.text !== 'Created By & At'
					}else{
						return val.text !=='Updated By & At'
					}
				})
			}
			else if(this.isWarehouse3PLProvider == true && this.isWarehouseConnected == true){
				if(this.currentTab == 3){
					return final_header = this.header3PLServiceProviderConnectedCompleted
				}else{
					final_header = this.header3PLServiceProviderConnected
					return final_header.filter(val =>{
						if(this.currentTab == 0){
							if(val.text == 'Order ID'){
								val.width = '10%'
							}
							if(val.text == 'Updated By & At'){
								val.width = '20%'
							}

							if(val.text == 'Truck'){
								val.width = '13%'
							}

							if(val.text == 'Reference'){
								val.width = '13%'
							}
							if(val.text == 'ETA/Arrival Time'){
								val.width = '33%'
							}
							if(val.text == ''){
								val.width = '10%'
							}
							return val.text !== 'Customer' && val.text !=='Departure Time' && val.text !== 'Status'
						}
						else if(this.currentTab == 1){
							return val.text !=='Departure Time' && val.text !== 'Status'
						}
						else if(this.currentTab == 2){
							if(val.text == 'Order ID'){
								val.width = '8%'
							}

							if(val.text == 'Truck'){
								val.width = '16%'
							}

							if(val.text == 'Reference'){
								val.width = '14%'
							}

							if(val.text == 'Customer'){
								val.width = '21%'
							}
							if(val.text == 'Departure Time'){
								val.width = '12%'
							}
							if(val.text == 'Status'){
								val.width = '27%'
							}
							return val.text !== 'Updated By & At' && val.text !== 'ETA/Arrival Time' && val.text !== ''
						}
						else if(this.currentTab == 4){
							if(val.text == 'Order ID'){
								val.width = '8%'
							}

							if(val.text == 'Truck'){
								val.width = '18%'
							}

							if(val.text == 'Reference'){
								val.width = '13%'
							}

							if(val.text == 'Customer'){
								val.width = '20%'
							}
							if(val.text == 'Departure Time'){
								val.width = '21%'
							}
							if(val.text == ''){
								val.width = '12%'
							}
							return  val.text !== 'Updated By & At' && val.text !== 'ETA/Arrival Time'
						}
					})
				}	
			}
			else{
				return final_header = this.headers
			}
		},
		currentWarehouseOutbounds() {
            let outboundsData = {}
			if(!this.isWarehouseConnected && !this.isWarehouse3PLProvider){
				if (this.currentTab === 0) {
                	outboundsData = this.pendingOutboundsDataLists
            	} else if (this.currentTab === 1) {
                	outboundsData = this.floorOutboundsDataLists
            	} else if (this.currentTab === 2) {
                	outboundsData = this.completedOutboundsDataLists
            	} else {
                	outboundsData = this.cancelledOutboundsDataLists
            	}
			} else if(!this.isWarehouseConnected && this.isWarehouse3PLProvider){
				if (this.currentTab === 0) {
                    outboundsData = this.pendingOutboundsDataLists
                } else if (this.currentTab === 1) {
                    outboundsData = this.pendingShippingProviderOutboundsDataLists
                } else if (this.currentTab === 2) {
                    outboundsData = this.floorOutboundsDataLists
                } else if (this.currentTab === 3) {
                    outboundsData = this.completedOutboundsDataLists
                } else {
                    outboundsData = this.cancelledOutboundsDataLists
                }
			} else{
				if (this.currentTab === 0) {
                    outboundsData = this.draftOutboundsDataLists
                } else if (this.currentTab === 1) {
                    outboundsData = this.pendingOutboundsDataLists
                } else if (this.currentTab === 2) {
                    outboundsData = this.pendingShippedOutboundsDataLists
                } else if (this.currentTab === 3) {
                    outboundsData = this.completedOutboundsDataLists
                } else {
                    outboundsData = this.cancelledOutboundsDataLists
                }
			}
            
            
            return outboundsData
        },
		currentWarehouseLoading() {
			if(!this.isWarehouseConnected && !this.isWarehouse3PLProvider){
				if (this.currentTab === 0) {
					return this.getPendingOutboundsLoading;
				} else if (this.currentTab === 1) {
					return this.getFloorOutboundsLoading;
				} else if (this.currentTab === 2) {
					return this.getCompletedOutboundsLoading;
				} else {
					return this.getCancelledOutboundsLoading;
				}
			}else if(!this.isWarehouseConnected && this.isWarehouse3PLProvider){
				if (this.currentTab === 0) {
					return this.getPendingOutboundsLoading;
				} else if (this.currentTab === 1) {
					return this.getPendingShippingProviderOutboundsLoading;
				} else if (this.currentTab === 2) {
					return this.getFloorOutboundsLoading;
				}else if (this.currentTab === 3) {
					return this.getCompletedOutboundsLoading;
				} else {
					return this.getCancelledOutboundsLoading;
				}
			}else {
				if (this.currentTab === 0) {
					return this.getDraftOutboundsLoading;
				} else if (this.currentTab === 1) {
					return this.getPendingOutboundsLoading;
				} else if (this.currentTab === 2) {
					return this.getPendingShippedOutboundsLoading;
				}else if (this.currentTab === 3) {
					return this.getCompletedOutboundsLoading;
				} else {
					return this.getCancelledOutboundsLoading;
				}
			}
			
		},
		getTotalPage: {
            get() {
                let total = 1
                let OutboundsData = this.currentWarehouseOutbounds
                if (OutboundsData !==undefined && typeof OutboundsData.last_page !== 'undefined' && OutboundsData.last_page !== undefined && OutboundsData.last_page !== null) {
                    total = OutboundsData.last_page
                }

                return total
            }
        },
        getCurrentPage: {
            get() {
                let current_page = 1
                let OutboundsData = this.currentWarehouseOutbounds

                if (OutboundsData !== undefined && typeof OutboundsData.current_page !== 'undefined' && OutboundsData.current_page !== undefined && OutboundsData.current_page !== null) {
                    current_page = OutboundsData.current_page
                }

                return current_page
            },
            set() {
                return {}
            }
        },
		getCurrentLoadingToDisplay() {
            if (this.search === '' && this.SearchCustomerFinalArray.length ===0) {
                return this.getHandlePageLoading
            }
			else if(this.SearchCustomerFinalArray.length > 0){
				return this.getfilteredOutboundsLoading
			}
			else {
                return this.getsearchedOutboundsLoading
            }
        },
        getHandlePageLoading() {
			if(!this.isWarehouseConnected && !this.isWarehouse3PLProvider){
				if (this.currentTab === 0) {
                	return this.pendingOutboundLoadingPage
            	} else if (this.currentTab === 1) {
            	    return this.floorOutboundLoadingPage
            	} else if (this.currentTab === 2) {
            	    return this.completedOutboundLoadingPage
            	} else {
            	    return this.cancelledOutboundLoadingPage
            	}
			} else if(!this.isWarehouseConnected && this.isWarehouse3PLProvider){
				if (this.currentTab === 0) {
                	return this.pendingOutboundLoadingPage
            	} else if (this.currentTab === 1) {
            	    return this.pendingShippingProviderOutboundLoadingPage
            	} else if (this.currentTab === 2) {
            	    return this.floorOutboundLoadingPage
            	} else if (this.currentTab === 3) {
            	    return this.completedOutboundLoadingPage
            	} else {
            	    return this.cancelledOutboundLoadingPage
            	}
			} else{
				if (this.currentTab === 0) {
                	return this.draftOutboundLoadingPage
            	} else if (this.currentTab === 1) {
            	    return this.pendingOutboundLoadingPage
            	} else if (this.currentTab === 2) {
            	    return this.pendingShippedOutboundLoadingPage
            	} else if (this.currentTab === 3) {
            	    return this.completedOutboundLoadingPage
            	} else {
            	    return this.cancelledOutboundLoadingPage
            	}
			}
            
        },
		handleSearchComponent() {

            let isShow = true

            if (this.search == '' && this.currentWarehouseOutbounds.data.length === 0 && this.SearchCustomerFinalArray.length ==0) {
                isShow = false
            } else if (this.search !== '' && this.currentWarehouseOutbounds.data.length === 0) {
                isShow = true
            }
			else if (this.SearchCustomerFinalArray.length > 0 && this.currentWarehouseOutbounds.data.length === 0) {
                isShow = false
            }
            return isShow
        },
		handleFilterComponent() {
            let isShow = true

            if (this.search == '' && this.SearchCustomerFinalArray.length ===0 &&  this.currentWarehouseOutbounds.data.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.currentWarehouseOutbounds.data.length === 0) {
                isShow = true
            }
			else if(this.search !== '' || this.currentWarehouseOutbounds.data.length > 0){
				isShow = true
			}
            return isShow
        },
		// warehouseCustomerLists() {
        //     let data = []

        //     if (typeof this.getWarehouseCustomersDropdown !== "undefined" && this.getWarehouseCustomersDropdown !== null) {
        //         if (typeof this.getWarehouseCustomersDropdown.data !== "undefined" &&
        //             this.getWarehouseCustomersDropdown.data.length !== "undefined") {
        //             data = this.getWarehouseCustomersDropdown.data;

        //             data.map((value) => {
        //                 if (value.address === null) {
        //                     value.address = "";
        //                 }

        //                 if (value.phone === null) {
        //                     value.phone = "";
        //                 }

        //                 if (value.emails === null) {
        //                     value.emails = "";
        //                 }
        //             });
        //         }
        //     }

        //     return data
        // },
		draftOutboundsDataLists(){
			let outboundLists = []
            if (typeof this.getSearchedOutbounds !== 'undefined' && this.getSearchedOutbounds !== null && this.getfilteredOutbounds !=='undefined' && this.getfilteredOutbounds !==null) {
                if (this.search !== '' && this.SearchCustomerFinalArray.length ===0  && this.getSearchedOutbounds.tab === 'draft') {
                    outboundLists = this.getSearchedOutbounds
                }
				// else if(this.SearchCustomerFinalArray.length > 0 && this.getfilteredOutbounds.tab === 'draft'){
				// 	outboundLists = this.getfilteredOutbounds
				// }
				else {
                    if (typeof this.getDraftOutboundPagination !== 'undefined' && 
                        this.getDraftOutboundPagination !== null) {
                        outboundLists = this.getDraftOutboundPagination
                    }
                }
            }

            return outboundLists
		},
		pendingShippedOutboundsDataLists(){
			let outboundLists = []

            if (typeof this.getSearchedOutbounds !== 'undefined' && this.getSearchedOutbounds !== null && this.getfilteredOutbounds !=='undefined' && this.getfilteredOutbounds !==null) {
                if (this.search !== '' && this.SearchCustomerFinalArray.length ===0 && this.getSearchedOutbounds.tab === 'shipping-pending-for-warehouse-customer') {
                    outboundLists = this.getSearchedOutbounds
                }
				// else if(this.SearchCustomerFinalArray.length > 0  && this.getfilteredOutbounds.tab === 'shipping-pending-for-warehouse-customer'){
				// 	outboundLists = this.getfilteredOutbounds
				// }
				else {
                    if (typeof this.getPendingShippedOutboundPagination !== 'undefined' && 
                        this.getPendingShippedOutboundPagination !== null) {
                        outboundLists = this.getPendingShippedOutboundPagination
                    }
                }
            }

            return outboundLists
		},
		pendingShippingProviderOutboundsDataLists(){
			let outboundLists = []

            if (typeof this.getSearchedOutbounds !== 'undefined' && this.getSearchedOutbounds !== null && this.getfilteredOutbounds !=='undefined' && this.getfilteredOutbounds !==null) {
                if (this.search !== '' && this.SearchCustomerFinalArray.length ===0 && this.getSearchedOutbounds.tab === 'shipping-pending') {
                    outboundLists = this.getSearchedOutbounds
                }
				else if(this.SearchCustomerFinalArray.length > 0  && this.getfilteredOutbounds.tab === 'shipping-pending'){
					outboundLists = this.getfilteredOutbounds
				}
				else {
                    if (typeof this.getPendingShippingProviderOutboundPagination !== 'undefined' && 
                        this.getPendingShippingProviderOutboundPagination !== null) {
                        outboundLists = this.getPendingShippingProviderOutboundPagination
                    }
                }
            }

            return outboundLists
		},
		pendingOutboundsDataLists() {
            let outboundLists = []
            if (typeof this.getSearchedOutbounds !== 'undefined' && this.getSearchedOutbounds !== null && this.getfilteredOutbounds !=='undefined' && this.getfilteredOutbounds !==null) {
                if (this.search !== '' && this.SearchCustomerFinalArray.length ===0 && this.getSearchedOutbounds.tab === 'pending') {
                    outboundLists = this.getSearchedOutbounds
                }
				else if(this.SearchCustomerFinalArray.length > 0 && this.getfilteredOutbounds.tab === 'pending'){
					outboundLists = this.getfilteredOutbounds
				}
				else {
                    if (typeof this.getPendingOutboundPagination !== 'undefined' && 
                        this.getPendingOutboundPagination !== null) {
                        outboundLists = this.getPendingOutboundPagination
                    }
                }
            }

            return outboundLists
        },
        floorOutboundsDataLists() {
            let outboundLists = []

            if (typeof this.getSearchedOutbounds !== 'undefined' && this.getSearchedOutbounds !== null && this.getfilteredOutbounds !=='undefined' && this.getfilteredOutbounds !==null) {
                if (this.search !== '' && this.SearchCustomerFinalArray.length ===0  && this.getSearchedOutbounds.tab === 'floor') {
                    outboundLists = this.getSearchedOutbounds
                }
				else if(this.SearchCustomerFinalArray.length > 0 && this.getfilteredOutbounds.tab === 'floor'){
					outboundLists = this.getfilteredOutbounds
				}
				else {
                    if (typeof this.getFloorOutboundPagination !== 'undefined' && 
                        this.getFloorOutboundPagination !== null) {
                        outboundLists = this.getFloorOutboundPagination
                    }
                }
            }

            return outboundLists
        },
        completedOutboundsDataLists() {
            let outboundLists = []

            if (typeof this.getSearchedOutbounds !== 'undefined' && this.getSearchedOutbounds !== null && this.getfilteredOutbounds !=='undefined' && this.getfilteredOutbounds !==null) {
                if (this.search !== '' && this.SearchCustomerFinalArray.length ===0  && this.getSearchedOutbounds.tab === 'completed') {
                    outboundLists = this.getSearchedOutbounds
                }
				else if(this.SearchCustomerFinalArray.length > 0  && this.getfilteredOutbounds.tab === 'completed'){
					outboundLists = this.getfilteredOutbounds
				}
				else {
                    if (typeof this.getCompletedOutboundPagination !== 'undefined' && 
                        this.getCompletedOutboundPagination !== null) {
                        outboundLists = this.getCompletedOutboundPagination
                    }
                }
            }

            return outboundLists
        },
        cancelledOutboundsDataLists() {
            let outboundLists = []

            if (typeof this.getSearchedOutbounds !== 'undefined' && this.getSearchedOutbounds !== null && this.getfilteredOutbounds !=='undefined' && this.getfilteredOutbounds !==null) {
                if (this.search !== '' && this.SearchCustomerFinalArray.length ===0 && this.getSearchedOutbounds.tab === 'cancelled') {
                    outboundLists = this.getSearchedOutbounds
                }
				else if(this.SearchCustomerFinalArray.length > 0  && this.getfilteredOutbounds.tab === 'cancelled'){
					outboundLists = this.getfilteredOutbounds
				}
				else {
                    if (typeof this.getCancelledOutboundPagination !== 'undefined' && 
                        this.getCancelledOutboundPagination !== null) {
                        outboundLists = this.getCancelledOutboundPagination
                    }
                }
            }

            return outboundLists
        },
		CLearAndCancelledButtonShow(){
			let show = false
			if(!this.isWarehouseConnected && !this.isWarehouse3PLProvider){
				if(this.selectedCancelled.length > 0 && this.currentTab !== 2 && this.currentTab !== 3){
					show = true
				}
			}else if(!this.isWarehouseConnected && this.isWarehouse3PLProvider){
				if(this.selectedCancelled.length > 0 && this.currentTab !== 0 && this.currentTab !== 3 && this.currentTab !== 4){
					show = true
				}
			}else{
				if(this.selectedCancelled.length > 0 && this.currentTab !== 2 && this.currentTab !== 3){
					show = true
				}
			}
			return show
		},
		creareSearchAndFilterButtonShow(){
			let show = false
			if(!this.isWarehouseConnected && !this.isWarehouse3PLProvider){
				if(this.selectedCancelled.length == 0 || this.currentTab == 2 || this.currentTab == 3){
					show = true
				}
			}else if(!this.isWarehouseConnected && this.isWarehouse3PLProvider){
				if(this.selectedCancelled.length == 0 || this.currentTab == 0 || this.currentTab == 3 || this.currentTab == 4){
					show = true
				}
			}else{
				if(this.selectedCancelled.length == 0 || this.currentTab == 2 || this.currentTab == 3){
					show = true
				}
			}
			return show
		}
	},
	methods: {
		...mapActions({
			fetchSingleProduct: "products/fetchSingleProduct",
			setEditInventory: "inventory/setEditInventory",
			deleteInventory: "inventory/deleteInventory",
			fetchInventories: "inventory/fetchInventories",
			setIsEditing: "inventory/setIsEditing",
			//
			cancelOutbound: "outbound/cancelOutbound",
			cancelMultipleOutboundApi: "outbound/cancelMultipleOutboundApi",
			deleteOutbound: "outbound/deleteOutbound",
			printOutboundOrder: "outbound/printOutboundOrder",
			fetchOutboundInventories: "outbound/fetchOutboundInventories",
			fetchPendingOutbounds: "outbound/fetchPendingOutbounds",
			fetchFloorOutbounds: "outbound/fetchFloorOutbounds",
			fetchCompletedOutbounds: "outbound/fetchCompletedOutbounds",
			fetchCancelledOutbounds: "outbound/fetchCancelledOutbounds",
			setCurrentOutboundTab: "outbound/setCurrentOutboundTab",
			// Products Dropdown
			setAllOutboundProductsLists: 'outbound/setAllOutboundProductsLists',
			fetchOutboundProducts:'outbound/fetchOutboundProducts',
			// Search Outbound
			setSearchedOutboundLoading: 'outbound/setSearchedOutboundLoading',
			setOutboundSearchedVal: 'outbound/setOutboundSearchedVal',
			fetchSearchedOutbounds: 'outbound/fetchSearchedOutbounds',
			// filtered Customer outbound
			setFilteredOutboundLoading:'outbound/setFilteredOutboundLoading',
			setOutboundFilteredVal:'outbound/setOutboundFilteredVal',
			fetchFilterOutboundsCustomers:'outbound/fetchFilterOutboundsCustomers',
			// 3PL
			multipleLoadProductApi:"outbound/multipleLoadProductApi",
			// 6PL
			wareHouse6plProduct: "outbound/wareHouse6plProduct",
			fetchWarehouseCustomersDropdown: "warehouseCustomers/fetchWarehouseCustomersDropdown",
            setAllWarehouseCustomerLists: 'warehouseCustomers/setAllWarehouseCustomerLists',
			fetchPendingShippingServiceProvider:'outbound/fetchPendingShippingServiceProvider',
			// connected 3pl Provider
			fetchDraftOutbounds: "outbound/fetchDraftOutbounds",
			fetchPendingShippedOutbounds: "outbound/fetchPendingShippedOutbounds",
			draftOutboundApi : "outbound/draftOutboundApi",
			AcceptConnectedOrderApi:"outbound/AcceptConnectedOrderApi",
			RejectConnectedOrderApi: "outbound/RejectConnectedOrderApi"

		}),
		...globalMethods,
		formatNewDate(date, time){
			let final_estimated_time_and_date = null;
			let newTime = null;
			let newDate = null;

			if (date !== "" && date !== null && date !== 'null') {
				newDate = moment(date).format("DD MMM YYYY ");				
			} else {
				newDate = ''
			}

			if (time !== "" && time !== null && time !== 'null') {
				newTime = moment(time, ["HH:mm"]).format("hh:mm A ");
			} else {
				newTime = ''
			}			
			if(!this.isWarehouseConnected){
			final_estimated_time_and_date = newDate + newTime
			}
			else {
			final_estimated_time_and_date = newTime + newDate
			}
			final_estimated_time_and_date = final_estimated_time_and_date !== '' ? final_estimated_time_and_date : '--'

			return final_estimated_time_and_date
		},
		formatCreatedAt(date_time){
			let final_estimated_time_and_date = null;
			if (typeof date_time !== "undefined" && date_time !==null && date_time !==undefined && date_time !=='') {
				final_estimated_time_and_date =	moment(date_time).format('DD MMM YYYY h:mm A');
			} else {
				final_estimated_time_and_date='--'
			}
			return final_estimated_time_and_date
		},
		onTabChange(i) {
			this.currentTab = i;
			this.setOutboundSearchedVal([])
			this.setOutboundFilteredVal([])
			this.SearchCustomerFinalArray = []
			this.SearchCustomerFinalArrayCopy = []
			this.selectedWhCustomer = []
			this.search = ''
			this.selectedCancelled = [];
			this.sendDataforSelectedCanceled = [];
			this.CancelOrderCondition=[]
		},
		// getTabCount(index) {
		// 	let data = "0";

		// 	if (index === 0) {
		// 		data = typeof this.getPendingOutbounds.total !== "undefined"
		// 			? this.getPendingOutbounds.total
		// 			: 0;
		// 	} else if (index === 1) {
		// 		data = typeof this.getFloorOutbounds.total !== "undefined"
		// 			? this.getFloorOutbounds.total
		// 			: 0;
		// 	} else if (index === 2) {
		// 		data = typeof this.getCompletedOutbounds.total !== "undefined"
		// 			? this.getCompletedOutbounds.total
		// 			: 0;
		// 	}

		// 	let finalData = data !== 0 ? data : "0";

		// 	return finalData;
		// },
		async onClickTab(i) {
			this.setOutboundSearchedVal([])
			this.setOutboundFilteredVal([])
			this.search = ''
			this.SearchCustomerFinalArray=[]
			this.SearchCustomerFinalArrayCopy = []
			this.selectedWhCustomer = []
			try {
				if (cancel !== undefined) {
					cancel("cancel_previous_request")
				}
				let storeOutboundTab = this.$store.state.outbound
                let dataWithPage = {
                    id: this.currentWarehouseSelected.id,
                    page: 1,
					cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                }
				if(!this.isWarehouseConnected && !this.isWarehouse3PLProvider){
					if (i === 0 && this.currentTab !== i) {
						dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
						await this.fetchPendingOutbounds(dataWithPage);
						storeOutboundTab.pendingOutboundPagination.old_page = storeOutboundTab.pendingOutboundPagination.current_page
						this.setCurrentOutboundTab(i);
					} else if (i === 1 && this.currentTab !== i) {
						dataWithPage.page = storeOutboundTab.floorOutboundPagination.current_page
						await this.fetchFloorOutbounds(dataWithPage);
						storeOutboundTab.floorOutboundPagination.old_page = storeOutboundTab.floorOutboundPagination.current_page
						this.setCurrentOutboundTab(i);
					} else if (i === 2 && this.currentTab !== i) {
						dataWithPage.page = storeOutboundTab.completedOutboundPagination.current_page
						await this.fetchCompletedOutbounds(dataWithPage);
						storeOutboundTab.completedOutboundPagination.old_page = storeOutboundTab.completedOutboundPagination.current_page
						this.setCurrentOutboundTab(i);
					} else if (i === 3 && this.currentTab !== i) {
						dataWithPage.page = storeOutboundTab.cancelledOutboundPagination.current_page
						Object.assign(dataWithPage, {status: ["cancelled"]})
						await this.fetchCancelledOutbounds(dataWithPage);
						storeOutboundTab.cancelledOutboundPagination.old_page = storeOutboundTab.cancelledOutboundPagination.current_page
						this.setCurrentOutboundTab(i);
					}	
				}else if(!this.isWarehouseConnected && this.isWarehouse3PLProvider){
					if (i === 0 && this.currentTab !== i) {
						dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
						await this.fetchPendingOutbounds(dataWithPage)
						storeOutboundTab.pendingOutboundPagination.old_page = storeOutboundTab.pendingOutboundPagination.current_page
						this.setCurrentOutboundTab(i);
					} else if (i === 1 && this.currentTab !== i) {
						dataWithPage.page = storeOutboundTab.pendingShippingProviderOutboundPagination.current_page
						await this.fetchPendingShippingServiceProvider(dataWithPage);
						storeOutboundTab.pendingShippingProviderOutboundPagination.old_page = storeOutboundTab.pendingShippingProviderOutboundPagination.current_page
						this.setCurrentOutboundTab(i);
					}  else if (i === 2 && this.currentTab !== i) {
						dataWithPage.page = storeOutboundTab.floorOutboundPagination.current_page
						await this.fetchFloorOutbounds(dataWithPage);
						storeOutboundTab.floorOutboundPagination.old_page = storeOutboundTab.floorOutboundPagination.current_page
						this.setCurrentOutboundTab(i);
					} else if (i === 3 && this.currentTab !== i) {
						dataWithPage.page = storeOutboundTab.completedOutboundPagination.current_page
						await this.fetchCompletedOutbounds(dataWithPage);
						storeOutboundTab.completedOutboundPagination.old_page = storeOutboundTab.completedOutboundPagination.current_page
						this.setCurrentOutboundTab(i);
					} else if (i === 4 && this.currentTab !== i) {
						dataWithPage.page = storeOutboundTab.cancelledOutboundPagination.current_page
						Object.assign(dataWithPage, {status: ["cancelled","rejected"]})
						await this.fetchCancelledOutbounds(dataWithPage);
						storeOutboundTab.cancelledOutboundPagination.old_page = storeOutboundTab.cancelledOutboundPagination.current_page
						this.setCurrentOutboundTab(i);
					}	
				} else{
					if (i === 0 && this.currentTab !== i) {
						dataWithPage.page = storeOutboundTab.draftOutboundPagination.current_page
						await this.fetchDraftOutbounds(dataWithPage)
						storeOutboundTab.draftOutboundPagination.old_page = storeOutboundTab.draftOutboundPagination.current_page
						this.setCurrentOutboundTab(i);
					} else if (i === 1 && this.currentTab !== i) {
						dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
						await this.fetchPendingOutbounds(dataWithPage);
						storeOutboundTab.pendingOutboundPagination.old_page = storeOutboundTab.pendingOutboundPagination.current_page
						this.setCurrentOutboundTab(i);
					}  else if (i === 2 && this.currentTab !== i) {
						dataWithPage.page = storeOutboundTab.pendingShippedOutboundPagination.current_page
						await this.fetchPendingShippedOutbounds(dataWithPage)
						storeOutboundTab.pendingShippedOutboundPagination.old_page = storeOutboundTab.pendingShippedOutboundPagination.current_page
						this.setCurrentOutboundTab(i);
					} else if (i === 3 && this.currentTab !== i) {
						dataWithPage.page = storeOutboundTab.completedOutboundPagination.current_page
						await this.fetchCompletedOutbounds(dataWithPage);
						storeOutboundTab.completedOutboundPagination.old_page = storeOutboundTab.completedOutboundPagination.current_page
						this.setCurrentOutboundTab(i);
					} else if (i === 4 && this.currentTab !== i) {
						dataWithPage.page = storeOutboundTab.cancelledOutboundPagination.current_page
						Object.assign(dataWithPage, {status: ["cancelled","rejected"]})
						await this.fetchCancelledOutbounds(dataWithPage);
						storeOutboundTab.cancelledOutboundPagination.old_page = storeOutboundTab.cancelledOutboundPagination.current_page
						this.setCurrentOutboundTab(i);
					}	
				}
				
			} catch (e) {
				this.notificationError(e);
				// console.log(e);
			}
		},
		// outboundActions
		createOutbound() {
			this.dialogCreate = true;
			this.$nextTick(() => {
				this.editedOutboundItems = Object.assign({}, this.defaultOutboundItems);
				this.editedOutboundIndex = -1;
			});
			if(this.isWarehouse3PLProvider == true && !this.isWarehouseConnected){
				this.fetchOutboundProductsAPiFunction('6plProvider','nothing')
			}else{
				this.fetchOutboundProductsAPiFunction('Outbound','nothing')
			}
		},
		closeCreate() {
			this.dialogCreate = false;
			this.$nextTick(() => {
				this.editedOutboundItems = Object.assign({}, this.defaultOutboundItems);
				this.editedOutboundIndex = -1;
			});
			if (this.currentEditInboundData !== null) {
                this.closeWarning()
            }
		},
		viewOutbound(e) {
			this.$router.push(
				`inventory/outbound-view?id=${e.id}&wid=${this.currentWarehouseSelected.id}`
			);
		},
		async editOrder(outbound, toDo) {
			this.editedOutboundIndex =
			this.currentWarehouseOutbounds.data.indexOf(outbound);
			let outboundItem = { ...outbound };
			this.outboundProducts_clone =outbound.outbound_products
			this.outbound_Status_for_3pl = outboundItem.outbound_status
			if (!outboundItem.outbound_products ||
				outboundItem.outbound_products.length === 0) {
				let buildProduct = [
				{
					product_id: "",
					quantity: "",
					shipping_unit: "",
					total_unit:'',
					status: '',
					outbound_product_id:null,
					pr_id:'',
					remaining_carton_count:0,
					remaining_total_unit:0,
					instructions:''
				},
				];

				outboundItem = { ...outboundItem, outbound_products: buildProduct };
			} else {
				// let	buildProduct = outboundItem.outbound_products.map((v) => ({
				// 	product_id: v.product_id,
				// 	quantity: v.carton_count !== null ? parseInt(v.carton_count) : "",
				// 	shipping_unit: v.shipping_unit,
				// 	total_unit: v.total_unit !== null ? parseInt(v.total_unit) : "",
				// 	status: v.status,
				// 	outbound_product_id:v.id,
				// 	remaining_carton_count:v.remaining_carton_count !==null ?v.remaining_carton_count:0,
				// 	remaining_total_unit:v.remaining_total_unit !==null ? v.remaining_total_unit:0,
				// 	pr_id:v.product_id
				// }))
				let buildProduct = outboundItem.outbound_products.map(v => {
                    let { id, product_id, carton_count,total_unit, shipping_unit, remaining_total_unit, remaining_carton_count } = v

                    if (shipping_unit === 'carton') {
                        return { 
                            outbound_product_id: id,
                            product_id,
                            quantity: carton_count !== null ? parseInt(carton_count) : '',
                            shipping_unit,
                            status: v.status,
                            carton_count: carton_count !== null ? parseInt(carton_count) : '',
							total_unit: total_unit !== null ? parseInt(total_unit) : "",
                            remaining_carton_count: remaining_carton_count !== null ? parseInt(remaining_carton_count) : 0,
							remaining_total_unit: remaining_total_unit !== null ? parseInt(remaining_total_unit) : 0,
							pr_id:v.product_id,
							instructions:v.instructions !== null ? v.instructions :''
                        }
                    } else {
                        return { 
                            outbound_product_id: id,
                            product_id,
                            quantity: total_unit !== null ? parseInt(total_unit) : '',
                            shipping_unit,
							status: v.status,
							carton_count: carton_count !== null ? parseInt(carton_count) : '',
							total_unit: total_unit !== null ? parseInt(total_unit) : "",
                            remaining_total_unit: remaining_total_unit !== null ? parseInt(remaining_total_unit) : '',
                            remaining_carton_count: remaining_carton_count !== null ? parseInt(remaining_carton_count) : 0,
							pr_id:v.product_id,
							instructions:v.instructions !== null ? v.instructions :''
                        }
                    }
                })

				// let productStatusCheck=[]
				// let buildProduct=[]

				// if(toDo == "edit"){
				// 	productStatusCheck = outboundItem.outbound_products.filter(val=>val.status !=='picked')
				// 	buildProduct = productStatusCheck.map((v) => ({
				// 	product_id: v.product_id,
				// 	quantity: v.carton_count !== null ? parseInt(v.carton_count) : "",
				// 	shipping_unit: v.shipping_unit,
				// 	total_unit: v.total_unit !== null ? parseInt(v.total_unit) : "",
				// }));
				// }else{
				// 	productStatusCheck =outboundItem.outbound_products
				// 	buildProduct = productStatusCheck.map((v) => ({
				// 	product_id: v.product_id,
				// 	quantity: v.carton_count !== null ? parseInt(v.carton_count) : "",
				// 	shipping_unit: v.shipping_unit,
				// 	total_unit: v.total_unit !== null ? parseInt(v.total_unit) : "",
				// }));}
				// if(!productStatusCheck || productStatusCheck.length==0){
				// 	buildProduct= [
				// {
				// 	product_id: "",
				// 	quantity: "",
				// 	shipping_unit: "",
				// 	total_unit:""
				// },
				// ];
				// }

				outboundItem = { ...outboundItem, outbound_products: buildProduct };
			}

			if (!outboundItem.outbound_documents ||
				outboundItem.outbound_documents == null) {
				outboundItem = { ...outboundItem, outbound_documents: [] };
			} else {
				let convertDocuments = outboundItem.outbound_documents.map((v) => {
					if (v.original_name !== "undefined") {
						return new File([v.original_name], `${v.original_name}`, {
							type: v.type,
						});
					}
				});

				outboundItem = {
					...outboundItem,
					outbound_documents: convertDocuments,
				};
			}

			if (!outboundItem.estimated_time ||
				outboundItem.estimated_time == "null") {
				outboundItem = { ...outboundItem, estimated_time: null };
			}

			if (!outboundItem.estimated_date ||
				outboundItem.estimated_date == "null") {
				outboundItem = { ...outboundItem, estimated_date: null };
			}

			// storable units
			if (!outboundItem.outbound_storable_units ||
				outboundItem.outbound_storable_units.length === 0) {
				let buildSortable = [
					{
						id: null,
						outbound_id: null,
						warehouse_id: null,
						customer_id: null,
						action: null,
						label: null,
						type: "",
						created_at: "",
						updated_at: "",
						parse_dimensions: {
							l: "",
							w: "",
							h: "",
							uom: "",
						},
						parse_weight: {
							value: "",
							unit: "",
						},
						outbound_storable_unit_products: [
							{
							id: null,
							outbound_product_id: null,
							outbound_storable_unit_id: null,
							carton_count: "",
							total_unit: "",
							in_each_carton: "",
							storable_unit_product_id: null,
							created_at: "",
							updated_at: "",
							},
						],
						products: null,
						status: "",
						no_of_sku: null,
						deleted_at: null,
						storable_unit_id: null,
					},
				],

				outboundItem = {
					...outboundItem,
					outbound_storable_units: buildSortable,
				};
			} else {
				let buildSortable = outboundItem.outbound_storable_units.map((v) => ({
					id: v.id,
					outbound_id: v.outbound_id,
					warehouse_id: v.warehouse_id,
					customer_id: v.customer_id,
					action: v.action,
					label: v.label,
					type: v.type,
					created_at: v.created_at,
					updated_at: v.updated_at,
					parse_dimensions: v.dimension,
					parse_weight: v.weight,
					products: v.products,
					status: v.status,
					no_of_sku: v.no_of_sku,
					deleted_at: v.deleted_at,
					storable_unit_id: v.storable_unit_id,
					outbound_storable_unit_products: v.outbound_storable_unit_products,
				}));

				outboundItem = {
					...outboundItem,
					outbound_storable_units: buildSortable,
				};
			}

			// if (!outboundItem.outbound_storable_units || outboundItem.outbound_storable_units.length === 0) {
			//     let buildProduct = [
			//         {
			//             product_id: '',
			//             quantity: '',
			//             shipping_unit: ''
			//         }
			//     ]

			//     outboundItem = { ...outboundItem, outbound_storable_units: buildProduct }

			// } else {
			//     let buildProduct = outboundItem.outbound_storable_units.map(v => ({
			//         product_id: v.product_id,
			//         quantity: v.carton_count !== null ? parseInt(v.carton_count) : '',
			//         shipping_unit: v.shipping_unit
			//     }))

			//     outboundItem = { ...outboundItem, outbound_storable_units: buildProduct }
			// }

			outboundItem.isDuplicate = toDo == "edit" ? false : true;
			this.editedOutboundIndex = outboundItem.isDuplicate
				? -1
				: this.editedOutboundIndex;
			// if is duplicate, set order id to empty
			outboundItem.order_id = outboundItem.isDuplicate
				? ""
				: outboundItem.order_id;

			this.editedOutboundItems = Object.assign({}, outboundItem);
			this.dialogCreate = true;
			if(this.isWarehouse3PLProvider ==true && !this.isWarehouseConnected){
				let warehouse_data = {
					id:outboundItem.warehouse_customer_id
				}
				await this.Warehouse6PL_ProductsOnchange(warehouse_data)
				await this.callWarehouseCustomerListsData('nothing')
			}
			else{
				this.fetchOutboundProductsAPiFunction('Outbound','nothing')
			}
		},
		// arrangeTracking(e) {
		// 	console.log("arrange", e);
		// },
		cancel(outbound) {
			if (outbound !== null) {
				this.cancelOrderData = outbound;
				this.dialogCancel = true;
			}
		},
		canceldelete(outbound) {
			if (outbound !== null) {
				this.deleteOrderData = outbound;
				this.dialogCanceldelete = true;
			}
		},
		async cancelConfirm() {
			if (this.cancelOrderData !== null) {
				try {
					let payload = {
						wid: this.cancelOrderData.warehouse_id,
						oid: this.cancelOrderData.id,
					};

					await this.cancelOutbound(payload);

					this.notificationMessage("Outbound has been cancelled.");
					try {
					let storeOutboundTab = this.$store.state.outbound 
					let dataWithPage = { id: this.currentWarehouseSelected.id,page: 1}
						if(!this.isWarehouseConnected && !this.isWarehouse6PL){
							if (this.currentTab == 0) {
								dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
								await this.fetchPendingOutbounds(dataWithPage);
							}
						
							if (this.currentTab == 1) {
								dataWithPage.page = storeOutboundTab.floorOutboundPagination.current_page
								await this.fetchFloorOutbounds(dataWithPage);
							}
						}else if(!this.isWarehouseConnected && this.isWarehouse6PL){
							if(this.currentTab == 0){
								dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
								await this.fetchPendingOutbounds(dataWithPage);
							}else if(this.currentTab == 1){
								dataWithPage.page = storeOutboundTab.pendingShippingProviderOutboundPagination.current_page
								await this.fetchPendingShippingServiceProvider(dataWithPage)
							}else {
								if(this.currentTab == 2){
									dataWithPage.page = storeOutboundTab.floorOutboundPagination.current_page
									await this.fetchFloorOutbounds(dataWithPage);
								}
							}
						}else{
							if(this.currentTab == 1){
								dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
								await this.fetchPendingOutbounds(dataWithPage);
							}
						}
						
						if(this.isWarehouse3PLProvider ==true && !this.isWarehouseConnected){
							let warehouse_data = {
								id:this.cancelOrderData.warehouse_customer_id
							}
							this.setAllOutboundProductsLists([])
							await this.Warehouse6PL_ProductsOnchange(warehouse_data)
							await this.callWarehouseCustomerListsData('nothing')
						} else{
							this.setAllOutboundProductsLists([])
							this.fetchOutboundProductsAPiFunction('Outbound','nothing')
						}
						this.dialogCancel = false;
						this.cancelOrderData = null;
					} catch (e) {
						this.notificationError(e);
					}
				} catch (e) {
					this.notificationError(e);
				}
			}
		},
		async cancelMultipleConfirm() {
			if (this.sendDataforSelectedCanceled.length > 0) {
				let payload = {
					wid: this.currentWarehouseSelected.id,
					idss: this.sendDataforSelectedCanceled,
				};

				try {
				let storeOutboundTab = this.$store.state.outbound 
					let dataWithPage = { id: this.currentWarehouseSelected.id,page: 1}
					await this.cancelMultipleOutboundApi(payload);
					this.notificationMessage("Outbounds has been cancelled.");
					if(!this.isWarehouseConnected && !this.isWarehouse3PLProvider){
						if (this.currentTab == 0) {
							dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
							await this.fetchPendingOutbounds(dataWithPage);
						}
						if (this.currentTab == 1) {
							dataWithPage.page = storeOutboundTab.floorOutboundPagination.current_page
							await this.fetchFloorOutbounds(dataWithPage);
						}
					}else if(!this.isWarehouseConnected && this.isWarehouse6PL){
							if(this.currentTab == 0){
								dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
								await this.fetchPendingOutbounds(dataWithPage);
							}else if(this.currentTab == 1){
								dataWithPage.page = storeOutboundTab.pendingShippingProviderOutboundPagination.current_page
								await this.fetchPendingShippingServiceProvider(dataWithPage)
							}else {
								if(this.currentTab == 2){
									dataWithPage.page = storeOutboundTab.floorOutboundPagination.current_page
									await this.fetchFloorOutbounds(dataWithPage);
								}
							}
					}else{
						if(this.currentTab == 1){
							dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
							await this.fetchPendingOutbounds(dataWithPage);
						}
					}
					this.setAllOutboundProductsLists([])
					this.fetchOutboundProductsAPiFunction('Outbound','nothing')
					this.dialogCancelMultiple = false;
					this.sendDataforSelectedCanceled = [];
					this.selectedCancelled = [];
					this.CancelOrderCondition=[];
				} catch (e) {
					this.notificationError(e);
				}
			}
		},
		async deleteConfirm() {
			if (this.deleteOrderData !== null) {
				try {
					let payload = {
						wid: this.deleteOrderData.warehouse_id,
						oid: this.deleteOrderData.id,
					};
					let storeOutboundTab = this.$store.state.outbound
					let dataWithPage = { id: this.currentWarehouseSelected.id,page: 1}
					
						await this.deleteOutbound(payload);
						this.notificationMessage("Outbound has been deleted.");
						try {
							if(!this.isWarehouseConnected){
								dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
								await this.fetchPendingOutbounds(dataWithPage);
							}else{
								dataWithPage.page = storeOutboundTab.draftOutboundPagination.current_page
								await this.fetchDraftOutbounds(dataWithPage);
							}
					
							this.dialogCanceldelete = false;
							this.deleteOrderData = null;
						} catch (e) {
							this.notificationError(e);
						}
					
				} catch (e) {
				this.notificationError(e);
				}
			}
		},
		closeCancel() {
			this.dialogCancel = false;
		},
		closeCancelMultiple() {
			this.dialogCancelMultiple = false;
			this.sendDataforSelectedCanceled = [];
			this.CancelOrderCondition=[]
		},
		closeDelete() {
			this.dialogCanceldelete = false;
		},
		clearCancelation() {
			this.selectedCancelled = [];
			this.sendDataforSelectedCanceled = [];
			this.CancelOrderCondition=[]
		},
		cancelMultipleOrder() {
			this.CancelOrderCondition =	this.selectedCancelled.filter(value =>{
				if(this.currentTab == 1 && this.isWarehouse3PLProvider == true && this.isWarehouseConnected == false && value.is_from_connected_3pl == 1){
					return
				}else{
					return value.outbound_storable_units.every(check =>{
						if(check.status =='loaded'){
							return
						}else{
							return value
						}
					})
				}
				 
			})
			this.CancelOrderCondition.filter((val) => {
				
				if (this.sendDataforSelectedCanceled.includes(val.id)) {
					return;
				} else {
					this.sendDataforSelectedCanceled.push(val.id);
				}
			});

			if (this.CancelOrderCondition.length) {
				this.dialogCancelMultiple = true;
			}
			else if(this.selectedCancelled.length){
				this.cancelOrder_StorableLoaded=true
			}
		},
		async printOrder(item) {
			if (item !== null) {
				let payload = {
					wid: item.warehouse_id,
					oid: item.id,
					order_id: item.order_id,
				};

				try {
					this.notificationCustom("Generating print order...");
					await this.printOutboundOrder(payload);
				} catch (e) {
					this.notificationError(e);
				}
			}
		},
		ActionButtonShow(item) {
			var confirm = true;
			if (this.selectedCancelled.length > 0) {
				this.selectedCancelled.find((val) => {
					if (val.order_id == item) {
						confirm = false;
					}
				});
			}
			return confirm;
		},
		// loadOutboundDataChecking() {
		// 	if(this.search ==='' && this.SearchCustomerFinalArray.length ===0){
        //         if (this.currentTab === 0) {
        //             if (typeof this.getPendingOutboundPagination !== 'undefined' && 
        //                 this.getPendingOutboundPagination !== null) {
        //                 return this.getPendingOutboundPagination
        //             }
        //         } else if (this.currentTab === 1) {
        //             if (typeof this.getFloorOutboundPagination !== 'undefined' && 
        //                 this.getFloorOutboundPagination !== null) {
        //                 return this.getFloorOutboundPagination
        //             }
        //         } else if (this.currentTab === 2) {
        //             if (typeof this.getCompletedOutboundPagination !== 'undefined' && 
        //                 this.getCompletedOutboundPagination !== null) {
        //                 return this.getCompletedOutboundPagination
        //             }
        //         } else if (this.currentTab === 3) {
        //             if (typeof this.getCancelledOutboundPagination !== 'undefined' && 
        //                 this.getCancelledOutboundPagination !== null) {
        //                 return this.getCancelledOutboundPagination
        //             }
        //         }
		// 	}

		// 	else if(this.search !=='' && this.SearchCustomerFinalArray.length ===0){
		// 		if (this.currentTab === 0) {
        //             if (typeof this.getSearchedOutbounds !== 'undefined' && 
        //                 this.getSearchedOutbounds !== null && this.getSearchedOutbounds.tab =='pending') {
        //                 return this.getSearchedOutbounds
        //             }
        //         } else if (this.currentTab === 1) {
        //             if (typeof this.getSearchedOutbounds !== 'undefined' && 
        //                 this.getSearchedOutbounds !== null && this.getSearchedOutbounds.tab =='floor') {
        //                 return this.getSearchedOutbounds
        //             }
        //         } else if (this.currentTab === 2) {
        //             if (typeof this.getSearchedOutbounds !== 'undefined' && 
        //                 this.getSearchedOutbounds !== null && this.getSearchedOutbounds.tab =='completed') {
        //                 return this.getSearchedOutbounds
        //             }
        //         } else if (this.currentTab === 3) {
        //             if (typeof this.getSearchedOutbounds !== 'undefined' && 
        //                 this.getSearchedOutbounds !== null && this.getSearchedOutbounds.tab =='cancelled') {
        //                 return this.getSearchedOutbounds
        //             }
        //         }
		// 	}
		// 	else{
		// 		if(this.SearchCustomerFinalArray.length >0){
		// 		if (this.currentTab === 0) {
        //             if (typeof this.getfilteredOutbounds !== 'undefined' && 
        //                 this.getfilteredOutbounds !== null && this.getfilteredOutbounds.tab =='pending') {
        //                 return this.getfilteredOutbounds
        //             }
        //         } else if (this.currentTab === 1) {
        //             if (typeof this.getfilteredOutbounds !== 'undefined' && 
        //                 this.getfilteredOutbounds !== null && this.getfilteredOutbounds.tab =='floor') {
        //                 return this.getfilteredOutbounds
        //             }
        //         } else if (this.currentTab === 2) {
        //             if (typeof this.getfilteredOutbounds !== 'undefined' && 
        //                 this.getfilteredOutbounds !== null && this.getfilteredOutbounds.tab =='completed') {
        //                 return this.getfilteredOutbounds
        //             }
        //         } else if (this.currentTab === 3) {
        //             if (typeof this.getfilteredOutbounds !== 'undefined' && 
        //                 this.getfilteredOutbounds !== null && this.getfilteredOutbounds.tab =='cancelled') {
        //                 return this.getfilteredOutbounds
        //             }
        //         }
		// 		}
		// 	}
        // },
		 getTabCount(index) {
            let data = '0'
			if(!this.isWarehouseConnected && !this.isWarehouse3PLProvider){
				if (index === 0) {
                	data = this.pendingOutboundsDataLists.total
            	} else if (index === 1) {
                	data = this.floorOutboundsDataLists.total
            	}
			} else if(!this.isWarehouseConnected && this.isWarehouse3PLProvider){
				if (index === 0) {
                    data = this.pendingOutboundsDataLists.total 
                } else if (index === 1) {
                    data = this.pendingShippingProviderOutboundsDataLists.total
                } else if (index === 2) {
                    data = this.floorOutboundsDataLists.total
                }
			} else{
				if (index === 0) {
                    data = this.draftOutboundsDataLists.total 
                } else if (index === 1) {
                    data = this.pendingOutboundsDataLists.total
                } else if (index === 2) {
                    data = this.pendingShippedOutboundsDataLists.total
                }
			}
            
          
            let finalData = data !== 0 ? data : '0'

            return finalData
        },
		async handlePageChange(page) {
			this.handleScrollToTop()
            if (page !== null) {
                let storeOutboundTab = this.$store.state.outbound

                let dataWithPage = {
                    id: this.currentWarehouseSelected.id,
                    page
                }

                if (this.search == '' && this.SearchCustomerFinalArray.length ===0) {
                    try {
						if(!this.isWarehouseConnected && !this.isWarehouse3PLProvider){
							if (storeOutboundTab.setCurrentTab === 0 && this.currentTab === 0) {
                            	if (storeOutboundTab.pendingOutboundPagination.old_page !== page) {
                            	    this.pendingOutboundLoadingPage = true
                            	    await this.fetchPendingOutbounds(dataWithPage)
                            	    storeOutboundTab.pendingOutboundPagination.old_page = page
                            	    this.pendingOutboundLoadingPage = false
                            	}
                        	} else if (storeOutboundTab.setCurrentTab === 1 && this.currentTab === 1) {
                        	    if (storeOutboundTab.floorOutboundPagination.old_page !== page) {
                        	        this.floorOutboundLoadingPage = true
                        	        await this.fetchFloorOutbounds(dataWithPage)
                        	        storeOutboundTab.floorOutboundPagination.old_page = page
                        	        this.floorOutboundLoadingPage = false
                        	    }
                        	} else if (storeOutboundTab.setCurrentTab === 2 && this.currentTab === 2) {
                        	    if (storeOutboundTab.completedOutboundPagination.old_page !== page) {
                        	        this.completedOutboundLoadingPage = true
                        	        await this.fetchCompletedOutbounds(dataWithPage)
                        	        storeOutboundTab.completedOutboundPagination.old_page = page
                        	        this.completedOutboundLoadingPage = false
                        	    }
                        	} else if(storeOutboundTab.setCurrentTab === 3 && this.currentTab === 3){
                        	    if (storeOutboundTab.cancelledOutboundPagination.old_page !== page) {
                        	        this.cancelledOutboundLoadingPage = true
									Object.assign(dataWithPage, {status: ["cancelled","rejected"]})
                        	        await this.fetchCancelledOutbounds(dataWithPage)
                        	        storeOutboundTab.cancelledOutboundPagination.old_page = page
                        	        this.cancelledOutboundLoadingPage = false
                        	    }
                        	}
						}else if(!this.isWarehouseConnected && this.isWarehouse3PLProvider){
					
							if (storeOutboundTab.setCurrentTab === 0 && this.currentTab === 0) {
                            	if (storeOutboundTab.pendingOutboundPagination.old_page !== page) {
                            	    this.pendingOutboundLoadingPage = true
                            	    await this.fetchPendingOutbounds(dataWithPage)
                            	    storeOutboundTab.pendingOutboundPagination.old_page = page
                            	    this.pendingOutboundLoadingPage = false
                            	}
                        	} else if (storeOutboundTab.setCurrentTab === 1 && this.currentTab === 1) {
                        	    if (storeOutboundTab.pendingShippingProviderOutboundPagination.old_page !== page) {
                        	        this.pendingOutboundLoadingPage = true
                        	        await this.fetchPendingShippingServiceProvider(dataWithPage)
                        	        storeOutboundTab.pendingShippingProviderOutboundPagination.old_page = page
                        	        this.pendingOutboundLoadingPage = false
                        	    }
                        	} else if (storeOutboundTab.setCurrentTab === 2 && this.currentTab === 2) {
                        	    if (storeOutboundTab.floorOutboundPagination.old_page !== page) {
                        	        this.floorOutboundLoadingPage = true
                        	        await this.fetchFloorOutbounds(dataWithPage)
                        	        storeOutboundTab.floorOutboundPagination.old_page = page
                        	        this.floorOutboundLoadingPage = false
                        	    }
                        	} else if (storeOutboundTab.setCurrentTab === 3 && this.currentTab === 3) {
                        	    if (storeOutboundTab.completedOutboundPagination.old_page !== page) {
                        	        this.completedOutboundLoadingPage = true
                        	        await this.fetchCompletedOutbounds(dataWithPage)
                        	        storeOutboundTab.completedOutboundPagination.old_page = page
                        	        this.completedOutboundLoadingPage = false
                        	    }
                        	} else if(storeOutboundTab.setCurrentTab === 4 && this.currentTab === 4){
                        	    if (storeOutboundTab.cancelledOutboundPagination.old_page !== page) {
                        	        this.cancelledOutboundLoadingPage = true
									Object.assign(dataWithPage, {status: ["cancelled","rejected"]})
                        	        await this.fetchCancelledOutbounds(dataWithPage)
                        	        storeOutboundTab.cancelledOutboundPagination.old_page = page
                        	        this.cancelledOutboundLoadingPage = false
                        	    }
                        	}
						}
						else{
							if (storeOutboundTab.setCurrentTab === 0 && this.currentTab === 0) {
                            	if (storeOutboundTab.draftOutboundPagination.old_page !== page) {
                            	    this.draftOutboundLoadingPage = true
                            	    await this.fetchDraftOutbounds(dataWithPage)
                            	    storeOutboundTab.draftOutboundPagination.old_page = page
                            	    this.draftOutboundLoadingPage = false
                            	}
                        	} else if (storeOutboundTab.setCurrentTab === 1 && this.currentTab === 1) {
                        	    if (storeOutboundTab.pendingOutboundPagination.old_page !== page) {
                        	        this.pendingOutboundLoadingPage = true
                        	        await this.fetchPendingOutbounds(dataWithPage)
                        	        storeOutboundTab.pendingOutboundPagination.old_page = page
                        	        this.pendingOutboundLoadingPage = false
                        	    }
                        	} else if (storeOutboundTab.setCurrentTab === 2 && this.currentTab === 2) {
                        	    if (storeOutboundTab.pendingShippedOutboundPagination.old_page !== page) {
                        	        this.pendingShippedOutboundLoadingPage = true
                        	        await this.fetchPendingShippedOutbounds(dataWithPage)
                        	        storeOutboundTab.pendingShippedOutboundPagination.old_page = page
                        	        this.pendingShippedOutboundLoadingPage = false
                        	    }
                        	} else if (storeOutboundTab.setCurrentTab === 3 && this.currentTab === 3) {
                        	    if (storeOutboundTab.completedOutboundPagination.old_page !== page) {
                        	        this.completedOutboundLoadingPage = true
                        	        await this.fetchCompletedOutbounds(dataWithPage)
                        	        storeOutboundTab.completedOutboundPagination.old_page = page
                        	        this.completedOutboundLoadingPage = false
                        	    }
                        	} else if(storeOutboundTab.setCurrentTab === 4 && this.currentTab === 4){
                        	    if (storeOutboundTab.cancelledOutboundPagination.old_page !== page) {
                        	        this.cancelledOutboundLoadingPage = true
									Object.assign(dataWithPage, {status: ["cancelled","rejected"]})
                        	        await this.fetchCancelledOutbounds(dataWithPage)
                        	        storeOutboundTab.cancelledOutboundPagination.old_page = page
                        	        this.cancelledOutboundLoadingPage = false
                        	    }
                        	}	
						}
                        
                    } catch (e) {
                        this.notificationError(e)
                    }
					}
					else if(this.search !=='' && this.SearchCustomerFinalArray.length ===0) {
                    let data = {
                        search: this.search,
                        page
                    }

                    this.handlePageSearched(data)
                }
				else{
					if(this.search !=='' && this.SearchCustomerFinalArray.length > 0){
						
						let	data = {
							filter_data : this.SearchCustomerFinalArray.map(val => val.id),
							search_data:this.search,
							wid:dataWithPage.id,
							page
						}
						
						this.handleFilterWithSearchFunction(data)
					}else{
						if(this.search ==='' && this.SearchCustomerFinalArray.length > 0){
							let	data = {
							filter_data : this.SearchCustomerFinalArray.map(val => val.id),
							search_data:this.search,
							wid:dataWithPage.id,
							page
						}
						this.handleFilterOnlyFunction(data)
						}
					}
					
				}
            }
        },
		async handlePageSearched(data) {
			this.handleScrollToTop()
            let searchedPagination = this.$store.state.outbound

            if (data !== null && this.search !== '') {
                if (searchedPagination.searchedOutbounds.old_page !== data.page) {
					let warehouse_id = this.currentWarehouseSelected.id
                    let passedData = {
						method: "get",
                        url: `${this.poBaseUrlState}/warehouse/${warehouse_id}/outbound/pending`,
						cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: {
                            search: this.search,
                            page: data.page
                        }
                    }
                    
					if(!this.isWarehouseConnected && !this.isWarehouse3PLProvider){
						if (this.currentTab == 0) {
                        	passedData.params.status = ["pending"]
                        	passedData.tab = 'pending'
                    	} else if (this.currentTab == 1) {
                    	    passedData.params.status = ["floor", "ready"]
                    	    passedData.tab = 'floor'
                    	} else if (this.currentTab == 2) {
                    	    passedData.params.status = ["completed"]
                    	    passedData.tab = 'completed'
                    	} else {
                    	    passedData.params.status = ["cancelled"]
                    	    passedData.tab = 'cancelled'
                    	}
					} else if(!this.isWarehouseConnected && this.isWarehouse3PLProvider){
						if (this.currentTab == 0) {
                        	passedData.params.status = ["pending"]
                        	passedData.tab = 'pending'
                    	} else if (this.currentTab == 1) {
                    	    passedData.params.status = ["shipping-pending"]
                    	    passedData.tab = 'shipping-pending'
                    	} else if (this.currentTab == 2) {
                    	    passedData.params.status = ["floor", "ready"]
                    	    passedData.tab = 'floor'
                    	} else if (this.currentTab == 3) {
                    	    passedData.params.status = ["completed"]
                    	    passedData.tab = 'completed'
                    	} else {
                    	    passedData.params.status = ["cancelled","rejected"]
                    	    passedData.tab = 'cancelled'
                    	}
					}
					else {
						if (this.currentTab == 0) {
                        	passedData.params.status = ['draft']
                        	passedData.tab = 'draft'
                    	} else if (this.currentTab == 1) {
                    	    passedData.params.status = ["pending"]
                        	passedData.tab = 'pending'
                    	} else if (this.currentTab == 2) {
                    	    passedData.params.status = ["shipping-pending", "ready", "floor"],
							passedData.params.tab = 'pending-shipping'
                        	passedData.tab = 'shipping-pending-for-warehouse-customer'
                    	} else if (this.currentTab == 2) {
                    	    passedData.params.status = ["completed"]
                    	    passedData.tab = 'completed'
                    	} else {
                    	    passedData.params.status = ["cancelled","rejected"]
                    	    passedData.tab = 'cancelled'
                    	}
					}
                    

                    if (passedData.url !== '') {
                        try {
                            await this.fetchSearchedOutbounds(passedData)
							searchedPagination.searchedOutbounds.old_page = data.page
                        } catch(e) {
                            this.notificationError(e)
                            this.setSearchedOutboundLoading(false)
                            console.log(e, 'handle page search error')
                        }
                    }
                }                
            } else {
                this.setOutboundSearchedVal([])
            }
        },
		async handleFilterWithSearchFunction(data){
			let filterPagination = this.$store.state.outbound
			if (data !== null && data.filter_data !==null &&  this.search !== '' && this.SearchCustomerFinalArray.length >0) {
                if (filterPagination.filteredOutbounds.old_page !== data.page) {
                    this.setFilteredOutboundLoading(true)
					var searchParams = new URLSearchParams();
					for(var ser =0 ;ser <data.filter_data.length;ser++){
					searchParams.append(`ids[]`, data.filter_data[ser])
					}
					searchParams.append('page',data.page)
                	let passedData = {
                    method: "get",
                    url: `${this.poBaseUrlState}/warehouse/${data.wid}/outbound/common?search=${data.search_data}&filter=true`,
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: searchParams
               		}

					if (this.currentTab == 0) {
						passedData.params.status = ["pending"]
                    	passedData.tab = 'pending'
                	} else if (this.currentTab == 1) {
						passedData.params.status = ["shipping-pending"]
                    	passedData.tab = 'shipping-pending'
                	} else if (this.currentTab == 2) {
						passedData.params.status = ["floor", "ready"]
                    	passedData.tab = 'floor'
                	} else if (this.currentTab == 3) {
						passedData.params.status = ["completed"]
                    	passedData.tab = 'completed'
                	} else {
						passedData.params.status = ["cancelled","rejected"]
                    	passedData.tab = 'cancelled'
                	}
					for(var status_parm =0 ;status_parm <passedData.params.status.length;status_parm++){
						searchParams.append(`status[]`, passedData.params.status[status_parm])
					}

                	if (passedData.url !== '') {
                    try {
                       	await this.fetchFilterOutboundsCustomers(passedData)
						filterPagination.filteredOutbounds.old_page = data.page
                    } catch(e) {
                        this.notificationError(e)
                        this.setFilteredOutboundLoading(false)
                        console.log(e, 'filter with serch error')
                    }
                	}
                }else{
					this.setOutboundFilteredVal([])
				}               
            } 
		},
		async handleFilterOnlyFunction(data){
			let filterPagination = this.$store.state.outbound
			if (data !== null && data.filter_data !==null &&  this.search === '' && this.SearchCustomerFinalArray.length >0) {
                if (filterPagination.filteredOutbounds.old_page !== data.page) {
                    this.setFilteredOutboundLoading(true)
					var searchParams = new URLSearchParams();
					for(var ser =0 ;ser <data.filter_data.length;ser++){
					searchParams.append(`ids[]`, data.filter_data[ser])
					}
					searchParams.append('page',data.page)
                	let passedData = {
                    method: "get",
                    url: `${this.poBaseUrlState}/warehouse/${data.wid}/outbound/common?filter=true`,
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: searchParams
               		}

					if (this.currentTab == 0) {
                    passedData.params.status = ["pending"]
                    passedData.tab = 'pending'
                	} else if (this.currentTab == 1) {
					passedData.params.status = ["shipping-pending"]
                    passedData.tab = 'shipping-pending'
                	} else if (this.currentTab == 2) {
					passedData.params.status = ["floor", "ready"]
                    passedData.tab = 'floor'
                	} else if (this.currentTab == 3) {
                    passedData.params.status = ["completed"]
                    passedData.tab = 'completed'
                	} else {
                    passedData.params.status = ["cancelled","rejected"]
                    passedData.tab = 'cancelled'
                	}
					for(var status_parm =0 ;status_parm <passedData.params.status.length;status_parm++){
						searchParams.append(`status[]`, passedData.params.status[status_parm])
					}

                	if (passedData.url !== '') {
                    try {
                      await  this.fetchFilterOutboundsCustomers(passedData)
					  filterPagination.filteredOutbounds.old_page = data.page
                    } catch(e) {
                        this.notificationError(e)
                        this.setFilteredOutboundLoading(false)
                        console.log(e, 'handle page error')
                    }
                	}
                }
				else{
					this.setOutboundFilteredVal([])
				}               
            } 
		},
	handleScrollToTop() {
            var table = this.$refs['my-table'];
            var wrapper = table.$el.querySelector('div.v-data-table__wrapper');
            
            this.$vuetify.goTo(table); // to table
            this.$vuetify.goTo(table, {container: wrapper}); // to header
        },
	showCancelButton(outbound){
     if(typeof outbound !=='undefined' && outbound !== null && outbound !== 'null'){
       let result = true
        outbound.outbound_storable_units.find(val=>{
         if( val.status =='loaded' ){
            return result = false
         }
        })
      return result
      }
    },
	conditionForFloorTab(index){
		if(index =='Floor' && this.isWarehouse3PL== true){
			return false
		}
		else{
			return true
		}
	},
	conditionForTabCounts(index,tab){
		if(this.search ==='' && this.SearchCustomerFinalArray.length ===0){
			if(index ==2 || index ==3) return false
			else if(tab =='Floor' && this.isWarehouse3PL == true) return false
			else if (typeof this.getPendingOutboundPagination !== 'undefined' && this.getPendingOutboundPagination !== null && index ==0 && this.getPendingOutboundPagination.total ==0) {
            	return false
			}
			else if(typeof this.getFloorOutboundPagination !== 'undefined' && this.getFloorOutboundPagination !== null && index ==1 && this.getFloorOutboundPagination.total ==0){
				return false
			}
			return true
		}

		else if(this.search !=='' && this.SearchCustomerFinalArray.length ===0){
			if(index ==2 || index ==3) return false
			else if(tab =='Floor' && this.isWarehouse3PL == true) return false
			else if (typeof this.getSearchedOutbounds !== 'undefined' && this.getSearchedOutbounds !== null && index ==0 && this.getSearchedOutbounds.total ==0) {
            	return false
			}
			else if(typeof this.getSearchedOutbounds !== 'undefined' && this.getSearchedOutbounds !== null && index ==1 && this.getFloorOutboundPagination.total ==0){
				return false
			}
			return true
		}

		else {
			
				if(index ==2 || index ==3) return false
				else if(tab =='Floor' && this.isWarehouse3PL == true) return false
				else if (typeof this.getfilteredOutbounds !== 'undefined' && this.getfilteredOutbounds !== null && index ==0 && this.getfilteredOutbounds.total ==0) {
            		return false
				}
				else if(typeof this.getfilteredOutbounds !== 'undefined' && this.getfilteredOutbounds !== null && index ==1 && this.getfilteredOutbounds.total ==0){
					return false
				}
				return true	
			
		}
	},
	closeWarning() {
            this.showWarningEditInboundDialog = false
            this.currentEditInboundData = null
        },
		checkEditInbound(item, toDo) {
                if (this.currentTab === 2 && this.isWarehouse3PL ==true) {
                    this.showWarningEditInboundDialog = true
                    let data = { item, toDo }
                    this.currentEditInboundData = data
                }
        },
		confirmEditOrder() {
            if (this.currentEditInboundData !== null) {
                this.editOrder(this.currentEditInboundData.item, this.currentEditInboundData.toDo)
            }
        },
		async loadAllProducts3PL(item){
			if(item !=='undefined' && item !=='' && item !==null){
				if(item.outbound_products.length>0){
				let final_value = item.outbound_products.filter(val =>{
				return	val.status !=='loaded'
				})
				var result2 = final_value.filter((dropdown) => {
					return this.productsListsData.some((product_row) => dropdown.product_id === product_row.product_id && ((dropdown.expected_carton_count > 0 && dropdown.expected_carton_count <= product_row.expected_carton_count) || (dropdown.total_unit > 0 && dropdown.total_unit <= product_row.total_unit) )) 
					});
				if(result2.length==0)return this.notificationError("We don't have enough product in the inventory")
				let Send_payload =[]
				result2 = result2.filter(val =>{
					Send_payload.push(val.id)
				})
				let payload ={
					wid:item.warehouse_id,
					oid:item.id,
					idss:Send_payload
				}
				try{
					let storeOutboundTab = this.$store.state.outbound 
					let dataWithPage = { id: this.currentWarehouseSelected.id,page: 1}
					await this.multipleLoadProductApi(payload)
					dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
					await this.fetchPendingOutbounds(dataWithPage);
					this.setAllOutboundProductsLists([])
                	this.$emit('fetchOutboundProductsAPiFunction','Outbound','nothing')
					this.notificationMessage('Product Loaded sucessfully')
				}catch(e){
					this.notificationError(e)
				}
				
				}
			}
		},
	async fetchOutboundProductsAPiFunction(from,dataWithPage){
		if(from =='6plProvider'){
			this.setAllOutboundProductsLists([])
		}
      let payload =null
      if(this.currentWarehouseSelected !==null && this.currentWarehouseSelected !=='undefined' && this.currentWarehouseSelected !==undefined){
      payload =this.currentWarehouseSelected.id
     }
      try {
                if (this.getAllOutboundProductsListsDropdownData.length === 0) {
                    this.current_page_is = 1
                    this.fetchProductLoading =true
                    let sendData ={
                      wid:payload,
                      page:this.current_page_is,
					  cancelToken:dataWithPage.cancelToken
                    }
                    await this.fetchOutboundProducts(sendData)
                    
                    if (typeof this.getallOutboundProductsLists !== 'undefined' && 
                        this.getallOutboundProductsLists !== null && 
                        typeof this.getallOutboundProductsLists.products !== 'undefined' && 
                        Array.isArray(this.getallOutboundProductsLists.products) &&
                        this.getallOutboundProductsLists.products.length > 0) {
                            
                        this.productsListsData = this.getallOutboundProductsLists.products.map(value => {
                            if(value.is_from_inventory ==true){
								return {
								product_id: value.product.id,
                                id: value.product.id,
                                name: value.product.name,
                                sku: value.sku,
                                image: value.product.image,
                                description: value.product.description,
								in_each_carton:value.in_each_carton,
                                category_sku: value.category_sku,
								category_id:value.product.category_id,
                                is_from_inventory:value.is_from_inventory,
								product:value.product,
								expected_carton_count: value.expected_carton_count,
								total_unit:value.total_unit,
								carton_count:value.carton_count,
								available:value.available
								}
							}
							else{
								return {
                                product_id: value.id,
                                id: value.id,
                                name: value.name,
                                sku: value.sku,
                                image: value.image,
                                description: value.description,
								in_each_carton:value.units_per_carton,
                                category_sku: value.category_sku,
								category_id:value.category_id,
                                is_from_inventory:value.is_from_inventory,
								product:null,
								expected_carton_count: null,
								total_unit:null,
								carton_count:null,
								available:0
                            }
							}
                        })

                        this.lastDataCheck = this.productsListsData

                        if (this.current_page_is < this.getallOutboundProductsLists.last_page) {
                            this.loadMoreProducts()
                        }
                        
                        this.setAllOutboundProductsLists(this.productsListsData)
                        this.fetchProductLoading = false
                    } else {
                        this.fetchProductLoading = false
                        this.productsListsData = []
                        this.lastDataCheck = []
                    }
                } else {
                    if (from === 'Outbound') {
                        this.productsListsData = this.getAllOutboundProductsListsDropdownData
                        this.fetchProductLoading = false
                    }
                } 
            } catch(e) {
                this.notificationError(e)
            }
    	},
    	async loadMoreProducts() {
        	if (this.current_page_is < this.getallOutboundProductsLists.last_page) {
				this.current_page_is++
				let payload =null
				if(this.currentWarehouseSelected !==null && this.currentWarehouseSelected !=='undefined' && this.currentWarehouseSelected !==undefined){
					payload =this.currentWarehouseSelected.id
				}
				let sendData = {
					wid:payload,
					page:this.current_page_is
				}

				try {
					await this.fetchOutboundProducts(sendData)

                    if (typeof this.getallOutboundProductsLists !== 'undefined' && 
                        this.getallOutboundProductsLists !== null && 
                        typeof this.getallOutboundProductsLists.products !== 'undefined' && Array.isArray(this.getallOutboundProductsLists.products) &&
                        this.getallOutboundProductsLists.products.length > 0) {
                            
                        let newloaddata = this.getallOutboundProductsLists.products.map(value => {
							if(value.is_from_inventory ==true){
								return {
								product_id: value.product.id,
                                id: value.product.id,
                                name: value.product.name,
                                sku: value.sku,
                                image: value.product.image,
                                description: value.product.description,
								in_each_carton:value.in_each_carton,
                                category_sku: value.category_sku,
								category_id:value.product.category_id,
                                is_from_inventory:value.is_from_inventory,
								product:value.product,
								expected_carton_count: value.expected_carton_count,
								total_unit:value.total_unit,
								carton_count:value.carton_count,
								available:value.available
								}
							}
							else{
								return {
                                product_id: value.id,
                                id: value.id,
                                name: value.name,
                                sku: value.sku,
                                image: value.image,
                                description: value.description,
								in_each_carton:value.units_per_carton,
                                category_sku: value.category_sku,
								category_id:value.category_id,
                                is_from_inventory:value.is_from_inventory,
								product:null,
								expected_carton_count: null,
								total_unit:null,
								carton_count:null,
								available:0
                            }
							}
                            
                        })

                        this.productsListsData = [...this.productsListsData, ...newloaddata]

                        if (this.current_page_is < this.getallOutboundProductsLists.last_page) {
                            this.loadMoreProducts()
                        }

                        this.setAllOutboundProductsLists(this.productsListsData)
                    } else {
                        this.productsListsData;
                    }
				} catch (e) {
					this.notificationError(e)
				}
			}
        },
		clearSearched() {
            this.search = ''
            this.setOutboundSearchedVal([])
			if (this.isWarehouse3PLProvider == true && !this.isWarehouseConnected) {
				if (this.SearchCustomerFinalArray.length > 0) {
					this.setOutboundFilteredVal([])
					this.filtermultipleCustomer()
				}
			}
            // document.getElementById("search-input").focus();
        },
		// for searching call api
		handleSearch() {
			if (cancel !== undefined) {
				cancel("cancel_previous_request")
			}
			this.setSearchedOutboundLoading(false)
			clearTimeout(this.typingTimeout)
			this.typingTimeout = setTimeout(() => {
				let data = { 
					search: this.search
				}  

				if (this.SearchCustomerFinalArray.length > 0) {
					this.filtermultipleCustomer()
				} else {
					this.apiCall(data)
				}                
			}, 500)
		},
		apiCall(data) {
            let storePagination = this.$store.state.outbound.setCurrentTab

            if (data !== null && this.search !== '' && this.SearchCustomerFinalArray.length === 0) {
				this.setSearchedOutboundLoading(true)
                let passedData = {
                    method: "get",
                    url: `${this.poBaseUrlState}/warehouse/${warehouse_id}/outbound/common`,
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: {
                        search: this.search,
                        page: 1
                    }
                }

                let warehouse_id = this.currentWarehouseSelected.id
				if(!this.isWarehouseConnected && !this.isWarehouse3PLProvider){
					if (storePagination == 0) {
						passedData.params.status = ["pending"]
                    	passedData.tab = 'pending'
                	} else if (storePagination == 1) {
                	    passedData.params.status = ["floor", "ready"]
                	    passedData.tab = 'floor'
                	} else if (storePagination == 2) {
                	    passedData.params.status = ["completed"]
                	    passedData.tab = 'completed'
                	} else {
                	    passedData.params.status = ["cancelled"]
                	    passedData.tab = 'cancelled'
                	}
				}else if(!this.isWarehouseConnected && this.isWarehouse3PLProvider){
					if (storePagination == 0) {
                    	passedData.params.status = ["pending"]
                    	passedData.tab = 'pending'
                	} else if (storePagination == 1) {
                	    passedData.params.status = ["shipping-pending"]
                	    passedData.tab = 'shipping-pending'
                	} else if (storePagination == 2) {
                	    passedData.params.status = ["floor", "ready"]
                	    passedData.tab = 'floor'
                	} else if (storePagination == 3) {
                	    passedData.params.status = ["completed"]
                	    passedData.tab = 'completed'
                	} else {
                	    passedData.params.status = ["cancelled","rejected"]
                	    passedData.tab = 'cancelled'
                	}
				} 
				else{
					if (storePagination == 0) {
                    	passedData.params.status = ['draft']
						passedData.tab = 'draft'
                	} else if (storePagination == 1) {
                	    passedData.params.status = ["pending"]
                    	passedData.tab = 'pending'
                	}else if (storePagination == 2) {
                	    passedData.params.status = ["shipping-pending", "ready", "floor"]
						passedData.params.tab = 'pending-shipping'
                    	passedData.tab = 'shipping-pending-for-warehouse-customer'
                	} else if (storePagination == 3) {
                	    passedData.params.status = ["completed"]
                	    passedData.tab = 'completed'
                	} else {
                	    passedData.params.status = ["cancelled","rejected"]
                	    passedData.tab = 'cancelled'
                	}
				}
                

                if (passedData.url !== '') {
                    try {
                        this.fetchSearchedOutbounds(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setSearchedOutboundLoading(false)
                        console.log(e, 'Search error')
                    }
                }
            } else {
                this.setOutboundSearchedVal([])
            }
        },
		getNameWithoutExt(name){
			if(name !==null){
				if(name.length>20){
					return name.substring(0, 20).toLowerCase() + "...";
				}else{
					return name.toLowerCase()
				}
			}
		},
		removeCustomerFilter(item){
			let getIndex = this.SearchCustomerFinalArray.indexOf(item);
			this.SearchCustomerFinalArray.splice(getIndex, 1);
			this.setOutboundFilteredVal([])
		},
		// 6pl
		async Warehouse6PL_ProductsOnchange(val) {
            this.fetchProductLoading = true
            this.productsListsData = []
            this.lastDataCheck = []

            if (val.id !== null && val.id !=='') {
                try {
                    let customer_id = (typeof this.getUser=='string') ? 
                        JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id
                    let warehouse_customer_id = val.id
                    this.current_page_6pl_products = 1
					let wid =null
					if(this.currentWarehouseSelected !==null && this.currentWarehouseSelected !=='undefined' && this.currentWarehouseSelected !==undefined){
						wid =this.currentWarehouseSelected.id
					}

                    let payload = { customer_id, warehouse_customer_id,wid, page: this.current_page_6pl_products }

                    await this.wareHouse6plProduct(payload)
                    
                    if (typeof this.getallProducts6PLists !== 'undefined' && 
                        this.getallProducts6PLists !== null && 
                        typeof this.getallProducts6PLists.products !== 'undefined' && 
                        Array.isArray(this.getallProducts6PLists.products) &&
                        this.getallProducts6PLists.products.length > 0) {
                            
                        this.productsListsData = this.getallProducts6PLists.products.map(value => {
                            if(value.is_from_inventory ==true){
								return {
								product_id: value.product.id,
                                id: value.product.id,
                                name: value.product.name,
                                sku: value.sku,
                                image: value.product.image,
                                description: value.product.description,
								in_each_carton:value.in_each_carton,
                                category_sku: value.category_sku,
								category_id:value.product.category_id,
                                is_from_inventory:value.is_from_inventory,
								product:value.product,
								expected_carton_count: value.expected_carton_count,
								total_unit:value.total_unit,
								carton_count:value.carton_count,
								available:value.available
								}
							}
							else{
								return {
                                product_id: value.id,
                                id: value.id,
                                name: value.name,
                                sku: value.sku,
                                image: value.image,
                                description: value.description,
								in_each_carton:value.units_per_carton,
                                category_sku: value.category_sku,
								category_id:value.category_id,
                                is_from_inventory:value.is_from_inventory,
								product:null,
								expected_carton_count: null,
								total_unit:null,
								carton_count:null,
								available:0
                            }
							}
                        })

                        this.lastDataCheck = this.productsListsData

                        if (this.current_page_6pl_products < this.getallProducts6PLists.last_page) {
                            this.loadMoreWarehouseCustomerProducts(val)
                        }
                        
                        this.setAllOutboundProductsLists(this.productsListsData)
                        this.fetchProductLoading = false
                    } else {
                        this.fetchProductLoading = false
                        this.productsListsData = []
                        this.lastDataCheck = []
                    }
                } catch(e) {
                    this.notificationError(e)
                    console.log(e)
                }
            } else {
                await this.fetchOutboundProductsAPiFunction('6plProvider','nothing')
            }
        },
        async loadMoreProducts6PL(val) {
            if (this.current_page_6pl_products < this.getallProducts6PLists.last_page) {
				this.current_page_6pl_products++

                let customer_id = (typeof this.getUser=='string') ? 
                    JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id
                let warehouse_customer_id = val.id
				let wid =null
					if(this.currentWarehouseSelected !==null && this.currentWarehouseSelected !=='undefined' && this.currentWarehouseSelected !==undefined){
						wid =this.currentWarehouseSelected.id
					}

                let payload = { customer_id, warehouse_customer_id,wid, page: this.current_page_6pl_products }

				try {
					await this.wareHouse6plProduct(payload)

                    if (typeof this.getallProducts6PLists !== 'undefined' && 
                        this.getallProducts6PLists !== null && 
                        typeof this.getallProducts6PLists.products !== 'undefined' && 
                        Array.isArray(this.getallProducts6PLists.products) &&
                        this.getallProducts6PLists.products.length > 0) {
                            
                        let newloaddata = this.getallProducts6PLists.products.map(value => {
                            if(value.is_from_inventory ==true){
								return {
								product_id: value.product.id,
                                id: value.product.id,
                                name: value.product.name,
                                sku: value.sku,
                                image: value.product.image,
                                description: value.product.description,
								in_each_carton:value.in_each_carton,
                                category_sku: value.category_sku,
								category_id:value.product.category_id,
                                is_from_inventory:value.is_from_inventory,
								product:value.product,
								expected_carton_count: value.expected_carton_count,
								total_unit:value.total_unit,
								carton_count:value.carton_count,
								available:value.available
								}
							}
							else{
								return {
                                product_id: value.id,
                                id: value.id,
                                name: value.name,
                                sku: value.sku,
                                image: value.image,
                                description: value.description,
								in_each_carton:value.units_per_carton,
                                category_sku: value.category_sku,
								category_id:value.category_id,
                                is_from_inventory:value.is_from_inventory,
								product:null,
								expected_carton_count: null,
								total_unit:null,
								carton_count:null,
								available:0
                            }
							}
                        })

                        this.productsListsData = [...this.productsListsData, ...newloaddata]

                        if (this.current_page_6pl_products < this.getallProducts6PLists.last_page) {
                            this.loadMoreProducts6PL()
                        }

                        this.setAllOutboundProductsLists(this.productsListsData)
                    } else {
                        this.productsListsData;
                    }
				} catch (e) {
					this.notificationError(e)
				}
			}
        },
		// call warehouse customers api
        async callWarehouseCustomerListsData(dataWithPage) {
			this.setAllWarehouseCustomerLists([])
            let parmsWarehouseCustomers = {
				id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id,
				page: 1,
				cancelToken: dataWithPage.cancelToken
			}

			try {				
				if (this.getAllWarehouseCustomerListsData.length === 0) {
					this.current_page_is_whcustomers = 1
                    this.fetchWarehouseCustomersLoading = true
					await this.fetchWarehouseCustomersDropdown(parmsWarehouseCustomers)

					if (typeof this.getWarehouseCustomersDropdown !== "undefined" && 
						this.getWarehouseCustomersDropdown !== null) {
							
						if (typeof this.getWarehouseCustomersDropdown.data !== "undefined" &&
							this.getWarehouseCustomersDropdown.data.length !== "undefined") {
							let data = this.getWarehouseCustomersDropdown.data;

							data.map((value) => {
								if (value.address === null) {
									value.address = "";
								}

								if (value.phone === null) {
									value.phone = "";
								}

								if (value.emails === null) {
									value.emails = "";
								}
							});
							this.warehouseCustomerListsData = data
							this.lastCheckWHData = data
							this.warehouseCustomerLists =data
							this.warehouseCustomerListsCopy =data

							if (this.current_page_is_whcustomers < this.getWarehouseCustomersDropdown.last_page) {
								this.loadMoreWarehouseCustomerData(parmsWarehouseCustomers)
							}
							
							this.setAllWarehouseCustomerLists(this.warehouseCustomerListsData)
                            this.fetchWarehouseCustomersLoading = false
						}
					} else {
                        this.fetchWarehouseCustomersLoading = false
						this.warehouseCustomerListsData = []
						this.lastCheckWHData = []
						this.warehouseCustomerLists =[]
						this.warehouseCustomerListsCopy =[]
					}
				}
			} catch(e) {
                this.fetchWarehouseCustomersLoading = false
				if (e !== "cancel_previous_request") this.notificationError(e)
			}
		},
		async loadMoreWarehouseCustomerData(parmsWarehouseCustomers) {
			if (this.current_page_is_whcustomers < this.getWarehouseCustomersDropdown.last_page) {
				this.current_page_is_whcustomers++
				parmsWarehouseCustomers.page = this.current_page_is_whcustomers

				try {
					await this.fetchWarehouseCustomersDropdown(parmsWarehouseCustomers)

					if (typeof this.getWarehouseCustomersDropdown !== "undefined" && 
						this.getWarehouseCustomersDropdown !== null) {
							
						if (typeof this.getWarehouseCustomersDropdown.data !== "undefined" &&
							this.getWarehouseCustomersDropdown.data.length !== "undefined") {
							let data = this.getWarehouseCustomersDropdown.data;

							data.map((value) => {
								if (value.address === null) {
									value.address = "";
								}

								if (value.phone === null) {
									value.phone = "";
								}

								if (value.emails === null) {
									value.emails = "";
								}
							});

							this.warehouseCustomerListsData = [...this.warehouseCustomerListsData, ...data]
							this.warehouseCustomerLists =[...this.warehouseCustomerListsData,...data]
							this.warehouseCustomerListsCopy =[...this.warehouseCustomerListsData,...data]

							if (this.current_page_is_whcustomers < this.getWarehouseCustomersDropdown.last_page) {
								this.loadMoreWarehouseCustomerData(parmsWarehouseCustomers)
							}
							
							this.setAllWarehouseCustomerLists(this.warehouseCustomerListsData)
						}
					} else {
						this.warehouseCustomerListsData
						this.warehouseCustomerListsCopy
						this.warehouseCustomerLists
					}
				} catch (e) {
					this.notificationError(e)
				}
			}
		},
		removeCustomerListsEmptyOnChange(){
			if(this.SearchCustomerFinalArrayCopy.length === 0){
				this.SearchCustomerFinalArrayCopy = []
				this.SearchCustomerFinalArray =[]
				this.selectedWhCustomer = []
				this.setOutboundFilteredVal([])
			}
			// this.warehouseCustomerListsCopy = this.warehouseCustomerLists
		},
		removeCustomerLists(item, removeIs) {
            if (removeIs === 'single') {
				this.SearchCustomerFinalArrayCopy = this.SearchCustomerFinalArrayCopy.filter(val => val.id !== item.id)
                // let index = this.SearchCustomerFinalArrayCopy.indexOf(item)
                // if (index >= 0){
					// this.SearchCustomerFinalArrayCopy.splice(index, 1)
					// if(this.SearchCustomerFinalArrayCopy.length === 0){
					// 	this.setOutboundFilteredVal([])
					// }
				// } 
            } else {
                this.SearchCustomerFinalArrayCopy = []
				this.selectedWhCustomer = []
				this.setOutboundFilteredVal([])

				if (this.search !== '') {
                    setTimeout(() => {
                        this.filterMenu = false
                        let data = { search: this.search } 
                        this.setSearchedOutboundLoading(true)
                        this.apiCall(data)  
                    }, 200);
                }
            }
			// this.SearchCustomerFinalArray = this.SearchCustomerFinalArrayCopy
        },
		// for filter warehouse customers
        searchWarehouseCustomers() {
            if (this.searchCustomerData !== '') {
                this.warehouseCustomerLists = this.warehouseCustomerListsCopy.filter((customer) => {
                    return customer.name.toLowerCase().indexOf(this.searchCustomerData.toLowerCase()) > -1
                })
            } else {
                this.warehouseCustomerLists = this.warehouseCustomerListsCopy
                return this.warehouseCustomerLists
            }
        },
		/* eslint-disable */
		cancelfiltermultipleCustomer(){
			if (this.SearchCustomerFinalArray.length === 0) {
                this.SearchCustomerFinalArrayCopy = []
                this.setOutboundFilteredVal([])
            } else {
                // if (this.SearchCustomerFinalArray !== this.SearchCustomerFinalArrayCopy) {
                //     this.SearchCustomerFinalArrayCopy = this.SearchCustomerFinalArray
                // }else{
				// 	this.SearchCustomerFinalArrayCopy =this.selectedWhCustomer.map(({dummy_value_Add_For_same_refrence,...rest}) => ({...rest}))
				// }
				this.SearchCustomerFinalArrayCopy =this.selectedWhCustomer.map(({dummy_value_Add_For_same_refrence,...rest}) => ({...rest}))
				this.SearchCustomerFinalArray = this.SearchCustomerFinalArrayCopy
			}            
            // this.$refs.select_customer_dropdown_3plprovider_ref.blur()
            this.searchCustomerData = ''
            this.warehouseCustomerLists = this.warehouseCustomerListsCopy
			this.filterMenu = false
			this.isActiveClicked = false
		},
		/* eslint-enable */
		filtermultipleCustomer(){
			let warehouse_id = this.currentWarehouseSelected.id
            this.setFilteredOutboundLoading(false)
                let data = { 
                    filter_data: this.SearchCustomerFinalArray,
					search_data:this.search,
					wid:warehouse_id
                }  

				if(this.SearchCustomerFinalArrayCopy.length > 0 && this.search !== ''){
					this.SearchCustomerFinalArray = this.SearchCustomerFinalArrayCopy
				data = {
					filter_data : this.SearchCustomerFinalArray.map(val => val.id),
					search_data:this.search,
					wid:warehouse_id
				}
                this.filteredWithSearchCustomer6PL(data)
				
				}
				else if(this.SearchCustomerFinalArrayCopy.length >0 && this.search ===''){
					this.SearchCustomerFinalArray = this.SearchCustomerFinalArrayCopy
					data = {
						filter_data : this.SearchCustomerFinalArray.map(val => val.id),
						search_data:this.search,
						wid:warehouse_id
					}
					this.filteredOnlyCustomer6PL(data)	
						
				}
				this.setOutboundFilteredVal([])
            	this.searchCustomerData = ''
            	this.warehouseCustomerLists = this.warehouseCustomerListsCopy
				this.filterMenu = false
				this.isActiveClicked = false
		},
		async filteredOnlyCustomer6PL(data){
			let storePagination = this.$store.state.outbound.setCurrentTab
            if (data !==null && data.filter_data !== null && this.SearchCustomerFinalArray.length !== 0 && this.search ==='' && data.search_data =='') {
				this.setFilteredOutboundLoading(true)
				// this.$refs.closeDropdownofCustomerFilter_Ref.blur();
				var searchParams = new URLSearchParams();
				for(var ser =0 ;ser <data.filter_data.length;ser++){
					searchParams.append(`ids[]`, data.filter_data[ser])
				}
					
					searchParams.append('page',1)
                let passedData = {
                    method: "get",
                    url: `${this.poBaseUrlState}/warehouse/${data.wid}/outbound/common?filter=true`,
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: searchParams
                }
			
                if (storePagination == 0) {
					passedData.params.status =  ["pending"]
                    passedData.tab = 'pending'
                } else if (storePagination == 1) {
					passedData.params.status = ["shipping-pending"]
                    passedData.tab = 'shipping-pending'
                }else if (storePagination == 2) {
					passedData.params.status = ["floor", "ready"]
                    passedData.tab = 'floor'
                } else if (storePagination == 3) {
					passedData.params.status = ["completed"]
                    passedData.tab = 'completed'
                } else {
					passedData.params.status = ["cancelled","rejected"]
                    passedData.tab = 'cancelled'
                }
				for(var status_parm =0 ;status_parm <passedData.params.status.length;status_parm++){
					searchParams.append(`status[]`, passedData.params.status[status_parm])
				}
			

                if (passedData.url !== '') {
                    try {
                      await  this.fetchFilterOutboundsCustomers(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setFilteredOutboundLoading(false)
                        console.log(e, 'filter only error')
                    }
                }
            } else {
                this.setOutboundFilteredVal([])
            }
		},
		async filteredWithSearchCustomer6PL(data){
			let storePagination = this.$store.state.outbound.setCurrentTab
            if (data !==null && data.filter_data !== null && this.SearchCustomerFinalArray.length !== 0 && this.search !=='' && data.search_data !=='') {
				this.setFilteredOutboundLoading(true)
				// this.$refs.closeDropdownofCustomerFilter_Ref.blur();
				var searchParams = new URLSearchParams();
				for(var ser =0 ;ser <data.filter_data.length;ser++){
					searchParams.append(`ids[]`, data.filter_data[ser])
					}
					searchParams.append('page',1)
                let passedData = {
                    method: "get",
                    url: `${this.poBaseUrlState}/warehouse/${data.wid}/outbound/common?search=${data.search_data}&filter=true`,
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: searchParams
                }
			
				if (storePagination == 0) {
					passedData.params.status =  ["pending"]
                    passedData.tab = 'pending'
                } else if (storePagination == 1) {
					passedData.params.status = ["shipping-pending"]
                    passedData.tab = 'shipping-pending'
                } else if (storePagination == 2) {
					passedData.params.status = ["floor", "ready"]
                    passedData.tab = 'floor'
                } else if (storePagination == 3) {
					passedData.params.status = ["completed"]
                    passedData.tab = 'completed'
                } else {
					passedData.params.status = ["cancelled","rejected"]
                    passedData.tab = 'cancelled'
                }
				for(var status_parm =0 ;status_parm <passedData.params.status.length;status_parm++){
					searchParams.append(`status[]`, passedData.params.status[status_parm])
				}

                if (passedData.url !== '') {
                    try {
                        await this.fetchFilterOutboundsCustomers(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setFilteredOutboundLoading(false)
                        console.log(e, 'Search error')
                    }
                }
            } else {
                this.setOutboundFilteredVal([])
            }
		},
		onClickFilter() {
            if (!this.filterMenu) {
				var deepCopy = _.cloneDeep(this.SearchCustomerFinalArrayCopy);
				this.selectedWhCustomer = deepCopy
				this.selectedWhCustomer = this.selectedWhCustomer.map(v => ({ ...v, dummy_value_Add_For_same_refrence: v.name }))
                this.filterMenu = true
            }
        },
		clickOutsideFilter() {
            if (this.isActiveClicked) {
                this.filterMenu = false
                this.cancelfiltermultipleCustomer()
                this.isActiveClicked = false
            }
        },
		// Connected 3pl warehouse provider, draft Api submit
		closeDraft(){
			this.confirmDraftSubmit = false
			this.draftOutboundData = null;
		},
		submitDraft(outbound){
			this.confirmDraftSubmit = true
			this.draftOutboundData = outbound
		},
		async submitDraftConfirm(){
			if (this.draftOutboundData !== null) {
				try {
			  		await this.draftOutboundApi(this.draftOutboundData.id);
	
			  		this.notificationMessage("Outbound has been Submitted Successfully.");
	 
					try {
					let storeOutboundTab = this.$store.state.outbound 
					let dataWithPage = { id: this.currentWarehouseSelected.id,page: 1}
						if(this.isWarehouseConnected){
							if (this.currentTab == 0) {
								dataWithPage.page = storeOutboundTab.draftOutboundPagination.current_page
								await this.fetchDraftOutbounds(dataWithPage);
							}
						}
						
						this.confirmDraftSubmit = false;
						this.draftOutboundData = null;
					} catch (e) {
						this.notificationError(e);
					}
	
				} catch (e) {
			  		this.notificationError(e);
				}
		  	}
		},
		closeConnectedWarehouseOrder(){
			this.AcceptConnectedWarehouseOutboundDialog = false
			this.acceptConnectedWarehouseOrderData = null;
		},
		acceptConnectedWarehouseOrder(acceptOutboundData){
			this.AcceptConnectedWarehouseOutboundDialog = true
			this.acceptConnectedWarehouseOrderData = acceptOutboundData
		},
		async AcceptConnectedOutboundConfirm(){
			if (this.acceptConnectedWarehouseOrderData !== null) {
				try {
			  		await this.AcceptConnectedOrderApi(this.acceptConnectedWarehouseOrderData.id);
	
			  		this.notificationMessage("Outbound has been Accepted Successfully.");
	 
					try {
					let storeOutboundTab = this.$store.state.outbound 
					let dataWithPage = { id: this.currentWarehouseSelected.id,page: 1}
						if(this.isWarehouse3PLProvider){
							if (this.currentTab == 0) {
								dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
								await this.fetchPendingOutbounds(dataWithPage);
							}
						}
						
						this.AcceptConnectedWarehouseOutboundDialog = false;
						this.acceptConnectedWarehouseOrderData = null;
					} catch (e) {
						this.notificationError(e);
					}
	
				} catch (e) {
			  		this.notificationError(e);
				}
		  	}
		},
		rejectConnectedWarehouseOrder(rejectdata){
			this.RejectConnectedWarehouseOutboundDialog = true;
			this.rejectConnectedWarehouseOrderData = rejectdata;
		},
		async RejectConnectedOutboundConfirm(){
			if(this.rejectConnectedWarehouseOrderData !==null){
				try {
					let payload = {
						oid:this.rejectConnectedWarehouseOrderData.id,
						notes : this.reasonForRejectionOutboundOrder
					}
			  		await this.RejectConnectedOrderApi(payload);
	
			  		this.notificationMessage("Outbound has been Accepted Successfully.");
	 
					try {
					let storeOutboundTab = this.$store.state.outbound 
					let dataWithPage = { id: this.currentWarehouseSelected.id,page: 1}
						if(this.isWarehouse3PLProvider){
							if (this.currentTab == 0) {
								dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
								await this.fetchPendingOutbounds(dataWithPage);
							}
						}
						
						this.RejectConnectedWarehouseOutboundDialog = false;
						this.rejectConnectedWarehouseOrderData = null;
						this.reasonForRejectionOutboundOrder = ''
					} catch (e) {
						this.notificationError(e);
					}
	
				} catch (e) {
			  		this.notificationError(e);
				}
			}
		},
		closeRejectConnectedWarehouseOrder(){
			this.RejectConnectedWarehouseOutboundDialog = false;
			this.rejectConnectedWarehouseOrderData = null;
			this.reasonForRejectionOutboundOrder = ''	
		},
		clearAllFilter() {
            if (this.SearchCustomerFinalArray.length > 0) {
                this.SearchCustomerFinalArrayCopy = []
                this.selectedWhCustomer = []
                this.setOutboundFilteredVal([])
            }
        }
	},
	async mounted() {
		if (this.getCurrentOutboundTab !== "undefined") {
			if (this.currentTab !== this.getCurrentOutboundTab) {
				this.currentTab = this.getCurrentOutboundTab;
			}
		}
		let checkWarehouseTypeId = this.currentWarehouseSelected !== null ? this.currentWarehouseSelected.warehouse_type_id : null
		// let checkWarehouseConnected = this.currentWarehouseSelected !== null ?  this.currentWarehouseSelected :null
		let currentInventoryTabName = this.$router.history.current.query.tab

        if (this.$store.state.page.currentInventoryTab === 4 && 
            typeof currentInventoryTabName !== 'undefined' && 
            currentInventoryTabName === 'Outbound') {	
			try {
				this.source = axios.CancelToken.source()
				// if(this.currentWarehouseSelected.warehouse_type_id !== 6){
				// 	this.setAllOutboundProductsLists([])
				// }
				let dataWithPage = {
					id: this.currentWarehouseSelected.id,
					page: 1,
					cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
				}
				if(this.search ==='' && this.SearchCustomerFinalArray.length ===0 && this.currentWarehouseOutbounds.data.length ===0){
					if(!this.isWarehouseConnected && !this.isWarehouse3PLProvider){
						await this.fetchPendingOutbounds(dataWithPage);
						await this.fetchFloorOutbounds(dataWithPage);
					}else if(!this.isWarehouseConnected && this.isWarehouse3PLProvider){
						await this.fetchPendingOutbounds(dataWithPage);
						await this.fetchPendingShippingServiceProvider(dataWithPage)
						await this.fetchFloorOutbounds(dataWithPage);
					} else{
						await this.fetchDraftOutbounds(dataWithPage);
						await this.fetchPendingOutbounds(dataWithPage);
						await this.fetchPendingShippedOutbounds(dataWithPage);
					}
				}else{
				// await this.fetchCompletedOutbounds(dataWithPage);
				// await this.fetchCancelledOutbounds(dataWithPage);
				}
				if(checkWarehouseTypeId !== null && checkWarehouseTypeId === 6 && !this.isWarehouseConnected){
					this.callWarehouseCustomerListsData(dataWithPage)
				}else{
					this.setAllOutboundProductsLists([])
					this.fetchOutboundProductsAPiFunction('Outbound',dataWithPage)
				}	
			} catch (e) {
				if (e !== "cancel_previous_request") this.notificationError(e)
			}

		}
	},
	beforeDestroy(){
		if (cancel !== undefined) {
			cancel("cancel_previous_request")
		}
	},
	updated() {},
};
</script>

<style lang="scss">
@import "@/assets/scss/buttons.scss";
@import "@/assets/scss/pages_scss/inventory/outbound/outboundTable.scss";

.status {
	.cancelled {
		background-color: #819fb2 !important;
		color: #ffffff !important;
		padding: 8px 10px !important;
	}
	.rejected {
		background-color: #819fb2 !important;
		color: #ffffff !important;
		padding: 8px 10px !important;
	}
}

.Cancelled {
	color: #f93131 !important;
}

.Cancelled-btn {
	background-color: #fff !important;
	color: #f93131 !important;
	border: 1px solid $light-grey !important;
	padding: 10px 16px !important;
	font-size: 14px;
	height: 40px !important;
	text-transform: capitalize;
	letter-spacing: 0;
	box-shadow: none !important;
	border-radius: 4px;
	font-family: "Inter-Regular", sans-serif;
	display: flex;
	justify-content: center;
	align-items: center;

	&:disabled {
		color: $dark-blue !important;
	}

	&.delete {
		color: $red !important;
	}
}

.clearSelection {
	font-size: 14px !important;
	color: #4a4a4a !important;
}
.paddingZero{
	padding: 0 !important;
	border-left: none !important;
}
.checkbox-border-style{
	display: flex;
	justify-items: center;
	align-items: center;
	padding: 0;
	margin-bottom: 0;
	height: 20px;
	.mdi-checkbox-blank-outline::before {
		content: "\F14FC";
		color: #6D858F;

	}
	.v-input {
		&.v-input--checkbox {
			margin-bottom: 0;
		}
	}

}
.secondary-Color {
	color: #6D858F !important;
}
</style>