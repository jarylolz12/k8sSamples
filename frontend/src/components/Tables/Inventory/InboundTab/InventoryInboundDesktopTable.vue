<template>
    <div class="inbounds-desktop-wrapper">
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
            :headers="headersComputed"
            :items="currentWarehouseInbounds.data"
            :page.sync="getCurrentPage"
            :items-per-page="itemsPerPage"
            item-key="order_id"
            @page-count="pageCount = $event"
            mobile-breakpoint="769"            
            class="inventory-table inbound-table elevation-1"
            v-bind:class="{
                'no-data-table': (typeof currentWarehouseInbounds.data !== 'undefined' && currentWarehouseInbounds.data.length === 0),
                'no-current-pagination' : (getTotalPage <= 1),
                'no-searched-data' : getSearchedDataClass,
                'type-3pl' : currentWarehouseSelected.warehouse_type_id === 3,
                'no-checkbox' : !removeCheckboxOnCompleteAndCancelled
            }"
            hide-default-footer
            fixed-header
            :show-select="removeCheckboxOnCompleteAndCancelled"
            ref="my-table"
            @click:row="viewInbound"
            v-model="selectedInboundOrders">

            <template v-slot:top>
                <div class="inventory-search-wrapper inbound-search" v-if="selectedInboundOrders.length === 0">
                    <v-tabs class="inventory-inner-tab" @change="onTabChange" v-model="currentTab">
                        <v-tab v-for="(tab, index) in tabsComputed" 
                            :key="index" 
                            :class="index === 3 ? `inventory-inner-tab-last ${tab}` : `${tab}`"
                            @click="onClickTab(index)"
                            :style="(isWarehouse3PLProvider && index === 3) ? 'margin-right: 0 !important;' : ''"> {{ tab }}

                            <!-- <span v-if="!isWarehouse3PLProvider">
                                <small v-if="(index == 0 || index == 1) && getTabCount(index) !== '0'">
                                    {{ getTabCount(index) }}
                                </small>
                            </span>

                            <span v-else>
                                <small v-if="(index === 0 || index === 1 || index === 2) && getTabCount(index) !== '0'">
                                    {{ getTabCount(index) }}
                                </small>
                            </span> -->
                        </v-tab>
                    </v-tabs>

                    <v-spacer></v-spacer>
                    
                    <div class="search-and-filter">
                        <div class="filter-tags-input" v-if="(isWarehouse3PLProvider && !isWarehouseConnected) && handleFilterComponent">
                            <FilterCustomers 
                                @onClickFilter="onClickFilter"
                                @filterAllWarehouseCustomers="filterAllWarehouseCustomers"
                                @cancelSelectingWarehouseCustomers="cancelSelectingWarehouseCustomers"
                                @searchWarehouseCustomers="searchWarehouseCustomers"
                                @removeCustomerLists="removeCustomerLists"
                                @removeCustomerListsEmptyOnChange="removeCustomerListsEmptyOnChange"
                                :searchCustomerData.sync="searchCustomerData"
                                :menu.sync="filterMenu"
                                :selectedWhCustomersCopy.sync="selectedWhCustomersCopy"
                                :selectedWhCustomers.sync="selectedWhCustomers"
                                :warehouseCustomerLists.sync="warehouseCustomerLists"
                                :loading="fetchWarehouseCustomersLoading"
                                @clickOutside="clickOutsideFilter"
                                :isActiveClicked.sync="isActiveClicked"
                                @clearAllFilter="clearAllFilter"
                            />
                        </div>
                        
                        <SearchComponent
                            placeholder="Search Inbound"
                            :searchValue.sync="search"
                            :handleSearchComponent="handleSearchComponent"
                            @handleSearch="handleSearch"
                            @clearSearched="clearSearched" />

                        <v-btn color="primary"
                            dark class="btn-blue ml-2" 
                            @click.stop="addNewInbound">
                            Create Inbound
                        </v-btn>
                    </div>
                </div>

                <div class="inventory-search-wrapper inbound-search" v-else>
                    <div class="inbound-orders-selected" v-if="selectedInboundOrders.length > 0">
                        <span style="color: #6D858F; font-size: 14px;">
                            {{ selectedInboundOrders.length }} 
                            Item<span v-if="selectedInboundOrders.length > 1">s</span> selected.
                        </span>
                        <v-btn class="btn-white order-clear-selection" @click="clearSelectedOrders">
                            Clear Selection
                        </v-btn>
                    </div>

                    <v-spacer></v-spacer>
                    
                    <div class="search-and-filter">
                        <v-btn v-if="isShowEmployeeOption"
                            dark class="btn-blue" 
                            @click.stop="assignEmployee">
                            Assign Employee
                        </v-btn>

                        <v-btn v-if="isShowEmployeeOption"
                            dark class="btn-white ml-2 btn-duplicate-m mr-0" 
                            @click.stop="assignToLocation">
                            {{ isHasLocationAssigned ? 'Update Location' : 'Assign to Location' }}
                        </v-btn>

                        <!-- <v-btn class="btn-white btn-duplicate-m multiple ml-2" 
                            @click.stop="duplicate">
                            Duplicate
                        </v-btn>

                        <v-btn dark class="btn-white btn-print-order-m multiple" 
                            @click.stop="printOrder">
                            Print Order
                        </v-btn> -->
                    </div>
                </div>
            </template>

            <template v-slot:[`item.order_id`]="{ item }">
                <div>
                    <div class="inventory-wrapper w-transfer-tooltip">
                        {{ 
                            typeof item.order_id !== 'undefined' && item.order_id !== null && 
                            item.order_id !== 'null' ? item.order_id : '--' 
                        }}
                        <v-tooltip bottom nudge-bottom="5" content-class="top-arrow">
                            <template v-slot:activator="{ on, attrs }">
                                <span 
                                    v-if="item.is_warehouse_transfer == 1"
                                    v-bind="attrs"
                                    v-on="on"  
                                    class="warehouse-transfer">
                                        WT
                                </span>
                            </template>
                            <span>Warehouse Transfer</span>  
                        </v-tooltip>
                    </div> 
                </div>
            </template>

            <template v-slot:[`item.name`]="{ item }">
                <div class="inventory-wrapper">
                    {{ 
                        typeof item.name !== 'undefined' && item.name !== null && 
                        item.name !== 'null' ? item.name : '--' 
                    }}
                </div>
            </template>

            <template v-slot:[`item.ref_no`]="{ item }">
                <div class="inventory-wrapper">
                    {{ 
                        typeof item.ref_no !== 'undefined' && item.ref_no !== null && 
                        item.ref_no !== 'null' ? item.ref_no : '--' 
                    }}
                </div>
            </template>

            <template v-slot:[`item.inbound_status`]="{ item }">
                <div v-if="currentWarehouseSelected.warehouse_type_id !== 3" class="own-warehouse">
                    <div class="status inbound">
                        <span :class="item.inbound_status" v-if="isWarehouseConnected">
                            {{ 
                                item.inbound_status === 'receive-pending' ? 'Pending Receiving' : 
                                item.inbound_status === 'floor' ? 'Receiving' :  item.inbound_status 
                            }}
                        </span>

                        <span :class="item.inbound_status" v-else>
                            {{ item.inbound_status }}
                        </span>

                        <span class="ml-2" v-if="typeof item.assignment_status !== 'undefined' && item.assignment_status !== null" :class="item.assignment_status">{{ item.assignment_status }}</span>
                    </div>

                    <p class="undershipped" v-if="item.is_undershipped !== 0">
                        {{ getOvershippedInbound(item) }}
                    </p>
                </div>

                <div v-else>
                    <!-- if not under/overshipped -->
                    <div v-if="item.is_undershipped === 0">
                        <!-- show status if it's not pending, else hide -->
                        <div v-if="item.inbound_status !== 'pending'">
                            <div class="status inbound">
                                <span :class="item.inbound_status"> 
                                    {{ item.inbound_status }} 
                                </span>
                            </div>
                        </div>
                    </div>

                    <div v-else>
                        <span class="undershipped-status"> 
                            {{ getOvershippedInbound(item) }} 
                        </span>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.estimated_time`]="{ item }">
                <div class="estimated_time">
                    <span>{{ formatNewDate(item.estimated_date, item.estimated_time) }}</span>
                </div>
            </template>

            <template v-slot:[`item.warehouse_customer`]="{ item }">
                <span> {{ item.warehouse_customer !== null ? item.warehouse_customer.company_name : '' }} </span>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="actions-wrapper d-flex justify-end align-center">
                    <div class="actions" v-if="!isWarehouseConnected && item.is_from_connected_3pl === 1 && currentTab === 0">
                        <button class="btn-accept inventory-btn-edit mr-2" @click.stop="acceptOrder(item, false)">
                            Accept
                        </button>

                        <button class="btn-edit inventory-btn-edit mr-2" @click.stop="rejectOrder(item, false)">
                            <img src="@/assets/icons/close-red.svg" alt="Reject">
                        </button>
                    </div>

                    <div class="actions mr-2" v-if="!isWarehouseConnected">
                        <v-menu bottom offset-y left content-class="outbound-lists-menu from-inbound">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn icon v-bind="attrs" v-on="on">
                                    <v-icon>mdi-dots-horizontal</v-icon>
                                </v-btn>
                            </template>

                            <v-list class="outbound-lists">
                                <v-list-item class="arrived-item" @click="arrived(item)" :class="isWarehouse3PL ? 'warehouse-3pl-hidden': ''" 
                                    v-if="(isWarehouse3PLProvider ? currentTab == 1 : currentTab == 0) && 
                                        item.inbound_status !== 'arrived'">
                                    <v-list-item-title class="arrived">
                                        <img src="@/assets/icons/checkMark.png" height="16px" width="16px" class="mr-2">
                                        Arrived
                                    </v-list-item-title>
                                </v-list-item>

                                <v-list-item class="all-receive-item" @click="receiveAllProductsInbound(item)" 
                                    v-if="isWarehouse3PL && currentTab == 0 && item.inbound_status === 'pending'">
                                    <v-list-item-title class="arrived">
                                        <img src="@/assets/icons/checkMark.png" height="16px" width="16px" class="mr-2">
                                        All Received
                                    </v-list-item-title>
                                </v-list-item>

                                <div class="lists-separator" 
                                    v-if="(!isWarehouse3PL && (isWarehouse3PLProvider ? currentTab == 1 : currentTab == 0) && item.inbound_status !== 'arrived')">
                                </div>

                                <div class="lists-separator" 
                                    v-if="(isWarehouse3PL && currentTab == 0 && item.inbound_status === 'pending')">
                                </div>                            

                                <!-- <v-list-item @click="completeOrder(item)" 
                                    v-show="findFloorReceivedProducts(item) && !isHasStorableUnits(item)">
                                    <v-list-item-title class="arrived"> Mark as Completed </v-list-item-title>
                                </v-list-item> -->

                                <v-list-item @click="viewInbound(item)">
                                    <v-list-item-title> View </v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="checkEditInbound(item, 'edit')" 
                                    v-if="canStillEditInbound(item) && item.is_from_connected_3pl !== 1">
                                    <v-list-item-title> Edit </v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="openNewStorableUnit(item)" v-show="findFloorReceivedProducts(item)">
                                    <v-list-item-title> New Storable Unit </v-list-item-title>
                                </v-list-item>                      

                                <v-list-item @click="checkEditInbound(item, 'copy')" 
                                    v-if="item.is_from_inventory !== 1 && item.is_from_connected_3pl !== 1">
                                    <v-list-item-title> Duplicate </v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="assignToLocation(item, true)" v-if="isShowEmployeeOption">
                                    <v-list-item-title> 
                                        {{ item.location_id !== null ? 'Update Location' : 'Assign to Location' }}
                                    </v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="assignEmployee(item, true)" v-if="isShowEmployeeOption">
                                    <v-list-item-title> Assign Employee </v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="printOrder(item)" v-if="item.is_from_inventory !== 1">
                                    <v-list-item-title> Print Order </v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="cancelOrder(item)" v-if="isShowCancelButton(item)">
                                    <v-list-item-title class="cancel"> Cancel Order </v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </div>

                    <div class="actions mr-2" v-if="isWarehouseConnected">
                        <button class="btn-edit inventory-btn-edit mr-2" 
                            @click.stop="checkEditInbound(item, 'edit')" v-if="currentTab === 0">
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
                                    <v-list-item @click="viewInbound(item)">
                                        <v-list-item-title> View </v-list-item-title>
                                    </v-list-item>
                                    
                                    <v-list-item v-if="currentTab === 0" @click="submitOrder(item, false)">
                                        <v-list-item-title> Submit </v-list-item-title>
                                    </v-list-item>

                                    <v-list-item v-if="currentTab === 0" @click="deleteOrder(item, false)">
                                        <v-list-item-title class="cancel"> Delete </v-list-item-title>
                                    </v-list-item>

                                    <v-list-item v-if="currentTab === 1" @click="cancelOrder(item)">
                                        <v-list-item-title class="cancel"> Cancel </v-list-item-title>
                                    </v-list-item>

                                    <v-list-item v-if="currentTab === 4" @click.stop="checkEditInbound(item, 'copy')">
                                        <v-list-item-title> Duplicate </v-list-item-title>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </div>                    
                    </div>
                </div>
            </template>

            <template v-slot:no-data>
                <div class="loading-wrapper" v-if="currentWarehouseLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate>
                    </v-progress-circular>
                </div>
                
                <div class="no-data-wrapper" 
                    v-if="!currentWarehouseLoading && currentWarehouseInbounds.data.length == 0">
                    <div class="no-data-heading" v-if="search === '' && selectedWhCustomers.length === 0">
                        <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">

                        <div v-if="currentTab === 0">
                            <h3> Create Inbound </h3>
                            <p> Let's add inbound to this warehouse. </p>

                            <div class="d-flex justify-center align-center mt-4">
                                <v-btn color="primary" dark class="btn-blue" @click.stop="addNewInbound">
                                    Create Inbound
                                </v-btn>
                            </div>
                        </div>

                        <div v-for="(tab, index) in tabsComputed" :key="index">
                            <span v-if="(currentTab === index && index !== 0)">
                                <h3> No {{ tab }} Inbounds </h3>
                                <p> There are no <span class="text-lowercase">{{ tab }}</span> inbounds listed. </p>
                            </span>                            
                        </div>
                    </div>

                    <div class="no-data-heading" v-if="search !== '' && selectedWhCustomers.length === 0">
                        <img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">

                        <div>
                            <h3> No Inbounds found. </h3>
                            <p> There are no inbounds listed. Try searching another keyword.</p>
                        </div>
                    </div>
                    <div v-if="selectedWhCustomers.length > 0">
						<img src="@/assets/icons/empty-inventory-icon.svg" width="40px" height="42px" alt="">

                        <div>
                            <h3> No Inbounds found. </h3>
                            <p> There are no inbounds listed. Change another Customer.</p>
                        </div>
					</div>
                </div>
            </template>

            <!-- FOR 3PL -->
            <template v-slot:[`item.created_at`]="{ item }">
                <div class="estimated_time" v-if="!isWarehouse3PLProvider">
                    <span>{{ formatDateDefault(item.created_at) }}</span>
                </div>

                <div class="estimated_time" v-if="isWarehouse3PLProvider">
                    <p class="mb-0">
                        {{ 
                            typeof item.customer_admin_name !== 'undefined' && 
                            item.customer_admin_name !== null ?
                            item.customer_admin_name : '--'
                        }}
                    </p>
                    <span style="color: #6D858F;">{{ formatDateDefault(item.created_at) }}</span>
                </div>
            </template>

            <template v-slot:[`item.updated_at`]="{ item }">
                <div class="estimated_time">
                    <span>{{ formatDateDefault(item.updated_at) }}</span>
                </div>
            </template>

            <template v-slot:[`item.assignment_status`]="{ item }">
                <div class="warehouse-status">
                    <v-chip :class="item.assignment_status">{{ item.assignment_status }}</v-chip>
                </div>
            </template>            
        </v-data-table>

        <CreateInboundDialog 
            :dialogCreate.sync="dialogCreate"
            :editedInboundIndex.sync="editedInboundIndex"
            :editedInboundItems.sync="editedInboundItems"
            :defaultInboundItems.sync="defaultInboundItems"
            :currentWarehouseSelected="currentWarehouseSelected"
            @close="closeCreate"
            :isMobile="isMobile"
            :parentTab.sync="currentTab"
            :productListsDropdownData.sync="productsListsData"
            :fetchProductLoading="fetchProductLoading"
            @callInboundProductsFor3PL="callInboundProductsFor3PL"
            :searchVal="search"
            :currentWarehouseInboundsData="currentWarehouseInbounds.data"
            @callWarehouseCustomerProducts="callWarehouseCustomerProducts"
            :fetchWarehouseCustomersLoading="fetchWarehouseCustomersLoading"
            @filterAllWarehouseCustomers="filterAllWarehouseCustomers"
            :selectedWhCustomers.sync="selectedWhCustomers"
            :selectedWhCustomersCopy.sync="selectedWhCustomersCopy"
            :isWarehouseConnected="isWarehouseConnected"
            :isWarehouseOwn="isWarehouseOwn" />

        <ConfirmDialog :dialogData.sync="dialogCancel">
            <template v-slot:dialog_icon>
				<div class="header-wrapper-close">
                    <img src="@/assets/icons/icon-delete.svg" alt="alert">
                </div>
			</template>

			<template v-slot:dialog_title>
				<h2> Cancel Order </h2>
			</template>

			<template v-slot:dialog_content>
				<p> 
					Are you sure you want to cancel inbound order
					<span class="name">"{{ cancelOrderData !== null ? cancelOrderData.order_id : '' }}"</span>?
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="cancelConfirm" text :disabled="getCancelInboundLoading">
					<span v-if="!getCancelInboundLoading">Confirm</span>
                    <span v-if="getCancelInboundLoading">Confirming...</span>
				</v-btn>

				<v-btn class="btn-white" text @click="closeCancel" :disabled="getCancelInboundLoading">
					Discard
				</v-btn>
			</template>
		</ConfirmDialog>      

        <TruckArrivedDialog 
            :dialogData.sync="dialogTruckArrived"
            :editedItemData.sync="truckArrivedData"
            @close="closeTruckArrivedDialog"
            :loading="getTruckArrivedLoading"
            :linkData="linkData"
            :isFetchSingleInbound="false"
            :isWarehouse3PLProvider="isWarehouse3PLProvider" />

        <NewStorableUnitDialog 
            :dialog.sync="dialogNewStorableUnit" 
            :editedItem.sync="storableItemsData"
            :productsData="newStorableData"
            :linkData="linkData"
            @close="closeStorableUnit"
            :index="-1" />  

        <PaginationComponent 
            :totalPage.sync="getTotalPage"
            :currentPage.sync="getCurrentPage"
            @handlePageChange="handlePageChange"
            :isMobile="isMobile" />

        <!-- SHOW edit once 3PL and Completed -->
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
					This order has already been completed. Do you still want to edit this inbound order?
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

        <ReceiveProductDialog 
            :dialogData.sync="dialogReceiveProduct"
            :editedItemData.sync="productSelectedToReceive"
            @close="closeProductReceive"
            :loading="false"
            :productLists="productsListsData"
            :linkData="linkData"
            :multiple="true"
            @clearSelection="clearSelection"
            :title="title"
            :inboundStatus="selectedInboundStatus"
            :isWarehouse3PL="isWarehouse3PL"
            :undershipped="isSelectedUndershipped"
            :isWarehouse3PLProvider="isWarehouse3PLProvider" />

        <!-- FOR EDIT INVENTORY -->
        <AddInventoryProducts 
            :editedProductIndex="editedProductIndex"
            :editedProductItems.sync="editedProductItems"
            :dialogAdd.sync="dialogEditFromInventory"
            :isMobile="isMobile"
            :productListsDropdownData="productsListsData"
            @close="closeEditInventory"
            :currentWarehouseSelected="currentWarehouseSelected"
            @openAddProductDialog="openAddProductDialog"
            :fetchProductLoading="fetchProductLoading"
            :isFetchSingleInbound="false" />

        <ProductAddDialog 
            :dialog.sync="dialogEditProduct"
            :editedIndex.sync="editedIndexProductCreate"
            :defaultItem.sync="defaultProductItemCreate"
            :editedItem.sync="editedProductItemCreate"
            :categoryLists="categoryListData"
            @close="closeProductDialogCreate"
            @setToDefault="setToDefault"
            :isMobile="isMobile"
            :isInventoryPage="true"
            :isWarehouse3PL="isWarehouse3PL"
            :isWarehouse3PLProvider="isWarehouse3PLProvider"
            @callInboundProductsFor3PL="callInboundProductsFor3PL"
            @callWarehouseCustomerProducts="callWarehouseCustomerProducts"
            :inboundItems="editedInboundItems" />

        <!-- Complete Order Dialog -->
        <ConfirmDialog :dialogData.sync="dialogConfirm">
            <template v-slot:dialog_icon>
                <div class="header-wrapper-close">
                    <img src="@/assets/icons/question-blue.svg" width="48px" height="48px">
                </div>
            </template>

            <template v-slot:dialog_title>
                <h2> Confirm Order as Completed? </h2>
            </template>

            <template v-slot:dialog_content>
                <p> Do you want to mark the Inbound order completed? </p>
            </template>

            <template v-slot:dialog_actions>
                <v-btn class="btn-blue" @click="confirmCompleted" text :disabled="getCompleteInboundOrderLoading">
                    <span v-if="!getCompleteInboundOrderLoading">Mark as Completed</span>
                    <span v-if="getCompleteInboundOrderLoading">Marking...</span>
                </v-btn>

                <v-btn class="btn-white" text @click="closeCompletedDialog">
                    Cancel
                </v-btn>
            </template>
        </ConfirmDialog>

        <!-- Delete Inbound Dialog -->
        <ConfirmDialog :dialogData.sync="deleteInboundDialog">
            <template v-slot:dialog_icon>
                <div class="header-wrapper-close">
                    <img src="@/assets/icons/icon-delete.svg" alt="alert">
                </div>
            </template>

            <template v-slot:dialog_title>
                <h2> Delete Inbound </h2>
            </template>

            <template v-slot:dialog_content>
                <p> Do you want to delete the inbound order from draft? <br/> Once deleted, you can't recover it. </p>
            </template>

            <template v-slot:dialog_actions>
                <v-btn class="btn-blue" @click="deleteOrder(currentSelectedInboundOrder, true)" text :disabled="getDeleteInboundLoading">
                    <span v-if="!getDeleteInboundLoading">Delete</span>
                    <span v-if="getDeleteInboundLoading">Deleting...</span>
                </v-btn>

                <v-btn class="btn-white" text @click="cancelDelete" :disabled="getDeleteInboundLoading">
                    Cancel
                </v-btn>
            </template>
        </ConfirmDialog>

        <!-- Submit Inbound Dialog -->
        <ConfirmDialog :dialogData.sync="submitInboundDialog">
            <template v-slot:dialog_icon>
                <div class="header-wrapper-close">
                    <img src="@/assets/icons/question-blue.svg" width="48px" height="48px">
                </div>
            </template>

            <template v-slot:dialog_title>
                <h2> Submit Inbound </h2>
            </template>

            <template v-slot:dialog_content>
                <p> Do you want to submit the draft inbound order to <br/>
                    <span style="font-family: 'Inter-SemiBold', sans-serif !important;">
                        '{{ currentSelectedInboundOrder !== null && currentSelectedInboundOrder.warehouse !== null ? 
                            currentSelectedInboundOrder.warehouse.name : '' 
                        }}'?
                    </span>
                </p>
            </template>

            <template v-slot:dialog_actions>
                <v-btn class="btn-blue" @click="submitOrder(currentSelectedInboundOrder, true)" text :disabled="getSubmitInboundLoading">
                    <span v-if="!getSubmitInboundLoading">Submit</span>
                    <span v-if="getSubmitInboundLoading">Submitting...</span>
                </v-btn>

                <v-btn class="btn-white" text @click="cancelSubmit" :disabled="getSubmitInboundLoading">
                    Cancel
                </v-btn>
            </template>
        </ConfirmDialog>

        <!-- Accept Inbound Dialog -->
        <ConfirmDialog :dialogData.sync="acceptInboundDialog">
            <template v-slot:dialog_icon>
                <div class="header-wrapper-close">
                    <img src="@/assets/icons/question-blue.svg" width="48px" height="48px">
                </div>
            </template>

            <template v-slot:dialog_title>
                <h2> Accept Inbound Order? </h2>
            </template>

            <template v-slot:dialog_content>
                <p> Do you want accept inbound order 
                    <span style="font-family: 'Inter-SemiBold', sans-serif !important;">
                        '{{ currentSelectedInboundOrder !== null ? currentSelectedInboundOrder.order_id : '' }}'
                    </span> from
                    <span style="font-family: 'Inter-SemiBold', sans-serif !important;">
                        '{{ currentSelectedInboundOrder !== null && currentSelectedInboundOrder.warehouse_customer !== null ? 
                            currentSelectedInboundOrder.warehouse_customer.company_name : '' 
                        }}'
                    </span>?
                    Once accepted you can't cancel it.
                </p>
            </template>

            <template v-slot:dialog_actions>
                <v-btn class="btn-blue" @click="acceptOrder(currentSelectedInboundOrder, true)" text :disabled="getAcceptInboundLoading">
                    <span v-if="!getAcceptInboundLoading">Accept</span>
                    <span v-if="getAcceptInboundLoading">Accepting...</span>
                </v-btn>

                <v-btn class="btn-white" text @click="cancelAccept" :disabled="getAcceptInboundLoading">
                    Cancel
                </v-btn>
            </template>
        </ConfirmDialog>

        <!-- Reject Inbound Dialog -->
        <ConfirmDialog :dialogData.sync="rejectInboundDialog">
            <template v-slot:dialog_icon>
                <div class="header-wrapper-close">
                    <img src="@/assets/icons/icon-delete.svg" alt="alert">
                </div>
            </template>

            <template v-slot:dialog_title>
                <h2> Reject Order </h2>
            </template>

            <template v-slot:dialog_content>
                <p class="mb-3"> Do you want to reject order
                    <span style="font-family: 'Inter-SemiBold', sans-serif !important;">
                        '{{ currentSelectedInboundOrder !== null ? currentSelectedInboundOrder.order_id : '' }}'
                    </span>
                    with the refence number <br/>
                    <span style="font-family: 'Inter-SemiBold', sans-serif !important;">
                        '{{ currentSelectedInboundOrder !== null ? currentSelectedInboundOrder.ref_no : '' }}'?
                    </span>
                </p>

                <div class="inbound-notes mt-4">
                    <p class="inbound-title">NOTE <span>(Optional)</span></p>
                    <v-textarea
                        class="note"
                        outlined
                        v-model="rejectNotes"
                        name="input-7-4"
                        placeholder="Type reason for rejection"
                        height="86px"
                        hide-details="auto">
                    </v-textarea>
                </div>
            </template>

            <template v-slot:dialog_actions>
                <v-btn class="btn-blue" @click="rejectOrder(currentSelectedInboundOrder, true)" text :disabled="getRejectInboundLoading">
                    <span v-if="!getRejectInboundLoading">Reject</span>
                    <span v-if="getRejectInboundLoading">Rejecting...</span>
                </v-btn>

                <v-btn class="btn-white" text @click="cancelReject" :disabled="getRejectInboundLoading">
                    Cancel
                </v-btn>
            </template>
        </ConfirmDialog>

        <AssignEmployeeDialog 
            :dialogData.sync="showAssignEmployeeDialog"
            :editedItemData.sync="selectedInboundOrders"
            @close="closeAssignEmployee"
            :fromComponent="'Inbound'"
            :editedIndexData.sync="editedIndexAssign" /> 

        <AssignLocationDialog 
            :dialogData.sync="showAssignLocationDialog"
            :editedItemData.sync="selectedInboundOrders"
            @close="closeAssignLocation"
            :fromComponent="'Inbound'"
            :isHasLocationAssigned="isHasLocationAssigned" /> 
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import SearchComponent from '../../../SearchComponent/SearchComponent.vue'
import CreateInboundDialog from '../../../InventoryComponents/InboundComponents/Dialogs/CreateInboundDialog.vue'
import ConfirmDialog from '../../../Dialog/GlobalDialog/ConfirmDialog.vue'
import TruckArrivedDialog from '../../../InventoryComponents/InboundComponents/Dialogs/TruckArrivedDialog.vue'
import NewStorableUnitDialog from '../../../InventoryComponents/InboundComponents/Dialogs/NewStorableUnitDialog.vue'
import PaginationComponent from '../../../PaginationComponent/PaginationComponent.vue'
import ReceiveProductDialog from '../../../InventoryComponents/InboundComponents/Dialogs/ReceiveProductDialog.vue'
import AddInventoryProducts from '../../../InventoryComponents/ProductsComponents/AddInventoryProducts.vue'
import ProductAddDialog from '../../../ProductComponents/Dialog/ProductAddDialog.vue'
import globalMethods from '../../../../utils/globalMethods'
import _ from 'lodash'
import inventoryGlobalMethods from '../../../../utils/inventoryMethods/inventoryGlobalMethods'
import FilterCustomers from '../../../InventoryComponents/FilterCustomers/FilterCustomers'
import AssignEmployeeDialog from '../../../InventoryComponents/InboundComponents/Dialogs/AssignEmployeeDialog.vue'
import AssignLocationDialog from '../../../InventoryComponents/InboundComponents/Dialogs/AssignLocationDialog.vue'

import axios from 'axios'
var cancel;
var CancelToken = axios.CancelToken;

export default {
    name: 'InventoryInboundDesktopTable',
    props: ['currentWarehouseSelected', 'isMobile', 'isWarehouseConnected', 'isWarehouse3PL', 'isWarehouse3PLProvider', 'isWarehouseOwn'],
    components: {
        SearchComponent,
        CreateInboundDialog,
        ConfirmDialog,
        TruckArrivedDialog,
        NewStorableUnitDialog,
        PaginationComponent,
        ReceiveProductDialog,
        AddInventoryProducts,
        ProductAddDialog,
        FilterCustomers,
        AssignEmployeeDialog,
        AssignLocationDialog
    },
    data: () => ({
        page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        search: '',
        selected: [],
        headersDefault: [
            {
				text: 'Order ID',
				align: 'start',
				sortable: false,
				value: 'order_id',
				fixed: true,
				width: "8%"
			},
            { 
                text: 'Created At',
                align: 'start',
                sortable: false,
                value: 'created_at',
                fixed: true,
                width: "16%"
            },
            { 
                text: 'Created By & At',
                align: 'start',
                sortable: false,
                value: 'created_at',
                fixed: true,
                width: "20%"
            },
            { 
				text: 'Truck',
				align: 'start',
				sortable: false,
				value: 'name',
				fixed: true,
				width: "12%"
			},
            { 
                text: 'Reference',
                align: 'start',
                sortable: false,
                value: 'ref_no',
                fixed: true,
                width: "12%"
            },
            { 
				text: 'Customer',
				align: 'start',
				sortable: false,
				value: 'warehouse_customer',
				fixed: true,
				width: "20%"
			},
            { 
                text: 'Updated At',
                align: 'start',
                sortable: false,
                value: 'updated_at',
                fixed: true,
                width: "16%"
            },
            { 
				text: 'ETA/Arrival Time',
				align: 'start',
				sortable: false,
				value: 'estimated_time',
				fixed: true,
				width: "16%"
			},
            { 
				text: 'Status',
				align: 'center',
				sortable: false,
				value: 'inbound_status',
				fixed: true,
				width: "12%"
			},
            { 
                text: 'Alert',
                align: 'center',
                sortable: false,
                value: 'inbound_status',
                fixed: true,
                width: "12%"
            },
            { 
                text: 'WHStatus',
                align: 'center warehouse-status-col',
                sortable: false,
                value: 'assignment_status',
                fixed: true,
                width: "12%"
            },
            { 
				text: '', 
				align: 'end',
				value: 'actions', 
				sortable: false,
				fixed: true,
				width: ""
			},
        ],
		dialogCreate: false,
		editedInboundIndex: -1,
        editedInboundItems: {
			order_id: '',
            supplier: '',
            shipping_from: '',
            notes: '',
            ref_no: '',
            name: '',
            estimated_date: null,
            estimated_time: null,
            inbound_products: [
                {
                    product: {
                        product_id: '',
                    },
                    product_id: '',
                    quantity: '',
                    shipping_unit: '',
                    instructions: ''
                    // total_unit: ''
                }
            ],
            documents: [],
            warehouse_customer_id: ''
		},
		defaultInboundItems: {
			order_id: '',
            supplier: '',
            shipping_from: '',
            notes: '',
            ref_no: '',
            name: '',
            estimated_date: null,
            estimated_time: null,
            inbound_products: [
                {
                    product: {
                        product_id: '',
                    },
                    product_id: '',
                    quantity: '',
                    shipping_unit: '',
                    instructions: ''
                }
            ],
            documents: [],
            warehouse_customer_id: ''
		},
        tabs: ["Pending", "Floor", "Completed", "Cancelled"],
        tabsConnected: ["Draft", "Waiting Approval", "Pending Receiving", "Completed", "Archived"],
        tabsForConnectedProvider: ["Waiting Approval", "Pending Receiving", "Floor", "Completed", "Cancelled"],
        currentTab: 0,
        dialogCancel: false,
        cancelOrderData: null,
        dialogTruckArrived: false,
        truckArrivedData: {
            name: '',
            ref_no: ''
        },
        linkData: {
            inbound_id: '',
            warehouse_id: ''
        },
        dialogNewStorableUnit: false,
        newStorableData: null,
        pendingInboundLoadingPage: false,
        floorInboundLoadingPage: false,
        completedInboundLoadingPage: false,
        cancelledInboundLoadingPage: false,
        storableItemsData: {
            action: "",
            type: "",
            customer_id: null,
            dimension: {
                l: '',
                w: '',
                h: '',
                uom: 'cm'
            },
            weight: {
                value: '',
                unit: 'KG'
            },
            products: [],
            copies: 1
        },
        productsListsData: [],
        lastDataCheck: [],
        current_page_is: 1,
        fetchProductLoading: false,
        fetchPendingInboundsLoading: true,
        showWarningEditInboundDialog: false,
        currentEditInboundData: null,
        dialogReceiveProduct: false,
        title: 'receive',
        productSelectedToReceive: [],
        selectedInboundStatus: 'pending',
        isSelectedUndershipped: 0,
        dialogEditFromInventory: false,
        editedProductIndex: 0,
        editedProductItems: {
            inventory_as_of: '',
            notes: '',
            inbound_products: []
        },
        defaultProductItems: {
            inventory_as_of: '',
            notes: '',
            inbound_products: []
        },
        // add product dialog
        dialogEditProduct: false,
        editedIndexProductCreate: -1,
        editedProductItemCreate: {
			sku: null,
			name: '',
			category_id: null,
			description: '',
			units_per_carton: '',
			image: null,
			classification_code: '',
			additional_classification_code: '',
			duty_rate: '',
			unit_price: '',
			upc_number: '',
			carton_upc: '',
			is_for_classification_code: 1,			
			userClassification: 0,
			country_from: '',
			country_to: '',
			product_classification_description: '',
			carton_dimensions: {
				l: '',
				w: '',
				h: '',
				uom: 'cm'
			},
			unit_dimensions: {
				l: '',
				w: '',
				h: '',
				uom: 'cm'
			},
			unit_weight: {
				value: '',
				unit: 'kg'
			},
			preferred_unit_quantity: '',
			warehouse_customer_id: null
		},
		defaultProductItemCreate: {
			sku: null,
			name: '',
			category_id: null,
			description: '',
			units_per_carton: '',
			image: null,
			classification_code: '',
			additional_classification_code: '',
			duty_rate: '',
			unit_price: '',
			upc_number: '',
			carton_upc: '',
			is_for_classification_code: 1,			
			userClassification: 0,
			country_from: '',
			country_to: '',
			product_classification_description: '',
			carton_dimensions: {
				l: '',
				w: '',
				h: '',
				uom: 'cm'
			},
			unit_dimensions: {
				l: '',
				w: '',
				h: '',
				uom: 'cm'
			},
			unit_weight: {
				value: '',
				unit: 'kg'
			},
			preferred_unit_quantity: '',
			warehouse_customer_id: null
		},
        categoryListData: [],
        current_page_is_category: 1,
        // warehouse customers data
        selectedWhCustomers: [],
        selectedWhCustomersCopy: [],
        current_page_is_warehouse_products: 1,
		current_page_is_whcustomers: 1,
        searchCustomerData: '',
        warehouseCustomerListsData: [],
        warehouseCustomerLists: [],
        warehouseCustomerListsCopy: [],
        fetchWarehouseCustomersLoading: true,
        filterMenu: false,
        isActiveClicked: false,
        // complete order
        dialogConfirm: false,
        toCompleteOrderData: null,
        selectedWhCustomerReAssignValue:[],
        currentSelectedInboundOrder: null,
        deleteInboundDialog: false,
        submitInboundDialog: false,
        acceptInboundDialog: false,
        rejectInboundDialog: false,
        rejectNotes: '',
        // assigning employee
        showAssignEmployeeDialog: false,
        selectedInboundOrders: [],
        showAssignLocationDialog: false,
        editedIndexAssign: -1
    }),
    computed: {
        ...mapGetters({
            getUser: 'getUser',
            getCancelInboundLoading: 'inbound/getCancelInboundLoading',
            getTruckArrivedLoading: 'inbound/getTruckArrivedLoading',
            // inbound loading
            getPendingInboundsLoading: 'inbound/getPendingInboundsLoading',
            getFloorInboundsLoading: 'inbound/getFloorInboundsLoading',
            getCompletedInboundsLoading: 'inbound/getCompletedInboundsLoading',
            getCancelledInboundsLoading: 'inbound/getCancelledInboundsLoading',
            // inbound data and paginations
            getPendingInboundPagination: 'inbound/getPendingInboundPagination',
            getFloorInboundPagination: 'inbound/getFloorInboundPagination',
            getCompletedInboundPagination: 'inbound/getCompletedInboundPagination',
            getCancelledInboundPagination: 'inbound/getCancelledInboundPagination',
            getCurrentInboundTab: 'inbound/getCurrentInboundTab',
            poBaseUrlState: 'products/poBaseUrlState',
            getSearchedInbounds: 'inbound/getSearchedInbounds',
            getSearchedInboundsLoading: 'inbound/getSearchedInboundsLoading',
            getAllInboundProductsLists: 'inbound/getAllInboundProductsLists',
            getAllInboundProductsListsDropdownData: 'inbound/getAllInboundProductsListsDropdownData',
            getIsShowCreateInboundDialog: 'inbound/getIsShowCreateInboundDialog',
            getAllCategoryDropdownLists: 'productInventories/getAllCategoryDropdownLists',
            getAllWarehouseCustomerInboundProductsLists: 'inbound/getAllWarehouseCustomerInboundProductsLists',
            getAllWarehouseCustomerListsData: 'warehouseCustomers/getAllWarehouseCustomerListsData',
			getWarehouseCustomersDropdown: 'warehouseCustomers/getWarehouseCustomersDropdown',
            // filter and search
            getfilteredInboundsLoading:'inbound/getfilteredInboundsLoading',
            getfilteredInbounds:'inbound/getfilteredInbounds',
            getCompleteInboundOrderLoading: 'inbound/getCompleteInboundOrderLoading',
            getPendingReceiveInboundPagination: 'inbound/getPendingReceiveInboundPagination',
            getPendingReceiveInboundLoading: 'inbound/getPendingReceiveInboundLoading',
            getPendingReceiveInboundPaginationWHCustomer: 'inbound/getPendingReceiveInboundPaginationWHCustomer',
            getPendingReceiveInboundLoadingWHCustomer: 'inbound/getPendingReceiveInboundLoadingWHCustomer',
            getDraftInboundPagination: 'inbound/getDraftInboundPagination',
            getDraftInboundPaginationLoading: 'inbound/getDraftInboundPaginationLoading',
            getDeleteInboundLoading: 'inbound/getDeleteInboundLoading',
            getSubmitInboundLoading: 'inbound/getSubmitInboundLoading',
            getAcceptInboundLoading: 'inbound/getAcceptInboundLoading',
            getRejectInboundLoading: 'inbound/getRejectInboundLoading'
        }),
        removeCheckboxOnCompleteAndCancelled() {
            let show = true

            if (this.isWarehouse3PLProvider) {
                if (this.currentWarehouseSelected !== null && this.currentWarehouseSelected.is_own !== 'undefined' &&
                    this.currentWarehouseSelected.is_own === 0) {
                    if (this.currentTab === 2 || this.currentTab === 3 || this.currentTab === 4) {
                        show = false
                    }
                } else {                    
                    if (this.currentTab === 3 || this.currentTab === 4) {
                        show = false
                    }
                }
            } else {
                if (this.currentTab === 2 || this.currentTab === 3) {
                    show = false
                }
            }

            return show
        },
        draftInboundsDataLists() {
            let inboundLists = []

            if (typeof this.getSearchedInbounds !== 'undefined' && this.getSearchedInbounds !== null && 
                this.getfilteredInbounds !== 'undefined' && this.getfilteredInbounds !== null) {
                if (this.search !== '' && this.currentTab === 0 && this.getSearchedInbounds.tab === 'draft') {
                    if (this.selectedWhCustomers.length === 0) {
                        inboundLists = this.getSearchedInbounds
                    } else {
                        inboundLists = this.getfilteredInbounds
                    }
                } else {
                    if (this.selectedWhCustomers.length > 0 && this.currentTab === 0 && this.getfilteredInbounds.tab === 'draft') {
                        inboundLists = this.getfilteredInbounds
                    } else {
                        inboundLists = this.getDraftInboundPagination
                    }
                }
            }  

            return inboundLists
        },
        pendingReceiveInboundsDataLists() {
            let inboundLists = []

            if (typeof this.getSearchedInbounds !== 'undefined' && this.getSearchedInbounds !== null && 
                this.getfilteredInbounds !== 'undefined' && this.getfilteredInbounds !== null) {
                if (!this.isWarehouseConnected) {
                    if (this.search !== '' && this.currentTab === 1 && this.getSearchedInbounds.tab === 'receive_pending') {
                        if (this.selectedWhCustomers.length === 0) {
                            inboundLists = this.getSearchedInbounds
                        } else {
                            inboundLists = this.getfilteredInbounds
                        }
                    } else {
                        if (this.selectedWhCustomers.length > 0 && this.currentTab === 1 && this.getfilteredInbounds.tab === 'receive_pending') {
                            inboundLists = this.getfilteredInbounds
                        } else {
                            inboundLists = this.getPendingReceiveInboundPagination
                        }
                    }
                } else {
                    if (this.search !== '' && this.currentTab === 2 && this.getSearchedInbounds.tab === 'receive_pending_wh') {
                        if (this.selectedWhCustomers.length === 0) {
                            inboundLists = this.getSearchedInbounds
                        } else {
                            inboundLists = this.getfilteredInbounds
                        }
                    } else {
                        if (this.selectedWhCustomers.length > 0 && this.currentTab === 2 && this.getfilteredInbounds.tab === 'receive_pending_wh') {
                            inboundLists = this.getfilteredInbounds
                        } else {
                            inboundLists = this.getPendingReceiveInboundPaginationWHCustomer
                        }
                    }
                }
            }  

            return inboundLists
        },
        pendingInboundsDataLists() {
            let inboundLists = []

            if (typeof this.getSearchedInbounds !== 'undefined' && this.getSearchedInbounds !== null && 
                this.getfilteredInbounds !== 'undefined' && this.getfilteredInbounds !== null) {
                if (this.search !== '' && this.getSearchedInbounds.tab === 'pending') {
                    if (this.selectedWhCustomers.length === 0) {
                        inboundLists = this.getSearchedInbounds
                    } else {
                        inboundLists = this.getfilteredInbounds
                    }
                } else {
                    if (this.selectedWhCustomers.length > 0 && this.getfilteredInbounds.tab === 'pending') {
                        inboundLists = this.getfilteredInbounds
                    } else {
                        inboundLists = this.getPendingInboundPagination
                    }
                }
            }            

            return inboundLists
        },
        floorInboundsDataLists() {
            let inboundLists = []

            if (typeof this.getSearchedInbounds !== 'undefined' && this.getSearchedInbounds !== null && 
                this.getfilteredInbounds !== 'undefined' && this.getfilteredInbounds !== null) {
                if (this.search !== '' && this.getSearchedInbounds.tab === 'floor') {
                    if (this.selectedWhCustomers.length === 0) {
                        inboundLists = this.getSearchedInbounds
                    } else {
                        inboundLists = this.getfilteredInbounds
                    }
                } else {
                    if (this.selectedWhCustomers.length > 0 && this.getfilteredInbounds.tab === 'floor') {
                        inboundLists = this.getfilteredInbounds
                    } else {
                        inboundLists = this.getFloorInboundPagination
                    }
                }
            } 

            return inboundLists
        },
        completedInboundsDataLists() {
            let inboundLists = []

            if (typeof this.getSearchedInbounds !== 'undefined' && this.getSearchedInbounds !== null && 
                this.getfilteredInbounds !== 'undefined' && this.getfilteredInbounds !== null) {
                if (this.search !== '' && this.getSearchedInbounds.tab === 'completed') {
                    if (this.selectedWhCustomers.length === 0) {
                        inboundLists = this.getSearchedInbounds
                    } else {
                        inboundLists = this.getfilteredInbounds
                    }
                } else {
                    if (this.selectedWhCustomers.length > 0 && this.getfilteredInbounds.tab === 'completed') {
                        inboundLists = this.getfilteredInbounds
                    } else {
                        inboundLists = this.getCompletedInboundPagination
                    }
                }
            } 

            return inboundLists
        },
        cancelledInboundsDataLists() {
            let inboundLists = []

            // if (typeof this.getSearchedInbounds !== 'undefined' && this.getSearchedInbounds !== null && 
            //     this.getfilteredInbounds !== 'undefined' && this.getfilteredInbounds !== null) {
            //     if (this.search !== '' && this.currentTab === 3 && this.getSearchedInbounds.tab === 'cancelled') {
            //         inboundLists = this.getSearchedInbounds
            //     } else if (this.selectedWhCustomers.length > 0 && this.currentTab === 3 && 
            //         this.getfilteredInbounds.tab === 'cancelled') {
			// 		inboundLists = this.getfilteredInbounds
			// 	} else {
            //         if (typeof this.getCancelledInboundPagination !== 'undefined' && 
            //             this.getCancelledInboundPagination !== null) {
            //             inboundLists = this.getCancelledInboundPagination
            //         }
            //     }
            // }

            if (typeof this.getSearchedInbounds !== 'undefined' && this.getSearchedInbounds !== null && 
                this.getfilteredInbounds !== 'undefined' && this.getfilteredInbounds !== null) {
                if (this.search !== '' && this.getSearchedInbounds.tab === 'cancelled') {
                    if (this.selectedWhCustomers.length === 0) {
                        inboundLists = this.getSearchedInbounds
                    } else {
                        inboundLists = this.getfilteredInbounds
                    }
                } else {
                    if (this.selectedWhCustomers.length > 0 && this.getfilteredInbounds.tab === 'cancelled') {
                        inboundLists = this.getfilteredInbounds
                    } else {
                        inboundLists = this.getCancelledInboundPagination
                    }
                }
            } 

            return inboundLists
        },
		currentWarehouseInbounds() {
            let inboundsData = {}

            if (!this.isWarehouseConnected) {
                if (this.currentTab === 0) {
                    inboundsData = this.pendingInboundsDataLists
                } else if (this.currentTab === 1) {
                    if (this.isWarehouse3PLProvider) {
                        inboundsData = this.pendingReceiveInboundsDataLists
                    } else {
                        inboundsData = this.floorInboundsDataLists
                    }
                } else if (this.currentTab === 2) {
                    if (this.isWarehouse3PLProvider) {
                        inboundsData = this.floorInboundsDataLists
                    } else {
                        inboundsData = this.completedInboundsDataLists
                    }
                } else if (this.currentTab === 3) {
                    if (this.isWarehouse3PLProvider) {
                        inboundsData = this.completedInboundsDataLists
                    } else {
                        inboundsData = this.cancelledInboundsDataLists
                    }                    
                } else {
                    inboundsData = this.cancelledInboundsDataLists
                }
            } else {
                // update for connected warehouse data
                if (this.currentTab === 0) {
                    inboundsData = this.draftInboundsDataLists
                } else if (this.currentTab === 1) {
                    inboundsData = this.pendingInboundsDataLists
                } else if (this.currentTab === 2) {
                    inboundsData = this.pendingReceiveInboundsDataLists
                } else if (this.currentTab === 3) {
                    inboundsData = this.completedInboundsDataLists
                } else {
                    inboundsData = this.cancelledInboundsDataLists
                }
            }
            
            return inboundsData
        },
        currentWarehouseLoading() {
            if (!this.isWarehouseConnected) {
                if (this.currentTab === 0) {
                    return this.getPendingInboundsLoading
                } else if (this.currentTab === 1) {
                    if (this.isWarehouse3PLProvider) {
                        return this.getPendingReceiveInboundLoading
                    } else {
                        return this.getFloorInboundsLoading
                    }
                } else if (this.currentTab === 2) {
                    if (this.isWarehouse3PLProvider) {
                        return this.getFloorInboundsLoading
                    } else {
                        return this.getCompletedInboundsLoading
                    }
                } else if (this.currentTab === 3) {
                    if (this.isWarehouse3PLProvider) {
                        return this.getCompletedInboundsLoading
                    } else {
                        return this.getCancelledInboundsLoading
                    }                    
                } else {
                    return this.getCancelledInboundsLoading
                }
            } else {
                // update for connected warehouse data
                if (this.currentTab === 0) {
                    return this.getDraftInboundPaginationLoading
                } else if (this.currentTab === 1) {
                    return this.getPendingInboundsLoading
                } else if (this.currentTab === 2) {
                    return this.getPendingReceiveInboundLoadingWHCustomer
                } else if (this.currentTab === 3) {
                    return this.getCompletedInboundsLoading
                } else {
                    return this.getCancelledInboundsLoading
                }
            }
        },
        getTotalPage: {
            get() {
                let total = 1
                let inboundsData = this.currentWarehouseInbounds

                if (typeof inboundsData.last_page !== 'undefined' && inboundsData.last_page !== null) {
                    total = inboundsData.last_page
                }

                return total
            }
        },
        getCurrentPage: {
            get() {
                let current_page = 1
                let inboundsData = this.currentWarehouseInbounds

                if (typeof inboundsData.current_page !== 'undefined' && inboundsData.current_page !== null) {
                    current_page = inboundsData.current_page
                }

                return current_page
            },
            set() {
                return {}
            }
        },
        getCurrentLoadingToDisplay() {
            if (this.search === '' && this.selectedWhCustomers.length === 0) {
                return this.getHandlePageLoading
            }else if(this.selectedWhCustomers.length > 0) {
                return this.getfilteredInboundsLoading
            } else {
                return this.getSearchedInboundsLoading
            }
        },
        getHandlePageLoading() {
            if (this.currentTab === 0) {
                // return this.fetchPendingInboundsLoading
                return this.pendingInboundLoadingPage
            } else if (this.currentTab === 1) {
                return this.floorInboundLoadingPage
            } else if (this.currentTab === 2) {
                return this.completedInboundLoadingPage
            } else {
                return this.cancelledInboundLoadingPage
            }
        },
        getSearchedDataClass() {
            if (this.currentWarehouseInbounds.data.length == 0 && this.search !== '') {
                return true
            } else {
                return false
            }
        },
        handleSearchComponent() {
            let isShow = true

            if (this.search == '' && this.currentWarehouseInbounds.data.length === 0 && this.selectedWhCustomers.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.currentWarehouseInbounds.data.length === 0) {
                isShow = true
            } else if (this.selectedWhCustomers.length > 0 && this.currentWarehouseInbounds.data.length === 0) {
                isShow = false
            }

            return isShow
        },
        handleFilterComponent() {
            let isShow = true

            if (this.search == '' && this.selectedWhCustomers.length === 0 && this.currentWarehouseInbounds.data.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.currentWarehouseInbounds.data.length === 0) {
                isShow = true
            } else if (this.search !== '' || this.currentWarehouseInbounds.data.length > 0) {
				isShow = true
			}
            return isShow
        },
        headersComputed() {
            let defaultHeaders = this.headersDefault

            if (this.isWarehouse3PL) {
                defaultHeaders = defaultHeaders.filter(v => {
                    if (v.text === 'Order ID') {
                        v.width = '12%'
                    }

                    if (v.text === 'Reference' || v.text === 'ETA/Arrival Time' || v.text === 'Created At') {
                        v.width = '20%'
                    }
                    
                    if (v.text === 'Alert' || v.text === 'Status') {
                        v.width = '15%'
                    }

                    if (this.currentTab === 0) {
                        return v.text !== 'Created By & At' && v.text !== 'Truck' && v.text !== 'Customer' && v.text !== 'Updated At' && v.text !== 'Status' && v.text !== 'WHStatus'
                    } else if (this.currentTab === 2) {
                        return v.text !== 'Created By & At' && v.text !== 'Truck' && v.text !== 'Customer' && v.text !== 'ETA/Arrival Time' && v.text !== 'Alert' && v.text !== 'WHStatus'
                    } else if (this.currentTab === 3) {
                        return v.text !== 'Created By & At' && v.text !== 'Truck' && v.text !== 'Customer' && v.text !== 'ETA/Arrival Time' && v.text !== 'Status' && v.text !== 'Alert' && v.text !== 'WHStatus'
                    }
                })
            } else if (this.isWarehouse3PLProvider) {
                defaultHeaders = defaultHeaders.filter(v => {
                    if (this.isWarehouseConnected) {
                        if (v.text == 'Created By & At') {
                            v.width = '22%'
                        }
                        
                        if (v.text === 'Order ID') {
                            v.width = '12%'
                        }

                        if (v.text === 'Truck') {
                            v.width = '18%'
                        }

                        if (v.text === 'Reference') {
                            v.width = '18%'
                        }

                        if (v.text === 'Status') {
                            v.width = '24%'
                        }

                        if (this.currentTab !== 2 && this.currentTab !== 3 && this.currentTab !== 4) {
                            return v.text !== 'Created At' && v.text !== 'Customer' && v.text !== 'Updated At' && v.text !== 'Alert' && v.text !== 'Status' && v.text !== 'WHStatus'
                        } else {
                            if (v.text === '') {
                                v.width = '12%'
                            }
                            
                            return v.text !== 'Created At' && v.text !== 'Created By & At' && v.text !== 'Customer' && v.text !== 'Updated At' && v.text !== 'Alert' && v.text !== 'WHStatus'
                        }
                    } else {
                        if (v.text == 'Created By & At') {
                            v.width = '20%'
                        }

                        if (this.currentTab === 0) {
                            if (v.text === 'Order ID') {
                                v.width = '8%'
                            }

                            if (v.text === 'Truck' || v.text === 'Reference') {
                                v.width = '12%'
                            }
                        }

                        if (this.currentTab === 1 || this.currentTab === 2) {
                            if (v.text === 'Order ID') {
                                v.width = '7%'
                            }

                            if (v.text === 'ETA/Arrival Time' || v.text === 'Customer') {
                                v.width = '18%'
                            }

                            if (v.text === 'Truck' || v.text === 'Reference') {
                                v.width = '13%'
                            }
                        }

                        if (this.currentTab === 3 || this.currentTab === 4) {
                            if (v.text === 'Order ID') {
                                v.width = '10%'
                            }
                        }

                        if (this.currentTab === 1 || this.currentTab === 2) {
                            return v.text !== 'Created At' && v.text !== 'Updated At' && v.text !== 'Alert' && v.text !== 'Status'
                        } else {
                            return v.text !== 'Created At' && v.text !== 'Updated At' && v.text !== 'Alert' && v.text !== 'Status' && v.text !== 'WHStatus'
                        }
                    }
                })
            } else {
                defaultHeaders = defaultHeaders.filter(v => {
                    if (v.text === 'Order ID') {
                        v.width = this.currentTab === 1 ? '9%' : '12%'
                    }

                    if (v.text === 'Truck' || v.text === 'Reference' || v.text === 'ETA/Arrival Time') {
                        v.width = this.currentTab === 1 ? '18%' : '20%'
                    }

                    v.align = v.text === 'Status' ? 'start status-own-wh' : v.align

                    if (v.text === 'Status') {
                        v.width = this.currentTab === 1 ? '25%' : '20%'
                    }
                    if (v.text === '') 
                        v.width = this.currentTab === 1 ? '8%' : '10%'
                                        
                    return v.text !== 'Created At' && v.text !== 'Created By & At' && v.text !== 'Customer' && v.text !== 'Updated At' && v.text !== 'Alert' && v.text !== 'WHStatus'
                })
            }

            return defaultHeaders
        },
        tabsComputed() {
            if (this.isWarehouseConnected) {
                return this.tabsConnected
            } else {
                if (this.isWarehouse3PLProvider) {
                    return this.tabsForConnectedProvider
                } else {
                    return this.tabs
                }
            }
        },
        isShowEmployeeOption() {
            let show = false

            if (this.isWarehouseConnected || this.isWarehouse3PL) {
                show = false
            } else {
                if (this.isWarehouse3PLProvider) {
                    if (this.currentTab === 1 || this.currentTab === 2) {
                        show = true
                    } else {
                        show = false
                    }
                } else {
                    if (this.currentTab === 0 || this.currentTab === 1) {
                        show = true
                    } else {
                        show = false
                    }
                }
            }

            return show
        },
        isHasLocationAssigned() {
            let hasAssigned = false

            if (this.selectedInboundOrders.length > 0) {
                let findOneAssigned = _.find(this.selectedInboundOrders, e => e.location_id !== null)
                if (findOneAssigned !== undefined)
                    hasAssigned = true
                else 
                    hasAssigned = false                
            } else {
                hasAssigned = false
            }

            return hasAssigned
        }
    },
    methods: {
        ...mapActions({
            cancelInbound: 'inbound/cancelInbound',            
            fetchPendingInbounds: 'inbound/fetchPendingInbounds',
            fetchFloorInbounds: 'inbound/fetchFloorInbounds',
            fetchCompletedInbounds: 'inbound/fetchCompletedInbounds',
            fetchCancelledInbounds: 'inbound/fetchCancelledInbounds',
            setCurrentInboundTab: 'inbound/setCurrentInboundTab',
            printInboundOrder: 'inbound/printInboundOrder',
            setInboundSearchedVal: 'inbound/setInboundSearchedVal',
            setSearchedInboundLoading: 'inbound/setSearchedInboundLoading',
            fetchSearchedInbounds: 'inbound/fetchSearchedInbounds',
            setSingleInboundData: 'inbound/setSingleInboundData',
            fetchInboundProducts: 'inbound/fetchInboundProducts',
            setAllInboundProductsLists: 'inbound/setAllInboundProductsLists',
            fetchSuppliers: "suppliers/fetchSuppliers",
            setIsCreateInboundShow: 'inbound/setIsCreateInboundShow',
            fetchCategoriesDropdown: 'category/fetchCategoriesDropdown',
            setAllCategoryDropdownLists: 'productInventories/setAllCategoryDropdownLists',
            fetchWarehouseCustomerInboundProducts: 'inbound/fetchWarehouseCustomerInboundProducts',
            setProductEmptyData: 'productInventories/setProductEmptyData',
            fetchWarehouseCustomersDropdown: "warehouseCustomers/fetchWarehouseCustomersDropdown",
            setAllWarehouseCustomerLists: 'warehouseCustomers/setAllWarehouseCustomerLists',
            setSelectedPrevCustomerWarehouseID: 'inbound/setSelectedPrevCustomerWarehouseID',
            // filter and search 
            setInboundFilteredVal:'inbound/setInboundFilteredVal',
            setFilteredInboundLoading:'inbound/setFilteredInboundLoading',
            fetchFilterInboundsCustomers:'inbound/fetchFilterInboundsCustomers',
            completeInboundOrder: 'inbound/completeInboundOrder',
            fetchDraftInbounds: 'inbound/fetchDraftInbounds',
            fetchPendingReceivingInbounds: 'inbound/fetchPendingReceivingInbounds',
            fetchPendingReceivingInboundsForWHCustomer: 'inbound/fetchPendingReceivingInboundsForWHCustomer',
            deleteInbound: 'inbound/deleteInbound',
            submitInbound: 'inbound/submitInbound',
            acceptInbound: 'inbound/acceptInbound',
            rejectInbound: 'inbound/rejectInbound',
            fetchWarehouseEmployees: 'employees/fetchWarehouseEmployees',
            fetchLocationsAssign: 'employees/fetchLocationsAssign'
        }),
        ...globalMethods,
        ...inventoryGlobalMethods,
        onTabChange(i) {
            this.currentTab = i
            this.setInboundSearchedVal([])
            this.setInboundFilteredVal([])
            this.selectedWhCustomers = []
            this.selectedWhCustomersCopy = []
            this.selectedWhCustomerReAssignValue =[]
            this.search = ''
        },
        formatNewDate(date, time) {
            return this.formatDateWithTimeSeparated(date, time, 'time-first')
		},
        formatDateDefault(data) {
            // return this.formatDateWithTimeAbbr(data)
            return this.formatTimeAndDateTogether(data)
        },
        getTabCount(index) {
            let data = '0'

            if (!this.isWarehouseConnected) {
                if (index === 0) {
                    data = this.pendingInboundsDataLists.total
                }
            } else {
                if (index === 0) {
                    data = this.draftInboundsDataLists.total 
                } else if (index === 1) {
                    data = this.pendingInboundsDataLists.total
                } else if (index === 2) {
                    data = this.pendingReceiveInboundsDataLists.total
                }
            }
          
            let finalData = data !== 0 ? data : '0'
            return finalData
        },
        async fetchWarehouseEmployeesListsAPI() {
            let id = this.currentWarehouseSelected !== 'undefined' && this.currentWarehouseSelected !== null 
                ? this.currentWarehouseSelected.id : null
            await this.fetchWarehouseEmployees(id)
        },
        async onClickTab(i) {
            this.setInboundSearchedVal([])
            this.setInboundFilteredVal([])
            this.selectedWhCustomers = []
            this.selectedWhCustomersCopy = []
            this.selectedWhCustomerReAssignValue = []
            this.search = ''

            try {
                if (i === 0 && this.currentTab !== i) {
                    if (!this.isWarehouseConnected) {
                        this.callInboundAPIAccordingToStatus('pending', i)
                    } else {
                        this.callInboundAPIAccordingToStatus('draft', i)
                    }
                } else if (i === 1 && this.currentTab !== i) {
                    if (!this.isWarehouseConnected) {
                        if (this.isWarehouse3PLProvider) {
                            this.callInboundAPIAccordingToStatus('receive_pending', i)
                        } else {
                            this.callInboundAPIAccordingToStatus('floor', i)
                        }
                    } else {
                        this.callInboundAPIAccordingToStatus('pending', i)
                    }
                } else if (i === 2 && this.currentTab !== i) {
                    if (!this.isWarehouseConnected) {
                        if (this.isWarehouse3PLProvider) {
                            this.callInboundAPIAccordingToStatus('floor', i)
                        } else {
                            this.callInboundAPIAccordingToStatus('completed', i)
                        }
                    } else {
                        this.callInboundAPIAccordingToStatus('receive_pending_wh', i)
                    }
                } else if (i === 3 && this.currentTab !== i) {
                    if (!this.isWarehouseConnected) {
                        if (this.isWarehouse3PLProvider) {
                            this.callInboundAPIAccordingToStatus('completed', i)
                        } else {
                            this.callInboundAPIAccordingToStatus('cancelled', i)
                        }                        
                    } else {
                        this.callInboundAPIAccordingToStatus('completed', i)
                    }
                } else if (i === 4 && this.currentTab !== i) {
                    if (this.isWarehouseConnected || this.isWarehouse3PLProvider) {
                        this.callInboundAPIAccordingToStatus('cancelled', i)
                    }
                }
            } catch(e) {
                this.notificationError(e)
            }
        },
        getOvershippedInbound(inbound) {
            return this.isDataOvershipped(inbound)
        },
        async callInboundAPIAccordingToStatus(status, i) {
            let storeInboundTab = this.$store.state.inbound
            let dataWithPage = { id: this.currentWarehouseSelected.id, page: 1 } 

            try {
                if (this.source) this.source.cancel("cancel_previous_request")
                this.source = axios.CancelToken.source()
                dataWithPage.cancelToken = this.source.token

                if (status === 'pending') { // call pending API
                    dataWithPage.page = storeInboundTab.pendingInboundPagination.current_page
                    await this.fetchPendingInbounds(dataWithPage)
                    storeInboundTab.pendingInboundPagination.old_page = storeInboundTab.pendingInboundPagination.current_page
                    this.setCurrentInboundTab(i)
                    
                } else if (status === 'draft') { // call draft api here
                    dataWithPage.page = storeInboundTab.draftInboundPagination.current_page
                    await this.fetchDraftInbounds(dataWithPage)
                    storeInboundTab.draftInboundPagination.old_page = storeInboundTab.draftInboundPagination.current_page
                    this.setCurrentInboundTab(i)
                    
                } else if (status === 'floor') { // call floor api here
                    dataWithPage.page = storeInboundTab.floorInboundPagination.current_page
                    await this.fetchFloorInbounds(dataWithPage)
                    storeInboundTab.floorInboundPagination.old_page = storeInboundTab.floorInboundPagination.current_page
                    this.setCurrentInboundTab(i)
                    
                } else if (status === 'receive_pending') { // call pending receive api here
                    dataWithPage.page = storeInboundTab.pendingReceiveInboundPagination.current_page
                    await this.fetchPendingReceivingInbounds(dataWithPage)
                    storeInboundTab.pendingReceiveInboundPagination.old_page = storeInboundTab.pendingReceiveInboundPagination.current_page
                    this.setCurrentInboundTab(i)

                } else if (status === 'receive_pending_wh') { // call pending receive api here for wh customer
                    dataWithPage.page = storeInboundTab.pendingReceiveInboundPaginationWHCustomer.current_page
                    await this.fetchPendingReceivingInboundsForWHCustomer(dataWithPage)
                    storeInboundTab.pendingReceiveInboundPaginationWHCustomer.old_page = storeInboundTab.pendingReceiveInboundPaginationWHCustomer.current_page
                    this.setCurrentInboundTab(i)

                } else if (status === 'completed') { // call completed api here
                    dataWithPage.page = storeInboundTab.completedInboundPagination.current_page
                    await this.fetchCompletedInbounds(dataWithPage)
                    storeInboundTab.completedInboundPagination.old_page = storeInboundTab.completedInboundPagination.current_page
                    this.setCurrentInboundTab(i)

                } else { // call cancelled api here
                    dataWithPage.page = storeInboundTab.cancelledInboundPagination.current_page
                    await this.fetchCancelledInbounds(dataWithPage)
                    storeInboundTab.cancelledInboundPagination.old_page = storeInboundTab.cancelledInboundPagination.current_page
                    this.setCurrentInboundTab(i)
                }
            } catch(e) {
                if (e !== "cancel_previous_request") this.notificationError(e)
            }
        },
        // inbounds
        async addNewInbound() {
            this.dialogCreate = true
            this.$nextTick(() => {
                this.editedInboundItems = Object.assign({}, this.defaultInboundItems)
                this.editedInboundIndex = -1;
            })

            this.setIsCreateInboundShow(false)
            if (this.isWarehouse3PLProvider) {
                await this.callInboundProductsFor3PL('Provider')
            } else {
                await this.callInboundProductsFor3PL('Inbound')
            }
        },
        closeCreate() {
            this.dialogCreate = false
            this.$nextTick(() => {
                this.editedInboundItems = Object.assign({}, this.defaultInboundItems)
                this.editedInboundIndex = -1;
            })

            if (this.currentEditInboundData !== null) {
                this.closeWarning()
            }
            this.setIsCreateInboundShow(false)
        },
        viewInbound(e) {
            this.$router.push(`inventory/inbound-view?inboundid=${e.id}&warehouseid=${this.currentWarehouseSelected.id}`)
        },
        checkEditInbound(item, toDo) {
            if (!this.isWarehouse3PL) {
                this.editOrder(item, toDo)
            } else {
                if (this.currentTab === 2) {
                    this.showWarningEditInboundDialog = true
                    let data = { item, toDo }
                    this.currentEditInboundData = data
                } else {
                    this.editOrder(item, toDo)
                }
            }
        },
        confirmEditOrder() {
            if (this.currentEditInboundData !== null) {
                if (this.currentEditInboundData.item.is_from_inventory === 0) {
                    this.editOrder(this.currentEditInboundData.item, this.currentEditInboundData.toDo)
                } else {
                    this.editAddInventoryInbound(this.currentEditInboundData.item)
                }                
            }
        },
        closeWarning() {
            this.showWarningEditInboundDialog = false
            this.currentEditInboundData = null
        },
        editAddInventoryInbound(item) {
            this.editedProductIndex = 0
            let inventoryItems = { ...item }

            if (!inventoryItems.inbound_products || inventoryItems.inbound_products.length === 0) {
                let buildProduct = [
                    {
                        product_id: '',
                        quantity: '',
                        shipping_unit: '',
                        status: ''
                    }
                ]
                inventoryItems = { ...inventoryItems, inbound_products: buildProduct }
            } else {
                let buildProduct = inventoryItems.inbound_products.map(v => {
                    let { id, product_id, expected_carton_count, shipping_unit, expected_total_unit, actual_carton_count, actual_total_unit } = v

                    if (shipping_unit === 'carton') {
                        return { 
                            inbound_product_id: id,
                            product_id,
                            quantity: expected_carton_count !== null ? parseInt(expected_carton_count) : '',
                            shipping_unit,
                            status: v.status,
                            expected_carton_count: expected_carton_count !== null ? parseInt(expected_carton_count) : '',
                            actual_carton_count: actual_carton_count !== null ? parseInt(actual_carton_count) : ''
                        }
                    } else {
                        return { 
                            inbound_product_id: id,
                            product_id,
                            quantity: expected_total_unit !== null ? parseInt(expected_total_unit) : '',
                            shipping_unit,
                            status: v.status,
                            expected_total_unit: expected_total_unit !== null ? parseInt(expected_total_unit) : '',
                            actual_total_unit: actual_total_unit !== null ? parseInt(actual_total_unit) : ''
                        }
                    }
                })
                inventoryItems = { ...inventoryItems, inbound_products: buildProduct }
            }

            inventoryItems.inventory_as_of = item.estimated_date
            inventoryItems.notes = item.notes

            this.editedProductItems = Object.assign({}, inventoryItems)
            this.dialogEditFromInventory = true
            this.callInboundProductsFor3PL('Inbound')
        },
        closeEditInventory() {
            this.dialogEditFromInventory = false
            this.$nextTick(() => {
				this.editedProductItems = Object.assign({}, this.defaultProductItems)
				this.editedProductIndex = -1
            })

            if (this.currentEditInboundData !== null) {
                this.closeWarning()
            }
        },
        async editOrder(item, toDo) {
            this.editedInboundIndex = this.currentWarehouseInbounds.data.indexOf(item)
            let inboundItem = { ...item }

            if (!inboundItem.inbound_products || inboundItem.inbound_products.length === 0) {
                let buildProduct = [{
                    product_id: '',
                    quantity: '',
                    shipping_unit: '',
                    status: '',
                    instructions: ''
                }]
                inboundItem = { ...inboundItem, inbound_products: buildProduct }
            } else {
                if (inboundItem.inbound_products.length !== 0) {
                    let buildProduct = inboundItem.inbound_products.map(v => {
                        let { id, product_id, expected_carton_count, shipping_unit, expected_total_unit, actual_carton_count, actual_total_unit, instructions } = v

                        if (shipping_unit === 'carton') {
                            return { 
                                inbound_product_id: id,
                                product_id,
                                quantity: expected_carton_count !== null ? parseInt(expected_carton_count) : '',
                                shipping_unit,
                                status: v.status,
                                expected_carton_count: expected_carton_count !== null ? parseInt(expected_carton_count) : '',
                                actual_carton_count: actual_carton_count !== null ? parseInt(actual_carton_count) : '',
                                instructions
                            }
                        } else {
                            return { 
                                inbound_product_id: id,
                                product_id,
                                quantity: expected_total_unit !== null ? parseInt(expected_total_unit) : '',
                                shipping_unit,
                                status: v.status,
                                expected_total_unit: expected_total_unit !== null ? parseInt(expected_total_unit) : '',
                                actual_total_unit: actual_total_unit !== null ? parseInt(actual_total_unit) : '',
                                instructions
                            }
                        }
                    })
                    inboundItem = { ...inboundItem, inbound_products: buildProduct }
                }
            }

            if (!inboundItem.documents || inboundItem.documents == null) {
                inboundItem = { ...inboundItem, documents: [] }
            } else {
                let convertDocuments = inboundItem.documents.map(v => {
                    if (v.original_name !== 'undefined') {
                        return new File([v.original_name], `${v.original_name}`, {
                            type: v.type
                        });
                    }
                })
                inboundItem = { ...inboundItem, documents: convertDocuments }
            }

            if (!inboundItem.estimated_time || inboundItem.estimated_time == 'null') {
                inboundItem = { ...inboundItem, estimated_time: null }
            }

            if (!inboundItem.estimated_date || inboundItem.estimated_date == 'null') {
                inboundItem = { ...inboundItem, estimated_date: null }
            }

            if (!inboundItem.ref_no || inboundItem.ref_no == 'null') {
                inboundItem = { ...inboundItem, ref_no: null }
            }

            if (!inboundItem.shipping_from || inboundItem.shipping_from == 'null') {
                inboundItem = { ...inboundItem, shipping_from: null }
            }

            if (!inboundItem.name || inboundItem.name == 'null') {
                inboundItem = { ...inboundItem, name: null }
            }

            if (!inboundItem.notes || inboundItem.notes == 'null') {
                inboundItem = { ...inboundItem, notes: null }
            }

            inboundItem.isDuplicate = toDo == 'edit' ? false : true
            this.editedInboundIndex = inboundItem.isDuplicate ? -1 : this.editedInboundIndex
            // if is duplicate, set order id to empty
            inboundItem.order_id = inboundItem.isDuplicate ? '' : inboundItem.order_id 

            this.editedInboundItems = Object.assign({}, inboundItem)
            this.dialogCreate = true

            if (this.isWarehouse3PLProvider) {
                await this.callWarehouseCustomerProducts(inboundItem)
            } else {
                await this.callInboundProductsFor3PL('Inbound')
            }
        },     
        arrived(item) {
            this.dialogTruckArrived = true
            this.linkData = {
                inbound_id: item.id,
                warehouse_id: item.warehouse_id
            }
        },
        closeTruckArrivedDialog() {
            this.dialogTruckArrived = false
            this.truckArrivedData = { name: '', ref_no: '' }
            this.linkData = { inbound_id: '', warehouse_id: '' }
        },
        async printOrder(item) {
            if (item !== null) {
                let payload = {
                    warehouse_id: item.warehouse_id,
                    inbound_id: item.id,
                    order_id: item.order_id
                }

                try {
                    this.notificationCustom('Generating print order...')
                    await this.printInboundOrder(payload)
                } catch(e) {
                    this.notificationError(e)
                }
            }
        },
        createWordOrder() {},        
        openNewStorableUnit(item) {
            if (item !== 'undefined' && item.inbound_products !== 'undefined') {
                if (item.inbound_status === 'floor') {
                    let findIndex = _.findIndex(item.inbound_products, (e) => e.status !== 'recieved')

                    if (findIndex === -1) {
                        this.newStorableData = item
                        this.dialogNewStorableUnit = true
                        this.linkData = {
                            inbound_id: item.id,
                            warehouse_id: item.warehouse_id
                        }
                    } else {
                        this.notificationError('You can add New Storable Unit only if all Inbound Products have been received.')
                    }
                } else {
                    this.notificationError("Something's wrong. Please reload the page and try again.")
                }
            }
        },
        closeStorableUnit() {
            this.newStorableData = null
            this.dialogNewStorableUnit = false
        },
        reportToVendor(item) {
            console.log(item);
        },
        cancelOrder(inbound) {
            if (inbound !== null) {
                this.cancelOrderData = inbound
                this.dialogCancel = true
            }
        },
        async cancelConfirm() {
            if (this.cancelOrderData !== null) {
                try {
                    let payload = {
                        wid: this.cancelOrderData.warehouse_id,
                        oid: this.cancelOrderData.id,
                        page: 1
                    }

                    let storePagination = this.currentTab === 0 ? 
                        this.$store.state.inbound.pendingInboundPagination : 
                        (this.currentTab === 1 ? this.$store.state.inbound.floorInboundPagination : 1)

                    let page = typeof storePagination.current_page !== 'undefined' ? storePagination.current_page : 1
                    
                    if (storePagination.data.length === 1 && storePagination.current_page !== 1) {
                        page = page - 1
                    } 

                    payload.page = page

                    await this.cancelInbound(payload)
                    this.dialogCancel = false
                    this.notificationCustom('Inbound has been cancelled.')

                    let dataWithPage = { id: this.cancelOrderData.warehouse_id, page }

                    if (!this.isWarehouse3PLProvider) {
                        if (this.currentTab === 0) {
                            await this.fetchPendingInbounds(dataWithPage)
                        } else if (this.currentTab === 1) {
                            await this.fetchFloorInbounds(dataWithPage)
                        }
                    } else {
                        if (this.currentWarehouseSelected !== null && this.currentWarehouseSelected.is_own !== 'undefined' &&
                            this.currentWarehouseSelected.is_own === 0) {
                            await this.fetchPendingInbounds(dataWithPage)
                        } else {
                            await this.fetchPendingReceivingInbounds(dataWithPage)
                        }
                    }

                    let cancelledData = { id: this.cancelOrderData.warehouse_id, page: 1 }
                    await this.fetchCancelledInbounds(cancelledData)

                    this.cancelOrderData = null
                    this.closeCancel()
                } catch(e) {
                    this.notificationError(e)
                }
            }
        },
        closeCancel() {
            this.dialogCancel = false
        },
        // paginations
        async handlePageChange(page) {
            this.handleScrollToTop()
            if (page !== null) {
                let storeInboundTab = this.$store.state.inbound
                let dataWithPage = { id: this.currentWarehouseSelected.id, page }

                if (this.search == '' && this.selectedWhCustomers.length === 0) {
                    try {
                        if (storeInboundTab.setCurrentTab === 0) {
                            if (storeInboundTab.pendingInboundPagination.old_page !== page) {
                                this.pendingInboundLoadingPage = true
                                await this.fetchPendingInbounds(dataWithPage)
                                storeInboundTab.pendingInboundPagination.old_page = page
                                this.pendingInboundLoadingPage = false
                            }
                        } else if (storeInboundTab.setCurrentTab === 1) {
                            if (storeInboundTab.floorInboundPagination.old_page !== page) {
                                this.floorInboundLoadingPage = true
                                await this.fetchFloorInbounds(dataWithPage)
                                storeInboundTab.floorInboundPagination.old_page = page
                                this.floorInboundLoadingPage = false
                            }
                        } else if (storeInboundTab.setCurrentTab === 2) {
                            if (storeInboundTab.completedInboundPagination.old_page !== page) {
                                this.completedInboundLoadingPage = true
                                await this.fetchCompletedInbounds(dataWithPage)
                                storeInboundTab.completedInboundPagination.old_page = page
                                this.completedInboundLoadingPage = false
                            }
                        } else {
                            if (storeInboundTab.cancelledInboundPagination.old_page !== page) {
                                this.cancelledInboundLoadingPage = true
                                await this.fetchCancelledInbounds(dataWithPage)
                                storeInboundTab.cancelledInboundPagination.old_page = page
                                this.cancelledInboundLoadingPage = false
                            }
                        }
                    } catch (e) {
                        this.notificationError(e)
                    }
                } else if (this.search !=='' && this.selectedWhCustomers.length === 0) {
                    let data = { search: this.search, page }
                    this.handlePageSearched(data)
                } else {
                    if (this.search !== '' && this.selectedWhCustomers.length > 0) {
						let	data = {
							filter_data: this.selectedWhCustomers.map(val => val.id),
							search_data: this.search,
							wid: dataWithPage.id,
							page,
                            isPagination: true
						}
						
						// this.handleFilterWithSearchFunction(data)
                        this.apiCallFilterAndSearchCustomer(data)
					} else {
						if (this.search === '' && this.selectedWhCustomers.length > 0) {
							let	data = {
                                filter_data: this.selectedWhCustomers.map(val => val.id),
                                search_data: this.search,
                                wid: dataWithPage.id,
                                page,
                                isPagination: true
                            }
						    // this.handleFilterOnlyFunction(data)
                            this.apiCallFilteredCustomerOnly(data)
						}
					}
                }
            }
        },
        async handlePageSearched(data) {
            this.handleScrollToTop()
            let searchedPagination = this.$store.state.inbound.searchedInbounds
            if (data !== null && this.search !== '') {
                if (searchedPagination.old_page !== data.page) {
                    let passedData = {
                        method: "get",
                        url: '',
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: { search: this.search, page: data.page }
                    }
                    
                    this.callAndFetchInboundAPIs(passedData, false)
                    searchedPagination.old_page = data.page
                }                
            } else {
                this.setInboundSearchedVal([])
            }
        },
        clearSearched() {
            this.search = ''
            this.setInboundSearchedVal([])
            if(this.isWarehouse3PLProvider) {
				if(this.selectedWhCustomers.length > 0) {
					this.setInboundFilteredVal([])
					this.filterAllWarehouseCustomers()
				}
			}
        },
        // for searching call api
        handleSearch() {
            if (cancel !== undefined) {
                cancel("cancel_previous_request")
            }
            this.setSearchedInboundLoading(false)
            clearTimeout(this.typingTimeout)
            this.typingTimeout = setTimeout(() => {
                let data = {  search: this.search }
                if (this.selectedWhCustomers.length > 0) {
                    return this.filterAllWarehouseCustomers()
                } else {
                    this.setSearchedInboundLoading(true)
                    this.apiCall(data)
                }
            }, 500)
        },
        async callAndFetchInboundAPIs(passedData, isFilter) {
            let storePagination = this.$store.state.inbound.setCurrentTab
            let warehouse_id = this.currentWarehouseSelected.id
            passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/inbound/common`

            if (!this.isWarehouseConnected) { // id warehouse is not a connected 3pl
                if (storePagination === 0) {
                    passedData.tab = 'pending'
                    passedData.params.status = ['pending']

                    if (this.isWarehouseOwn) passedData.params.status.push('arrived')
                } else if (storePagination === 1) {
                    passedData.tab = 'floor'
                    passedData.params.status = ['floor']

                    if (this.isWarehouse3PLProvider) {
                        passedData.tab = 'receive_pending'
                        passedData.params.status = ['receive-pending', 'arrived']
                    }
                } else if (storePagination === 2) {
                    passedData.tab = 'completed'
                    passedData.params.status = ['completed']

                    if (this.isWarehouse3PLProvider) {
                        passedData.tab = 'floor'
                        passedData.params.status = ['floor']
                    }
                } else if (storePagination === 3) {
                    passedData.tab = 'cancelled'
                    passedData.params.status = ['cancelled']

                    if (this.isWarehouse3PLProvider) {
                        passedData.tab = 'completed'
                        passedData.params.status = ['completed']
                    }
                } else {
                    passedData.tab = 'cancelled'
                    passedData.params.status = ['cancelled', 'rejected']
                }
            } else { // if warehouse is connected 3pl
                if (storePagination === 0) {
                    passedData.tab = 'draft'
                    passedData.params.status = ['draft']
                } else if (storePagination === 1) {
                    passedData.tab = 'pending'
                    passedData.params.status = ['pending']
                } else if (storePagination === 2) {
                    passedData.tab = 'receive_pending_wh'
                    passedData.params.status =  ['receive-pending', 'arrived', 'floor']
                } else if (storePagination === 3) {
                    passedData.tab = 'completed'
                    passedData.params.status = ['completed']
                } else {
                    passedData.tab = 'cancelled'
                    passedData.params.status = ['cancelled', 'rejected']
                }
            }

            if (!isFilter) {
                if (passedData.url !== '') {
                    try {
                        this.fetchSearchedInbounds(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setSearchedInboundLoading(false)
                        console.log(e, 'Search error')
                    }
                }
            } else {
                for (var ser = 0 ; ser < passedData.params.status.length; ser++) {
					passedData.params.append(`status[]`, passedData.params.status[ser])
				}
                if (passedData.url !== '') {
                    try {
                        await this.fetchFilterInboundsCustomers(passedData)                       
                    } catch(e) {
                        this.notificationError(e)
                        this.setFilteredInboundLoading(false)
                        console.log(e, 'filter only error')
                    }
                }
            }
        },
        apiCall(data) {
            if (data !== null && this.search !== '') {
                let passedData = {
                    method: "get",
                    url: '',
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: { search: this.search, page: 1 }
                }

                this.callAndFetchInboundAPIs(passedData, false)
            } else {
                this.setInboundSearchedVal([])
            }
        },
        async callInboundProductsFor3PL(from) {
            if (from === 'Provider') {                
                this.setAllInboundProductsLists([])
            }

            try {
                if (this.getAllInboundProductsListsDropdownData.length === 0) {
                    this.current_page_is = 1
                    await this.fetchInboundProducts(1)
                    
                    if (typeof this.getAllInboundProductsLists !== 'undefined' && 
                        this.getAllInboundProductsLists !== null && 
                        typeof this.getAllInboundProductsLists.products !== 'undefined' && 
                        Array.isArray(this.getAllInboundProductsLists.products) &&
                        this.getAllInboundProductsLists.products.length > 0) {                            
                        this.productsListsData = this.getAllInboundProductsLists.products.map(value => {
                            return {
                                product_id: value.id,
                                id: value.id,
                                name: value.name,
                                sku: value.sku,
                                image: value.image,
                                description: value.description,
                                category_id: value.category_id,
                                units_per_carton: value.units_per_carton,
                                category_sku: value.category_sku
                            }
                        })

                        this.lastDataCheck = this.productsListsData
                        if (this.current_page_is < this.getAllInboundProductsLists.last_page) {
                            this.loadMoreProducts()
                        }                        
                        this.setAllInboundProductsLists(this.productsListsData)
                        this.fetchProductLoading = false
                    } else {
                        this.fetchProductLoading = false
                        this.productsListsData = []
                        this.lastDataCheck = []
                    }
                } else {
                    if (from === 'Inbound') {
                        this.productsListsData = this.getAllInboundProductsListsDropdownData
                        this.fetchProductLoading = false
                    } else {
                        let last_page = this.getAllInboundProductsLists.last_page

                        if (typeof last_page !== 'undefined') {
                            this.current_page_is = last_page
                            await this.fetchInboundProducts(last_page)

                            if (typeof this.getAllInboundProductsLists !== 'undefined' && 
                                this.getAllInboundProductsLists !== null && 
                                typeof this.getAllInboundProductsLists.products !== 'undefined' && 
                                Array.isArray(this.getAllInboundProductsLists.products) &&
                                this.getAllInboundProductsLists.products.length > 0) {
                                    
                                let newloaddata = this.getAllInboundProductsLists.products.map(value => {
                                    return {
                                        product_id: value.id,
                                        id: value.id,
                                        name: value.name,
                                        sku: value.sku,
                                        image: value.image,
                                        description: value.description,
                                        category_id: value.category_id,
                                        units_per_carton: value.units_per_carton,
                                        category_sku: value.category_sku
                                    }
                                })

                                this.productsListsData = [...this.productsListsData, ...newloaddata]
                                if (this.current_page_is < this.getAllInboundProductsLists.last_page) {
                                    this.loadMoreProducts()
                                }                                
                                this.setAllInboundProductsLists(this.productsListsData)
                                this.fetchProductLoading = false
                            } else {
                                this.fetchProductLoading = false
                                this.productsListsData = []
                                this.lastDataCheckProducts = []
                            }
                        }
                    }                    
                }
            } catch(e) {
                this.notificationError(e)
            }
        },
        async loadMoreProducts() {
            if (this.current_page_is < this.getAllInboundProductsLists.last_page) {
				this.current_page_is++

				try {
					await this.fetchInboundProducts(this.current_page_is)

                    if (typeof this.getAllInboundProductsLists !== 'undefined' && this.getAllInboundProductsLists !== null && 
                        typeof this.getAllInboundProductsLists.products !== 'undefined' && Array.isArray(this.getAllInboundProductsLists.products) &&
                        this.getAllInboundProductsLists.products.length > 0) {

                        let newloaddata = this.getAllInboundProductsLists.products.map(value => {
                            return {
                                product_id: value.id,
                                id: value.id,
                                name: value.name,
                                sku: value.sku,
                                image: value.image,
                                description: value.description,
                                category_id: value.category_id,
                                units_per_carton: value.units_per_carton,
                                category_sku: value.category_sku
                            }
                        })

                        this.productsListsData = [...this.productsListsData, ...newloaddata]
                        if (this.current_page_is < this.getAllInboundProductsLists.last_page) {
                            this.loadMoreProducts()
                        }
                        this.setAllInboundProductsLists(this.productsListsData)
                    } else {
                        this.productsListsData;
                    }
				} catch (e) {
					this.notificationError(e)
				}
			}
        },
        handleScrollToTop() {
            this.scrollTableToTop()
        },
        canStillEditInbound(item) {
            if (item !== null) {
                if (!this.isWarehouse3PL) {
                    if (this.isWarehouse3PLProvider) {
                        if (this.currentTab === 0 || ((this.currentTab === 1 || this.currentTab === 2) && item.is_from_connected_3pl !== 1)) {
                            return true
                        } else {
                            return false
                        }
                    } else {
                        if (this.currentTab === 0 || this.currentTab === 1) {
                            return true
                        } else {
                            return false
                        } 
                    }
                } else {
                    if (this.currentTab === 3) {
                        return false
                    } else {
                        return true
                    }
                }
            }
        },
        receiveAllProductsInbound(item) {
            this.linkData = { inbound_id: item.id, warehouse_id: item.warehouse_id }
            this.selectedInboundStatus = item.inbound_status
            this.isSelectedUndershipped = item.is_undershipped
            
            if (item.inbound_products.length !== 'undefined' && item.inbound_products.length > 0) {
                item.inbound_products.map(prod => {
                    let { id, product_id, product, expected_carton_count, expected_total_unit, in_each_carton, shipping_unit, actual_carton_count, actual_total_unit, storable_units, remaining_carton_count, remaining_total_unit, notes } = prod

                    let findProduct = _.findIndex(item.inbound_products, e => (id === e.id && e.status !== 'recieved'))
                    if (findProduct !== -1) {
                        this.productSelectedToReceive.push({
                            id,
                            product_id,
                            name: product.name,
                            carton_count: shipping_unit === 'single_item' ? null : parseInt(expected_carton_count),
                            total_unit: expected_total_unit,
                            in_each_carton: in_each_carton,
                            product_sku: product.sku,
                            shipping_unit,
                            expected_carton_count,
                            expected_total_unit,
                            actual_carton_count,
                            actual_total_unit,
                            storable_units,
                            remaining_carton_count,
                            remaining_total_unit,
                            notes: notes !== null ? notes : '',
                            noteError: false
                        })
                    }                    
                })
            }
            
            this.dialogReceiveProduct = true
        },
        findFloorReceivedProducts(item) {
            // if (this.currentTab === 1) { }
            if (typeof item !== 'undefined' && item !== null) {
                if (typeof item.inbound_products !== 'undefined' && item.inbound_products !== undefined) {
                    if (item.inbound_status === 'floor') {
                        let findIndex = _.findIndex(item.inbound_products, (e) => e.status !== 'recieved')

                        if (findIndex === -1) {
                            return true
                        } else {
                            return false
                        }
                    } else {
                        return false
                    }
                }
            }
        },
        isHasStorableUnits(item) {
            let hasStorable = false

            // if (this.currentTab === 1) { }
            if (typeof item !== 'undefined' && item !== null) {
                if (item.inbound_status === 'floor') {
                    if (typeof item.no_of_storable_units !== 'undefined' && item.no_of_storable_units !== undefined) {
                        if (item.no_of_storable_units > 0) {
                            hasStorable = true
                        }
                    }
                }
            }

            return hasStorable
        },
        async closeProductReceive() {
            this.dialogReceiveProduct = false
            this.productSelectedToReceive = []
        },
        clearSelection() {
            this.productSelectedToReceive = []
        },
        isShowCancelButton(item) {
            let show = false
            if (!this.isWarehouse3PL) {
                if (!this.isWarehouse3PLProvider) {
                    if (item.inbound_status === 'pending' || item.inbound_status === 'arrived') {
                        show = true
                    } else {
                        show = false
                    }
                } else {
                    if ((item.inbound_status === 'receive-pending' || item.inbound_status === 'arrived') && 
                        item.is_from_connected_3pl !== 1) {
                        show = true
                    }
                }
            } else {
                if (item.inbound_status === 'pending') {
                    let productsLists = item.inbound_products
                    if (typeof productsLists !== 'undefined' && productsLists.length > 0) {
                        let findProduct = _.findIndex(productsLists, e => (e.status === 'recieved'))

                        if (findProduct > -1) {
                            show = false
                        } else {
                            show = true
                        }
                    }
                }
            }            
            return show
        },
        openAddProductDialog() {
            this.dialogEditProduct = true
            this.editedIndexProductCreate = -1
            this.callCategories()
        },
        closeProductDialogCreate() {
            this.$nextTick(() => {
				this.editedProductItemCreate = Object.assign({}, this.defaultProductItemCreate)
				this.editedIndexProductCreate = 0				
			})
			this.dialogEditProduct = false
        },
        setToDefault() {
            this.$nextTick(() => {
				this.editedProductItemCreate = Object.assign({}, this.defaultProductItemCreate)
				this.editedIndexProductCreate = -1
			})
            this.dialogEditProduct = true
        },
        async callCategories() {
            if (this.getAllCategoryDropdownLists.length === 0) {
                await this.fetchCategoriesDropdown(1)
                if (typeof this.getCategoriesDropdown !== 'undefined' && this.getCategoriesDropdown !== null && 
                    typeof this.getCategoriesDropdown.categories !== 'undefined' &&
                    Array.isArray(this.getCategoriesDropdown.categories) &&
                    this.getCategoriesDropdown.categories.length > 0) {

                    this.categoryListData = this.getCategoriesDropdown.categories.map((value) => {
                        let nameWithId = value.name + ' (' + value.id + ')'                        
                        return {
                            id: value.id,
                            name: value.name,
                            nameWithId
                        }
                    })

                    this.lastDataCheck = this.categoryListData                                    
                    if (this.current_page_is_category < this.getCategoriesDropdown.last_page) {
                        this.loadMoreCategories()
                    }
                    this.setAllCategoryDropdownLists(this.categoryListData)
                } else {
                    this.categoryListData = []
                    this.lastDataCheck = []
                }
            } else {
                this.categoryListData = this.getAllCategoryDropdownLists
                this.setAllCategoryDropdownLists(this.categoryListData)
            }
        },
        async loadMoreCategories() {
			if (this.current_page_is_category < this.getCategoriesDropdown.last_page) {
				this.current_page_is_category++

				try {
					await this.fetchCategoriesDropdown(this.current_page_is_category)
					if (typeof this.getCategoriesDropdown !== 'undefined' && this.getCategoriesDropdown !== null && 
						typeof this.getCategoriesDropdown.categories !== 'undefined' && 
						Array.isArray(this.getCategoriesDropdown.categories) && 
						this.getCategoriesDropdown.categories.length > 0) {

						let newloaddata = this.getCategoriesDropdown.categories.map((value) => {
							let nameWithId = value.name + ' (' + value.id + ')'
							return {
								id: value.id,
								name: value.name,
								nameWithId
							}
						})

						this.categoryListData = [...this.categoryListData, ...newloaddata]
						if (this.current_page_is_category < this.getCategoriesDropdown.last_page) {
							this.loadMoreCategories()
						}
                        this.setAllCategoryDropdownLists(this.categoryListData)
					} else {
						this.categoryListData;
                        this.setAllCategoryDropdownLists(this.categoryListData)
					}
				} catch (e) {
					this.notificationError(e)
				}
			}
        },
        // call warehouse customer products
        async callWarehouseCustomerProducts(val) {
            this.productsListsData = []
            this.lastDataCheck = []

            if (val.warehouse_customer_id !== null) {
                try {                    
                    this.fetchProductLoading = true
                    let warehouse_customer_id = val.warehouse_customer_id
                    this.current_page_is_warehouse_products = 1                    
                    let default_customer_id = (typeof this.getUser == 'string') ? 
                        JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id

                    let is_connected_warehouse_id = null
                    let customer_id = default_customer_id

                    if (typeof val.warehouse_customer !== 'undefined') {
                        is_connected_warehouse_id = val.warehouse_customer.warehouse_customer_company_id
                    }

                    if (typeof val.is_from_connected_3pl !== 'undefined' && val.is_from_connected_3pl === 1) {
                        customer_id = is_connected_warehouse_id
                    }                    

                    let payload = { customer_id, warehouse_customer_id, page: this.current_page_is_warehouse_products }
                    await this.fetchWarehouseCustomerInboundProducts(payload)
                    this.setSelectedPrevCustomerWarehouseID(warehouse_customer_id)
                    
                    if (typeof this.getAllWarehouseCustomerInboundProductsLists !== 'undefined' && 
                        this.getAllWarehouseCustomerInboundProductsLists !== null && 
                        typeof this.getAllWarehouseCustomerInboundProductsLists.products !== 'undefined' && 
                        Array.isArray(this.getAllWarehouseCustomerInboundProductsLists.products) &&
                        this.getAllWarehouseCustomerInboundProductsLists.products.length > 0) {
                            
                        this.productsListsData = this.getAllWarehouseCustomerInboundProductsLists.products.map(value => {
                            return {
                                product_id: value.id,
                                id: value.id,
                                name: value.name,
                                sku: value.sku,
                                image: value.image,
                                description: value.description,
                                category_id: value.category_id,
                                units_per_carton: value.units_per_carton,
                                category_sku: value.category_sku
                            }
                        })

                        this.lastDataCheck = this.productsListsData
                        if (this.current_page_is_warehouse_products < this.getAllWarehouseCustomerInboundProductsLists.last_page) {
                            this.loadMoreWarehouseCustomerProducts(val)
                        }                        
                        this.setAllInboundProductsLists(this.productsListsData)
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
                await this.callInboundProductsFor3PL('Provider')
            }
        },
        async loadMoreWarehouseCustomerProducts(val) {
            if (this.current_page_is_warehouse_products < this.getAllWarehouseCustomerInboundProductsLists.last_page) {
				this.current_page_is_warehouse_products++

                let customer_id = (typeof this.getUser=='string') ? 
                    JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id
                let warehouse_customer_id = val.warehouse_customer_id

                let payload = { customer_id, warehouse_customer_id, page: this.current_page_is_warehouse_products }
				try {
					await this.fetchWarehouseCustomerInboundProducts(payload)
                    if (typeof this.getAllWarehouseCustomerInboundProductsLists !== 'undefined' && 
                        this.getAllWarehouseCustomerInboundProductsLists !== null && 
                        typeof this.getAllWarehouseCustomerInboundProductsLists.products !== 'undefined' && 
                        Array.isArray(this.getAllWarehouseCustomerInboundProductsLists.products) &&
                        this.getAllWarehouseCustomerInboundProductsLists.products.length > 0) {
                            
                        let newloaddata = this.getAllWarehouseCustomerInboundProductsLists.products.map(value => {
                            return {
                                product_id: value.id,
                                id: value.id,
                                name: value.name,
                                sku: value.sku,
                                image: value.image,
                                description: value.description,
                                category_id: value.category_id,
                                units_per_carton: value.units_per_carton,
                                category_sku: value.category_sku
                            }
                        })

                        this.productsListsData = [...this.productsListsData, ...newloaddata]
                        if (this.current_page_is_warehouse_products < this.getAllWarehouseCustomerInboundProductsLists.last_page) {
                            this.loadMoreWarehouseCustomerProducts()
                        }
                        this.setAllInboundProductsLists(this.productsListsData)
                    } else {
                        this.productsListsData;
                    }
				} catch (e) {
					this.notificationError(e)
				}
			}
        },
        // call warehouse customers lists
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
					await this.fetchWarehouseCustomersDropdown(parmsWarehouseCustomers)

					if (typeof this.getWarehouseCustomersDropdown !== "undefined" && 
						this.getWarehouseCustomersDropdown !== null) {							
						if (typeof this.getWarehouseCustomersDropdown.data !== "undefined" &&
							this.getWarehouseCustomersDropdown.data.length !== "undefined") {
							let data = this.getWarehouseCustomersDropdown.data;
							this.warehouseCustomerListsData = data
							this.warehouseCustomerLists = data
							this.warehouseCustomerListsCopy = data

							if (this.current_page_is_whcustomers < this.getWarehouseCustomersDropdown.last_page) {
								this.loadMoreWarehouseCustomerData(parmsWarehouseCustomers)
							}							
							this.setAllWarehouseCustomerLists(this.warehouseCustomerListsData)
                            this.fetchWarehouseCustomersLoading = false
						}
					} else {
                        this.fetchWarehouseCustomersLoading = false
						this.warehouseCustomerListsData = []
						this.warehouseCustomerLists = []
						this.warehouseCustomerListsCopy = []
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
							this.warehouseCustomerListsData = [...this.warehouseCustomerListsData, ...data]
							this.warehouseCustomerLists =[...this.warehouseCustomerListsData, ...data]
							this.warehouseCustomerListsCopy =[...this.warehouseCustomerListsData, ...data]

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
        removeCustomerListsEmptyOnChange() {
            if (this.selectedWhCustomersCopy.length === 0) {
				this.setInboundFilteredVal([])
                this.selectedWhCustomersCopy = []
                this.selectedWhCustomers = []
                this.selectedWhCustomerReAssignValue = []
			}
            // this.warehouseCustomerListsCopy = this.warehouseCustomerLists
		},
        removeCustomerLists(item, removeIs) {
            if (removeIs === 'single') {
                // this.selectedWhCustomersCopy = this.selectedWhCustomersCopy.filter(val => val.id !== item.id)
                // let index = this.selectedWhCustomersCopy.indexOf(item)
                let indexLodash = _.findIndex(this.selectedWhCustomersCopy, e => (e.id == item.id))	
                
                if (indexLodash > -1){
					this.selectedWhCustomersCopy.splice(indexLodash, 1)
				}
            } else {
                this.selectedWhCustomersCopy = []
                this.selectedWhCustomerReAssignValue = []
                this.setInboundFilteredVal([])

                if (this.search !== '') {
                    setTimeout(() => {
                        this.filterMenu = false
                        let data = { search: this.search } 
                        this.setSearchedInboundLoading(true)
                        this.apiCall(data)  
                    }, 200);
                }
            }
            // this.selectedWhCustomers = this.selectedWhCustomersCopy
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
        cancelSelectingWarehouseCustomers() {
            if (this.selectedWhCustomers.length === 0) {
                this.selectedWhCustomersCopy = []
                this.selectedWhCustomerReAssignValue = []
                this.setInboundFilteredVal([])
            } else {
                // if (this.selectedWhCustomers !== this.selectedWhCustomersCopy) {
                //     this.selectedWhCustomersCopy = this.selectedWhCustomers
                // }
                this.selectedWhCustomersCopy =this.selectedWhCustomerReAssignValue.map(({dummy_value_Add_For_same_refrence,...rest}) => ({...rest}))
                this.selectedWhCustomers = this.selectedWhCustomersCopy
            }
            this.searchCustomerData = ''
            this.warehouseCustomerLists = this.warehouseCustomerListsCopy
            this.filterMenu = false
            this.isActiveClicked = false
        },
        /* eslint-enable */
        filterAllWarehouseCustomers(isFromDialog) {
            this.setFilteredInboundLoading(false)
            let data = { page: 1, isPagination: false }

            if (isFromDialog !== undefined && isFromDialog) {
                data.page = this.$store.state.inbound.filteredInbounds.current_page
            }

            if (this.selectedWhCustomersCopy.length > 0 && this.search === '') {
                this.selectedWhCustomers = this.selectedWhCustomersCopy
                this.apiCallFilteredCustomerOnly(data)
            } else if (this.selectedWhCustomersCopy.length > 0 && this.search !== '') {
                this.selectedWhCustomers = this.selectedWhCustomersCopy
                this.apiCallFilterAndSearchCustomer(data)
            }
            this.setInboundFilteredVal([])
            this.searchCustomerData = ''
            this.warehouseCustomerLists = this.warehouseCustomerListsData
            this.filterMenu = false
            this.isActiveClicked = false
        },
        async apiCallFilteredCustomerOnly(data) {
            let final_selectedWhCustomers = this.selectedWhCustomers.map(val => val.id)
            let searchedPagination = this.$store.state.inbound.filteredInbounds

            if (this.selectedWhCustomers.length > 0 && final_selectedWhCustomers.length > 0) {
                var searchParams = new URLSearchParams()

                searchParams.append('page', data.page)
                searchParams.append('filter', true)

                for (var ser = 0 ; ser < final_selectedWhCustomers.length; ser++) {
					searchParams.append(`ids[]`, final_selectedWhCustomers[ser])
				}
                
                let passedData = {
                    method: "get",
                    url: '',
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: searchParams
                }    
                
                if (data.isPagination) {
                    if (searchedPagination.old_page !== data.page) {                        
                        this.setFilteredInboundLoading(true)
                        this.callAndFetchInboundAPIs(passedData, true)
                        searchedPagination.old_page = data.page
                    }
                } else {                    
                    this.setFilteredInboundLoading(true)
                    this.callAndFetchInboundAPIs(passedData, true)
                }
            } else {
                this.setInboundFilteredVal([])
            }
        },
        async apiCallFilterAndSearchCustomer(data) {
            let final_selectedWhCustomers = this.selectedWhCustomers.map(val => val.id)
            let searchedPagination = this.$store.state.inbound.filteredInbounds

            if(this.selectedWhCustomers.length > 0 && final_selectedWhCustomers.length > 0 && this.search !== '') {
                var searchParams = new URLSearchParams()
                searchParams.append('page', data.page)
                searchParams.append('filter', true)
                searchParams.append('search', this.search)

                for (var ser = 0 ;ser < final_selectedWhCustomers.length; ser++) {
					searchParams.append(`ids[]`, final_selectedWhCustomers[ser])
				}                

                let passedData = {
                    method: "get",
                    url: '',
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: searchParams
                }
                
                if (data.isPagination) {
                    if (searchedPagination.old_page !== data.page) {                        
                        this.setFilteredInboundLoading(true)
                        this.callAndFetchInboundAPIs(passedData, true)
                        searchedPagination.old_page = data.page
                    }
                } else {                    
                    this.setFilteredInboundLoading(true)
                    this.callAndFetchInboundAPIs(passedData, true)
                }
            } else {
                this.setInboundFilteredVal([])
            }
        },
        onClickFilter() {
            if (!this.filterMenu) {
                this.filterMenu = true
                var deepCopy = _.cloneDeep(this.selectedWhCustomersCopy);
                this.selectedWhCustomerReAssignValue = deepCopy
                this.selectedWhCustomerReAssignValue = this.selectedWhCustomerReAssignValue.map(v => ({ ...v, dummy_value_Add_For_same_refrence: v.name }))
            }
        },
        clickOutsideFilter() {
            if (this.isActiveClicked) {
                this.filterMenu = false
                this.cancelSelectingWarehouseCustomers()
                this.isActiveClicked = false
            }
        },
        completeOrder(item) {
            this.dialogConfirm = true
            this.toCompleteOrderData = item
        },
        async confirmCompleted() {
            if (typeof this.toCompleteOrderData !== 'undefined' && this.toCompleteOrderData !== null) {
                if (typeof this.toCompleteOrderData.id !== 'undefined' && this.toCompleteOrderData.warehouse_id !== null) {
                    let payload = {
                        id: this.toCompleteOrderData.warehouse_id,
                        inbound_id: this.toCompleteOrderData.id
                    }

                    await this.completeInboundOrder(payload)
                    this.notificationMessage('Inbound Order has been completed.')
                    this.dialogConfirm = false

                    let dataWithPage = { id: this.toCompleteOrderData.warehouse_id, page: 1 }
                    await this.fetchFloorInbounds(dataWithPage)
                    await this.fetchCompletedInbounds(dataWithPage)
                    this.closeCompletedDialog()
                }
            }
        },
        closeCompletedDialog() {
            this.dialogConfirm = false
            this.toCompleteOrderData = null    
        },
        // connected 3pl warehouse
        async deleteOrder(inbound, isConfirm) {
            if (!isConfirm) {
                this.deleteInboundDialog = true
                this.currentSelectedInboundOrder = inbound
            }

            if (isConfirm) {
                let draftInboundPagination = this.$store.state.inbound.draftInboundPagination

                if (this.currentSelectedInboundOrder !== null) {
                    let payload = {
                        wid: this.currentSelectedInboundOrder.warehouse_id,
                        iid: this.currentSelectedInboundOrder.id
                    }

                    try {
                        let current_page = draftInboundPagination.current_page                    
                        let dataWithPage = {
                            id: this.currentSelectedInboundOrder.warehouse_id,
                            page: current_page
                        }

                        if (draftInboundPagination.data.length === 1 && current_page !== 1) {
                            dataWithPage.page = current_page - 1
                        }

                        await this.deleteInbound(payload)
                        this.notificationCustom('Inbound has been deleted.')
                        this.deleteInboundDialog = false
                        await this.fetchDraftInbounds(dataWithPage)
                        this.cancelDelete()
                    } catch (e) {
                        console.log(e);
                        this.notificationError(e)
                    }
                }
            }
        },
        cancelDelete() {
            this.deleteInboundDialog = false
            this.currentSelectedInboundOrder = null
        },
        async submitOrder(item, isConfirm) {
            if (!isConfirm) {
                this.submitInboundDialog = true
                this.currentSelectedInboundOrder = item
            }

            if (isConfirm) {
                let draftInboundPagination = this.$store.state.inbound.draftInboundPagination

                if (this.currentSelectedInboundOrder !== null) {
                    try {
                        let current_page = draftInboundPagination.current_page                    
                        let dataWithPage = {
                            id: this.currentSelectedInboundOrder.warehouse_id,
                            page: current_page
                        }

                        if (draftInboundPagination.data.length === 1 && current_page !== 1) {
                            dataWithPage.page = current_page - 1
                        }

                        await this.submitInbound(this.currentSelectedInboundOrder.id)
                        this.notificationMessage('Inbound has been submitted.')
                        this.submitInboundDialog = false
                        await this.fetchDraftInbounds(dataWithPage)                        

                        dataWithPage.page = 1
                        await this.fetchPendingInbounds(dataWithPage)
                        this.cancelSubmit()
                    } catch (e) {
                        console.log(e);
                        this.notificationError(e)
                    }
                }
            }
        },
        cancelSubmit() {
            this.submitInboundDialog = false
            this.currentSelectedInboundOrder = null
        },
        async acceptOrder(item, isConfirm) {
            if (!isConfirm) {
                this.acceptInboundDialog = true
                this.currentSelectedInboundOrder = item
            }

            if (isConfirm) {
                let pendingInboundPagination = this.$store.state.inbound.pendingInboundPagination

                if (this.currentSelectedInboundOrder !== null) {
                    try {
                        let current_page = pendingInboundPagination.current_page                    
                        let dataWithPage = {
                            id: this.currentSelectedInboundOrder.warehouse_id,
                            page: current_page
                        }

                        if (pendingInboundPagination.data.length === 1 && current_page !== 1) {
                            dataWithPage.page = current_page - 1
                        }

                        await this.acceptInbound(this.currentSelectedInboundOrder.id)
                        this.notificationMessage('Inbound has been accepted.')
                        this.acceptInboundDialog = false
                        await this.fetchPendingInbounds(dataWithPage)                        

                        dataWithPage.page = 1
                        await this.fetchPendingReceivingInbounds(dataWithPage)
                        this.cancelAccept()
                    } catch (e) {
                        console.log(e);
                        this.notificationError(e)
                    }
                }
            }
        },
        cancelAccept() {
            this.acceptInboundDialog = false
            this.currentSelectedInboundOrder = null
        },
        async rejectOrder(item, isConfirm) {
            if (!isConfirm) {
                this.rejectInboundDialog = true
                this.currentSelectedInboundOrder = item
            }

            if (isConfirm) {
                let pendingInboundPagination = this.$store.state.inbound.pendingInboundPagination

                if (this.currentSelectedInboundOrder !== null) {
                    let payload = {
                        id: this.currentSelectedInboundOrder.id,
                        notes: this.rejectNotes,
                    }

                    try {
                        let current_page = pendingInboundPagination.current_page                    
                        let dataWithPage = {
                            id: this.currentSelectedInboundOrder.warehouse_id,
                            page: current_page
                        }

                        if (pendingInboundPagination.data.length === 1 && current_page !== 1) {
                            dataWithPage.page = current_page - 1
                        }

                        await this.rejectInbound(payload)
                        this.notificationCustom('Inbound has been rejected.')
                        this.rejectInboundDialog = false
                        await this.fetchPendingInbounds(dataWithPage)                        

                        dataWithPage.page = 1
                        await this.fetchCancelledInbounds(dataWithPage)
                        this.cancelReject()
                    } catch (e) {
                        console.log(e);
                        this.notificationError(e)
                    }
                }
            }
        },
        cancelReject() {
            this.rejectInboundDialog = false
            this.currentSelectedInboundOrder = null
        },
        clearAllFilter() {
            if (this.selectedWhCustomers.length > 0) {
                this.selectedWhCustomersCopy = []
                this.selectedWhCustomerReAssignValue = []
                this.setInboundFilteredVal([])
            }
        },
        // assigning employee/location
        async assignEmployee(item, isSingle) {
            this.showAssignEmployeeDialog = true

            if (typeof isSingle !== 'undefined' && isSingle) {
                item.no_of_units = item.total_expected_units
                item.no_of_cartons = item.total_expected_cartons

                this.selectedInboundOrders.push(item)
            }
        },
        closeAssignEmployee() {
            this.showAssignEmployeeDialog = false
            this.selectedInboundOrders = []
        },
        async assignToLocation(item, isSingle) {
            this.showAssignLocationDialog = true
            
            if (typeof isSingle !== 'undefined' && isSingle) {
                item.no_of_units = item.total_expected_units
                item.no_of_cartons = item.total_expected_cartons

                this.selectedInboundOrders.push(item)
            }

            await this.fetchLocationsAssign(this.currentWarehouseSelected.id)
        },
        closeAssignLocation() {
            this.showAssignLocationDialog = false
            this.selectedInboundOrders = []
        },
        clearSelectedOrders() {
            this.selectedInboundOrders = []
        }
    },
    async mounted() {
        this.setSingleInboundData([])
        this.setInboundFilteredVal([])
        this.fetchProductLoading = true        

        //set tab
        if (this.getCurrentInboundTab !== 'undefined') {
            if (this.currentTab !== this.getCurrentInboundTab) {
                this.currentTab = this.getCurrentInboundTab
            }
        }

        let checkWarehouseTypeId = this.currentWarehouseSelected !== null ? 
            this.currentWarehouseSelected.warehouse_type_id : null
        let currentInventoryTabName = this.$router.history.current.query.tab

        try {
            this.source = axios.CancelToken.source()

            if (this.$store.state.page.currentInventoryTab === 3 &&
                typeof currentInventoryTabName !== 'undefined' && 
                currentInventoryTabName === 'Inbound') {

                if (this.search === '' && this.currentWarehouseInbounds.data.length === 0) {
                    try {                        
                        let dataWithPage = {
                            id: this.currentWarehouseSelected.id,
                            page: 1,
                            cancelToken: new CancelToken(function executor(c) {
                                cancel = c
                            }),
                        }

                        if (checkWarehouseTypeId === 3 && this.getIsShowCreateInboundDialog) {
                            this.addNewInbound()
                            this.setIsCreateInboundShow(false)
                        }                    

                        if (!this.isWarehouseConnected) {
                            await this.fetchPendingInbounds(dataWithPage)
                            this.fetchPendingInboundsLoading = false

                            // if (this.isWarehouseOwn) {
                            //     await this.fetchFloorInbounds(dataWithPage)
                            // } else if (this.isWarehouse3PL) {
                            //     await this.fetchCompletedInbounds(dataWithPage)
                            // }
                        } else {
                            await this.fetchDraftInbounds(dataWithPage)
                            await this.fetchPendingInbounds(dataWithPage)
                            this.fetchPendingInboundsLoading = false
                        }                        

                        if (checkWarehouseTypeId !== null && checkWarehouseTypeId === 6) {
                            if (!this.isWarehouseConnected) {
                                await this.callWarehouseCustomerListsData(dataWithPage)
                            }
                        }

                        if (!this.isWarehouseConnected && !this.isWarehouse3PL) {                           
                            await this.fetchWarehouseEmployeesListsAPI()
                        }
                    } catch(e) {
                        if (e !== "cancel_previous_request") this.notificationError(e)
                        this.fetchPendingInboundsLoading = false
                    }
                }
            }
        } catch(e) {
            if (e !== "cancel_previous_request") this.notificationError(e)
        }
    },
    beforeDestroy(){
		if (cancel !== undefined) {
			cancel("cancel_previous_request")
		}
	},
    updated() {}
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/inventory/inbound/inboundTable.scss';
</style>