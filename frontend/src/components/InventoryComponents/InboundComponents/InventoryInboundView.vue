<template>
    <div class="inbound-view-wrapper" v-resize="onResize"
        :class="isWarehouse3PL ? 'inbound-view-warehouse-is-3pl' : isWarehouseConnected ? 'inbound-warehouse-connected' : ''">
        <div class="loading-wrapper" v-if="fetchSingleInboundLoading">
            <v-progress-circular
                :size="40"
                color="#0171a1"
                indeterminate>
            </v-progress-circular>
        </div>

        <div class="inbound-view-contents-wrapper" 
            v-if="!fetchSingleInboundLoading && typeof singleInboundData !== 'undefined' && singleInboundData !== null">          
            <div class="inbound-header-wrapper-content">
                <div class="details-breadcrumbs" >
                    <router-link to="/inventory?tab=Products" class="warehouse-name" @click.native="onClickBreadcrumbs(0)">
                        {{
                            $store.state.warehouse.currentWarehouse !== null ? 
                            $store.state.warehouse.currentWarehouse.name : '--'
                        }}
                    </router-link>

                    <span class="right-chevron">
                        <img src="../../../assets/images/right_chevron.svg" alt="" srcset="" width="7px">
                    </span>

                    <router-link to="/inventory?tab=Inbound" class="warehouse-name" @click.native="onClickBreadcrumbs(3)">
                        Inbounds
                    </router-link>

                    <span class="right-chevron">
                        <img src="../../../assets/images/right_chevron.svg" alt="" srcset="" width="7px">
                    </span>

                    <p class="order-id">
                        {{ 
                            typeof singleInboundData.data !== 'undefined' && 
                            typeof singleInboundData.data.order_id !== 'undefined' && 
                            singleInboundData.data.order_id !== null ? singleInboundData.data.order_id : '--' 
                        }}
                    </p>
                </div>

                <div class="inbound-header-wrapper">
                    <div class="inbound-info">
                        <div class="info-wrapper">
                            <div class="order-status">
                                <h2>Order ID 
                                    {{ 
                                        typeof singleInboundData.data !== 'undefined' && 
                                        typeof singleInboundData.data.order_id !== 'undefined' && 
                                        singleInboundData.data.order_id !== null ? singleInboundData.data.order_id : '--' 
                                    }}
                                </h2>
                                
                                <!-- <div class="status-wrapper">
                                    <span class="status" :class="getStatusClass(singleInboundData)" 
                                        v-if="!isWarehouseConnected &&
                                        typeof singleInboundData.data !== 'undefined' && 
                                        typeof singleInboundData.data.is_from_connected_3pl !== 'undefined' && 
                                        singleInboundData.data.is_from_connected_3pl !== 1">
                                        {{
                                            typeof inboundStatus !== 'undefined' && inboundStatus !== '' && 
                                            inboundStatus !== null ? 
                                                inboundStatus === 'receive-pending' ? 'Pending Receiving' : 
                                            inboundStatus : '--'
                                        }}
                                    </span>

                                    <span class="status" :class="getStatusClass(singleInboundData)" 
                                        v-if="isWarehouse3PLProvider && typeof singleInboundData.data !== 'undefined' && 
                                        typeof singleInboundData.data.is_from_connected_3pl !== 'undefined' && 
                                        singleInboundData.data.is_from_connected_3pl === 1">
                                        {{
                                            typeof inboundStatus !== 'undefined' && inboundStatus !== '' && 
                                            inboundStatus !== null ? 
                                                inboundStatus === 'pending' ? 'Waiting Approval' : 
                                                inboundStatus === 'floor' ? 'Receiving' : 
                                                inboundStatus === 'receive-pending' ? 'Pending Receiving' : 
                                            inboundStatus : '--'
                                        }}
                                    </span>

                                    <span class="status cancelled" 
                                        v-if="typeof singleInboundData.data !== 'undefined' && 
                                            typeof singleInboundData.data.is_undershipped !== 'undefined' && 
                                            singleInboundData.data.is_undershipped !== 0">

                                        {{ getOvershippedInbound(singleInboundData) }}
                                    </span>
                                </div> -->
                            
                                <div class="status-wrapper">
                                    <span v-if="isWarehouse3PLProvider">
                                        <span v-if="isWarehouseConnected" class="status" :class="getStatusClass(singleInboundData)">
                                            {{
                                                typeof inboundStatus !== 'undefined' && inboundStatus !== '' && 
                                                inboundStatus !== null ? 
                                                    inboundStatus === 'pending' ? 'Waiting Approval' : 
                                                    inboundStatus === 'floor' ? 'Receiving' : 
                                                    inboundStatus === 'receive-pending' ? 'Pending Receiving' : 
                                                inboundStatus : '--'
                                            }}
                                        </span>

                                        <span v-else class="status" :class="getStatusClass(singleInboundData)">
                                            {{
                                                typeof inboundStatus !== 'undefined' && inboundStatus !== '' && 
                                                inboundStatus !== null ? 
                                                    inboundStatus === 'pending' ? 'Waiting Approval' : 
                                                    inboundStatus === 'receive-pending' ? 'Pending Receiving' : 
                                                inboundStatus : '--'
                                            }}
                                        </span>
                                    </span>

                                    <span v-if="!isWarehouse3PLProvider" class="status" :class="getStatusClass(singleInboundData)">
                                        {{
                                            typeof inboundStatus !== 'undefined' && inboundStatus !== '' && 
                                            inboundStatus !== null ? 
                                                inboundStatus === 'receive-pending' ? 'Pending' : 
                                            inboundStatus : '--'
                                        }}
                                    </span>

                                    <span class="status cancelled" 
                                        v-if="typeof singleInboundData.data !== 'undefined' && 
                                            typeof singleInboundData.data.is_undershipped !== 'undefined' && 
                                            singleInboundData.data.is_undershipped !== 0">

                                        {{ getOvershippedInbound(singleInboundData) }}
                                    </span>
                                </div>
                            </div>

                            <p>Last Updated: 
                                <span>
                                    {{ 
                                        typeof singleInboundData.data !== 'undefined' &&
                                        typeof singleInboundData.data.updated_at !== '' && 
                                        singleInboundData.data.updated_at !== null &&
                                        singleInboundData.data.updated_at !== "null" ?
                                        formatDate(singleInboundData.data.updated_at) : 'N/A'                                    
                                    }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="info-buttons" v-if="!isWarehouseConnected">
                        <button class="btn-blue receive-all-products" 
                            @click.stop="receiveAllProductsInbound(singleInboundData)" 
                            v-if="isWarehouse3PL && inboundStatus === 'pending'">

                            <img src="@/assets/icons/white-check.svg" 
                                width="16px" 
                                height="16px" 
                                class="mr-1" 
                                style="margin-top: 2px;">
                            Receive All
                        </button>

                        <button class="btn-blue btn-truck-arrived" @click.stop="openTruckArrivedDialog" 
                            v-if="isShowTruckArrived(singleInboundData)">
                            Truck Arrived
                        </button>

                        <button class="btn-blue btn-new-storable-unit" @click.stop="openNewStorableUnit"
                            v-show="findFloorReceivedProducts(singleInboundData)">
                            New Storable Unit
                        </button>

                        <button class="btn-white btn-mark-completed" @click.stop="completeOrder(singleInboundData.data)"
                            v-show="findFloorReceivedProducts(singleInboundData) && !isHasStorableUnits(singleInboundData)">
                            Mark as Completed
                        </button>

                        <div v-if="!isWarehouseConnected && isWarehouse3PLProvider && inboundStatus === 'pending'" 
                            class="d-flex">
                            <button class="btn-white btn-accept" 
                                @click.stop="acceptOrder(singleInboundData.data, false)">
                                Accept
                            </button>

                            <button class="btn-white btn-reject" @click.stop="rejectOrder(singleInboundData.data, false)">
                                <img src="@/assets/icons/close-red.svg" alt="Reject">
                            </button>
                        </div>

                        <v-menu bottom left offset-y content-class="outbound-lists-menu">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn class="btn-white more-menu" icon v-bind="attrs" v-on="on" width="40px">
                                    <img src="@/assets/icons/dots.svg" alt="dots" width="16px" height="16px">
                                </v-btn>
                            </template>

                            <v-list class="outbound-lists">
                                <v-list-item @click="checkEditInbound(singleInboundData.data, 'edit')"
                                    v-if="isShowEditButton(singleInboundData)">                                    
                                    <v-list-item-title>
                                        Edit
                                    </v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="assignToLocation(singleInboundData, true)" v-if="isShowAssignOptions">
                                    <v-list-item-title>
                                        {{ isHasLocationAssigned ? 'Update Location' : 'Assign to Location' }}
                                    </v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="assignEmployee(singleInboundData, true)" v-if="isShowAssignOptions">
                                    <v-list-item-title>
                                        Assign Employee
                                    </v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="checkEditInbound(singleInboundData.data, 'copy')" 
                                    v-if="typeof singleInboundData.data !== 'undefined' &&
                                    typeof singleInboundData.data.is_from_inventory !== 'undefined' && 
                                    singleInboundData.data.is_from_inventory !== 1 && 
                                    singleInboundData.data.is_from_connected_3pl !== 1">
                                    <v-list-item-title>
                                        Duplicate
                                    </v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="printOrder(singleInboundData.data)" 
                                    v-if="typeof singleInboundData.data !== 'undefined' &&
                                    typeof singleInboundData.data.is_from_inventory !== 'undefined' && 
                                    singleInboundData.data.is_from_inventory !== 1">
                                    <v-list-item-title>
                                        Print Order
                                    </v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="cancelOrder(singleInboundData.data)" 
                                    v-if="isShowCancelButton(singleInboundData)">
                                    <v-list-item-title class="cancel">
                                        Cancel Order
                                    </v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </div>

                    <div class="info-buttons" v-if="isWarehouseConnected">
                        <button class="btn-blue btn-edit" v-if="inboundStatus === 'draft'"
                            @click="checkEditInbound(singleInboundData.data, 'edit')">
                            Edit Order
                        </button>                        

                        <v-menu bottom left offset-y content-class="outbound-lists-menu" 
                            v-if="inboundStatus === 'draft' || inboundStatus === 'pending' || inboundStatus === 'cancelled'">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn class="btn-white more-menu" icon v-bind="attrs" v-on="on" width="40px">
                                    <img src="@/assets/icons/dots.svg" alt="dots" width="16px" height="16px">
                                </v-btn>
                            </template>

                            <v-list class="outbound-lists">
                                <v-list-item  @click="submitOrder(singleInboundData.data, false)"
                                    v-if="inboundStatus === 'draft'">                                    
                                    <v-list-item-title>
                                        Submit
                                    </v-list-item-title>
                                </v-list-item>

                                 <v-list-item @click="deleteOrder(singleInboundData.data, false)"
                                    v-if="inboundStatus === 'draft'">
                                    <v-list-item-title class="cancel">
                                        Delete
                                    </v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="cancelOrder(singleInboundData.data)" 
                                    v-if="inboundStatus === 'pending'">
                                    <v-list-item-title class="cancel">
                                        Cancel
                                    </v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="checkEditInbound(singleInboundData.data, 'copy')" 
                                    v-if="inboundStatus === 'cancelled'">
                                    <v-list-item-title>
                                        Duplicate
                                    </v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </div>
                </div>
            </div>

            <div class="inbound-body-wrapper">
                <div class="inbound-informations">
                    <v-row>
                        <v-col cols="12" sm="4">
                            <div class="inbound-info dflex warehouse" 
                                v-if="typeof singleInboundData.data !== 'undefined' &&
                                typeof singleInboundData.data.is_from_inventory !== 'undefined' && 
                                singleInboundData.data.is_from_inventory === 0">
                                
                                <p class="inbound-title">Truck</p>
                                <p class="inbound-data">
                                    {{ 
                                        typeof singleInboundData.data !== 'undefined' &&
                                        typeof singleInboundData.data.name !== '' && 
                                        singleInboundData.data.name !== null && 
                                        singleInboundData.data.name !== "null" ?
                                        singleInboundData.data.name : '--' 
                                    }}
                                </p>
                            </div>

                            <!-- <div class="inbound-info dflex warehouse">
                                <p class="inbound-title">Supplier</p>
                                <p class="inbound-data">
                                    {{ 
                                        typeof singleInboundData.data !== 'undefined' &&
                                        typeof singleInboundData.data.supplier !== '' && 
                                        singleInboundData.data.supplier !== null ?
                                        singleInboundData.data.supplier : '--' 
                                    }}
                                </p>
                            </div> -->

                            <div class="inbound-info dflex warehouse" v-if="isWarehouse3PLProvider && !isWarehouseConnected">
                                <p class="inbound-title">Customer</p>
                                <p class="inbound-data">
                                    {{ 
                                        typeof singleInboundData.data !== 'undefined' &&
                                        typeof singleInboundData.data.warehouse_customer !== 'undefined' && 
                                        singleInboundData.data.warehouse_customer !== null ?
                                        singleInboundData.data.warehouse_customer.company_name : '--' 
                                    }}
                                </p>
                            </div>

                            <div class="inbound-info dflex warehouse">
                                <p class="inbound-title">Reference</p>
                                <p class="inbound-data">
                                    {{ 
                                        typeof singleInboundData.data !== 'undefined' &&
                                        typeof singleInboundData.data.ref_no !== '' && 
                                        singleInboundData.data.ref_no !== null &&
                                        singleInboundData.data.ref_no !== "null" ?
                                        singleInboundData.data.ref_no : '--' 
                                    }}
                                </p>
                            </div>

                            <div class="inbound-info dflex warehouse" v-if="!isWarehouse3PL">
                                <p class="inbound-title">WAREHOUSE</p>
                                <p class="inbound-data" 
                                    v-if="typeof singleInboundData.data !== 'undefined' &&
                                        typeof singleInboundData.data.warehouse !== '' && 
                                        singleInboundData.data.warehouse !== null">
                                        
                                    {{ singleInboundData.data.warehouse.name }} <br />
                                    {{ singleInboundData.data.warehouse.address }}
                                </p>

                                <p class="inbound-data" v-else>
                                    --
                                </p>
                            </div>

                            <div class="inbound-info dflex warehouse" v-if="isHasLocationAssigned">
                                <p class="inbound-title">LOCATION</p>
                                <p class="inbound-data">                                        
                                    {{ singleInboundData.data.location.label }}
                                </p>
                            </div>

                            <!-- SHOW IF WAREHOUSE 3PL AND IS FROM INVENTORY CREATED -->
                            <div class="inbound-info dflex" v-if="isWarehouse3PL && 
                                typeof singleInboundData.data !== 'undefined' &&
                                typeof singleInboundData.data.is_from_inventory !== 'undefined' && 
                                singleInboundData.data.is_from_inventory === 1">

                                <p class="inbound-title">AS OF</p>
                                <p class="inbound-data">
                                    {{ formatEta(singleInboundData) }}
                                </p>
                            </div>

                            <!-- SHOW IF WAREHOUSE 3PL AND NOT FROM INVENTORY CREATED -->
                            <div class="inbound-info dflex" v-if="isWarehouse3PL && 
                                typeof singleInboundData.data !== 'undefined' &&
                                typeof singleInboundData.data.is_from_inventory !== 'undefined' && 
                                singleInboundData.data.is_from_inventory === 0">

                                <p class="inbound-title">ETA</p>
                                <p class="inbound-data">
                                    {{ formatEta(singleInboundData) }}
                                </p>
                            </div>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <div class="inbound-info dflex" v-if="!isWarehouse3PL">
                                <p class="inbound-title">ETA</p>
                                <p class="inbound-data">
                                    {{ formatEta(singleInboundData) }}
                                </p>
                            </div>

                            <div class="inbound-info warehouse-3pl-hidden dflex">
                                <p class="inbound-title">Actual Arrival</p>
                                <p class="inbound-data">
                                    {{ 
                                        typeof singleInboundData.data !== 'undefined' &&
                                        typeof singleInboundData.data.arrival_time !== '' && 
                                        singleInboundData.data.arrival_time !== null &&
                                        singleInboundData.data.arrival_time !== "null" ?
                                        formatDate(singleInboundData.data.arrival_time) : 'N/A' 
                                    }}
                                </p>
                            </div>

                            <div class="inbound-info dflex">
                                <p class="inbound-title">No of SKU</p>
                                <p class="inbound-data">
                                    {{ 
                                        typeof singleInboundData.no_of_sku !== 'undefined' && 
                                        singleInboundData.no_of_sku !== null ? 
                                        singleInboundData.no_of_sku : 0
                                    }}
                                </p>
                            </div>

                            <div class="inbound-info dflex">
                                <p class="inbound-title">Cartons</p>
                                <p class="inbound-data">
                                    {{ 
                                        typeof singleInboundData.total_expected_cartons !== 'undefined' && 
                                        singleInboundData.total_expected_cartons !== null ? 
                                        singleInboundData.total_expected_cartons : 0
                                    }}

                                    <span class="received-data" :class="getOvershippedInbound(singleInboundData)"
                                        v-if="typeof singleInboundData.data !== 'undefined' && 
                                        typeof singleInboundData.data.inbound_status !== 'undefined' && 
                                        singleInboundData.data.inbound_status !== 'pending'">

                                        (Received 
                                        {{ typeof singleInboundData.total_actual_cartons !== 'undefined' && 
                                            singleInboundData.total_actual_cartons !== null ? 
                                            singleInboundData.total_actual_cartons : 0 
                                        }})
                                    </span>
                                </p>
                            </div>

                            <div class="inbound-info warehouse-3pl-hidden dflex">
                                <p class="inbound-title">Storable Unit</p>
                                <p class="inbound-data">
                                    {{ 
                                        typeof singleInboundData.no_of_storable_units !== 'undefined' &&
                                        singleInboundData.no_of_storable_units !== null ? 
                                        singleInboundData.no_of_storable_units : 0
                                    }}
                                </p>
                            </div>

                            <div class="inbound-info dflex">
                                <p class="inbound-title">Units</p>
                                <p class="inbound-data">
                                    {{ 
                                        typeof singleInboundData.total_expected_units !== 'undefined' &&
                                        singleInboundData.total_expected_units !== null ? 
                                        singleInboundData.total_expected_units : 0
                                    }}

                                    <span class="received-data" :class="getOvershippedInbound(singleInboundData)"
                                        v-if="typeof singleInboundData.data !== 'undefined' && 
                                        typeof singleInboundData.data.inbound_status !== 'undefined' && 
                                        singleInboundData.data.inbound_status !== 'pending'">

                                        (Received 
                                        {{typeof singleInboundData.total_actual_units !== 'undefined' && 
                                            singleInboundData.total_actual_units !== null ? 
                                            singleInboundData.total_actual_units : 0 
                                        }})
                                    </span>
                                </p>
                            </div>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <div class="inbound-info">
                                <p class="inbound-title" v-if="!isWarehouseConnected && isWarehouse3PLProvider">
                                    NOTES/INSTRUCTIONS
                                </p>
                                <p class="inbound-title" v-if="!isWarehouseConnected && !isWarehouse3PLProvider">
                                    NOTES
                                </p>
                                <p class="inbound-title" v-if="isWarehouseConnected">SPECIAL INSTRUCTION</p>
                                <p class="inbound-data">
                                    {{
                                        typeof singleInboundData.data !== 'undefined' &&
                                        typeof singleInboundData.data.notes !== '' && 
                                        singleInboundData.data.notes !== null && 
                                        singleInboundData.data.notes !== "null" ?
                                        singleInboundData.data.notes : '--' 
                                    }}
                                </p>
                            </div>
                        </v-col>
                    </v-row>
                </div>

                <div class="inbound-tabs" 
                    :class="typeof singleInboundData.data !== 'undefined' &&
                    typeof singleInboundData.data.is_from_inventory !== 'undefined' && 
                    singleInboundData.data.is_from_inventory === 0 ? '' : 'is_from_inventory'">

                    <div class="inbound-tabs-header">
                        <v-tabs class="inbound-inner-tab" @change="onTabChange" v-model="currentTab">
                            <v-tab v-for="(tab, index) in tabsComputed" :key="index" :class="tab">
                                {{ tab }}
                            </v-tab>
                        </v-tabs>

                        <div class="inbound-multiple-selection" v-if="isShowReceivedButton(singleInboundData)">
                            <button class="btn-white selection" @click.stop="clearSelection">
                                Clear Selection
                            </button>

                            <button class="btn-white receive" @click.stop="receiveMultipleProducts">
                                Receive
                            </button>
                        </div>

                        <div class="inbound-multiple-selection" v-if="isShowPlacedButton(singleInboundData)">
                            <button class="btn-white selection" @click.stop="clearStorableSelection">
                                Clear Selection
                            </button>

                            <button class="btn-white receive" @click.stop="placeMultipleStorableUnit">
                                Placed
                            </button>
                        </div>

                        <div class="inbound-multiple-selection" 
                            v-if="currentTab == 2 && !isMobile && inboundStatus !== 'cancelled'">
                            <button class="btn-white receive" @click.stop="uploadInboundDocument">
                                Upload
                            </button>
                        </div>
                    </div>
                    
                    <div class="product-section-table inbound-table-wrapper" v-if="currentTab == 0">
                        <ProductSection 
                            :inboundProductsData="singleInboundData.data"
                            :isMobile="isMobile"
                            :warehouse_id="linkData.warehouse_id"
                            :productLists="productsListsData"
                            :linkData="linkData"
                            :selected.sync="productSelected"
                            :isWarehouse3PL="isWarehouse3PL"
                            :isFromInventory="isFromInventory"
                            :isWarehouseConnected="isWarehouseConnected"
                            :isWarehouse3PLProvider="isWarehouse3PLProvider" />
                    </div>

                    <div class="pallets-section-table inbound-table-wrapper" v-if="currentTab == 1">
                        <PalletSection 
                            :inboundProductsData="singleInboundData.data"
                            :isMobile="isMobile"
                            :warehouse_id="linkData.warehouse_id"
                            :productLists="productsListsData"
                            :linkData="linkData"
                            :selected.sync="storableSelected"
                            :locationsLists="locationsLists"
                            :fetchLocationsListsLoading="fetchLocationsListsLoading"
                            @clickFetchLocations="clickFetchLocations"
                            :isWarehouseConnected="isWarehouseConnected" />
                    </div>

                    <div class="pallets-section-table inbound-table-wrapper" v-if="currentTab == 2">
                        <DocumentSection 
                            :inboundProductsData="singleInboundData.data"
                            :isMobile="isMobile"
                            :warehouse_id="linkData.warehouse_id"
                            :productLists="productsListsData"
                            :linkData="linkData" 
                            @uploadInboundDocument="uploadInboundDocument" />
                    </div>

                    <div class="pallets-section-table inbound-table-wrapper" v-if="currentTab == 3 && !isWarehouse3PL">
                        <TaskSection 
                            :inboundProductsData="singleInboundData"
                            :isMobile="isMobile"
                            :currentWarehouseSelected="$store.state.warehouse.currentWarehouse"
                            :isWarehouseConnected="isWarehouseConnected" />
                    </div>
                </div>
            </div>

            <TruckArrivedDialog 
                :dialogData.sync="dialogTruckArrived"
                :editedItemData.sync="truckArrivedData"
                @close="closeTruckArrivedDialog"
                :loading="getTruckArrivedLoading"
                :linkData="linkData"
                :isFetchSingleInbound="true"
                :isWarehouse3PLProvider="isWarehouse3PLProvider" />

            <ReceiveProductDialog 
                :dialogData.sync="dialogReceiveProduct"
                :editedItemData.sync="productsSelectedToReceive"
                @close="closeProductReceive"
                :loading="false"
                :productLists="productsListsData"
                :linkData="linkData"
                :multiple="true"
                @clearSelection="clearSelection"
                :title="title"
                :inboundStatus="inboundStatus"
                :isWarehouse3PL="isWarehouse3PL"
                :undershipped="undershipped"
                :isWarehouse3PLProvider="isWarehouse3PLProvider" />

            <PrintLabelDialog
                @init="getPrintLabelInterface"
                @close="showPrintLabelDialog = false"
                :showDialog.sync="showPrintLabelDialog"/>

            <NewStorableUnitDialog
                @print="printLabel"
                :dialog.sync="dialogNewStorableUnit" 
                :editedItem.sync="storableItems"
                :productsData="singleInboundData.data"
                :linkData="linkData"
                @close="closeStorableUnit"
                :index="-1"
                :isMobile="isMobile" />

            <UploadInboundDialog 
                :dialogData.sync="dialogOpenUploadDoc"
                :linkData="linkData"
                @close="closeInboundDocument"
                :inboundStatus="inboundStatus" />

            <CreateInboundDialog 
                :dialogCreate.sync="dialogCreate"
                :editedInboundIndex.sync="editedInboundIndex"
                :editedInboundItems.sync="editedInboundItems"
                :defaultInboundItems.sync="defaultInboundItems"
                :currentWarehouseSelected="$store.state.warehouse.currentWarehouse"
                @close="closeCreate"
                :isMobile="isMobile"
                :parentTab.sync="getCurrentInboundTab"
                :linkData="linkData"
                :isFetchSingleInbound="true"
                :productListsDropdownData="productsListsData"
                :fetchProductLoading="fetchProductLoading"
                @callInboundProductsFor3PL="callInboundProductsFor3PL"
                @callWarehouseCustomerProducts="callWarehouseCustomerProducts"
                :fetchWarehouseCustomersLoading="fetchWarehouseCustomersLoading"
                :selectedWhCustomers.sync="selectedWhCustomersCopy"
                :selectedWhCustomersCopy.sync="selectedWhCustomersCopy"
                :isWarehouseConnected="isWarehouseConnected"  />

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

            <PlaceStorableUnitDialog 
                :dialog.sync="dialogPlaceStorable"
                :placeStorableUnitItems.sync="storableSelectedToReceive"
                @close="closePlaceMultipleDialog"
                :isMultiple="true"
                @clearSelection="clearStorableSelection"
                :linkData="linkData"
                :inboundStatus="inboundStatus"
                :locationsLists="locationsLists"
                :fetchLocationsListsLoading="fetchLocationsListsLoading" />

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

            <!-- FOR EDIT INVENTORY -->
            <AddInventoryProducts 
                :editedProductIndex="editedProductIndex"
                :editedProductItems.sync="editedProductItems"
                :dialogAdd.sync="dialogEditFromInventory"
                :isMobile="isMobile"
                :productListsDropdownData="productsListsData"
                @close="closeEditInventory"
                :currentWarehouseSelected="$store.state.warehouse.currentWarehouse"
                @openAddProductDialog="openAddProductDialog"
                :fetchProductLoading="fetchProductLoading"
                :isFetchSingleInbound="true" />

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

            <!-- Assign Dialogs -->
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
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import ProductSection from './TabsComponents/ProductSection.vue'
import PalletSection from './TabsComponents/PalletSection.vue'
import DocumentSection from './TabsComponents/DocumentSection.vue'
import TruckArrivedDialog from './Dialogs/TruckArrivedDialog.vue'
import ReceiveProductDialog from './Dialogs/ReceiveProductDialog.vue'
import NewStorableUnitDialog from './Dialogs/NewStorableUnitDialog.vue'
import UploadInboundDialog from './Dialogs/UploadInboundDialog.vue'
import CreateInboundDialog from './Dialogs/CreateInboundDialog.vue'
import PlaceStorableUnitDialog from './Dialogs/PlaceStorableUnitDialog.vue'
import ConfirmDialog from '../../Dialog/GlobalDialog/ConfirmDialog.vue'
import AddInventoryProducts from '../ProductsComponents/AddInventoryProducts.vue'
import ProductAddDialog from '../../ProductComponents/Dialog/ProductAddDialog.vue'
import globalMethods from '../../../utils/globalMethods'
import inventoryGlobalMethods from '../../../utils/inventoryMethods/inventoryGlobalMethods'
import _ from 'lodash'
import PrintLabelDialog from "@/components/InventoryComponents/InboundComponents/Dialogs/PrintLabelDialog";
import TaskSection from './TabsComponents/TaskSection.vue'
import AssignEmployeeDialog from '../InboundComponents/Dialogs/AssignEmployeeDialog.vue'
import AssignLocationDialog from '../InboundComponents/Dialogs/AssignLocationDialog.vue'

export default {
    name: 'InventoryInboundView',
    props: [],
    components: {
        PrintLabelDialog,
        ProductSection,
        PalletSection,
        DocumentSection,
        TruckArrivedDialog,
        ReceiveProductDialog,
        NewStorableUnitDialog,
        UploadInboundDialog,
        CreateInboundDialog,
        PlaceStorableUnitDialog,
        ConfirmDialog,
        AddInventoryProducts,
        ProductAddDialog,
        TaskSection,
        AssignEmployeeDialog,
        AssignLocationDialog
    },
    data: () => ({
        tabs: ["Products", "Storable Unit", "Documents", "Task"],
        currentTab: 0,
        isMobile: false,
        dialogTruckArrived: false,
        truckArrivedData: {
            name: '',
            ref_no: ''
        },
        linkData: {
            inbound_id: '',
            warehouse_id: ''
        },
        productSelected: [],
        dialogReceiveProduct: false,
        dialogNewStorableUnit: false,
        dialogOpenUploadDoc: false,
        dialogCreate: false,
        editedInboundIndex: 0,
        editedInboundItems: {
			order_id: '',
            customer: '',
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
                    shipping_unit: ''
                }
            ],
            documents: []
		},
		defaultInboundItems: {
			order_id: '',
            customer: '',
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
                    shipping_unit: ''
                }
            ],
            documents: []
		},
        dialogCancel: false,
        cancelOrderData: null,
        fetchSingleInboundLoading: false,
        storableItems: {
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
        title: 'receive',
        dialogPlaceStorable: false,
        storableSelected: [],
        // 3pl order completion
        dialogConfirm: false,
        toCompleteOrderData: null,
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
        showWarningEditInboundDialog: false,
        currentEditInboundData: null,
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
        // warehouse customers products
        current_page_is_warehouse_products: 1,
        // locations lists
        locationsLists: [],
        location_page: 1,
        lastLocationsListsCheck: [],
        fetchLocationsListsLoading: false,
        // warehouse customers
		current_page_is_whcustomers: 1,
		warehouseCustomerListsData: [],
		lastCheckWHData: [],
        fetchWarehouseCustomersLoading: false,
        showPrintLabelDialog: false,
        selectedWhCustomersCopy: [],
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
            getSingleInbound: 'inbound/getSingleInbound',
            getSingleInboundLoading: 'inbound/getSingleInboundLoading',
            getSingleWarehouse: 'warehouse/getSingleWarehouse',
            getTruckArrivedLoading: 'inbound/getTruckArrivedLoading',
            getCurrentInboundTab: 'inbound/getCurrentInboundTab',
            getCancelInboundLoading: 'inbound/getCancelInboundLoading',
            getProducts: 'products/getProducts',
            getAllInboundProductsLists: 'inbound/getAllInboundProductsLists',
            getSuppliers: "suppliers/getSuppliers",
            // 3pl
            getCompleteInboundOrderLoading: 'inbound/getCompleteInboundOrderLoading',
            getAllInboundProductsListsDropdownData: 'inbound/getAllInboundProductsListsDropdownData',
            getAllCategoryDropdownLists: 'productInventories/getAllCategoryDropdownLists',
            getAllWarehouseCustomerInboundProductsLists: 'inbound/getAllWarehouseCustomerInboundProductsLists',
            getAllWarehouseLocations: 'inbound/getAllWarehouseLocations',
            getAllWarehouseCustomerListsData: 'warehouseCustomers/getAllWarehouseCustomerListsData',
			getWarehouseCustomersDropdown: 'warehouseCustomers/getWarehouseCustomersDropdown',
            getDeleteInboundLoading: 'inbound/getDeleteInboundLoading',
            getSubmitInboundLoading: 'inbound/getSubmitInboundLoading',
            getAcceptInboundLoading: 'inbound/getAcceptInboundLoading',
            getRejectInboundLoading: 'inbound/getRejectInboundLoading'
        }),
        isWarehouse3PLProvider() {
            if (this.$store.state.warehouse.currentWarehouse !== null) {
                if (typeof this.$store.state.warehouse.currentWarehouse.warehouse_type_id !== 'undefined' && 
                    this.$store.state.warehouse.currentWarehouse.warehouse_type_id === 6) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        isWarehouseConnected() {
            if (this.$store.state.warehouse.currentWarehouse !== null) {
                if (typeof this.$store.state.warehouse.currentWarehouse.is_own !== 'undefined' && 
                    this.$store.state.warehouse.currentWarehouse.is_own === 0) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        isWarehouseOwn() {
            if (this.$store.state.warehouse.currentWarehouse !== null) {
                if (typeof this.$store.state.warehouse.currentWarehouse.warehouse_type_id !== 'undefined' && 
                    this.$store.state.warehouse.currentWarehouse.warehouse_type_id === 1) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        isWarehouse3PL() {
            if (this.$store.state.warehouse.currentWarehouse !== null) {
                if (typeof this.$store.state.warehouse.currentWarehouse.warehouse_type_id !== 'undefined' && 
                    this.$store.state.warehouse.currentWarehouse.warehouse_type_id === 3) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        singleInboundData() {
            let newData = null

            if (typeof this.getSingleInbound !== 'undefined' && this.getSingleInbound !== null && 
                this.getSingleInbound.data !== 'undefined') {

                newData = {
                    data: this.getSingleInbound.data,
                    no_of_storable_units: this.getSingleInbound.no_of_storable_units,
                    no_of_sku: this.getSingleInbound.no_of_sku,
                    total_expected_cartons: this.getSingleInbound.total_expected_cartons,
                    total_expected_units: this.getSingleInbound.total_expected_units,
                    total_actual_cartons: this.getSingleInbound.total_actual_cartons,
                    total_actual_units: this.getSingleInbound.total_actual_units
                }
            }

            return newData
        },
        productsData: {
            get() {
                let newProductsData = []

                if (typeof this.inboundProductsData !== 'undefined' && this.inboundProductsData !== null) {
                    newProductsData = this.inboundProductsData
                }

                return newProductsData
            },
            set(value) {
                this.$emit('update:inboundProductsData', value)
            }
        },
        productsSelectedToReceive() {
            let productsListsSelected = []

            if (this.productSelected.length !== 'undefined' && this.productSelected.length > 0) {
                this.productSelected.map(prod => {
                    let { id, product_id, product, expected_carton_count, expected_total_unit, in_each_carton, shipping_unit, actual_carton_count, actual_total_unit, storable_units, remaining_carton_count, remaining_total_unit, notes } = prod

                    let findProduct = _.findIndex(this.productSelected, e => (id === e.id && e.status !== 'recieved'))

                    if (findProduct !== -1) {
                        productsListsSelected.push({
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

            return productsListsSelected
        },
        storableSelectedToReceive() {
            let storableSelectedLists = []
            let item = this.storableSelected

            if (item !== 'undefined' && item.length > 0) {
                item.map(v => {
                    let { ...otherItems } = v
                    let products = []
                    
                    let findUnit = _.findIndex(this.storableSelected, e => (v.id === e.id && e.status !== 'placed'))

                    if (findUnit !== -1) {
                        if (otherItems.inbound_storable_products !== 'undefined' && 
                            otherItems.inbound_storable_products.length > 0) {
                            otherItems.inbound_storable_products.map(unit => {

                                products.push({
                                    inbound_storable_unit_id: unit.inbound_storable_unit_id,
                                    sku: unit.inbound_product.sku,
                                    name: unit.inbound_product.product_name,
                                    carton_count: unit.carton_count,
                                    total_unit: unit.total_unit,
                                    shipping_unit: unit.inbound_product.shipping_unit
                                })
                            })
                        }

                        otherItems.products = products
                        storableSelectedLists.push(otherItems)
                    }                    
                })
            }

            return storableSelectedLists
        },
        inboundStatus() {
            let inbound_status = ''

            if (this.singleInboundData !== 'undefined' && this.singleInboundData !== null && 
                typeof this.singleInboundData.data !== 'undefined' ) {

                inbound_status = this.singleInboundData.data.inbound_status
            }

            return inbound_status
        },
        undershipped() {
            let val = 0

            if (typeof this.singleInboundData !== 'undefined' && this.singleInboundData !== null) {
                if (typeof this.singleInboundData.data !== 'undefined' && this.singleInboundData.data !== null) {
                    if (typeof this.singleInboundData.data.is_undershipped !== 'undefined') {
                        let undershipped_val = this.singleInboundData.data.is_undershipped

                        val = undershipped_val
                    }
                }                
            }

            return val
        },
        isFromInventory() {
            if (typeof this.singleInboundData !== 'undefined' && this.singleInboundData !== null &&
                typeof this.singleInboundData.data !== 'undefined' &&
                this.singleInboundData.data !== null &&
                typeof this.singleInboundData.data.is_from_inventory !== 'undefined' && 
                this.singleInboundData.data.is_from_inventory === 0) {
                return false
            } else {
                return true
            }
        },
        isHasLocationAssigned() {
            let hasAssigned = false

            if (typeof this.singleInboundData !== 'undefined' && this.singleInboundData !== null &&
                typeof this.singleInboundData.data !== 'undefined' && this.singleInboundData.data !== null &&
                this.singleInboundData.data.location !== null) {
                hasAssigned = true
            } else {
                hasAssigned = false
            }

            return hasAssigned
        },
        tabsComputed() {
            if (this.isWarehouse3PL) {
                let newTabs = this.tabs.filter(v => { return v !== 'Task' })
                return newTabs
            } else {
                return this.tabs
            }
        },
        isShowAssignOptions() {
            let show = false

            if (this.isWarehouseConnected || this.isWarehouse3PL) {
                show = false
            } else {
                if (this.isWarehouse3PLProvider) {
                    if (this.inboundStatus === 'receive-pending' || this.inboundStatus === 'arrived' || this.inboundStatus === 'floor') {
                        show = true
                    } else {
                        show = false
                    }
                } else {
                    if (this.inboundStatus === 'pending' || this.inboundStatus === 'arrived' || this.inboundStatus === 'floor') {
                        show = true
                    } else {
                        show = false
                    }
                }
            }

            return show
        }
    },
    methods: {
        ...mapActions({
            fetchSingleInbound: 'inbound/fetchSingleInbound',
            fetchSingleWarehouse: 'warehouse/fetchSingleWarehouse',            
            getPlaceStorableLocations: 'inbound/getPlaceStorableLocations',
            printInboundOrder: 'inbound/printInboundOrder',
            cancelInbound: 'inbound/cancelInbound',
            setCurrentInboundTab: 'inbound/setCurrentInboundTab',
            fetchInboundProducts: 'inbound/fetchInboundProducts',
            setAllInboundProductsLists: 'inbound/setAllInboundProductsLists',
            fetchPendingInbounds: 'inbound/fetchPendingInbounds',
            fetchFloorInbounds: 'inbound/fetchFloorInbounds',
            fetchCancelledInbounds: 'inbound/fetchCancelledInbounds',
            fetchCompletedInbounds: 'inbound/fetchCompletedInbounds',
            // fetchSuppliers: "suppliers/fetchSuppliers",
            // 3pl
            completeInboundOrder: 'inbound/completeInboundOrder',
            fetchCategoriesDropdown: 'category/fetchCategoriesDropdown',
            setAllCategoryDropdownLists: 'productInventories/setAllCategoryDropdownLists',
            fetchWarehouseCustomerInboundProducts: 'inbound/fetchWarehouseCustomerInboundProducts',
            checkWarehouse3PLProvider: 'warehouseCustomers/checkWarehouse3PLProvider',
			setWarehouseTypeHas3PL: 'warehouseCustomers/setWarehouseTypeHas3PL',
            fetchWarehouseCustomersDropdown: "warehouseCustomers/fetchWarehouseCustomersDropdown",
			setAllWarehouseCustomerLists: 'warehouseCustomers/setAllWarehouseCustomerLists',
            setSelectedPrevCustomerWarehouseID: 'inbound/setSelectedPrevCustomerWarehouseID',
            deleteInbound: 'inbound/deleteInbound',
            submitInbound: 'inbound/submitInbound',
            fetchDraftInbounds: 'inbound/fetchDraftInbounds',
            fetchPendingReceivingInbounds: 'inbound/fetchPendingReceivingInbounds',
            acceptInbound: 'inbound/acceptInbound',
            rejectInbound: 'inbound/rejectInbound',
            fetchWarehouseEmployees: 'employees/fetchWarehouseEmployees',
            fetchLocationsAssign: 'employees/fetchLocationsAssign'
        }),
        ...globalMethods,
        ...inventoryGlobalMethods,
        onTabChange(i) {
            this.currentTab = i
        },
        formatDate(inbound) {
            return this.formatDateWithTimeAbbr(inbound)
        },
        formatEta(inbound) {
            if (inbound !== 'undefined' && inbound !== null && typeof inbound.data !== 'undefined') {
                if (inbound.data.estimated_date !== 'undefined') {
                    let date = inbound.data.estimated_date
                    let time = inbound.data.estimated_time
                    
                    // show date only if inbound is from inventory
                    return !this.isFromInventory ? this.formatDateWithTimeSeparated(date, time, 'time-first') : this.formatDateOnly(date)
                }
            }
        },
        onClickBreadcrumbs(index) {
            this.$store.dispatch("page/setCurrentInventoryTab", index)
            this.setAllInboundProductsLists([])
        },
        getStatusClass(inbound) {
            if (inbound !== 'undefined' && inbound !== null && typeof inbound.data !== 'undefined') {
                return inbound.data.inbound_status
            } else {
                return ''
            }
        },
        getOvershippedInbound(inbound) {
            if (inbound !== 'undefined' && inbound !== null && typeof inbound.data !== 'undefined') {
                return this.isDataOvershipped(inbound.data)
            }
        },
        isShowTruckArrived() {
            let show = false
            if (!this.isWarehouseConnected) {
                if (!this.isWarehouse3PLProvider) {
                    if (typeof this.inboundStatus !== 'undefined' && this.inboundStatus === 'pending') 
                        show = true
                    else 
                        show = false
                } else {
                    if (typeof this.inboundStatus !== 'undefined' && this.inboundStatus === 'receive-pending') 
                        show = true
                    else 
                        show = false
                }
            }
            return show
        },
        isShowReceivedButton(inbound) {
            let show = false

            if(!this.isMobile) {
                if (typeof inbound !== 'undefined' && inbound !== null && typeof inbound.data !== 'undefined') {
                    if (this.currentTab === 0) {
                        if (this.productSelected.length > 0) {
                            if (!this.isWarehouse3PL) {
                                if (this.inboundStatus !== 'pending' && this.inboundStatus !== 'receive-pending' && 
                                    this.inboundStatus !== 'cancelled') {
                                    let findNotReceivedYet = _.findIndex(this.productSelected, (e) => e.status !== 'recieved' )

                                    if (findNotReceivedYet === -1) {
                                        show = false
                                    } else {
                                        show = true
                                    }
                                }
                            } else {
                                let findNotReceivedYet = _.findIndex(this.productSelected, (e) => e.status !== 'recieved' )

                                if (findNotReceivedYet === -1) {
                                    show = false
                                } else {
                                    show = true
                                }
                            }
                        }
                    }
                }
            }

            return show
        },
        isShowPlacedButton(inbound) {
            let show = false

            if (typeof inbound !== 'undefined' && inbound !== null && typeof inbound.data !== 'undefined') {
                if (this.currentTab === 1 && this.inboundStatus !== 'pending' && 
                    this.inboundStatus !== 'receive-pending' && this.inboundStatus !== 'cancelled') {
                    if (this.storableSelected.length > 0) {
                        let findNotPlacedYet = _.findIndex(this.storableSelected, (e) => e.status !== 'placed' )

                        if (findNotPlacedYet === -1) {
                            show = false
                        } else {
                            show = true
                        }
                    }
                }
            }            

            return show
        },        
        isShowEditButton(inbound) {
            let show = false

            if (typeof inbound !== 'undefined' && inbound !== null && typeof inbound.data !== 'undefined') {
                if (!this.isWarehouse3PL) {
                    if (this.isWarehouse3PLProvider) {
                        if ((this.inboundStatus === 'receive-pending' || this.inboundStatus === 'floor') && 
                            inbound.data.is_from_connected_3pl !== 1) {
                            show = true
                        } else {
                            show = false
                        }
                    } else {
                        if (this.inboundStatus === 'cancelled' || this.inboundStatus === 'completed') {
                            show = false
                        } else {
                            show = true
                        }
                    }
                } else {
                    if (this.inboundStatus === 'cancelled') {
                        show = false
                    } else {
                        show = true
                    }
                }
            }

            return show
        },
        onResize() {
            if (window.innerWidth < 769) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
        },
        openTruckArrivedDialog() {
            this.dialogTruckArrived = true
        },
        closeTruckArrivedDialog() {
            this.dialogTruckArrived = false
            this.truckArrivedData = {
                name: '',
                ref_no: ''
            }
        },
        receiveMultipleProducts() {
            this.dialogReceiveProduct = true            
        },
        async closeProductReceive() {
            this.dialogReceiveProduct = false
        },
        clearSelection() {
            this.productSelected = []
        },
        // place storable
        placeMultipleStorableUnit() {
            this.dialogPlaceStorable = true
            this.clickFetchLocations()
        },
        closePlaceMultipleDialog() {
            this.dialogPlaceStorable = false
        },
        clearStorableSelection() {
            this.storableSelected = []
        },
        // new storable
        openNewStorableUnit() {
            this.dialogNewStorableUnit = true
        },
        getPrintLabelInterface(printLabelInterface){
          this.$options.printLabel = printLabelInterface
        },
        printLabel(label) {
          console.log(label)
          this.$options.printLabel.print(label)
        },
        closeStorableUnit() {
            this.dialogNewStorableUnit = false
        },
        findFloorReceivedProducts(inbound) {
            if (inbound !== 'undefined' && inbound !== null && typeof inbound.data !== 'undefined') {
                if (inbound.data.inbound_products !== 'undefined') {
                    if (inbound.data.inbound_status === 'floor') {
                        let findIndex = _.findIndex(inbound.data.inbound_products, (e) => e.status !== 'recieved')

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
        isHasStorableUnits(inbound) {
            let hasStorable = false

            if (inbound !== 'undefined' && inbound !== null && typeof inbound.data !== 'undefined') {
                if (inbound.data.inbound_storable_units !== 'undefined') {
                    if (inbound.data.inbound_storable_units.length !== 'undefined') {
                        if (inbound.data.inbound_storable_units.length > 0) {
                            hasStorable = true
                        }
                    }
                }
            }

            return hasStorable
        },
        uploadInboundDocument() {
            this.dialogOpenUploadDoc = true
        },
        closeInboundDocument() {
            this.dialogOpenUploadDoc = false
        },
        closeCreate() {
            this.dialogCreate = false
            if (this.currentEditInboundData !== null) {
                this.closeWarning()
            }
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
        checkEditInbound(item, toDo) {
            if (!this.isWarehouse3PL) {
                this.editOrder(item, toDo)
            } else {
                if (item.inbound_status === 'completed') {
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
                if (!this.isFromInventory) {
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
        async editOrder(item, toDo) {
            this.editedInboundIndex = 0
            let inboundItem = { ...item }

            if (!inboundItem.inbound_products || inboundItem.inbound_products.length === 0) {
                let buildProduct = [
                    {
                        product_id: '',
                        quantity: '',
                        shipping_unit: ''
                    }
                ]

                inboundItem = { ...inboundItem, inbound_products: buildProduct }

            } else {
                if (inboundItem.inbound_products.length !== 0) {
                    let buildProduct = inboundItem.inbound_products.map(v => {
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
                this.callInboundProductsFor3PL('Inbound')
            }
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

                    await this.cancelInbound(payload)
                    this.dialogCancel = false
                    this.notificationCustom('Inbound has been cancelled.')

                    let singlePayload = { wid: this.cancelOrderData.warehouse_id, iid: this.cancelOrderData.id }
                    await this.fetchSingleInbound(singlePayload)
                    this.closeCancel()

                    let dataWithPage = { id: this.cancelOrderData.warehouse_id, page: 1 }

                    if (!this.isWarehouse3PLProvider) {
                        if (this.getCurrentInboundTab === 0) {
                            await this.fetchPendingInbounds(dataWithPage)
                        } else if (this.getCurrentInboundTab === 1) {
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

                    await this.fetchCancelledInbounds(dataWithPage)
                    this.cancelOrderData = null
                } catch(e) {
                    this.notificationError(e)
                }
            }
        },
        closeCancel() {
            this.dialogCancel = false
        },
        // for 3PL
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

                    let singlePayload = {  wid: this.toCompleteOrderData.warehouse_id,  iid: this.toCompleteOrderData.id }
                    await this.fetchSingleInbound(singlePayload)
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
        isShowCancelButton(inbound) {
            let show = false

            if (typeof inbound !== 'undefined' && inbound !== null && typeof inbound.data !== 'undefined') {
                if (!this.isWarehouse3PL) {
                    if (!this.isWarehouse3PLProvider) {
                        if (typeof inbound.data.inbound_status !== 'undefined' && 
                            (inbound.data.inbound_status === 'pending' ||
                            inbound.data.inbound_status === 'arrived')) {
                            show = true
                        } else {
                            show = false
                        }
                    } else {
                        if ((inbound.data.inbound_status === 'receive-pending' || 
                            inbound.data.inbound_status === 'arrived') && 
                            inbound.data.is_from_connected_3pl !== 1) {
                            show = true
                        }
                    }
                } else {
                    if (typeof inbound.data !== 'undefined' && typeof inbound.data.inbound_status !== 'undefined' && 
                        (inbound.data.inbound_status === 'pending')) {
                            
                        let productsLists = inbound.data.inbound_products

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
            }            
            
            return show
        },
        // calling for Products
        async callInboundProductsFor3PL(from) {
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

                    if (typeof this.getAllInboundProductsLists !== 'undefined' && 
                        this.getAllInboundProductsLists !== null && 
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
        receiveAllProductsInbound(item) {  
            if (typeof item !== 'undefined' && item !== null) {
                if (typeof item.data !== 'undefined' && item.data !== null) {
                    this.linkData = {
                        inbound_id: item.data.id,
                        warehouse_id: item.data.warehouse_id
                    }

                    if (typeof item.data.inbound_products !== 'undefined' &&
                        item.data.inbound_products !== null &&
                        item.data.inbound_products.length !== 'undefined' &&
                        item.data.inbound_products.length > 0) {
                        this.productSelected = item.data.inbound_products
                    }
                    
                    this.dialogReceiveProduct = true
                }
            }
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
        async loadMoreCategories(){
			if (this.current_page_is_category < this.getCategoriesDropdown.last_page ){
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
            this.setAllInboundProductsLists([])

            // let currentSelectedWarehouseCustomer = typeof this.singleInboundData !== 'undefined' 
            // && typeof this.singleInboundData.data !== 'undefined' ? this.singleInboundData.data.warehouse_customer_id : null           

            if (val.warehouse_customer_id !== null) {
                if (this.getAllInboundProductsListsDropdownData.length === 0) {
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
                    this.productsListsData = this.getAllInboundProductsListsDropdownData
                    this.setAllInboundProductsLists(this.productsListsData)
                    this.fetchProductLoading = false
                }
                
            } else {
                await this.callInboundProductsFor3PL('Inbound')
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
        // fetch Locations For Place Storable Unit
        async clickFetchLocations() {
            if (typeof this.getSingleWarehouse !== 'undefined') {
                let currentWarehouse = this.$store.state.warehouse.currentWarehouse

                if (currentWarehouse !== null) {
                    let currentWarehouseCustomerSaved = typeof this.singleInboundData.data !== 'undefined' &&
                        typeof this.singleInboundData.data.warehouse_customer_id !== 'undefined' ? 
                        this.singleInboundData.data.warehouse_customer_id : null

                    let getLocationsPayload = {
                        warehouse_id: currentWarehouse.id,
                        warehouse_customer_id: currentWarehouseCustomerSaved,
                        page: 1,
                        per_page: 500
                    }
                        
                    if (currentWarehouse.warehouse_type === '3PL Provider') {
                        if (typeof currentWarehouseCustomerSaved !== 'undefined' && currentWarehouseCustomerSaved !== null) {
                            getLocationsPayload.type = 'provider'
                            await this.fetchListsOfLocations(getLocationsPayload)
                        } else {
                            // call all locations as own to get all locations since there are no warehouse customers
                            getLocationsPayload.type = 'own'
                            await this.fetchListsOfLocations(getLocationsPayload)
                        }
                    } else {                
                        if (this.inboundStatus !== 'cancelled') {
                            if (!this.isWarehouse3PL) {
                                getLocationsPayload.type = 'own'
                                await this.fetchListsOfLocations(getLocationsPayload)
                            }
                        }
                    }
                }
            }
        },
        async fetchListsOfLocations(getLocationsPayload) {
            this.fetchLocationsListsLoading = true
            this.location_page = 1
            this.locationsLists = []

            try {
                await this.getPlaceStorableLocations(getLocationsPayload)
                
                if (typeof this.getAllWarehouseLocations !== 'undefined' && 
                    this.getAllWarehouseLocations !== null && 
                    typeof this.getAllWarehouseLocations.results !== 'undefined' && 
                    Array.isArray(this.getAllWarehouseLocations.results) &&
                    this.getAllWarehouseLocations.results.length > 0) {
                        
                    this.locationsLists = this.getAllWarehouseLocations.results.map(v => {
                        // let name = v.type === 'storage' ? v.row + v.rack + v.shelf : v.gate_name
                        let name = v.label

                        return {
                            id: v.id,
                            name,
                            storable_count: v.storable_unit_count
                        }
                    })

                    this.lastLocationsListsCheck = this.locationsLists
                    this.fetchLocationsListsLoading = false

                    if (this.location_page < this.getAllWarehouseLocations.last_page) {
                        this.loadMoreLocations(getLocationsPayload)
                    }
                } else {
                    this.locationsLists = []
                    this.lastLocationsListsCheck = []
                    this.fetchLocationsListsLoading = false
                }
            } catch(e) {
                this.fetchLocationsListsLoading = false
                this.notificationError(e)
                console.log(e)
            }
        },
        async loadMoreLocations(getLocationsPayload) {
            if (this.location_page < this.getAllWarehouseLocations.last_page) {
				this.location_page++
                getLocationsPayload.page = this.location_page

				try {
					await this.getPlaceStorableLocations(getLocationsPayload)

                    if (typeof this.getAllWarehouseLocations !== 'undefined' && 
                        this.getAllWarehouseLocations !== null && 
                        typeof this.getAllWarehouseLocations.results !== 'undefined' && 
                        Array.isArray(this.getAllWarehouseLocations.results) &&
                        this.getAllWarehouseLocations.results.length > 0) {
                        
                        let newloaddata = this.getAllWarehouseLocations.results.map(v => {
                            // let name = v.type === 'storage' ? v.row + v.rack + v.shelf : v.gate_name
                            // let name = v.row + v.rack + v.shelf
                            let name = v.label

                            return {
                                id: v.id,
                                name,
                                storable_count: v.storable_unit_count
                            }
                        })

                        this.locationsLists = [...this.locationsLists, ...newloaddata]

                        if (this.location_page < this.getAllWarehouseLocations.last_page) {
                            this.loadMoreLocations(getLocationsPayload)
                        }
                    } else {
                        this.locationsLists;
                    }
				} catch (e) {
					this.notificationError(e)
				}
			}
        },
        // call warehouse customers api
        async callWarehouseCustomerListsData() {
            let parmsWarehouseCustomers = {
				id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id,
				page: 1
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
					}
				}
			} catch(e) {
                this.fetchWarehouseCustomersLoading = false
				this.notificationError(e)
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

							if (this.current_page_is_whcustomers < this.getWarehouseCustomersDropdown.last_page) {
								this.loadMoreWarehouseCustomerData(parmsWarehouseCustomers)
							}
							
							this.setAllWarehouseCustomerLists(this.warehouseCustomerListsData)
						}
					} else {
						this.warehouseCustomerListsData
					}
				} catch (e) {
					this.notificationError(e)
				}
			}
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

                        let singlePayload = {  
                            wid: this.currentSelectedInboundOrder.warehouse_id,  
                            iid: this.currentSelectedInboundOrder.id 
                        }
                        await this.fetchSingleInbound(singlePayload)
                        await this.fetchDraftInbounds(dataWithPage)

                        this.onClickBreadcrumbs(3)
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
                        let singlePayload = {  
                            wid: this.currentSelectedInboundOrder.warehouse_id,  
                            iid: this.currentSelectedInboundOrder.id 
                        }
                        await this.fetchSingleInbound(singlePayload)
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
                        let singlePayload = {  
                            wid: this.currentSelectedInboundOrder.warehouse_id,  
                            iid: this.currentSelectedInboundOrder.id 
                        }
                        await this.fetchSingleInbound(singlePayload)
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
                        let singlePayload = {  
                            wid: this.currentSelectedInboundOrder.warehouse_id,  
                            iid: this.currentSelectedInboundOrder.id 
                        }
                        await this.fetchSingleInbound(singlePayload)
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
        // assigning employee/location
        assignEmployee(item, isSingle) {
            this.showAssignEmployeeDialog = true

            if (typeof isSingle !== 'undefined' && isSingle) {
                let { no_of_sku, total_expected_cartons, total_expected_units, data } = item
                data.no_of_sku = no_of_sku
                data.no_of_units = total_expected_units
                data.no_of_cartons = total_expected_cartons

                this.selectedInboundOrders.push(data)
            }
        },
        closeAssignEmployee() {
            this.showAssignEmployeeDialog = false
            this.selectedInboundOrders = []
        },
        async assignToLocation(item, isSingle) {
            this.showAssignLocationDialog = true
            
            if (typeof isSingle !== 'undefined' && isSingle) {
                let { no_of_sku, total_expected_cartons, total_expected_units, data } = item
                data.no_of_sku = no_of_sku
                data.no_of_units = total_expected_units
                data.no_of_cartons = total_expected_cartons

                this.selectedInboundOrders.push(data)
            }

            let currentWarehouse = this.$store.state.warehouse.currentWarehouse
            await this.fetchLocationsAssign(currentWarehouse.id)
        },
        closeAssignLocation() {
            this.showAssignLocationDialog = false
            this.selectedInboundOrders = []
        },
    },
    async mounted() {
        //set current page
        this.$store.dispatch("page/setPage", "inventory");

        this.fetchSingleInboundLoading = true
        let inbound_id = new URL(location.href).searchParams.get('inboundid')
        let warehouse_id = new URL(location.href).searchParams.get('warehouseid')
        this.linkData = { inbound_id, warehouse_id }

        if (inbound_id !== null && warehouse_id !== null) {
            let payload = { wid: warehouse_id, iid: inbound_id }

            try {
                await this.fetchSingleInbound(payload)
                this.fetchSingleInboundLoading = false
                await this.fetchSingleWarehouse(warehouse_id)

                if (typeof this.getSingleWarehouse !== 'undefined') {
                    this.$store.state.warehouse.currentWarehouse = this.getSingleWarehouse
                    let currentWarehouse = this.$store.state.warehouse.currentWarehouse

                    if (currentWarehouse !== null) {
                        if (currentWarehouse.warehouse_type === '3PL Provider') {
                            await this.callWarehouseCustomerListsData()
                            this.setWarehouseTypeHas3PL(true)            
                        }

                        if (!this.isWarehouseConnected && !this.isWarehouse3PL) {                           
                            await this.fetchWarehouseEmployees(currentWarehouse.id)
                        }
                    }
                }
            } catch(e) {
                this.notificationError(e)
                this.fetchSingleInboundLoading = false
            }
        }
    },
    updated() {}
}
</script>

<style lang="scss">
@import '@/assets/scss/buttons.scss';
@import '@/assets/scss/pages_scss/inventory/inbound/inboundView.scss';
@import "@/assets/scss/tables.scss";
</style>