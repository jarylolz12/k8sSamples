<template>
	<div class="outbound-view-wrapper" :class="isWarehouse3PL && hideCheckBoxOnCompletedRejectedCancelledStatus ? 
	'outbound-view-warehouse-is-3pl outbound-status-completed-rejected-cancelled' : isWarehouse3PL ? 'outbound-view-warehouse-is-3pl' : 
	hideCheckBoxOnCompletedRejectedCancelledStatus ? 'outbound-status-completed-rejected-cancelled' : ''" v-resize="onResize">
		<div class="loading-wrapper" v-if="fetchSingleOutboundLoading">
			<v-progress-circular
				:size="40"
				color="#0171a1"
				indeterminate>
			</v-progress-circular>
		</div>
	<div
	  v-if="
		!fetchSingleOutboundLoading &&
		typeof singleOutboundData !== 'undefined' &&
		singleOutboundData !== null
	  "
	>

	  <div class="details-breadcrumbs">
		<router-link to="/inventory?tab=Products" class="warehouse-name" @click.native="onClickBreadcrumbs(0)">
		  {{
			typeof singleOutboundData.data !== 'undefined' && 
			typeof singleOutboundData.data.warehouse !== 'undefined' &&
			typeof singleOutboundData.data.warehouse !== null &&
			singleOutboundData.data.warehouse.name !== "null"
			  ? singleOutboundData.data.warehouse.name
			  : "--"
		  }}
		</router-link>
		<span class="right-chevron">
		  <img
			src="../../../assets/images/right_chevron.svg"
			alt=""
			srcset=""
			width="7px"
		  />
		</span>
		 <router-link to="/inventory?tab=Outbound" class="warehouse-name" @click.native="onClickBreadcrumbs(4)">
					Outbound
				</router-link>
		<span class="right-chevron">
		  <img
			src="../../../assets/images/right_chevron.svg"
			alt=""
			srcset=""
			width="7px"
		  />
		</span>

		<p class="order-id">
		  {{
			typeof singleOutboundData.data !== 'undefined' && 
			typeof singleOutboundData.data.order_id !== 'undefined' &&
			typeof singleOutboundData.data.order_id !== null &&
			singleOutboundData.data.order_id !== "" &&
			singleOutboundData.data.order_id !== "null"
			  ? singleOutboundData.data.order_id
			  : "--"
		  }}
		</p>
	  </div>

	  <div class="outbound-header-wrapper">
		<div class="outbound-info">
		  <div class="info-wrapper">
			<div class="order-status">
			  <h2>
				Order ID
				{{
				  typeof singleOutboundData.data !== 'undefined' && 
				  typeof singleOutboundData.data.order_id !== 'undefined' &&
				  typeof singleOutboundData.data.order_id !== null &&
				  singleOutboundData.data.order_id !== "" &&
				  singleOutboundData.data.order_id !== "null"
					? singleOutboundData.data.order_id
					: "--"
				}}
			  </h2>
				<span v-if="!isWarehouseConnected">
					<span
			  			v-if="singleOutboundData.data"
						class="status"
						:class="singleOutboundData.data.outbound_status"
			  		>
				 	{{ 
						typeof outboundStatusForOwn3plAnd3plProvider !== 'undefined' && 
						outboundStatusForOwn3plAnd3plProvider !== null ? outboundStatusForOwn3plAnd3plProvider :'--'  
					}}
			  		</span>
				</span>
			  	<span v-else>
					<span
			  			v-if="singleOutboundData.data && getCurrentOutboundTab  !== undefined && getCurrentOutboundTab !=='undefined' &&
						getCurrentOutboundTab == 2 && (singleOutboundData.data.status == 'picked' || singleOutboundData.data.status == 'pending-pick')"
						class="status"
						:class="singleOutboundData.data.status"
			  		>
				 	{{ 
					 	typeof singleOutboundData.data !== 'undefined' && 
					 	typeof singleOutboundData.data.status !== 'undefined' && 
					 	singleOutboundData.data.status !== null && singleOutboundData.data.status == 'pending-pick' ? 'Pending Pick' :
						typeof singleOutboundData.data !== 'undefined' && 
					 	typeof singleOutboundData.data.status !== 'undefined' && 
					 	singleOutboundData.data.status !== null && singleOutboundData.data.status == 'picked' ?  'picked' : '--'  
					}}
			  		</span>
					<span
			  			v-else
						class="status"
						:class="singleOutboundData.data.outbound_status"
			  		>
				 	{{ 
					 	typeof singleOutboundData.data !== 'undefined' && 
					 	typeof singleOutboundData.data.outbound_status !== 'undefined' && 
					 	singleOutboundData.data.outbound_status !== null && singleOutboundData.data.outbound_status == 'pending' ? 'Waiting Approval' :
						typeof singleOutboundData.data !== 'undefined' && 
					 	typeof singleOutboundData.data.outbound_status !== 'undefined' && 
					 	singleOutboundData.data.outbound_status !== null ? singleOutboundData.data.outbound_status : '--'  
					}}
			  		</span>
			  	</span>
			  	
			</div>

			<p>
			  Last Updated:
			  <span>{{ formatDate(singleOutboundData) }}</span>
			</p>
			 <div
			 class="d-flex mb-3"
			v-if="isMobile"
			 >
		   <button
		   v-if="singleOutboundData.data && !isWarehouse3PL && singleOutboundData.data.outbound_status !=='cancelled' && singleOutboundData.data.outbound_status !=='completed' && !isWarehouseConnected"
		   :disabled="BuildButtonCondition(singleOutboundData)"
			class="btn-white font-weight-medium mr-2 storable-hide-btn"
			@click="BuildStorableUnitModelFunction"
		  >
			Build Storable Unit
		  </button>
		   <button
		   v-if="singleOutboundData.data && isWarehouse3PL==true && singleOutboundData.data.outbound_status =='pending'"
			class="btn-blue font-weight-medium mr-2"
			@click="LoadAllProducts3PLDialogOpen"
		  >
		   <v-icon class="mr-1 white--text">mdi-check</v-icon> All Loaded
		  </button>
			<v-menu bottom left offset-y content-class="outbound-lists-menu">
			  <template v-slot:activator="{ on, attrs }">
				<v-btn class="btn-white" icon v-bind="attrs" v-on="on">
				  <v-icon>mdi-dots-horizontal</v-icon>
				</v-btn>
			  </template>

			  <v-list v-if="singleOutboundData.data" class="outbound-lists">
				<!-- <v-list-item>
				  <v-list-item-title> Create Work Order </v-list-item-title>
				</v-list-item>

				<v-list-item>
				  <v-list-item-title> Arrange Trucking </v-list-item-title>
				</v-list-item> -->

				<v-list-item  v-if="ConditionForEditstatus(singleOutboundData.data.outbound_status)">
				  <v-list-item-title @click.stop="editOrder(singleOutboundData.data, 'edit')"> Edit Order </v-list-item-title>
				</v-list-item>

				<v-list-item>
				  <v-list-item-title  @click.stop="editOrder(singleOutboundData.data, 'copy')"> Duplicate </v-list-item-title>
				</v-list-item>
				
				 <v-list-item>
				  <v-list-item-title  @click.stop="printOrder(singleOutboundData.data)"> Print Order </v-list-item-title>
				</v-list-item>

				<v-list-item v-if="showCancelButton(singleOutboundData)"  @click.stop="cancelOrder(singleOutboundData.data)">
				  <v-list-item-title class="cancel">
					Cancel Order
				  </v-list-item-title>
				</v-list-item>
			  </v-list>
			</v-menu>
		  </div>
		</div>

		</div>

		<!-- <div class="info-buttons" v-if="!isMobile && singleOutboundData.data.outbound_status !== 'cancelled'"> -->
		<div class="info-buttons" v-if="!isMobile" >
		  <!-- <button class="btn-blue" @click.stop="createWorkOrder">
						Create Work Order
					</button>

					<button class="btn-white">
						Arrange Trucking
					</button> -->

		  <button
		  v-if="singleOutboundData.data && singleOutboundData.data.outbound_status !=='cancelled' &&
		   singleOutboundData.data.outbound_status !=='completed' && singleOutboundData.data.outbound_status !=='rejected' && !isWarehouseConnected"
		   :disabled="BuildButtonCondition(singleOutboundData)"
			class="btn-white font-weight-medium storable-hide-btn"
			@click="BuildStorableUnitModelFunction"
		  >
			Build Storable Unit
		  </button>
		  <button
		   v-if="singleOutboundData.data && isWarehouse3PL==true && singleOutboundData.data.outbound_status =='pending'"
			class="btn-blue font-weight-medium mr-0"
			@click="LoadAllProducts3PLDialogOpen"
		  >
		   <v-icon left class="white--text mr-1">mdi-check</v-icon> All Loaded
		  </button>

		  	<div v-if="!isWarehouseConnected && isWarehouse6PL && outboundStatus === 'pending'" 
                class="d-flex">
                <button class="btn-white btn-accept" 
                    @click.stop="acceptConnectedWarehouseOrder(singleOutboundData.data)">
                    Accept
                </button>

                <button class="btn-white btn-reject" @click.stop="rejectConnectedWarehouseOrder(singleOutboundData.data)">
                    <img src="@/assets/icons/close-red.svg" alt="Reject">
                </button>
            </div>
		  <v-menu bottom left offset-y content-class="outbound-lists-menu" v-if="!isWarehouseConnected">
			<template v-slot:activator="{ on, attrs }">
			  <v-btn class="btn-white btn-more" icon v-bind="attrs" v-on="on">
				<v-icon>mdi-dots-horizontal</v-icon>
			  </v-btn>
			</template>

			<v-list v-if="singleOutboundData.data" class="outbound-lists">
				
			  <v-list-item :style="isWarehouse6PL && !isWarehouseConnected && singleOutboundData.data.is_from_connected_3pl == 1 ? 'display: none':'' " v-if="ConditionForEditstatus(singleOutboundData.data.outbound_status)" @click.stop="editOrder(singleOutboundData.data, 'edit')">
				<v-list-item-title>
				  Edit Order
				</v-list-item-title>
			  </v-list-item>
				
			  <v-list-item  @click.stop="editOrder(singleOutboundData.data, 'copy')" :style="isWarehouse6PL && !isWarehouseConnected && singleOutboundData.data.is_from_connected_3pl == 1 ? 'display: none':'' ">
				<v-list-item-title>
				  Duplicate
				</v-list-item-title>
			  </v-list-item>

			  <v-list-item @click.stop="printOrder(singleOutboundData.data)">
				<v-list-item-title>
				  Print Order
				</v-list-item-title>
			  </v-list-item>

			  <v-list-item :style="isWarehouse6PL && !isWarehouseConnected && singleOutboundData.data.is_from_connected_3pl == 1 ? 'display:none':''" v-if="showCancelButton(singleOutboundData)"  @click.stop="cancelOrder(singleOutboundData.data)">
				<v-list-item-title class="cancel">
				  Cancel Order 
				</v-list-item-title>
			  </v-list-item>
			</v-list>
		  </v-menu>
		  <v-menu v-if="isWarehouseConnected == true && (singleOutboundData.data.outbound_status == 'draft' || singleOutboundData.data.outbound_status == 'pending' )" bottom left offset-y content-class="outbound-lists-menu">
			<template v-slot:activator="{ on, attrs }">
			  <v-btn class="btn-white btn-more" icon v-bind="attrs" v-on="on">
				<v-icon>mdi-dots-horizontal</v-icon>
			  </v-btn>
			</template>

			<v-list v-if="singleOutboundData.data" class="outbound-lists">
			  <v-list-item v-if="singleOutboundData.data.outbound_status == 'draft'" @click.stop="editOrder(singleOutboundData.data, 'edit')">
				<v-list-item-title>
				  Edit
				</v-list-item-title>
			  </v-list-item>
			  <v-list-item v-if="singleOutboundData.data.outbound_status == 'draft'" @click="submitDraft(singleOutboundData.data)">
				<v-list-item-title>
				  Submit
				</v-list-item-title>
			  </v-list-item>
			  <v-list-item v-if="singleOutboundData.data.outbound_status == 'draft'" @click="canceldelete(singleOutboundData.data)">
				<v-list-item-title class="cancel">
				  Delete
				</v-list-item-title>
			  </v-list-item>
			   <v-list-item v-if="showCancelButton(singleOutboundData) && singleOutboundData.data.outbound_status !== 'draft'"  @click.stop="cancelOrder(singleOutboundData.data)">
				<v-list-item-title class="cancel">
				  Cancel 
				</v-list-item-title>
			  </v-list-item>

			  <!-- <v-list-item @click.stop="printOrder(singleOutboundData.data)">
				<v-list-item-title>
				  Print Order
				</v-list-item-title>
			  </v-list-item> -->
			</v-list>
		  </v-menu>
		  <v-dialog
			v-model="dialogFailed"
			width="500"
			content-class="outbound-dialog-pick"
		  >
			<v-card>
			  <v-card-title class="headline">
				<div class="question-icon mt-3 mb-1">
				  <img
					src="@/assets/icons/icon-delete.svg"
					alt=""
					width="48px"
					height="48px"
				  />
				</div>
			  </v-card-title>

			  <v-card-text style="padding-bottom: 15px">
				<h2>Cancellation Failed</h2>
				<p>
				  You cannot cancel <span>Outbound order 55069872</span> at this
				  moment. This has already been completed.
				</p>
			  </v-card-text>

			  <v-card-actions>
				<button class="btn-blue confirm-pick" @click="closeFailed">
				  Go Back
				</button>
			  </v-card-actions>
			</v-card>
		  </v-dialog>
		</div>
		 <v-dialog
			v-model="dialogCancel"
			max-width="500"
			content-class="outbound-dialog-pick"
		  >
			<v-card>
			  <v-card-title class="headline">
				<div class="question-icon mt-3 mb-1">
				  <img
					src="@/assets/icons/icon-delete.svg"
					alt=""
					width="48px"
					height="48px"
				  />
				</div>
			  </v-card-title>

			  <v-card-text style="padding-bottom: 15px">
				<h2 v-if="CancelOrderCheck">Cancellation Failed</h2>
				<h2 v-else>Cancel Order</h2>
				 <p v-if="CancelOrderCheck">
				{{CancelOrderError }}
				</p>
				<p v-else>
				  Do you want to cancel <span v-if="cancelOrderData">Outbound order {{cancelOrderData.order_id}}</span>?
				  Once cancelled, order cannot be reopened.
				</p>
			  </v-card-text>

			  <v-card-actions>
				<button v-if="!CancelOrderCheck" :disabled="getCancelOutboundLoading" class="btn-blue confirm-pick" @click="CancelOrderConfirm">
				  Cancel Order
				</button>

				<button v-if="!CancelOrderCheck" class="btn-white cancel-pick" @click="closeCancelOrder">
				  Close
				</button>
				<button v-if="CancelOrderCheck" class="btn-blue confirm-pick" @click="closeCancelOrder">
				  Go Back
				</button>
				
			  </v-card-actions>
			</v-card>
		  </v-dialog>
	  </div>

	  <div class="outbound-body-wrapper">
		<div class="outbound-informations">
		  <v-row>
			<v-col cols="12" sm="4">
			  <div class="outbound-info">
				<p class="outbound-title">Truck</p>
				<p class="outbound-data">
				  {{
					typeof singleOutboundData.data !== 'undefined' &&
					typeof singleOutboundData.data.name !== "" &&
					singleOutboundData.data.name !== null &&
					singleOutboundData.data.name !== "null" &&
					isWarehouse3PL == false
					  ? singleOutboundData.data.name
					  : "--"
				  }}
				</p>
			  </div>

			  <div class="outbound-info" v-if="!isWarehouse6PL && !isWarehouseConnected">
				<p class="outbound-title">Customer</p>
				<p class="outbound-data">
				  {{
					typeof singleOutboundData.data !== 'undefined' &&
					typeof singleOutboundData.data.customer !== "" &&
					singleOutboundData.data.customer !== null &&
					singleOutboundData.data.customer !== "null"
					  ? singleOutboundData.data.customer
					  : "--"
				  }}
				</p>
			  </div>
			  <div class="outbound-info" v-if="isWarehouse6PL ==true && !isWarehouseConnected">
				<p class="outbound-title">Customer</p>
				<p class="outbound-data">
				  {{
					typeof singleOutboundData.data !== 'undefined' &&
					typeof singleOutboundData.data.warehouse_customer !== "" &&
					singleOutboundData.data.warehouse_customer !== null &&
					singleOutboundData.data.warehouse_customer !== "null"
					  ? singleOutboundData.data.warehouse_customer.company_name
					  : "--"
				  }}
				</p>
			  </div>
			  <div class="outbound-info" v-if="isWarehouseConnected">
				<p class="outbound-title">Buyer</p>
				<p class="outbound-data">
				  {{
					typeof singleOutboundData.data !== 'undefined' &&
					typeof singleOutboundData.data.warehouse_customer !== "" &&
					singleOutboundData.data.warehouse_customer !== null &&
					singleOutboundData.data.warehouse_customer !== "null"
					  ? singleOutboundData.data.warehouse_customer.company_name
					  : "--"
				  }}
				</p>
			  </div>
			  <div class="outbound-info">
				<p class="outbound-title">Reference</p>
				<p class="outbound-data">
				  {{
					typeof singleOutboundData.data !== 'undefined' &&
					typeof singleOutboundData.data.ref_no !== "" &&
					singleOutboundData.data.ref_no !== null &&
					singleOutboundData.data.ref_no !== "null"
					  ? singleOutboundData.data.ref_no
					  : "--"
				  }}
				</p>
			  </div>

			  <div class="outbound-info">
				<p class="outbound-title">Warehouse</p>
				<p class="outbound-data deliver-to">
				  {{
					typeof singleOutboundData.data !== 'undefined' &&
					typeof singleOutboundData.data.deliver_to !== "" &&
					singleOutboundData.data.deliver_to !== null &&
					singleOutboundData.data.deliver_to !== "null"
					  ? singleOutboundData.data.deliver_to
					  : "--"
				  }}
				</p>
			  </div>
			</v-col>

			<v-col cols="12" sm="4">
			  <div class="outbound-info">
				<p class="outbound-title">ETA</p>
				<p class="outbound-data">
				  {{
					formatEta(
					  singleOutboundData
					)
				  }}
				</p>
			  </div>

			  <!-- <div class="outbound-info">
								<p class="outbound-title">Actual Arrival</p>
								<p class="outbound-data">N/A</p>
							</div>

							<div class="outbound-info">
								<p class="outbound-title">Departure</p>
								<p class="outbound-data">N/A</p>
							</div> -->

			  <div class="outbound-info">
				<p class="outbound-title">No of SKU</p>
				<p class="outbound-data">
				  {{
					typeof singleOutboundData.data !== 'undefined' &&
					typeof singleOutboundData.no_of_sku !== "undefined" &&
					singleOutboundData.no_of_sku !== null &&
					singleOutboundData.no_of_sku !== "null"
					  ? singleOutboundData.no_of_sku
					  : 0
				  }}
				</p>
			  </div>

			  <div class="outbound-info">
				<p class="outbound-title">Cartons</p>
				<p class="outbound-data">
				  {{
					typeof singleOutboundData.data !== 'undefined' &&
					typeof singleOutboundData.total_cartons !== "undefined" &&
					singleOutboundData.total_cartons !== null &&
					singleOutboundData.total_cartons !== "null"
					  ? singleOutboundData.total_cartons
					  : 0
				  }}
				</p>
			  </div>

			  <div class="outbound-info warehouse-3pl-hidden">
				<p class="outbound-title">STORABLE UNIT</p>
				<p class="outbound-data">
				  {{
					typeof singleOutboundData.data !== 'undefined' &&
					typeof singleOutboundData.data.outbound_storable_units !==
					  "undefined" &&
					singleOutboundData.data.outbound_storable_units !== null &&
					singleOutboundData.data.outbound_storable_units.length !==
					  "undefined"
					  ? singleOutboundData.data.outbound_storable_units.length
					  : 0
				  }}
				</p>
			  </div>

			  <div class="outbound-info">
				<p class="outbound-title">Units</p>
				<p class="outbound-data">
				  {{
					 typeof singleOutboundData.data !== 'undefined' &&
					typeof singleOutboundData.total_units !== "undefined" &&
					singleOutboundData.total_units !== null &&
					singleOutboundData.total_units !== "null"
					  ? singleOutboundData.total_units
					  : 0
				  }}
				</p>
			  </div>
			</v-col>

			<v-col cols="12" sm="4">
			  <div class="outbound-info">
				<p class="outbound-title" v-if="!isWarehouseConnected && isWarehouse6PL">NOTES/INSTRUCTIONS</p>
				<p class="outbound-title" v-if="!isWarehouseConnected && !isWarehouse6PL">NOTES</p>
				<p class="outbound-title" v-if="isWarehouseConnected">SPECIAL INSTRUCTION</p>
				<p class="outbound-data">
				  {{
					typeof singleOutboundData.data !== 'undefined' &&
					typeof singleOutboundData.data.notes !== "" &&
					singleOutboundData.data.notes !== null &&
					singleOutboundData.data.notes !== "undefined" &&
					singleOutboundData.data.notes !== "null"
					  ? singleOutboundData.data.notes.substr(0, 256)
					  : "--"
				  }}
				  <span
					v-if="
					  typeof singleOutboundData.data !== 'undefined' &&
					  singleOutboundData.data.notes &&
					  singleOutboundData.data.notes.length > 256
					"
					>...</span
				  >
				  <v-btn
					text
					small
					class="text-capitalize font-weight-bold pr-1 pl-0"
					color="#0171A1"
					v-if="
					  typeof singleOutboundData.data !== 'undefined' &&
					  singleOutboundData.data.notes &&
					  singleOutboundData.data.notes.length > 256
					"
					@click="showMoreText"
					>Show All</v-btn
				  >
				</p>
			  </div>
			</v-col>
		  </v-row>
		</div>

		<div class="outbound-tabs">
		  <v-tabs
			class="outbound-inner-tab"
			@change="onTabChange"
			v-model="currentTab"
		  >
			<v-tab  v-for="(tab, index) in tabs" :key="index" :class="tab">
			 {{ tab }}
			</v-tab>
		  </v-tabs>

		  <div
			class="product-section-table outbound-table-wrapper"
			v-if="currentTab == 0 && componentLoaded"
		  >
			<ProductSection
			  :outboundProductsData="singleOutboundData.data"
			  :isMobile="isMobile"
			  :hidewareFloorTab="isWarehouse3PL"
			  :productsListsData="productsListsData"
			  :singleOutbound_status="singleOutboundData.data.outbound_status"
			  :isWarehouseConnected="isWarehouseConnected"
			  :isWarehouse6PL="isWarehouse6PL"
			  @Warehouse6PL_ProductsOnchange="Warehouse6PL_ProductsOnchange"
			  @fetchOutboundProductsAPiFunction="fetchOutboundProductsAPiFunction"
			  :isWarehouse3PL="isWarehouse3PL"
			/>
		  </div>

		  <div
			class="storable-section-table outbound-table-wrapper"
			 v-if="currentTab == 1 && isWarehouse3PL ==false"
		  >
			<StorableSection
			  :outboundProductsData="singleOutboundData.data"
			  :isMobile="isMobile"
			  :singleOutbound_status="singleOutboundData.data.outbound_status"
			  :isWarehouseConnected="isWarehouseConnected"
			  :isWarehouse6PL="isWarehouse6PL"
			/>
		  </div>

		  <!-- <div class="work-section-table outbound-table-wrapper" v-if="currentTab == 2">
						<WorkSection />
					</div> -->

		  <div
			class="documents-section-table outbound-table-wrapper"
			v-if="currentTab == 2"
		  >
			<DocumentSection
			  :outboundProductsData="singleOutboundData.data"
			  :isMobile="isMobile"
			/>
		  </div>

		  <!-- <div class="carrier-section-table outbound-table-wrapper" v-if="currentTab == 4">
						<CarrierSection />
					</div> -->

		  <!-- <div class="invoices-section-table outbound-table-wrapper" v-if="currentTab == 5">
						<InvoiceSection />
					</div> -->

		  <!-- <div class="profit-section-table outbound-table-wrapper" v-if="currentTab == 6">
						<ProfitSection />
					</div> -->

		  <!-- <div class="pallets-section-table outbound-table-wrapper" v-if="currentTab == 210">
						<PalletSection />
					</div> -->
		</div>
	  </div>
	</div>
	<DeleteOutboundDialog
		:dialogData.sync="dialogCanceldelete"
		:editedItemData.sync="deleteOrderData"
		@cancel="deleteConfirm"
		@close="closeDelete"
		:loading="getDeleteOutboundLoading"
		:isWarehouseConnected="isWarehouseConnected" />
	<CreateWorkOrderDialog
	  :dialogCreate.sync="dialogCreateWorkOrder"
	  :editedOrderIndex.sync="editedOrderIndex"
	  :defaultOrderItems.sync="defaultOrderItems"
	  :outboundData="singleOutboundData"
	  @close="close"
	/>
	<div v-if="componentLoaded">
	<CreateOutboundDialog
	  :dialogCreate.sync="dialogCreate"
	  :editedOutboundIndex.sync="editedOutboundIndex"
	  :editedOutboundItems.sync="editedOutboundItems"
	  :defaultOutboundItems.sync="defaultOutboundItems"
	  :currentWarehouseSelected="$store.state.warehouse.currentWarehouse"
	  :isFetchSingleOutboundbound="true"
	  @close="closeCreate"
	  :isMobile="isMobile"
	  :hidewareFloorTab="isWarehouse3PL"
	  :status="singleOutboundData.data.outbound_status"
	  :outboundProducts_clone="outboundProducts_clone"
	  :productsListsData="productsListsData"
	  :fetchProductLoading="fetchProductLoading"
	  @Warehouse6PL_ProductsOnchange="Warehouse6PL_ProductsOnchange"
	  @fetchOutboundProductsAPiFunction="fetchOutboundProductsAPiFunction"
	  :fetchWarehouseCustomersLoading="fetchWarehouseCustomersLoading"
	  :isWarehouseConnected="isWarehouseConnected"
	/>
	</div>
	<div v-if="componentLoaded">
	  <NewSortableUnitOutboundDialog
		:dialog.sync="BuildStorableUnitModel"
		:editedItem.sync="storableItems"
		:productsData="singleOutboundData.data"
		@close="closeStorableUnitModelFunction"
		:index="-1"
		:isMobile="isMobile"
	  />
	</div>

	<!-- Notes Dialog If Notes length exceeds start start -->
	<v-dialog v-model="notesModel" width="500">
	  <v-card class="py-0 my-0">
		<v-card-title class="pa-0 my-0 font-weight-medium">
		  <h3>Notes</h3>
		  <v-spacer></v-spacer>
		  <v-btn @click="notesModel = false" icon>
			<v-icon>mdi-close</v-icon>
		  </v-btn>
		</v-card-title>
		<v-card-text class="font-weight-bold mt-3" v-if="componentLoaded">
		  {{
			typeof mynotes !== "" &&
			 typeof mynotes !== "undefined" &&
			mynotes !== null &&
			mynotes !== "undefined" &&
			mynotes !== "null"
			  ? mynotes
			  : "--"
		  }}
		</v-card-text>
		<v-card-actions class="py-0 my-0" :class="isMobile ? 'notes-card-button-attach':''">
		  <v-btn
			color=""
			text
			outlined
			class="text-capitalize"
			@click="notesModel = false"
		  >
			Close
		  </v-btn>
		</v-card-actions>
	  </v-card>
	</v-dialog>
	<v-dialog v-model="LoadAllProducts3PLDialog" width="500" content-class="outbound-dialog-pick">
			<v-card>
				<v-card-title class="headline">
					<div class="question-icon mt-3 mb-1">
						<img src="@/assets/icons/question.svg" alt="" width="48px" height="48px">
					</div>
				</v-card-title>

				<v-card-text style="padding-bottom: 15px;">
					<h2>Confirm Load</h2>

					<p>Do you confirm that selected product(s) are loaded into the truck? Once confirmed, you cannot remove these from the order.</p>
				</v-card-text>

				<v-card-actions>
					<button
					:disabled="getsetBatchProductLoading" class="btn-blue confirm-pick" @click="LoadAllProducts3PLFunction">         

						<span>{{ getsetBatchProductLoading ? 'Confirming...' : 'Confirm Load'}}</span>  
					</button>
					<button class="btn-white cancel-pick" @click="LoadAllProducts3PLDialog=false">
						Cancel
					</button>
				</v-card-actions>
			</v-card>
		</v-dialog>
		<!-- connected warehouse dialog -->
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
				<p v-if="currentWarehouseSelectedData"> 
					Do you want to submit the draft outbound order to 
					'<span v-if="draftOutboundData" style="font-family: 'Inter-SemiBold', sans-serif !important;">
						{{typeof draftOutboundData !== 'undefined' && draftOutboundData !== undefined && draftOutboundData.warehouse !== undefined &&
						draftOutboundData.warehouse !== 'undefined' ? draftOutboundData.warehouse.name :'--'  }}
					</span>' ?
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="submitDraftConfirm" :disabled="getDraftSbmitLoading" text>
                    Submit
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
				<h2> Accept Outbound Order?</h2>
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
					'</span>
					? Once accepted you can't cancel it.
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="AcceptConnectedOutboundConfirm" :disabled="getAcceptOrderByProviderOutboundLoading" text>
                    Accept
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
                    Reject
				</v-btn>

				<v-btn class="btn-white" text @click="closeRejectConnectedWarehouseOrder" :disabled="getRejectOrderByProviderOutboundLoading">
					Close
				</v-btn>
			</template>
		</ConfirmDialog>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import ProductSection from "./TabsComponents/ProductSection.vue";
import StorableSection from "./TabsComponents/StorableSection.vue";
// import WorkSection from './TabsComponents/WorkSection.vue'
import DocumentSection from "./TabsComponents/DocumentSection.vue";
// import CarrierSection from './TabsComponents/ProductSection.vue'
// import InvoiceSection from './TabsComponents/ProductSection.vue'
// import ProfitSection from './TabsComponents/ProductSection.vue'
// import PalletSection from './TabsComponents/PalletSection.vue'
import CreateWorkOrderDialog from "./CreateWorkOrderDialog";
import NewSortableUnitOutboundDialog from "./Dialogs/NewSortableUnitOutboundDialog.vue";
import CreateOutboundDialog from "./CreateOutboundDialog.vue"
import globalMethods from "../../../utils/globalMethods";
import ConfirmDialog from '../../Dialog/GlobalDialog/ConfirmDialog.vue'
import DeleteOutboundDialog from '../OutboundComponents/DeleteOutboundDialog.vue'
import moment from "moment";

export default {
  	name: "InventoryOutboundView",
  	props: [],
  	components: {
		ProductSection,
		StorableSection,
		// WorkSection,
		DocumentSection,
		// CarrierSection,
		// InvoiceSection,
		// ProfitSection,
		// PalletSection,
		CreateWorkOrderDialog,
		NewSortableUnitOutboundDialog,
		CreateOutboundDialog,
		ConfirmDialog,
		DeleteOutboundDialog
  	},
  	data: () => ({
		// tabs: ["Products", "Pallets", "Documents", "Invoices", "Profits"],
		// tabs: ["Products", "Storable Unit", "Work Orders", "Documents", "Carrier", "Invoices", "Profit"],
		hidewareFloorTab:'',
		componentLoaded: false,
		mynotes: "",
		tabs: ["Products", "Storable Unit", "Documents"],
		currentTab: 0,
		BuildStorableUnitModel: false,
		palletsHeader: [
	  		{
				text: "Label",
				align: "start",
				sortable: false,
				value: "label",
				fixed: true,
				width: "10%",
	  		},
	  		{
				text: "Type",
				align: "start",
				sortable: false,
				value: "type",
				fixed: true,
				width: "12%",
	  		},
	  		{
				text: "WORK ORDER",
				align: "start",
				sortable: false,
				value: "work_order",
				fixed: true,
				width: "12%",
	  		},
	  		{
				text: "Updated at",
				align: "start",
				sortable: false,
				value: "updated_at",
				fixed: true,
				width: "18%",
	  		},
	  		{
				text: "No of sku",
				align: "start",
				sortable: false,
				value: "no_of_sku",
				fixed: true,
				width: "10%",
	  		},
	  		{
				text: "CARTON",
				align: "end",
				sortable: false,
				value: "carton_count",
				fixed: true,
				width: "12%",
	  		},
	  		{
				text: "UNIT",
				align: "end",
				sortable: false,
				value: "units",
				fixed: true,
				width: "12%",
	  		},
	  		{
				text: "",
				align: "end",
				value: "actions",
				sortable: false,
				fixed: true,
				width: "15%",
	  		},
		],
		palletItems: [
	  		{
				label: "558612",
				type: "Regular",
				updated_at: "--",
				no_of_sku: "1",
				work_order: "WO19293",
				carton_count: "80",
				units: "615",
				status: "pending",
	  		},
	  		{
				label: "892816",
				type: "Drum",
				updated_at: "--",
				no_of_sku: "3",
				work_order: "WO19294",
				carton_count: "40",
				units: "428",
				status: "pending",
	  		},
	  		{
				label: "827302",
				type: "Regular",
				updated_at: "01:47PM, Aug 16, 2021",
				no_of_sku: "2",
				work_order: "WO19295",
				carton_count: "100",
				units: "640",
				status: "done",
	  		},
		],
		dialogCreateWorkOrder: false,
		dialogCreate: false,
		editedOutboundIndex: 0,
		editedOrderIndex: -1,
		editedOrderItems: {
	  		order_id: "",
	  		ref_no: "",
	  		type: "",
	  		assigned_to: "",
	  		carton_count: 0,
	  		in_each_carton: 0,
	  		bundle_products: [],
	  		details: [],
		},
		defaultOrderItems: {
	  		order_id: "",
	  		ref_no: "",
	  		type: "",
	  		assigned_to: "",
	  		carton_count: 0,
	  		in_each_carton: 0,
	  		bundle_products: [],
	  		details: [],
		},
		isMobile: false,
		dialogCancel: false,
		dialogFailed: false,
		notesModel: false,
		storableItems: {
	  		action: "",
	  		type: "",
	  		copies:1,
	  		customer_id: null,
	  		dimension: {
				l: "",
				w: "",
				h: "",
				uom: "cm",
	  		},
	  		weight: {
				value: "",
				unit: "KG",
	  		},
	  		products: [],
	  		currentWarehouseSelected: "",
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
		  			total_unit: '',
		  			outbound_product_id:null,
					instructions:''
				},
	  		],
	  		pallets: [],
	  		outbound_documents: [],
	  		bol_number: "",
	  		outbound_storable_units: [],
		},
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
		  			total_unit: '',
		  			outbound_product_id:null,
					instructions:''
				},
	  		],
	  		pallets: [],
	  		outbound_documents: [],
	  		bol_number: "",
	  		outbound_storable_units:[],
	  		// outbound_storable_units: [
	  		//   {
	  		//     id: null,
	  		//     outbound_id: null,
	  		//     warehouse_id: null,
	  		//     customer_id: null,
	  		//     action: null,
	  		//     label: null,
	  		//     type: "",
	  		//       created_at:"",
	  		//           updated_at:"",
	  		//     dimension: {
	  		//       l: "",
	  		//       w: "",
	  		//       h: "",
	  		//       uom: "",
	  		//     },
	  		//     weight: {
	  		//       value: "",
	  		//       unit: "",
	  		//     },
	  		//     products: null,
	  		//     status: "",
	  		//     no_of_sku: null,
	  		//     deleted_at: null,
	  		//     storable_unit_id: null,
	  		//     outbound_storable_unit_products: [
	  		//       {
	  		//         id: null,
	  		//         outbound_product_id: null,
	  		//         outbound_storable_unit_id: null,
	  		//         carton_count: "",
	  		//         total_unit: "",
	  		//         in_each_carton: "",
	  		//         storable_unit_product_id: null,
	  		//           created_at:"",
	  		//           updated_at:"",
	  		//       },
	  		//     ],
	  		//   },
	  		// ],
		},
		cancelOrderData:null,
		CancelOrderCheck:false,
		CancelOrderError:'',
		fetchSingleOutboundLoading: false,
		LoadAllProducts3PLDialog:false,
		outboundProducts_clone:[],
		// Products Drop Down 
		lastDataCheck: [],
		productsListsData: [],
		current_page_is: 1,
		fetchProductLoading:false,
		// wareHouse 6PL
		current_page_6pl_products:1,
		// warehouse customers
		current_page_is_whcustomers: 1,
		warehouseCustomerListsData: [],
		lastCheckWHData: [],
		fetchWarehouseCustomersLoading: false,
		// connected warehouse 3pl provider
		confirmDraftSubmit: false,
		draftOutboundData: null,
		dialogCanceldelete: false,
		deleteOrderData: null,
		AcceptConnectedWarehouseOutboundDialog:false,
		acceptConnectedWarehouseOrderData : null,
		RejectConnectedWarehouseOutboundDialog:false,
		rejectConnectedWarehouseOrderData:null,
		reasonForRejectionOutboundOrder:'',
		currentWarehouseSelectedData:null,
		currentWarehouseSelected:null,
  	}),
  	computed: {
		...mapGetters({
	  		getUser: 'getUser',
	  		getSingleOutbound: "outbound/getSingleOutbound",
	  		getSingleOutboundLoading: "outbound/getSingleOutboundLoading",
	  		getSingleWarehouse: "warehouse/getSingleWarehouse",
	  		getEnableButtonSortableUnit: "outbound/getEnableButtonSortableUnit",
	  		getCancelOutboundLoading: "outbound/getCancelOutboundLoading",
	  		getconfirmOrderCompletionLoading:"outbound/getconfirmOrderCompletionLoading",
	  		getAllOutboundProductsListsDropdownData:'outbound/getAllOutboundProductsListsDropdownData',
	  		getallOutboundProductsLists:'outbound/getallOutboundProductsLists',
	  		//3PL
	  		getsetBatchProductLoading:"outbound/getsetBatchProductLoading",
	  		//6PL
			getWarehouseCustomersDropdown: 'warehouseCustomers/getWarehouseCustomersDropdown',
	  		getAllWarehouseCustomerListsData: 'warehouseCustomers/getAllWarehouseCustomerListsData',
			getallProducts6PLists: 'outbound/getallProducts6PLists',
			getCurrentOutboundTab: "outbound/getCurrentOutboundTab",
			getDraftSbmitLoading : "outbound/getDraftSbmitLoading",
			getDeleteOutboundLoading: "outbound/getDeleteOutboundLoading",
			getAcceptOrderByProviderOutboundLoading:"outbound/getAcceptOrderByProviderOutboundLoading",
			getRejectOrderByProviderOutboundLoading:"outbound/getRejectOrderByProviderOutboundLoading"
		}),
		outboundStatus() {
            let outbound_status = ''

            if (this.singleOutboundData !== 'undefined' && this.singleOutboundData !== null && 
                typeof this.singleOutboundData.data !== 'undefined' ) {

                outbound_status = this.singleOutboundData.data.outbound_status
            }

            return outbound_status
        },
		outboundStatusForOwn3plAnd3plProvider(){
			 let outbound_status = ''
			if (this.singleOutboundData !== 'undefined' && this.singleOutboundData !== null && 
                typeof this.singleOutboundData.data !== 'undefined' && 
				typeof this.singleOutboundData.data.is_from_connected_3pl !== 'undefined') {
					if(this.isWarehouse6PL && this.singleOutboundData.data.is_from_connected_3pl == 1 && 
					this.outboundStatus == 'pending'){
						outbound_status = 'Waiting Approval'
					}else if(this.isWarehouse6PL && 
					this.outboundStatus == 'shipping-pending'){
						outbound_status = 'Pending Shipping'
					}else{
						outbound_status = this.outboundStatus
					}
				}
				return outbound_status
		},
		hideCheckBoxOnCompletedRejectedCancelledStatus(){
			if (typeof this.singleOutboundData !== 'undefined' && 
				this.singleOutboundData !== null && 
				typeof this.singleOutboundData.data !== 'undefined' &&
				this.singleOutboundData.data !== null &&
				this.singleOutboundData.data.outbound_status !== 'undefined') {
				if (this.singleOutboundData.data.outbound_status === 'completed' || 
				this.singleOutboundData.data.outbound_status === 'cancelled' || 
				this.singleOutboundData.data.outbound_status === 'rejected' ||
				this.singleOutboundData.data.outbound_status === 'draft' || 
				(this.singleOutboundData.data.outbound_status === 'pending' && this.isWarehouseConnected ) || 
				(this.singleOutboundData.data.outbound_status === 'ready' && this.isWarehouseConnected ) || 
				(this.singleOutboundData.data.outbound_status === 'floor' && this.isWarehouseConnected) || 
				(this.singleOutboundData.data.outbound_status === 'shipping-pending' && this.isWarehouseConnected)) {
					return true
				} else {
					return false
				}
			} else {
				return false
			}
		},
		singleOutboundData() {
	  		let newData = null;

	  		if (
				typeof this.getSingleOutbound !== "undefined" &&
				this.getSingleOutbound !== null &&
				this.getSingleOutbound.data !== "undefined"
	  		) {
				newData = {
				  data: this.getSingleOutbound.data,
				  no_of_sku: this.getSingleOutbound.no_of_sku,
				  total_cartons: this.getSingleOutbound.total_cartons,
				  total_units: this.getSingleOutbound.total_units,
				};
	  		}

	  		return newData;
		},
	 	isWarehouse3PL() {
			if (typeof this.singleOutboundData !== 'undefined' && 
				this.singleOutboundData !== null && 
				typeof this.singleOutboundData.data !== 'undefined' &&
				this.singleOutboundData.data !== null &&
				this.singleOutboundData.data.warehouse !== 'undefined') {
				if (this.singleOutboundData.data.warehouse.warehouse_type_id === 3) {
					return true
				} else {
					return false
				}
			} else {
				return false
			}
	  	},
	  	isWarehouse6PL() {
			if (typeof this.singleOutboundData !== 'undefined' && 
				this.singleOutboundData !== null && 
				typeof this.singleOutboundData.data !== 'undefined' &&
				this.singleOutboundData.data !== null &&
				this.singleOutboundData.data.warehouse !== 'undefined') {
				if (this.singleOutboundData.data.warehouse.warehouse_type_id === 6) {
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
  	},
  	methods: {
		...mapActions({
	  		fetchSingleOutbound: "outbound/fetchSingleOutbound",
	  		fetchSingleWarehouse: "warehouse/fetchSingleWarehouse",
	  		printOutboundOrder: "outbound/printOutboundOrder",
	  		cancelOutbound: "outbound/cancelOutbound",
	  		setAllOutboundProductsLists: 'outbound/setAllOutboundProductsLists',
	  		fetchOutboundProducts:'outbound/fetchOutboundProducts',
	  		// 3pl
	  		multipleLoadProductApi:"outbound/multipleLoadProductApi",
	  		// 6PL
			wareHouse6plProduct: "outbound/wareHouse6plProduct",
	  		fetchWarehouseCustomersDropdown: "warehouseCustomers/fetchWarehouseCustomersDropdown",
	  		setWarehouseTypeHas3PL: 'warehouseCustomers/setWarehouseTypeHas3PL',
	  		setAllWarehouseCustomerLists: 'warehouseCustomers/setAllWarehouseCustomerLists',
	  		// fiter products empty
	  		setOutboundFilteredVal:'outbound/setOutboundFilteredVal',
	  		fetchPendingOutbounds: "outbound/fetchPendingOutbounds",
	  		fetchFloorOutbounds: "outbound/fetchFloorOutbounds",
			fetchCompletedOutbounds: "outbound/fetchCompletedOutbounds",
			// connected warehouse 3pl provider
			draftOutboundApi : "outbound/draftOutboundApi",
			fetchDraftOutbounds : "outbound/fetchDraftOutbounds",
			deleteOutbound: "outbound/deleteOutbound",
			AcceptConnectedOrderApi:"outbound/AcceptConnectedOrderApi",
			RejectConnectedOrderApi: "outbound/RejectConnectedOrderApi"
		}),
		...globalMethods,
		onClickBreadcrumbs(index) {
			this.$store.dispatch("page/setCurrentInventoryTab", index)
			this.setAllOutboundProductsLists([])
			this.setOutboundFilteredVal([])
			},
		BuildStorableUnitModelFunction() {
		  	this.BuildStorableUnitModel = true;
		},
		closeStorableUnitModelFunction() {
		  	this.BuildStorableUnitModel = false;
		},

		onResize() {
	  		if (window.innerWidth < 1024) {
				this.isMobile = true;
	  		} else {
				this.isMobile = false;
	  		}
		},
		onTabChange(i) {
	  		this.currentTab = i;
		},
		formatDate(Outbound) {
	  		if (typeof Outbound !== 'undefined' && Outbound !== null && typeof Outbound.data !== 'undefined') {
				if (Outbound.data.updated_at !== 'undefined') {
					let date = Outbound.data.updated_at
					return moment(date).format('h:mm MM/DD/YYYY')
				} else {
					return 'N/A'
				}
	  		}
		},
	 	formatEta(outbound) {
			let newTime = null
			let newDate = null

			let final_estimated_time_and_date = null

			if (outbound !== 'undefined' && outbound !== null && typeof outbound.data !== 'undefined') {
				if (outbound.data.estimated_date !== 'undefined') {
					let date = outbound.data.estimated_date
					let time = outbound.data.estimated_time

					if (date !== '' && date !== null && date !== 'null') {
						newDate = moment(date).format('MMM DD, YYYY')

						if (time !== '' && time !== null && time !== 'null') {
							newTime = moment(time, ["HH:mm"]).format("hh:mmA, ");
						}   
					}
				}
			}

			if (newDate !== null && newDate !== 'null') {
				if (newTime !== null && newTime !=='null') {
					final_estimated_time_and_date = newTime + newDate
				} else {
					final_estimated_time_and_date = 'N/A, ' + newDate
				}
			} else {
				if (newTime !== null && newTime !=='null') {
					final_estimated_time_and_date = newTime + ', N/A'
				} else {
					final_estimated_time_and_date = '--'
				}
			}

			return final_estimated_time_and_date
   		},
		createWorkOrder() {
	  		this.dialogCreateWorkOrder = true;
	  		this.$nextTick(() => {
				this.editedOrderItems = Object.assign({}, this.defaultOrderItems);
				this.editedOrderIndex = -1;
	  		});
		},
		close() {
	  		this.dialogCreateWorkOrder = false;
	  		this.$nextTick(() => {
				this.editedOrderItems = Object.assign({}, this.defaultOrderItems);
				this.editedOrderIndex = -1;
	  		});
		},
		cancelOrder(cancelOutboundparam) {
	  		this.cancelOrderData= cancelOutboundparam
	   		this.dialogCancel = true;
		},
		closeCancelOrder() {
	  		this.dialogCancel = false;
	  		this.CancelOrderCheck=false;
	  		this.CancelOrderError=''
		},
   		async CancelOrderConfirm(){
			if (this.cancelOrderData !== null) {
				try {
			  		let payload = {
						wid: this.cancelOrderData.warehouse_id,
						oid: this.cancelOrderData.id,
			  		};

			  		await this.cancelOutbound(payload);
	
			  		this.notificationMessage("Outbound has been cancelled.");
			   		try {
						await this.fetchSingleOutbound(payload);
						this.dialogCancel = false;
						
						let storeOutboundTab = this.$store.state.outbound
						let dataWithPage = { id: this.singleOutboundData.data.warehouse_id,page: 1}
						if(this.getCurrentOutboundTab !== 'undefined' && this.getCurrentOutboundTab !== undefined){
							if(!this.isWarehouseConnected && !this.isWarehouse6PL){
								if(this.getCurrentOutboundTab == 0){
									dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
									await this.fetchPendingOutbounds(dataWithPage);
								}else if(this.getCurrentOutboundTab == 2){
									dataWithPage.page = storeOutboundTab.completedOutboundPagination.current_page
									await this.fetchCompletedOutbounds(dataWithPage)
								}else {
									if(this.getCurrentOutboundTab == 1){
										dataWithPage.page = storeOutboundTab.floorOutboundPagination.current_page
										await this.fetchFloorOutbounds(dataWithPage);
									}
								}
							}else if(!this.isWarehouseConnected && this.isWarehouse6PL){
								if(this.getCurrentOutboundTab == 0){
									dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
									await this.fetchPendingOutbounds(dataWithPage);
								}else if(this.getCurrentOutboundTab == 1){
									dataWithPage.page = storeOutboundTab.pendingShippingProviderOutboundPagination.current_page
									await this.fetchPendingShippingServiceProvider(dataWithPage)
								}else {
									if(this.getCurrentOutboundTab == 2){
										dataWithPage.page = storeOutboundTab.floorOutboundPagination.current_page
										await this.fetchFloorOutbounds(dataWithPage);
									}
								}
							}
							else{
								if(this.getCurrentOutboundTab == 1){
									dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
									await this.fetchPendingOutbounds(dataWithPage);
								}
							}
							
						}
						if(this.isWarehouse6PL ==true && !this.isWarehouseConnected){
							let warehouse_data = {
								id:this.cancelOrderData.warehouse_customer_id
							}
							this.setAllOutboundProductsLists([])
							await this.Warehouse6PL_ProductsOnchange(warehouse_data)
							await this.callWarehouseCustomerListsData()
						} else{
							this.setAllOutboundProductsLists([])
							this.fetchOutboundProductsAPiFunction('Outbound')
						}
						this.cancelOrderData = null;
					} catch(e) {
						this.notificationError(e)
					} 
	
				} catch (e) {
					this.CancelOrderCheck=true
					this.CancelOrderError=e
			  		this.notificationError(e);
				}
		  	}
		},
		cancellationFailed() {
		  	this.dialogFailed = true;
		},
		closeFailed() {
		  	this.dialogFailed = false;
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
		editOrder(outbound, toDo) {
	  		this.editedOutboundIndex=0
	   		let outboundItem = { ...outbound };
	   		this.outboundProducts_clone =outbound.outbound_products
	  		if (
				!outboundItem.outbound_products ||
				outboundItem.outbound_products.length === 0
	  		) {
				let buildProduct = [
		  			{
						product_id: "",
						quantity: "",
						shipping_unit: "",
						total_unit:"",
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
				// let buildProduct = outboundItem.outbound_products.map((v) => ({
		  		// 	product_id: v.product_id,
		  		// 	quantity: v.carton_count !== null ? parseInt(v.carton_count) : "",
		  		// 	shipping_unit: v.shipping_unit,
		  		// 	total_unit:v.total_unit !==null ? parseInt(v.total_unit):"",
		  		// 	status: v.status,
		  		// 	outbound_product_id:v.id,
		  		// 	pr_id:v.product_id,
		  		// 	remaining_carton_count:v.remaining_carton_count !==null ?v.remaining_carton_count:0,
				// 	remaining_total_unit:v.remaining_total_unit !==null ? v.remaining_total_unit:0,
				// }));
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
				// if(toDo == 'edit'){
				//   productStatusCheck = outboundItem.outbound_products.filter(val=>{
				//  return val.status !=='picked'
				// })
				// buildProduct = productStatusCheck.map((v) => ({
				//   product_id: v.product_id,
				//   quantity: v.carton_count !== null ? parseInt(v.carton_count) : "",
				//   shipping_unit: v.shipping_unit,
				//   total_unit:v.total_unit !==null ? parseInt(v.total_unit):""
				// }));
				// }else{
				//  productStatusCheck = outboundItem.outbound_products
				//  buildProduct = productStatusCheck.map((v) => ({
				//   product_id: v.product_id,
				//   quantity: v.carton_count !== null ? parseInt(v.carton_count) : "",
				//   shipping_unit: v.shipping_unit,
				//   total_unit:v.total_unit !==null ? parseInt(v.total_unit):""
				// }));
				// }

				// if(!productStatusCheck || productStatusCheck.length==0 ){
				//    buildProduct = [
				//   {
				//     product_id: "",
				//     quantity: "",
				//     shipping_unit: "",
				//     total_unit:""
				//   },
				// ];
				// }

				outboundItem = { ...outboundItem, outbound_products: buildProduct };
	  		}

	  		if (
				!outboundItem.outbound_documents ||
				outboundItem.outbound_documents == null
	  		) {
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

	  		if (
				!outboundItem.estimated_time ||
				outboundItem.estimated_time == "null"
	  		) {
				outboundItem = { ...outboundItem, estimated_time: null };
	  		}

	  		if (
				!outboundItem.estimated_date ||
				outboundItem.estimated_date == "null"
	  		) {
				outboundItem = { ...outboundItem, estimated_date: null };
	  		}

	  		// storable units
	  		if (
				!outboundItem.outbound_storable_units ||
				outboundItem.outbound_storable_units.length === 0
	  		) {
				let buildSortable =  [
					{
		  				id: null,
		  				outbound_id: null,
		  				warehouse_id: null,
		  				customer_id: null,
		  				action: null,
		  				label: null,
		  				type: "",
		  				created_at:"",
		 				updated_at:"",
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
								created_at:"",
								updated_at:"", 
							},
		  				],
		  				products: null,
		  				status: "",
		  				no_of_sku: null,
		  				deleted_at: null,
		  				storable_unit_id: null
					},
	  			],
	   			outboundItem = { ...outboundItem, outbound_storable_units: buildSortable }
	  		}
	  		else{
		   		let buildSortable = outboundItem.outbound_storable_units.map(v=>({
			   		id:v.id,
					outbound_id: v.outbound_id,
					warehouse_id: v.warehouse_id,
					customer_id: v.customer_id,
					action: v.action,
					label: v.label,
					type: v.type,
					created_at:v.created_at,
					updated_at:v.updated_at,
				 	parse_dimensions:v.dimension,
					parse_weight:v.weight,
				 	products: v.products,
				  	status:v.status,
					no_of_sku: v.no_of_sku,
					deleted_at: v.deleted_at,
					storable_unit_id: v.storable_unit_id,
					outbound_storable_unit_products:v.outbound_storable_unit_products
		   		}))
				outboundItem = { ...outboundItem, outbound_storable_units: buildSortable }
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
			outboundItem.isDuplicate = toDo == 'edit' ? false : true
			this.editedOutboundIndex = outboundItem.isDuplicate ? -1 : this.editedOutboundIndex
			// if is duplicate, set order id to empty
			outboundItem.order_id = outboundItem.isDuplicate ? '' : outboundItem.order_id 

	  		this.editedOutboundItems = Object.assign({}, outboundItem);
	  		this.dialogCreate = true;
	  		if (this.isWarehouse6PL == true && !this.isWarehouseConnected) {
				let warehouse_data = {
					id:outboundItem.warehouse_customer_id
				}
				this.Warehouse6PL_ProductsOnchange(warehouse_data)
			} else {
				this.fetchOutboundProductsAPiFunction('Outbound')
			}
		},
		showMoreText() {
	  		if (
				this.singleOutboundData.data.notes !== null &&
				this.singleOutboundData.data.notes.length > 256
	  		) {
				this.notesModel = true;
				this.mynotes = this.singleOutboundData.data.notes;
	  		}
		},
		closeCreate() {
	  		this.dialogCreate = false;
	  		this.editedOrderIndex =0
		},
		checkStatus(status) {
			return status.status == 'picked';
		},
	 	BuildButtonCondition(outbound){
	  		if(typeof outbound.data !=='undefined' && outbound.data !== null && outbound.data !== 'null'){
	  			let result = false
	  			if(outbound.data.outbound_products.length>0){
					result = outbound.data.outbound_products.some(this.checkStatus)
	  			}
	  			if(result ==true && (outbound.data.outbound_status !=='cancelled' || outbound.data.outbound_status !=='completed')){
					result =false
	  			}else{
					result =true
	  			}
	  				return result
	  		}  
		},
		showCancelButton(outbound){
		 	if(typeof outbound.data !=='undefined' && outbound.data !== null && outbound.data !== 'null' && outbound.data.outbound_storable_units.length>0){
		   		let result = true
				outbound.data.outbound_storable_units.find(val=>{
			 		if( val.status =='loaded' ||  outbound.data.outbound_status =='cancelled' || outbound.data.outbound_status =='completed' ){
						return result = false
			 		}
				})
		  		return result
		  	}
		  	else if(typeof outbound.data !=='undefined' && outbound.data !== null && outbound.data !== 'null'){
			  	return ( outbound.data.outbound_status !=='cancelled' && outbound.data.outbound_status !=='completed' ) 
		  	}
		},
		conditionForStorableTab(index){
			if(index =='Storable Unit' && this.isWarehouse3PL==true){
				return false
			}
			else{
				return true
			}
		},
  		ConditionForEditstatus(status){
			if( this.isWarehouse3PL ===false){
	  			return ( status !=='cancelled' && status !=='completed' && status !== 'rejected' ) 
			}else{
				return  status !=='cancelled'
			}
  		},
  		LoadAllProducts3PLDialogOpen(){
			if(typeof this.singleOutboundData.data !== 'undefined' &&
	   		this.singleOutboundData.data !== "" && this.singleOutboundData.data !== null && this.singleOutboundData.data !== "undefined"){
	  			if(this.singleOutboundData.data.outbound_products !=="undefined" && this.singleOutboundData.data.outbound_products !==null && 
	 			this.singleOutboundData.data.outbound_products.length>0){
	  				let result = this.singleOutboundData.data.outbound_products.filter(val => val.status !=='loaded')
	  				var result2 = result.filter((dropdown) => {
	  					return this.productsListsData.some((product_row) => dropdown.product_id === product_row.product_id && ((dropdown.expected_carton_count > 0 && dropdown.expected_carton_count <= product_row.expected_carton_count) || (dropdown.total_unit > 0 && dropdown.total_unit <= product_row.total_unit) ) ) 
	  				});
	  				if(result2.length>0) return this.LoadAllProducts3PLDialog=true
	  				return this.notificationError("We don't have enough product in the inventory")
	 			}
	   		}
  		},
  		async LoadAllProducts3PLFunction(){
			if(typeof this.singleOutboundData.data !== 'undefined' &&
		   	this.singleOutboundData.data !== "" && this.singleOutboundData.data !== null && this.singleOutboundData.data !== "undefined"){
		  		if(this.singleOutboundData.data.outbound_products !=="undefined" && this.singleOutboundData.data.outbound_products !==null && 
					this.singleOutboundData.data.outbound_products.length>0){
		  			let finalVAlue = this.singleOutboundData.data.outbound_products.filter(val=> val.status !=='loaded')
		  			if(finalVAlue.length==0) return
		  			let Send_payload =[]
		  			finalVAlue = finalVAlue.filter(val =>{
						Send_payload.push(val.id)
		  			})
		  			let payload ={
						wid:this.singleOutboundData.data.warehouse_id,
						oid:this.singleOutboundData.data.id,
						idss:Send_payload
					}
					try{
						await this.multipleLoadProductApi(payload)
			  			this.notificationMessage('Product Loaded sucessfully')
			  			let payload_single={
							wid:this.singleOutboundData.data.warehouse_id,
							oid:this.singleOutboundData.data.id
						}
						this.LoadAllProducts3PLDialog=false
						await this.fetchSingleOutbound(payload_single);
						this.setAllOutboundProductsLists([])
						this.$emit('fetchOutboundProductsAPiFunction','Outbound')
						if(this.getCurrentOutboundTab !== 'undefined' && this.getCurrentOutboundTab !== undefined){
							let storeOutboundTab = this.$store.state.outbound 
							let dataWithPage = { id: this.singleOutboundData.data.warehouse_id,page: 1}
							if(!this.isWarehouseConnected){
								if(this.getCurrentOutboundTab == 0){
									dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
									await this.fetchPendingOutbounds(dataWithPage);
								}else{
									if(this.getCurrentOutboundTab == 2){
										dataWithPage.page = storeOutboundTab.completedOutboundPagination.current_page
										await this.fetchCompletedOutbounds(dataWithPage)
									}
								}
							}else{
								if(this.getCurrentOutboundTab == 1){
									dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
									await this.fetchPendingOutbounds(dataWithPage);
								}
							}	
						}		
					}catch(e){
						this.notificationError(e)
					}
		 		}
			}
  		},
  		async fetchOutboundProductsAPiFunction(from){
			if(from =='6plProvider'){
	  			this.setAllOutboundProductsLists([])
			}
	  		let payload =null
	  		if(this.currentWarehouseSelected !==null && this.currentWarehouseSelected !=='undefined' && this.currentWarehouseSelected !==undefined){
	  			payload =this.currentWarehouseSelected
	 		}
	  		try {
				if (this.getAllOutboundProductsListsDropdownData.length === 0) {
					this.current_page_is = 1
					this.fetchProductLoading =true
					let sendData ={
					  wid:payload,
					  page:this.current_page_is
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
									units_per_carton: value.units_per_carton,
									total_unit:value.total_unit,
									carton_count:value.carton_count,
									expected_carton_count: value.expected_carton_count,
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
									units_per_carton: null,
									total_unit:null,
									carton_count:null,
									expected_carton_count:null,
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
	  				payload =this.currentWarehouseSelected
				}
				let sendData ={
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
		// 6pl
		async Warehouse6PL_ProductsOnchange(val) {
			this.fetchProductLoading = true
			this.productsListsData = []
			this.lastDataCheck = []

			if (val.id !== null) {
				try {
					let customer_id = (typeof this.getUser=='string') ? 
						JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id
					let warehouse_customer_id = val.id
					this.current_page_6pl_products = 1
					let wid =null
					if(this.currentWarehouseSelected !==null && this.currentWarehouseSelected !=='undefined' && this.currentWarehouseSelected !==undefined){
					  	wid =this.currentWarehouseSelected
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
				await this.fetchOutboundProductsAPiFunction('6plProvider')
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
					wid =this.currentWarehouseSelected
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
					let payload = {
						wid: this.draftOutboundData.warehouse_id,
						oid: this.draftOutboundData.id,
			  		};
	 				await this.fetchSingleOutbound(payload);
					try {
						let storeOutboundTab = this.$store.state.outbound 
						let dataWithPage = { id: this.currentWarehouseSelected,page: 1}
						if(this.getCurrentOutboundTab !== 'undefined' && this.getCurrentOutboundTab !== undefined){
							if(this.isWarehouseConnected){
								if (this.getCurrentOutboundTab == 0) {
									dataWithPage.page = storeOutboundTab.draftOutboundPagination.current_page
									await this.fetchDraftOutbounds(dataWithPage);
								}
							}
						
							this.confirmDraftSubmit = false;
							this.draftOutboundData = null;
						}
					} catch (e) {
						this.notificationError(e);
					}
	
				} catch (e) {
			  		this.notificationError(e);
				}
		  	}
		},
		canceldelete(outbound) {
			if (outbound !== null) {
				this.deleteOrderData = outbound;
				this.dialogCanceldelete = true;
			}
		},
		closeDelete() {
			this.dialogCanceldelete = false;
		},
		async deleteConfirm() {
			if (this.deleteOrderData !== null) {
				try {
					let storeOutboundTab = this.$store.state.outbound
					let dataWithPage = { id: this.currentWarehouseSelected,page: 1}
					let payloadDelete = {
						wid: this.deleteOrderData.warehouse_id,
						oid: this.deleteOrderData.id,
					};
					await this.deleteOutbound(payloadDelete);
					this.notificationMessage("Outbound has been deleted.");
				
					dataWithPage.page = storeOutboundTab.draftOutboundPagination.current_page
					await this.fetchDraftOutbounds(dataWithPage);
					this.dialogCanceldelete = false;
					this.deleteOrderData = null;
					setTimeout(() => {
						this.$router.push(`/inventory?tab=Outbound`);
						this.$store.dispatch("page/setCurrentInventoryTab", 4)
					}, 1000);
					
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
	 				let payload = {
						wid: this.acceptConnectedWarehouseOrderData.warehouse_id,
						oid: this.acceptConnectedWarehouseOrderData.id,
			  		};
	 				await this.fetchSingleOutbound(payload);
					try {
					let storeOutboundTab = this.$store.state.outbound 
					let dataWithPage = { id: this.currentWarehouseSelected,page: 1}
						if(this.isWarehouse6PL){
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
					let payloadReject = {
						oid:this.rejectConnectedWarehouseOrderData.id,
						notes : this.reasonForRejectionOutboundOrder
					}
			  		await this.RejectConnectedOrderApi(payloadReject);
	
			  		this.notificationMessage("Outbound has been Accepted Successfully.");
	 				let payload = {
						wid: this.acceptConnectedWarehouseOrderData.warehouse_id,
						oid: this.acceptConnectedWarehouseOrderData.id,
			  		};
	 				await this.fetchSingleOutbound(payload);
					try {
					let storeOutboundTab = this.$store.state.outbound 
					let dataWithPage = { id: this.currentWarehouseSelected,page: 1}
						if(this.isWarehouse6PL){
								dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
								await this.fetchPendingOutbounds(dataWithPage);
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
		}
  	},
  	async mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "inventory");
		this.fetchSingleOutboundLoading = true

		let oid = new URL(location.href).searchParams.get("id");
		let wid = new URL(location.href).searchParams.get("wid");
		this.currentWarehouseSelected = wid;
		this.currentWarehouseSelectedData = this.$store.state.warehouse.currentWarehouse
		if (wid !== null && oid !== null) {
	  		let payload = { wid: wid, oid: oid };

	  		try {
	  		  	await this.fetchSingleOutbound(payload);
				this.componentLoaded = true;
	  		  	this.fetchSingleOutboundLoading = false
	  		  	await this.fetchSingleWarehouse(wid);
	  		  	this.$store.dispatch("page/setCurrentInventoryTab", 4);
	  		  	


	  		  	if (typeof this.getSingleWarehouse !== "undefined") {
	  				this.$store.state.warehouse.currentWarehouse = this.getSingleWarehouse;
	  			  	let currentWarehouse = this.$store.state.warehouse.currentWarehouse
	  			  	if (currentWarehouse !== null) {
	  					if (currentWarehouse.warehouse_type === '3PL Provider') {
	  						this.callWarehouseCustomerListsData()
	  						await this.fetchOutboundProductsAPiFunction('Outbound')
	  						this.setWarehouseTypeHas3PL(true)
	  					} else {
	  						await this.fetchOutboundProductsAPiFunction('Outbound')
	  					}
	  				}
	  		  	}
	  		} catch (e) {
			this.notificationError(e);
			this.fetchSingleOutboundLoading = false
	  		}
		}
  	},

  	updated() {},
};
</script>

<style lang="scss">
	@import "@/assets/scss/buttons.scss";
	@import "@/assets/scss/pages_scss/inventory/outbound/outboundView.scss";
	@import "@/assets/scss/tables.scss";
	
	.notes-card-button-attach {
	   position: sticky !important;
	}
	.classForStorableTab{
	min-width: 1px !important;
	padding: 0 !important;
	}
</style>