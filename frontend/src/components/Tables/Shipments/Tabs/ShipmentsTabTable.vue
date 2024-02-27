<template>
    <div class="shipment-tabs-table-wrapper-component" v-resize="onResize">        
        <div class="default-view" v-if="currentViewTab === 0 || currentViewTab === 1 || currentViewTab === 2">
            <!-- FOR DESKTOP -->
            <v-data-table 
                :headers="headersShipment" 
                :items="getCurrentShipmentsData"
                mobile-breakpoint="769"
                :page="getCurrentShipmentPage"
                :items-per-page="getShipmentItemsPerPage"
                item-key="name"
                class="shipments-table"
                :id="getCurrentShipmentsData.length !== 0 ? '' : 'table-no-data'"
                v-bind:class="{
                    'no-data-table': (typeof getCurrentShipmentsData !== 'undefined' && getCurrentShipmentsData.length === 0),
                    'no-current-pagination' : getCurrentPaginationCountClass(),
                    'has-searched-data' : search !== '' && getCurrentShipmentsData.length === 0
                }"
                hide-default-footer
                style="box-shadow: none !important"
                @page-count="pageCount = $event"
                @click:row="handleClick"
                :item-class="itemRowBackground"
                fixed-header
                hide-default-header
                v-if="!isMobile"
                ref="my-table">

                <template v-slot:header="{ props: { headers } }">
                    <thead>
                        <tr>
                            <th v-for="(item, index) in headers" 
                                :key="index"
                                role="columnheader"
                                :aria-label="item.text"
                                scope="col"
                                v-bind:class="
                                    [item.sortable ? 'sortable' : '',
                                    item.align == ' d-none' ? 'd-none' : `text-${item.align}`]"
                                @click="item.sortable ? changeSort(item.field) : ''"
                                v-bind:style="`width: ${item.width}; min-width: ${item.width};`">

                                {{ item.text }}

                                <v-icon v-if="item.sortable" small>{{ shipmentIcon }}</v-icon>
                            </th>
                        </tr>  
                    </thead>
                </template>
                
                <template v-slot:no-data>
                    <div class="no-data-preloader" v-if="getShipmentLoadingStatus">
                        <v-progress-circular
                            :size="40"
                            color="#0171a1"
                            indeterminate>
                        </v-progress-circular>
                    </div>

                    <div v-if="!getShipmentLoadingStatus && getCurrentShipmentsData.length == 0" class="no-data-wrapper">
                        <div class="no-data-icon">
                            <img src="@/assets/icons/noShipmentData.svg" alt="" width="65px">
                        </div>

                        <div class="no-data-heading">
                            <p v-if="search === ''"> No Shipments </p>
                            <p v-if="search !== ''"> No Shipments found. Try searching another keyword.</p>
                        </div>
                    </div>
                </template>

                <template v-slot:[`item.ReferenceID`]="{ item }">
                <div class="departure-content-wrapper">
                    <div class="flag-wrapper">
                    <p style="margin-bottom: 0px">
                        {{ item.ReferenceID }}
                    </p>
                    </div>

                    <div>
                    <p style="color: #6D858F; margin-top: 2px;">
                        {{ item.customer_reference_number !== "null" && item.customer_reference_number !== null ? item.customer_reference_number :'N/A' }}
                    </p>
                    </div>
                </div>
                </template>

                <template v-slot:[`item.Departure`]="{ item }">
                    <div class="departure-content-wrapper">
                        <div class="flag-wrapper">
                            <p style="margin-bottom: 0px">
                                {{ item.Departure.location !== 'Not specified' ? item.Departure.location : 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <p style="color: #0171A1; margin-top: 2px;">
                                {{ item.Departure.etd !== 'Not Specified' ? item.Departure.etd : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </template>

                <template v-slot:[`item.mode`]="{ item }">
                    <div class="shipment-type" v-if="item.type !== null && item.type !== '' && item.type !== 'null'">
                        <span class="d-flex align-center mr-1">
                            <img v-if="item.mode == 'Ocean'" src="@/assets/icons/ocean.svg" width="20px" height="20px" />
                            <img v-if="item.mode == 'Air'" src="@/assets/icons/airplane.svg" width="20px" height="20px" />
                            <img v-if="item.mode == 'Truck'" src="@/assets/icons/truck.svg" width="20px" height="20px" />
                        </span>

                        <img v-if="item.type == 'LCL'" src="@/assets/images/small-container.svg" width="20px" height="20px" />
                        <img v-if="item.type == 'Air'" src="@/assets/images/airplane-shipment.svg" width="20px" height="20px" />
                        <img v-if="item.type == 'FCL'" src="@/assets/images/big-container.svg" width="20px" height="20px" />
                        <span style="padding-bottom:2px;" v-if="item.type == 'FCL' && item.container_num_list.length !== 0"> 
                            ({{ item.container_num_list.length }})
                        </span>
                    </div>

                    <div class="no-shipment-type" 
                        v-if="(item.type == null || item.type == '' || item.type == 'null') && item.external_shipment_tracking==0">
                        N/A
                    </div>
                    
                    <div class="shipment-type" v-if="item.external_shipment_tracking===1 && item.external_shipment_containers.length > 0 && (item.type == null || item.type == '' || item.type == 'null')">
                        <img src="@/assets/images/big-container.svg" />
                        <span style="padding-bottom:2px;">({{ item.external_shipment_containers.length }})</span>
                    </div>
                </template>

                <template v-slot:[`item.Arrival`]="{ item }">
                    <div class="arrival-content-wrapper">
                        <div class="flag-wrapper">
                            <p style="margin-bottom: 0px">
                                {{ item.Arrival.location !== 'Not specified' ? item.Arrival.location : 'N/A' }}
                            </p>
                        </div>
                        <p style="color: #0171A1; margin-top: 2px;">
                            {{ item.Arrival.eta !== 'Not Specified' ? item.Arrival.eta : 'N/A' }}
                        </p>
                    </div>
                </template>

                <template v-slot:[`item.Suppliers`]="{ item }">
                    <div class="supplier-desktop" :class="activeShipmentTab == 0 ? 'active-tab' : ''">
                        <span>{{ checkSuppliersName(item.Suppliers) }}</span>

                        <span v-if="item.Suppliers == null || item.Suppliers.length == 0">
                            <span>N/A</span>
                        </span>
                    </div>
                </template>

                <template v-slot:[`item.PO`]="{ item }">
                    <div class="po-num-desktop">
                        <p>
                            <span v-for="(name, index) in item.po_list" :key="index">
                                {{ name }}<template v-if="index + 1 < item.po_list.length">, </template>
                            </span>
                        </p>
                    </div>

                    <div v-if="item.po_list == null || item.po_list.length == 0">
                        <p> N/A </p>
                    </div>              
                </template>

                <template v-slot:[`item.Status`]="{ item }">
                    <div class="status d-flex" :class="getStatusClass(item.Status)">
                        <div class="d-flex t-shipment-status-wrapper">
                            <div style="margin-right: 6px;" :class="getShipmentStatusClass(item,'tracking')" 
                                v-if="typeof item.tracking_status !== 'undefined' && item.tracking_status !== ''" class="font-medium">
                                {{ item.tracking_status }}
                            </div>

                            <div style="margin-right: 2px !important;" class="font-medium" 
                                v-if="item.Status==='In transit - hold'">
                                <span style="color: #4A4A4A;">{{ "In Transit "}}</span>
                                <span style="color: #F93131;">{{" - Hold"}}</span>
                            </div>
                            <div style="margin-right: 2px !important;" class="font-medium" 
                                v-else-if="item.Status==='Partially discharged - released'">
                                <span style="color: #4A4A4A;" class="pr-1 font-medium">{{ "Partial Discharged - " }}</span>
                                <span style="color: #16B442;" class="font-medium">{{ "Released" }}</span>
                            </div>
                            <div style="margin-right: 2px; !important" class="font-medium" 
                                v-else-if="item.Status==='Partially discharged - hold'">
                                <span style="color: #4A4A4A;" class="pr-1 font-medium">{{ "Partial Discharged - " }}</span>
                                <span style="color: #F93131;" class="font-medium">{{ "Hold" }}</span>
                            </div>
                            <div style="margin-right: 2px !important;" class="font-medium" 
                                v-else :class="getShipmentStatusClass(item,'default')">
                                {{ ucFirst(item.Status.toLowerCase()) }}
                            </div>
                        </div>

                        <!-- <v-chip v-if="1==2" v-html="getStatus(item.Status)"></v-chip> -->
                    </div>
                </template>

                <template v-slot:[`item.todos`]="{  }">
                    <!-- static only for now -->
                    <div class="shipment-item-todos d-flex align-center justify-center">
                        <img src="../../../../assets/icons/to-dos-blue.svg" alt="" height="12px" width="12px"> 
                        <span class="todos-count ml-1 font-medium" style="color: #0171A1;">(1)</span>
                    </div>     
                </template>

                <template v-slot:[`item.actions`]="{ item }">
                    <div class="shipment-action-button d-flex align-end justify-end mr-1">
                        <button class="btn-white" @click="handleClick(item)">
                            <img src="../../../../assets/icons/visibility-po.svg" alt="" height="16px" width="16px">
                        </button>
                        <div class="three-dots-wrapper">
                            <v-menu
                                offset-y
                                bottom
                                left
                                content-class="pending-dropdown-container"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <button v-bind="attrs" v-on="on" class="shipment-three-dots-container">
                                        <custom-icon
                                            iconName="three-dots"
                                            color="#0171A1"
                                            width="11"
                                            height="3"
                                        />
                                    </button>
                                </template>
                                <v-list>
                                    <v-list-item>
                                        <v-list-item-title class="k-fw-sm k-font-inter-regular k-dark-blue" @click="markShipmentCompletedDialog(item)">
                                            Mark As Completed
                                        </v-list-item-title>
                                    </v-list-item>
                                    <!-- <v-list-item>
                                        <v-list-item-title class="k-fw-sm k-font-inter-regular">
                                            Edit Shipment
                                        </v-list-item-title>
                                    </v-list-item> -->
                                    <v-list-item>
                                        <v-list-item-title class="k-fw-sm k-font-inter-regular k-red" @click="cancelRequestForm(item.id)">
                                            Request Cancellation
                                        </v-list-item-title>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </div>
                    </div>     
                </template>
            </v-data-table>

            <!-- FOR MOBILE -->
            <v-data-table 
                :headers="headersShipment" 
                :items="getCurrentShipmentsData"
                mobile-breakpoint="769"
                :page="getCurrentShipmentPage"
                :items-per-page="getShipmentItemsPerPage"
                item-key="name"
                class="table-mobile shipments-table-mobile"
                v-bind:class="{
                    'no-data-table': (typeof getCurrentShipmentsData !== 'undefined' && getCurrentShipmentsData.length === 0),
                    'no-current-pagination' : getCurrentPaginationCountClass(),
                    'has-searched-data' : search !== '' && getCurrentShipmentsData.length === 0
                }"
                :id="getCurrentShipmentsData.length !== 0 ? '' : 'table-no-data'"
                hide-default-footer
                style="box-shadow: none !important"
                @page-count="pageCount = $event"
                @click:row="handleClick"
                :item-class="itemRowBackground"
                v-if="isMobile"
                ref="my-table">
                
                <template v-slot:no-data>
                    <div class="no-data-preloader" v-if="getShipmentLoadingStatus">
                        <v-progress-circular
                            :size="40"
                            color="#0171a1"
                            indeterminate
                            v-if="getShipmentLoadingStatus">
                        </v-progress-circular>
                    </div>

                    <div v-if="!getShipmentLoadingStatus && getCurrentShipmentsData.length == 0" class="no-data-wrapper">
                        <div class="no-data-icon">
                            <img src="@/assets/icons/noShipmentData.svg" alt="" width="65px">
                        </div>

                        <div class="no-data-heading">
                            <p v-if="activeTab == 1 && search === ''"> No Shipments </p>
                            <p v-if="activeTab == 1 && search !== ''"> No Shipments found. <br/> Try searching another keyword.</p>
                        </div>
                    </div>
                </template>

                <template v-slot:[`item.ReferenceID`]="{ item, index }">
                    <div class="table-mobile-data mobile-reference k-flex k-items-start">
                        <div class="mobile-reference-content k-flex k-flex-col k-items-start">
                            <span class="k-flex k-items-start">
                                <span class="mr-2">{{ item.ReferenceID }}</span>

                                <div class="mobile-mode d-flex align-center">
                                    <span class="mr-2 d-flex align-center">
                                        <img v-if="item.mode == 'Ocean'" src="@/assets/icons/ocean.svg" width="20px" height="18px" />
                                        <img v-if="item.mode == 'Air'" src="@/assets/icons/airplane.svg" width="20px" height="18px" />
                                        <img v-if="item.mode == 'Truck'" src="@/assets/icons/truck.svg" width="20px" height="18px" />
                                    </span>

                                    <div class="d-flex align-center mr-1" 
                                        v-if="item.type !== null && item.type !== '' && item.type !== 'null'">                                    
                                        <img v-if="item.type == 'LCL'" src="@/assets/images/small-container.svg" width="20px" height="18px" />
                                        <img v-if="item.type == 'Air'" src="@/assets/images/airplane-shipment.svg" width="20px" height="18px" />
                                        <img v-if="item.type == 'FCL'" src="@/assets/images/big-container.svg" width="20px" height="18px" />
                                        <span style="margin-left: 6px; font-weight: 500;" class="font-regular"
                                            v-if="item.type == 'FCL' && item.container_num_list.length !== 0"> 
                                            ({{ item.container_num_list.length }})
                                        </span>
                                    </div>
                                </div>
                            </span>
                        </div>

                        <div class="shipment-item-todos-mobile d-flex align-center justify-center" 
                            v-if="index !== 1 && index !== 2">
                            <img src="../../../../assets/icons/to-dos-blue.svg" alt="" height="12px" width="12px"> 
                            <span class="todos-count ml-2 font-medium" style="color: #0171A1;">(1)</span>
                        </div> 

                        <!-- static only for viewing purposes -->
                        <div class="shipment-item-todos-mobile to-dos-red d-flex align-center justify-center" v-if="index === 1">
                            <img src="../../../../assets/icons/to-dos-red.svg" alt="" height="12px" width="12px"> 
                            <span class="todos-count ml-2 font-medium" style="color: #F93131;">(1)</span>
                        </div> 

                        <div class="shipment-item-todos-mobile d-flex align-center justify-center" v-if="index === 2">
                            <img src="../../../../assets/icons/to-dos-orange.svg" alt="" height="12px" width="12px"> 
                            <span class="todos-count ml-2 font-medium" style="color: #D68A1A;">(1)</span>
                        </div> 
                    </div>
                </template>

                <template v-slot:[`item.Departure`]="{ item }">
                    <div class="table-mobile-data-content">
                        <div class="table-mobile-data mb-2">
                            <div class="mobile-departure-wrapper">
                                <span style="color: #6D858F;">Departure</span>
                            </div>

                            <div class="mobile-departure-wrapper" style="text-align: left !important;">
                                <div class="flag-wrapper">
                                    <p style="margin-bottom: 0px;">
                                        <span>
                                            {{ item.Departure.location !== 'Not specified' ? item.Departure.location : 'N/A' }}

                                            <span class="departure-date ml-1" style="color: #0171A1;">
                                                {{ item.Departure.etd !== 'Not Specified' ? item.Departure.etd : 'N/A' }}
                                            </span>
                                        </span>                                    
                                    </p>
                                </div>
                            </div>      
                        </div>

                        <div class="table-mobile-data mb-2">
                            <div class="mobile-arrival-wrapper">
                                <span style="color: #6D858F;">Arrival</span>
                            </div>

                            <div class="arrival-wrapper" style="text-align: right !important;">
                                <div class="flag-wrapper">
                                    <p style="margin-bottom: 0px">
                                        <span>
                                            {{ item.Arrival.location !== 'Not specified' ? item.Arrival.location : 'N/A' }}
                                            
                                            <span class="arrival-date ml-1" style="color: #0171A1;">
                                                {{ item.Arrival.eta !== 'Not Specified' ? item.Arrival.eta : 'N/A' }}
                                            </span>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="table-mobile-data mb-2">
                            <div class="mobile-pos-wrapper">
                                <span style="color: #6D858F;">POs</span>
                            </div>

                            <div class="po-num-mobile">
                                <p>
                                    <span v-for="(name, index) in item.po_list" :key="index">
                                        <span v-if="index === 0 || index === 1">
                                            {{ name }}<template v-if="index + 1 < item.po_list.length">, </template>
                                        </span>
                                    </span>

                                    <span v-if="item.po_list.length > 2" style="color: #0171A1;">
                                        +{{ item.po_list.length - 2 }} Other
                                    </span>
                                </p>
                            </div>

                            <div v-if="item.po_list == null || item.po_list.length == 0">
                                <p> {{ item.is_tracking_shipment ? '' : 'N/A'}} </p>
                            </div>
                        </div>
                    </div>
                </template>

                <template v-slot:[`item.mode`]="{ item }">
                    <div class="k-flex mb-1 status-mobile" :class="getStatusClass(item.Status)">
                        <div class="d-flex t-shipment-status-wrapper" v-if="(windowWidth>768 || windowWidth<=375)">
                            <div style="margin-right: 4px !important;" :class="getShipmentStatusClass(item,'tracking')" 
                                v-if="typeof item.tracking_status !== 'undefined' && item.tracking_status !== ''" class="font-medium">
                                {{ item.tracking_status }}
                            </div>

                            <div class="font-medium mr-0" 
                                v-if="item.Status==='In transit - hold'">
                                <span style="color: #4A4A4A;">{{ "In Transit "}}</span>
                                <span style="color: #F93131;">{{" - Hold"}}</span>
                            </div>
                            <div class="font-medium mr-0" 
                                v-else-if="item.Status==='Partially discharged - released'">
                                <span style="color: #4A4A4A;" class="pr-1 font-medium">{{ "Partial Discharged - " }}</span>
                                <span style="color: #16B442;" class="font-medium">{{ "Released" }}</span>
                            </div>
                            <div class="font-medium mr-0" 
                                v-else-if="item.Status==='Partially discharged - hold'">
                                <span style="color: #4A4A4A;" class="pr-1 font-medium">{{ "Partial Discharged - " }}</span>
                                <span style="color: #F93131;" class="font-medium">{{ "Hold" }}</span>
                            </div>
                            <div class="font-medium mr-0" 
                                v-else :class="getShipmentStatusClass(item,'default')">
                                {{ ucFirst(item.Status.toLowerCase()) }}
                            </div>
                        </div>
                        
                        <!-- <div class="status-mobile k-flex mr-1" :class="getStatusClass(item.Status)">
                            <v-chip v-html="getStatus(item.Status)"></v-chip>
                        </div> -->
                    </div>
                </template>
            </v-data-table>
        </div>

        <div class="compact-view" v-if="currentViewTab === 3">
            <div id="data-grid-demo">
                <DxDataGrid
                    ref="dataGridReference"
					id="gridContainer"
                    :show-borders="true"
                    :data-source="getCurrentShipmentsData">

                    <DxFilterRow :visible="isFilterEnabled" />

                    <DxColumn data-field="ReferenceID"
                        :width="100"
                        caption="Reference"
                        :allow-sorting="false">
                    </DxColumn>

                    <DxColumn data-field="Departure"
                        :width="130"
                        caption="Departure"
                        :allow-sorting="false"
                        cell-template="departure-template">
                    </DxColumn>

                    <DxColumn data-field="DepartureDate"
                        :width="150"
                        caption="Departure Date"
                        :allow-sorting="false"
                        cell-template="departure-template-date">
                    </DxColumn>

                    <DxColumn data-field="mode"
                        :width="75"
                        caption="Mode"
                        :allow-sorting="false"
                        cell-template="mode-template">
                    </DxColumn>

                    <DxColumn data-field="type"
                        :width="75"
                        caption="Type"
                        :allow-sorting="false"
                        cell-template="type-template">
                    </DxColumn>

                    <DxColumn data-field="Container"
                        :width="100"
                        caption="Container"
                        :allow-sorting="false"
                        cell-template="container-template">
                    </DxColumn>

                    <DxColumn data-field="Arrival"
                        :width="130"
                        caption="Arrival"
                        :allow-sorting="false"
                        cell-template="arrival-template">
                    </DxColumn>

                    <DxColumn data-field="ArrivalDate"
                        :width="130"
                        caption="Arrival Date"
                        :allow-sorting="false"
                        cell-template="arrival-template-date">
                    </DxColumn>

                    <DxColumn data-field="po_list"
                        :width="200"
                        caption="PO"
                        :allow-sorting="false"
                        cell-template="po-template">
                    </DxColumn>

                    <DxColumn data-field="Status"
                        :width="210"
                        caption="Status"
                        :allow-sorting="false"
                        cell-template="status-template">
                    </DxColumn>

                    <DxColumn cell-template="action-cell"
						:width="100"
						type="buttons"
						caption=""
						:allow-filtering="false" />

					<DxScrolling column-rendering-mode="infinite" />

                    <template #departure-template="{ data }">
						<div class="template-data">
                            {{ data.data.Departure.location === 'Not specified' ? 'N/A' : data.data.Departure.location }}
                        </div>
					</template>

                    <template #departure-template-date="{ data }">
						<div class="template-data">
                            <span :style="`color: ${data.data.Departure.etd === 'Not Specified' ? '#4a4a4a' : '#0171A1'}`">
                                {{ data.data.Departure.etd === 'Not Specified' ? 'N/A' : data.data.Departure.etd }}
                            </span>                            
                        </div>
					</template>

                    <template #mode-template="{ data }">
						<div class="template-data d-flex justify-center align-center">
                            <div class="shipment-type" v-if="data.data.mode !== null && data.data.mode !== '' && data.data.mode !== 'null'">
                                <span class="d-flex align-center mr-1">
                                    <img v-if="data.data.mode == 'Ocean'" src="@/assets/icons/ocean.svg" width="20px" height="20px" />
                                    <img v-if="data.data.mode == 'Air'" src="@/assets/icons/airplane.svg" width="20px" height="20px" />
                                    <img v-if="data.data.mode == 'Truck'" src="@/assets/icons/truck.svg" width="20px" height="20px" />
                                </span>
                            </div>

                            <div class="no-shipment-type" 
                                v-if="(data.data.mode == null || data.data.mode == '' || data.data.mode == 'null') && data.data.external_shipment_tracking==0">
                                N/A
                            </div>
                        </div>
					</template>

                    <template #type-template="{ data }">
						<div class="template-data d-flex justify-center align-center">
                            <div class="shipment-type d-flex justify-center align-center" v-if="data.data.type !== null && data.data.type !== '' && data.data.type !== 'null'">
                                <img v-if="data.data.type == 'LCL'" src="@/assets/images/small-container.svg" width="20px" height="20px" />
                                <img v-if="data.data.type == 'Air'" src="@/assets/images/airplane-shipment.svg" width="20px" height="20px" />
                                <img v-if="data.data.type == 'FCL'" src="@/assets/images/big-container.svg" width="20px" height="20px" />
                            </div>

                            <div class="no-shipment-type" v-if="(data.data.type == null || data.data.type == '' || data.data.type == 'null') && data.data.external_shipment_tracking==0">
                                N/A
                            </div>
                        </div>
					</template>

                    <template #container-template="{ data }">
						<div class="template-data d-flex justify-center align-center">
                            {{ data.data.container_num_list.length }}
                        </div>
					</template>

                    <template #arrival-template="{ data }">
						<div class="template-data">
                            {{ data.data.Arrival.location === 'Not specified' ? 'N/A' : data.data.Arrival.location }}
                        </div>
					</template>

                    <template #arrival-template-date="{ data }">
						<div class="template-data">
                            <span :style="`color: ${data.data.Arrival.eta === 'Not Specified' ? '#4a4a4a' : '#0171A1'}`">
                                {{ data.data.Arrival.eta === 'Not Specified' ? 'N/A' : data.data.Arrival.eta }}
                            </span>                            
                        </div>
					</template>

                    <template #po-template="{ data }">
						<div class="template-data">
                            <div class="po-num-desktop">
                                <p class="mb-0">
                                    <span v-for="(name, index) in data.data.po_list" :key="index">
                                        {{ name }}<template v-if="index + 1 < data.data.po_list.length">, </template>
                                    </span>
                                </p>
                            </div>

                            <div v-if="data.data.po_list == null || data.data.po_list.length == 0">
                                <p class="mb-0"> N/A </p>
                            </div>     
                        </div>
					</template>

                    <template #status-template="{ data }">
						<div class="status d-flex compact" :class="getStatusClass(data.data.Status)" 
                            style="height: 30px !important; padding:0;">
                            <div class="d-flex t-shipment-status-wrapper">
                                <div style="margin-right: 2px !important;" class="font-medium" 
                                    v-if="data.data.Status==='In transit - hold'">
                                    <span style="color: #4A4A4A;">{{ "In Transit "}}</span>
                                    <span style="color: #F93131;">{{" - Hold"}}</span>
                                </div>
                                <div style="margin-right: 2px !important;" class="font-medium" 
                                    v-else-if="data.data.Status==='Partially discharged - released'">
                                    <span style="color: #4A4A4A;" class="pr-1 font-medium">{{ "Partial Discharged - " }}</span>
                                    <span style="color: #16B442;" class="font-medium">{{ "Released" }}</span>
                                </div>
                                <div style="margin-right: 2px; !important" class="font-medium" 
                                    v-else-if="data.data.Status==='Partially discharged - hold'">
                                    <span style="color: #4A4A4A;" class="pr-1 font-medium">{{ "Partial Discharged - " }}</span>
                                    <span style="color: #F93131;" class="font-medium">{{ "Hold" }}</span>
                                </div>
                                <div style="margin-right: 2px !important;" class="font-medium" 
                                    v-else :class="getShipmentStatusClass(data.data,'default')">
                                    {{ ucFirst(data.data.Status.toLowerCase()) }}
                                </div>
                            </div>
                        </div>
					</template>

                    <template #action-cell="{ data }">
                        <div class="shipment-action-button d-flex align-center justify-center">
                            <button class="btn-white" @click="handleClick(data.data)"
                                style="width: 34px !important; height: 34px !important;">
                                <img src="../../../../assets/icons/visibility-po.svg" alt="" height="16px" width="16px">
                            </button>
                            <div class="three-dots-wrapper">
                                <v-menu offset-y bottom left content-class="pending-dropdown-container">
                                    <template v-slot:activator="{ on, attrs }">
                                        <button v-bind="attrs" v-on="on" class="shipment-three-dots-container"
                                        style="width: 34px !important; height: 34px !important;">
                                            <custom-icon
                                                iconName="three-dots"
                                                color="#0171A1"
                                                width="11"
                                                height="3" />
                                        </button>
                                    </template>
                                    <v-list>
                                        <v-list-item>
                                            <v-list-item-title class="k-fw-sm k-font-inter-regular k-dark-blue" @click="markShipmentCompletedDialog(data.data)">
                                                Mark As Completed
                                            </v-list-item-title>
                                        </v-list-item>
                                        <!-- <v-list-item>
                                            <v-list-item-title class="k-fw-sm k-font-inter-regular">
                                                Edit Shipment
                                            </v-list-item-title>
                                        </v-list-item> -->
                                        <v-list-item>
                                            <v-list-item-title class="k-fw-sm k-font-inter-regular k-red" @click="cancelRequestForm(data.data.id)">
                                                Request Cancellation
                                            </v-list-item-title>
                                        </v-list-item>
                                    </v-list>
                                </v-menu>
                            </div>
                        </div>  
                    </template>
                </DxDataGrid>
            </div>
        </div>

        <div class="pagination-wrapper pt-3" 
            v-if="activeShipmentTab === 1 && getCurrentShipmentsData !== 'undefined' && 
            search == '' && pagination.shipmentTab.total > 1">
            <v-pagination
                v-model="pagination.shipmentTab.current_page"
                :length="pagination.shipmentTab.total"
                prev-icon="Previous"
                next-icon="Next"
                :total-visible="!isMobile ? '11' : '5' "
                @input="handlePageChange">
            </v-pagination>
        </div> 
        <!-- v-if="activeShipmentTab === 1 && getCurrentShipmentsData !== 'undefined' && 
        getCurrentShipmentsData.length !== 0 && search == '' && pagination.shipmentTab.total > 1" -->

        <div class="pagination-wrapper pt-3" 
            v-if="activeShipmentTab === 1 && getCurrentShipmentsData !== 'undefined' && 
            search !== '' && getShipmentPagination > 1">
            <v-pagination
                v-model="getCurrentShipmentPage"
                :length="getShipmentPagination"
                prev-icon="Previous"
                next-icon="Next"
                :total-visible="!isMobile ? '11' : '5' "
                @input="handlePageSearched">
            </v-pagination>
        </div> 
        <!-- v-if="activeShipmentTab === 1 && getCurrentShipmentsData !== 'undefined' && getCurrentShipmentsData.length > 0 && search !== ''" -->

        <ConfirmDialog :dialogData.sync="openCancelRequestForm">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Request Cancellation</h2>
			</template>

			<template v-slot:dialog_content>
				<p>
					Do you want to request cancellation for this Shipment?
				</p>
                <div class="form-label mt-3">
                    <span class="cancel-reason-label">REASON FOR CANCELLATION</span>
                </div>
                <v-textarea
                    :rows="4"
                    outlined
                    placeholder="Type reason for cancellation"
                    hide-details="auto"
                    class="text-fields"
                    required
                    v-model="cancel_reason"
                >
                </v-textarea>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" text @click="submitCancelRequestForm()" :disabled="getCancelShipmentLoading">
					<span v-if="getCancelShipmentLoading">Cancel Request Processing...</span>
					<span v-else>Cancel Request</span>
				</v-btn>
				<v-btn class="btn-white" text @click="openCancelRequestForm = false" :disabled="getCancelShipmentLoading">
					Close
				</v-btn>
			</template>
		</ConfirmDialog>

        <ConfirmDialog :dialogData.sync="markShipmentDialog">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Mark Completed & Stop Tracking</h2>
			</template>

			<template v-slot:dialog_content>
				<p>
					If you mark this shipment as completed, we will stop tracking of this particular shipment. Are you sure?
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" text @click="submitMarkShipmentCompleted(markCompletedShipment)" :disabled="getmarkShipmentCompletedLoading">
					<span>Mark Completed</span>
				</v-btn>
				<v-btn class="btn-white" text @click="markShipmentDialog = false" :disabled="getmarkShipmentCompletedLoading">
					Close
				</v-btn>
			</template>
		</ConfirmDialog>

    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import globalMethods from '../../../../utils/globalMethods'
import _ from 'lodash';
import CustomIcon from "@/components/Icons/CustomIcon";
import ConfirmDialog from "@/components/Dialog/GlobalDialog/ConfirmDialog.vue";
import DxDataGrid, {
	DxColumn,
	// DxEditing,
	DxScrolling,
	// DxLookup,
	// DxPaging,
	DxFilterRow,
	// DxHeaderFilter,
} from 'devextreme-vue/data-grid';

export default {
    props: ['shipments', 'activeTab', 'isMobile', 'search', 'paginationData', 'shipmentIcon', 'currentViewTab'],
    components: {
        CustomIcon,
        ConfirmDialog,
        DxDataGrid,
        DxColumn,
        DxFilterRow,
        DxScrolling
    },
    data: () => ({
        pageCount: 0,
        windowWidth: window.innerWidth,
        trackingStatusMap: [
            {
                class: 'manual-tracking',
                value: 'Manual Tracking'
            },
            {
                class: 'auto-tracking',
                value: 'Auto Tracking'
            },
            {
                class: 'auto-tracked',
                value: 'Auto Tracked'
            },
            {
                class: 'manually-tracked',
                value: 'Manually Tracked'
            },
            {
                class: 'not-tracking',
                value: 'Not Tracking'
            },
            {
                class: 'past-last-free-day',
                value: 'Past Last Free Day'
            },
            {
                class: 'discharged-released',
                value: 'Discharged - released'
            },
            {
                class: 'in-transit-hold',
                value: 'In transit - hold'
            },
            {
                class: 'in-transit-released',
                value: 'In transit - released'
            },
            {
                class: 'discharged-hold',
                value: 'Discharged - Hold'
            },
            {
                class: 'partially-discharged',
                value: 'Partially discharged'
            }
        ],
        headersShipment: [
            {
                text: "Ref#/Cus Ref",
                align: "start",
                sortable: false,
                value: "ReferenceID",
                width: "10%",
                fixed: true
            },
            {
                text: "Departure",
                align: "start",
                value: "Departure",
                sortable: true,
                width: "13%", 
                fixed: true,
                field: 'etd',
            },
            {
                text: "Mode/Type",
                align: "start",
                value: "mode",
                sortable: false,
                width: "8%",
                fixed: true
            },
            {
                text: "Arrival",
                align: "start",
                value: "Arrival",
                sortable: true,
                width: "15%", 
                fixed: true,
                field: 'eta'
            },
            {
                text: "Suppliers",
                align: "start",
                value: "Suppliers",
                sortable: false,
                width: "16%",
                fixed: true
            },
            {
                text: "PO",
                align: "start",
                value: "PO",
                sortable: false,
                width: "12%",
                fixed: true
            },
            {
                text: "Status",
                value: "Status",
                align: 'start',
                sortable: false,
                width: "auto",
                fixed: true
            },
            /*
            {
                text: "ToDos",
                value: "todos",
                align: 'center',
                sortable: false,
                width: "5%",
                fixed: true
            },*/
            {
                text: "",
                value: "actions",
                align: 'end',
                sortable: false,
                width: "5%",
                fixed: true
            },
            {
                text: "Container",
                align: ' d-none',
                sortable: false,
                value: "container_num_list",
                width: "0",
                fixed: true
            },
            {
                text: "Mbl",
                align: ' d-none',
                sortable: false,
                value: "mbl_num",
                width: "0",
                fixed: true
            },
            {
                text: "Hbl",
                align: ' d-none',
                sortable: false,
                value: "hbl_num",
                width: "0",
                fixed: true
            },
            {
                text: "Ams",
                align: ' d-none',
                sortable: false,
                value: "ams_num",
                width: "0",
                fixed: true
            },
        ],
        openCancelRequestForm: false,
        cancel_reason: '',
        markShipmentDialog: false,
        cancelShipmentItemId: null,
        markCompletedShipment: null,
        // 
        isFilterEnabled: false
    }),
    computed: {
        ...mapGetters([
            "getAllShipments",
            "getShipmentLoadingStatus",
            "getSearchedShipments",
            "getSearchedShipmentLoading",
            "getSearchedShipmentPagination",
            "getCancelShipmentLoading",
            "getmarkShipmentCompletedLoading",
        ]),
        activeShipmentTab: {
            get() {
                return this.activeTab
            },
            set(value) {
                this.$emit('update:activeTab', value)
            }
        },
        pagination: {
            get() {
                return this.paginationData
            },
            set(value) {
                this.$emit('update:paginationData', value)
            }
        },
        getCurrentShipmentsData() {
            let filteredShipments = []

            if (typeof this.getSearchedShipments !== 'undefined' && this.getSearchedShipments !== null) {
                if (this.search !== '' && this.getSearchedShipments.tab === 'shipments') {
                // if (this.search !== '' && this.getSearchedShipments.tab === 'shipments-completed-merge') {
                    filteredShipments = this.getSearchedShipments.shipments
                    //return this.getSearchedShipments.shipments
                } else {
                    filteredShipments = this.shipments
                    //return this.shipments
                }
            } else {
                filteredShipments = this.shipments
                //return this.shipments
            }

            if (filteredShipments.length > 0) {
                filteredShipments.map((fs, key ) => {
                    filteredShipments[key].external_shipment = 0
                    filteredShipments[key].external_shipment_tracking = 0
                    if (!Array.isArray(fs.po_list)) {
                        filteredShipments[key].po_list = []
                    }

                    if (fs.is_tracking_shipment==1) {
                        filteredShipments[key].external_shipment = 1
                    }
                    if (typeof fs.terminal_fortynine!=='undefined' && fs.terminal_fortynine!==null && typeof fs.terminal_fortynine.attributes!=='undefined') {
                        
                        let getAttributes = fs.terminal_fortynine.attributes
                        let getContainers = fs.terminal_fortynine.containers
                        if (getAttributes !=='' && getAttributes!==null && fs.is_tracking_shipment == 1) {
                            filteredShipments[key].external_shipment_tracking = 1
                            filteredShipments[key].external_shipment_containers = []
                        }

                        if (getContainers!=='undefined' ) {
                            getContainers = JSON.parse(getContainers)
                            filteredShipments[key].external_shipment_containers = getContainers
                            // console.log(getContainers)
                        }
                    }
                })
            }

            return filteredShipments
        },
        getCurrentShipmentPage: {
            get() {
                if (typeof this.getSearchedShipments !== 'undefined' && this.getSearchedShipments !== null) {
                    if (this.search !== '' && this.getSearchedShipments.tab === 'shipments') {
                    // if (this.search !== '' && this.getSearchedShipments.tab === 'shipments-completed-merge') {
                        return this.getSearchedShipmentPagination.current_page
                    } else {
                        return this.pagination.shipmentTab.current_page
                    }
                } else {
                    return this.pagination.shipmentTab.current_page
                }
            },
            set(value) {
                if (typeof this.getSearchedShipments !== 'undefined' && this.getSearchedShipments !== null) {
                    if (this.search !== '' && this.getSearchedShipments.tab === 'shipments') {
                    // if (this.search !== '' && this.getSearchedShipments.tab === 'shipments-completed-merge') {
                        this.$store.state.shipment.searchedShipmentsPagination.current_page = value
                    } else {
                        this.$emit('update:paginationData', value)

                    }
                } else {
                    this.$emit('update:paginationData', value)
                }
            }
        },
        getShipmentItemsPerPage() {
            if (typeof this.getSearchedShipments !== 'undefined' && this.getSearchedShipments !== null) {
                if (this.search !== '' && this.getSearchedShipments.tab === 'shipments') {
                // if (this.search !== '' && this.getSearchedShipments.tab === 'shipments-completed-merge') {
                    return this.getSearchedShipmentPagination.per_page
                } else {
                    return this.pagination.shipmentTab.per_page
                }
            } else {
                return this.pagination.shipmentTab.per_page
            }
        },
        getShipmentPagination() {
            if (typeof this.getSearchedShipments !== 'undefined' && this.getSearchedShipments !== null) {
                if (this.search !== '' && this.getSearchedShipments.tab === 'shipments') {
                // if (this.search !== '' && this.getSearchedShipments.tab === 'shipments-completed-merge') {
                    return this.getSearchedShipmentPagination.total
                } else {
                    return this.pagination.shipmentTab.total
                }
            } else {
                return this.pagination.shipmentTab.total
            }
        }
    },
    mounted() {
        //set current page
        this.$store.dispatch("page/setPage", "shipments")
    },
    methods: {
        ...mapActions([
            "fetchShipments",
            "setShipmentOrder",
            "fetchShipmentsSearched",
            "cancelShipment",
            "markShipmentCompleted",
            "fetchCompletedShipments",
        ]),
        ...globalMethods,
        ucFirst(str) {
            let pieces = str.split(" ")
            for ( let i = 0; i < pieces.length; i++ ) {
                let j = pieces[i].charAt(0).toUpperCase()
                pieces[i] = j + pieces[i].substr(1)
            }
            return pieces.join(" ")
        },
        getShipmentStatusClass(item, type) {
            let { tracking_status, Status } = item

            let setValue = (type ==='tracking') ? tracking_status : Status
            let setClass = _.find(this.trackingStatusMap, e => e.value === setValue)
            setClass = (typeof setClass!=='undefined') ? setClass.class : ''
            
            if (setClass === 'not-tracking' && item.Status === 'Past last free day')
                setClass = 'not-tracking-past'
            return setClass
        },
        getCurrentPaginationCountClass() {
            if (this.search === '') {
                return typeof this.pagination.shipmentTab.total !== 'undefined' ? this.pagination.shipmentTab.total <= 1 : false
            } else {
                return typeof this.getShipmentPagination !== 'undefined' ? this.getShipmentPagination <= 1 : false
            }
        },
        handleClick(value) {
            this.$emit('handleClick', value)
        },
        onResize() {
            this.windowWidth = window.innerWidth
        },
        async changeSort(field) {
            this.$emit('sort', 1, field)
        },
        itemRowBackground: function (item) {
            return item.Status == 'Past last free day' ? 'light-red' : ''
        },
        getStatus(status) {
            if (status !== null) {
                if (status == 'In transit - hold') {
                    return "<span class='chip-text'>In Transit - <span>Pending Release</span><span>"

                } else if (status == 'In Transit - Released') {
                    return "<span class='chip-text'>In Transit - <span class='green--text'>Released</span><span>"

                } else if (status == 'Partially discharged - hold') {
                    return "<span class='chip-text'>Partially Discharged - <span class='red--text'>Hold</span><span>"

                } else if (status == 'Partially discharged - released') {
                    return "<span class='chip-text'>Partially Discharged - <span class='green--text'>Released</span><span>"

                } else if (status == 'Discharged - Hold') {
                    return `<span class='chip-text red--text'>${status}<span>`

                } else if (status == 'Completed' || status == 'Empty In' || status == 'Discharged - released') {
                    return `<span class='chip-text green--text completed'>${status}<span>`

                } else if (status == 'Past last free day') {
                    return `<span class='chip-text red--text'>${status}<span>`

                } else {
                    return `<span class='chip-text'>${status}</span>`
                }
            }

        },
        getStatusClass(status) {
            if (status == 'Past last free day') {
                return 'Past-day'
            } else if (status == 'Empty In') {
                return 'Empty-in'
            } else if (status == 'Full Out') {
                return 'Full-out'
            } else if (status == 'Discharged - Hold') {
                return 'Discharged-hold'
            } else if (status == 'Discharged - released') {
                return 'Discharged-released'
            } else if (status == 'Partially discharged - hold') {
                return 'Partial-hold'
            } else if (status == 'Partially discharged - released') {
                return 'Partial-released'
            } else {
                return status
            }
        },
        async handlePageChange(page) {
            this.$emit('handlePageChange', page)
            this.handleScrollToTop()
        },
        async handlePageSearched(page) {
            let data = { 
                page,
                tab: 'shipments'
                // tab: 'shipments-completed-merge'
            }

            this.$emit('handlePageSearched', data)
            this.handleScrollToTop()
        },
        handleScrollToTop() {
            var table = this.$refs['my-table'];
            var wrapper = table.$el.querySelector('div.v-data-table__wrapper');
            
            this.$vuetify.goTo(table); // to table
            this.$vuetify.goTo(table, {container: wrapper}); // to header
        },
        cancelRequestForm(item) {
            this.cancelShipmentItemId = item;
            this.openCancelRequestForm = true;
            this.cancel_reason = '';
        },
        submitCancelRequestForm() {
            if(this.cancelShipmentItemId && this.cancelShipmentItemId !== 0 && this.cancel_reason !== '') {
				let payloadObject = {'shipmentId': this.cancelShipmentItemId, 'cancel_reason': this.cancel_reason, 'type': 'shipment'}
				this.cancelShipment(payloadObject)
					.then((response) => {
						this.openCancelRequestForm = false;
						this.notificationErrorCustom(response.data.message);
					})
					.catch((e) => {
						console.log(e);
                        this.openCancelRequestForm = false;
                        this.notificationErrorCustom('SOMETHING WENT WRONG!');
					})
			} else {
                this.notificationErrorCustom('Please fill the reason for cancellation field.');
            }
        },
        markShipmentCompletedDialog(item){
            this.markShipmentDialog = true
            this.markCompletedShipment = item
        },
        async submitMarkShipmentCompleted(item){
            this.markShipmentCompleted(item.id).then(()=>{
                this.notificationMessage(`Reference #${item.ReferenceID} has been marked as completed`)
                this.callShipmentAPI();
                this.markShipmentDialog = false
            }).catch((e) => {
                console.log(e);
            });
            
        },
        callShipmentAPI(){
            let storePagination = this.$store.state.shipment.shipmentsPagination;
            let storeShipmentTabData = this.$store.state.shipment;

            let payloadShipments = {
                page: (storePagination.shipmentTab.currentTab === 1) ? storePagination.shipmentTab.current_page : 1,
                order: storeShipmentTabData.shipmentOrder.order
            }

            let payloadCompleted = {
                page: (storePagination.completedTab.currentTab === 2) ? storePagination.completedTab.current_page : 1,
                order: storeShipmentTabData.completedOrder.order
            }

            this.fetchShipments(payloadShipments);
            this.fetchCompletedShipments(payloadCompleted);
        },
        checkSuppliersName(suppliers){
            if(Array.isArray(suppliers) && suppliers !== null){
                return suppliers.filter(item => item !== '').join(', ');
            }else{
                return '';
            }
        }
    },
    updated() { }
}
</script>

<style lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');
@import '../../../../assets/scss/utilities.scss';
@import './scss/shipmentsTabTable.scss';

</style>
