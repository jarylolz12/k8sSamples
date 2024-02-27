<template>
	<div v-if="productsData.outbound_products" class="outbound-product-section">
		<!-- <div class="btn-headerMenus mt-n1" v-if="selected.length > 0 && 
				outboundProductsData.outbound_status !== 'pending' && outboundProductsData.outbound_status !=='floor' "  > -->
		<div class="btn-headerMenus mt-n1" v-if="selected.length > 0 && !isWarehouseConnected && hideBatchPickedButton">
			<button  @click="clearSelection" :disabled="disableClearSelection(outboundProductsData.outbound_status)">
				<span class="btn-clear"> Clear Selection </span>
			</button>
			<button v-if="hidewareFloorTab ==true" @click="multipleLoadProducts" :disabled="outboundProductsData.outbound_status == 'cancelled' || outboundProductsData.outbound_status == 'rejected'">
				<span class="btn-whitee"> Batch Load </span>
			</button>
			<button v-if="hidewareFloorTab ==false" @click="createPickProducts" :disabled="outboundProductsData.outbound_status == 'cancelled' || outboundProductsData.outbound_status == 'completed' || outboundProductsData.outbound_status == 'rejected' ">
				<span class="btn-whitee"> Batch Picked </span>
			</button>
			<!-- <button v-if="hidewareFloorTab ==true" @click="SelectAllProdsucts" :disabled="outboundProductsData.outbound_status=='cancelled'">
				<span class="btn-whitee"> Batch All Loaded</span>
			</button> -->
		</div>
		<v-data-table
			:headers="headersComputed"
			:items="productsData.outbound_products"
			item-key="id"
			class="elevation-1 outbound-view-products"
			:class="
				typeof productsData.outbound_products !== 'undefined' &&
				productsData.outbound_products !== null &&
				productsData.outbound_products.length > 0
				? ''
				: 'no-data-table'"
			mobile-breakpoint="769"
			hide-default-footer
			v-model="selected"
			:single-select="singleSelect"
			show-select>

			<template v-slot:no-data>
				<div class="no-data-wrapper" v-if="productsData.outbound_products.length == 0">
					<div class="no-data-heading">
						<div>
							<h3>No Products</h3>
							<p>You can start adding products by editing this Outbound.</p>
						</div>
					</div>
				</div>
			</template>

			<template v-slot:[`item.sku`]="{ item }">
				<div class="name-wrapper" v-if="!isMobile">
					<p>{{item.product.category_sku !== '' && 
                            item.product.category_sku !== null ? 
                            item.product.category_sku : '--'}}</p>
					<span class="mb-0" style="color: #6D858F;">
						{{ item.product_name !== "" && 
							item.product_name !== null && 
							item.product_name !== 'null' ? 
							item.product_name : "--" }}
					</span>
				</div>

				<div class="mobile-content-wrapper" v-if="isMobile">
					<div class="mobile-content">
						<span class="mr-1">SKU</span>
						<span class="skuColor">#{{item.product.category_sku !== '' && 
                            item.product.category_sku !== null ? 
                            item.product.category_sku : '--'}}</span>
					</div>
						<div class="actions-wrapper" v-if="selected.length == 0 && hidewareFloorTab ==false && !isWarehouseConnected">
					<button
						:class="item.status == 'picked'  && outboundProductsData.outbound_status !== 'cancelled' && outboundProductsData.outbound_status !== 'completed' && outboundProductsData.outbound_status !== 'rejected' ? 'myclass' : 'btn-status btn-whitee'"
						:disabled="outboundProductsData.outbound_status == 'cancelled' || outboundProductsData.outbound_status == 'completed' || outboundProductsData.outbound_status == 'rejected' "
						@click.stop="pickProduct(item)">

						<span class="btn-content">
							<span v-if="item.status == 'picked'">
								<template>
									<div class="d-flex align-center" :class="outboundProductsData.outbound_status == 'cancelled' || outboundProductsData.outbound_status == 'completed' || outboundProductsData.outbound_status == 'rejected'? '':'myclass' ">
										<img
										class="py-0"
										src="@/assets/icons/checkMark-green.svg"
										width="12"/>

										<span class="ml-1 greenColor" 
											style="font-size: 12px !important; text-transform: capitalize;">
											{{ item.status }}
										</span>
									</div>
								</template>
							</span>

							<span v-else>
								{{ item.status !== null && item.status !== ""
									? item.status
									: "Picked" 
								}}
							</span>
						</span>
					</button>

					<!-- <button :disabled="outboundProductsData.outbound_status=='cancelled' || outboundProductsData.outbound_status=='completed' " v-if=" outboundProductsData.outbound_status !== 'pending'" class="btn-status btn-white btn-dotted">
						<span class="btn-content">
							<img src="@/assets/icons/dots.svg" width="13" />
						</span>
					</button> -->
				</div>
				<div  class="actions-wrapper" v-if="selected.length == 0 && isMobile && hidewareFloorTab ==true && !isWarehouseConnected">
				<button
						:class="item.status == 'loaded' && outboundProductsData.outbound_status !== 'cancelled' && outboundProductsData.outbound_status !== 'completed' && outboundProductsData.outbound_status !== 'rejected' ? 'myclass' : 'btn-status btn-whitee'"
						:disabled="outboundProductsData.outbound_status == 'cancelled' || outboundProductsData.outbound_status == 'rejected' || (outboundProductsData.outbound_status == 'completed' && item.status == 'loaded' )"
						@click.stop="loadProductFunction(item)">

						<span class="btn-content">
							<span v-if="item.status == 'loaded'">
								<template>
									<div class="d-flex align-center " :class="outboundProductsData.outbound_status == 'cancelled' ||  outboundProductsData.outbound_status == 'completed' || outboundProductsData.outbound_status == 'rejected'? '':'myclass' ">
										<img
										class="py-0"
										src="@/assets/icons/checkMark-green.svg"
										width="12"/>

										<span class="ml-1 greenColor" 
											style="font-size: 12px !important; text-transform: capitalize;">
											{{ item.status }}
										</span>
									</div>
								</template>
							</span>

							<span v-else>
								{{ item.status !== null && item.status !== ""
									? item.status
									: "Load" 
								}}
							</span>
						</span>
					</button>
				</div>
				</div>
			</template>

			<template v-slot:[`item.storable_units`]="{ item }">
				<div class="name-wrapper" v-if="!isMobile">
					<p>
						{{
							item.storable_units !== "" &&
							item.storable_units !== null &&
							item.storable_units.length >= 1
								? item.storable_units[0].label
							: "--"
						}}
					</p>
				</div>
			</template>

			<!-- <template v-slot:[`item.work_order`]="{ item }">
				<div class="work-order-wrapper" v-if="!isMobile">
				<p class="blueTxt">
					{{
					typeof item.work_order !== "undefined" ? item.work_order : "--"
					}}
				</p>
				</div>

				<div class="mobile-content-wrapper" v-if="isMobile">
				<div class="mobile-content">
					<p>WORK ORDER</p>
					<span>{{
					typeof item.work_order !== "undefined" ? item.work_order : "--"
					}}</span>
				</div>
				</div>
			</template> -->

			<template v-slot:[`item.carton_count`]="{ item }">
				<div class="name-wrapper" v-if="!isMobile">
					<p>
						{{
							item.carton_count !== "" && item.carton_count !== null
							? item.carton_count
							: "--"
						}}
					</p>
				</div>

				<div class="mobile-content-wrapper dgrid" v-if="isMobile">
					<div class="mobile-content">
						<p>CARTON</p>
						<span>
							{{ typeof item.carton_count !== "undefined" && item.carton_count !== null
								? item.carton_count
								: "--"
							}}
						</span>
					</div>

					<div class="mobile-content">
						<p>IN EACH</p>
						<span>
							{{ typeof item.in_each_carton !== "undefined" && item.in_each_carton !== null
								? item.in_each_carton
								: "--" 
							}}
						</span>
					</div>

					<div class="mobile-content">
						<p>UNIT</p>
						<span>{{ typeof item.total_unit !== "undefined" && item.total_unit !==null ? item.total_unit : "--" }}</span>
					</div>
				</div>
			</template>

			<template v-slot:[`item.in_each_carton`]="{ item }">
				<div class="name-wrapper" v-if="!isMobile">
					<p>
						{{ item.in_each_carton !== "" && item.in_each_carton !== null
							? item.in_each_carton
							: "--"
						}}
					</p>
				</div>
			</template>

			<template v-slot:[`item.total_unit`]="{ item }">
				<div class="name-wrapper" v-if="!isMobile">
					<p>
						{{
						item.total_unit !== "" && item.total_unit !== null
							? item.total_unit
							: "--"
						}}
					</p>
				</div>
			</template>
			<template v-slot:[`item.notes`]="{ item }">
                <div> {{ item.instructions !==null &&  item.instructions !=='' ?  item.instructions :'--'  }} </div>
            </template>

			<template v-slot:[`item.actions`]="{ item }">
				<div class="actions-wrapper" v-if="selected.length == 0 && !isMobile && !hidewareFloorTab && !isWarehouseConnected">
					<button
						:class="item.status == 'picked' && outboundProductsData.outbound_status !=='cancelled' && outboundProductsData.outbound_status!=='completed' && outboundProductsData.outbound_status !== 'rejected' ? 'myclass' : 'btn-status btn-whitee'"
						:disabled="outboundProductsData.outbound_status == 'cancelled' || outboundProductsData.outbound_status == 'completed' || outboundProductsData.outbound_status == 'rejected' || (isWarehouse6PL && outboundProductsData.outbound_status == 'pending' && outboundProductsData.is_from_connected_3pl == 1) || !item.is_in_inventory"
						@click.stop="pickProduct(item)"
						:title="!item.is_in_inventory ? 'This product is not from inventory' : ''">

						<span class="btn-content">
							<span v-if="item.status == 'picked'">
								<template>
									<div class="d-flex align-center " :class="outboundProductsData.outbound_status == 'cancelled' ||  outboundProductsData.outbound_status == 'completed' || outboundProductsData.outbound_status == 'rejected'? '':'myclass' ">
										<img
										class="py-0"
										src="@/assets/icons/checkMark-green.svg"
										width="12"/>

										<span class="ml-1 greenColor" 
											style="font-size: 12px !important; text-transform: capitalize;">
											{{ item.status }}
										</span>
									</div>
								</template>
							</span>

							<span v-else>
								{{ item.status !== null && item.status !== ""
									? item.status
									: "Picked" 
								}}
							</span>
						</span>
					</button>

					<!-- <button v-if=" outboundProductsData.outbound_status !== 'pending'" class="btn-status btn-white btn-dotted">
						<span class="btn-content">
							<img src="@/assets/icons/dots.svg" width="13" />
						</span>
					</button> -->
				</div>
				<div  class="actions-wrapper" v-if="selected.length == 0 && !isMobile && hidewareFloorTab ==true && !isWarehouseConnected">
					<button
						:class="item.status == 'loaded' && outboundProductsData.outbound_status !=='cancelled' && outboundProductsData.outbound_status!=='completed' ? 'myclass' : 'btn-status btn-whitee'"
						:disabled="outboundProductsData.outbound_status == 'cancelled' || outboundProductsData.outbound_status == 'rejected' || (outboundProductsData.outbound_status=='completed' && item.status =='loaded' )"
						@click.stop="loadProductFunction(item)">

						<span class="btn-content">
							<span v-if="item.status == 'loaded'">
								<template>
									<div class="d-flex align-center " :class="outboundProductsData.outbound_status == 'cancelled' || outboundProductsData.outbound_status == 'rejected' ||  outboundProductsData.outbound_status == 'completed'? '':'myclass' ">
										<img
										class="py-0"
										src="@/assets/icons/checkMark-green.svg"
										width="12"/>

										<span class="ml-1 greenColor" 
											style="font-size: 12px !important; text-transform: capitalize;">
											{{ item.status }}
										</span>
									</div>
								</template>
							</span>

							<span v-else>
								{{ item.status !== null && item.status !== ""
									? item.status
									: "Load" 
								}}
							</span>
						</span>
					</button>
				</div>
			</template>
		</v-data-table>
		<v-dialog v-model="loadProductDialog" width="500" content-class="outbound-dialog-pick">
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
					v-if="multipleLoadDialog"
                    :disabled="getsetBatchProductLoading" class="btn-blue confirm-pick" @click="LoadProductMultiple">         

                        <span>{{ getsetBatchProductLoading ? 'Confirming...' : 'Confirm Load'}}</span>  
                    </button>
					<button 
					v-else
                    :disabled="getloadProductLoading" class="btn-blue confirm-pick" @click="LoadProduct">         

                        <span>{{ getloadProductLoading ? 'Confirming...' : 'Confirm Load'}}</span>  
                    </button>
                    <button class="btn-white cancel-pick" @click="loadProductDialogClose">
                        Cancel
                    </button>
                </v-card-actions>
            </v-card>
        </v-dialog>
		
		<PickProductsDialog
			@close="close"
			:dialogCreatepick.sync="dialog_pick_products"
			:MUltiplePickProductData="MUltiplePickProductData"
			:sortableData="sortableData"
			:locationDataDropdown="locationDataDropdown"
			:StorableUnitDataDropdown="StorableUnitDataDropdown"
			:batchPickProduct_loader="batchPickProduct_loader"
			:singleOutbound_status="singleOutbound_status"
			:isWarehouse6PL="isWarehouse6PL"
			:isWarehouseConnected="isWarehouseConnected"
			@Warehouse6PL_ProductsOnchange="Warehouse6PL_ProductsOnchange"
			@fetchOutboundProductsAPiFunction="fetchOutboundProductsAPiFunction"
			:productsData="productsData"
			:pickMultipleData="pickMultipleData" />

		<SinglePickProduct
			@closeSinglePick="closeSinglePick"
			:dialogCreateSingle.sync="dialogPick"
			:currentPickedProduct="currentPickedProduct"
			:pickProductsDAta="pickProductsDAta"
			:sortableData="sortableData"
			:isMobile="isMobile"
			:singlePickProductloader="singlePickProductloader"
			:singleOutbound_status="singleOutbound_status"
			:isWarehouse6PL="isWarehouse6PL"
			:isWarehouseConnected="isWarehouseConnected"
			@Warehouse6PL_ProductsOnchange="Warehouse6PL_ProductsOnchange"
			@fetchOutboundProductsAPiFunction="fetchOutboundProductsAPiFunction"
			:productsData="productsData" />
	</div>
</template>

<script>
import { mapActions,mapMutations,mapGetters } from "vuex";
import globalMethods from "../../../../utils/globalMethods";
import PickProductsDialog from "../Dialogs/PickProductsDialog.vue";
import SinglePickProduct from "..//Dialogs/SinglePickProduct.vue";
import axios from "axios";
import _ from "lodash";

export default {
	name: "ProductSection",
	props: ["outboundProductsData", "isMobile","hidewareFloorTab","productsListsData","singleOutbound_status","isWarehouseConnected","isWarehouse6PL", "isWarehouse3PL"],
	components: {
		PickProductsDialog,
		SinglePickProduct,
	},
	data: () => ({
		productsHeader: [
		{
			text: "SKU & NAME",
			align: "start",
			sortable: false,
			value: "sku",
			fixed: true,
			width: "25%",
		},
		{
			text: "STORABLE UNIT",
			align: "start",
			sortable: false,
			value: "storable_units",
			fixed: true,
			width: "20%",
		},
		// {
		//   text: "WORK ORDER",
		//   align: "start",
		//   sortable: false,
		//   value: "work_order",
		//   fixed: true,
		//   width: "15%",
		// },
		{
			text: "CARTON",
			align: "end",
			sortable: false,
			value: "carton_count",
			fixed: true,
			width: "12%",
		},
		{
			text: "IN EACH",
			align: "end",
			sortable: false,
			value: "in_each_carton",
			fixed: true,
			width: "12%",
		},
		{
			text: "UNIT",
			align: "end",
			sortable: false,
			value: "total_unit",
			fixed: true,
			width: "12%",
		},
		{
			text: "NOTE",
			align: "start",
			sortable: false,
			value: "notes",
			fixed: true,
			width: "25%",
		},
		{
			text: "",
			align: "end",
			value: "actions",
			sortable: false,
			fixed: true,
			width: "20%",
		},
		],
		dialogPick: false,
		batchPickDialog: false,
		currentPickedProduct: null,
		selected: [],
		singleSelect: false,
		linkData: {},
		dialog_pick_products: false,
		PickProductData: {},
		pickProductsDAta: [],
		MUltiplePickProductData: [],
		locationDataDropdown: [],
		StorableUnitDataDropdown: [],
		selectedValue:[],
		singlePickProductloader:false,
		batchPickProduct_loader:false,
		loadProductDialog:false,
		loadProductData:{},
		selectedLoaded:[],
		sendataLoaded:[],
		multipleLoadDialog:false,
		pickMultipleData:[],
		pickFromArray:[{name:'Storable Unit',value:'storable_unit'},{name:'Floor',value:'floor'}]
	}),
	computed: {
		...mapGetters({getloadProductLoading:"outbound/getloadProductLoading",
			getsetBatchProductLoading:"outbound/getsetBatchProductLoading",
			getCurrentOutboundTab: "outbound/getCurrentOutboundTab"
			}),
		productsData: {
			get() {
				let newProductsData = [];

				if ( typeof this.outboundProductsData !== "undefined" && this.outboundProductsData !== null ) {
					newProductsData = this.outboundProductsData;
				}

				return newProductsData;
			},
			set(value) {
				this.$emit("update:outboundProductsData", value);
			},
		},
		sortableData: {
			get() {
				let newStorableUnitData = [];

				if ( typeof this.outboundProductsData !== "undefined" && this.outboundProductsData !== null ) {
					if ( this.outboundProductsData.outbound_storable_units !== "undefined" &&
						this.outboundProductsData.outbound_storable_units.length > 0 ) {
						newStorableUnitData = this.outboundProductsData.outbound_storable_units;
					}
				}

				return newStorableUnitData;
			},
			set(value) {
			this.$emit("update:outboundProductsData", value);
			},
		},
		hideBatchPickedButton(){
			let status = true
			if(this.outboundProductsData !== 'undefined' && this.outboundProductsData !== undefined){
				if(this.isWarehouse6PL && this.outboundProductsData.outbound_status == 'pending' && this.outboundProductsData.is_from_connected_3pl == 1 ){
					status = false
				}else{
					status = true
				}
			}
			return status
		},
		headersComputed() {
            let defaultHeaders = this.productsHeader

			// if warehouse is 3pl type, do not show storable unit and note field in products tab
            if (this.isWarehouse3PL) {
                defaultHeaders = defaultHeaders.filter(v => {
					if (v.text === 'SKU & NAME') {
						v.width = '30%'
					}

					if (v.text === '') {
						v.width = ''
					}
					
                    return v.text !== 'STORABLE UNIT' && v.text !== 'NOTE' 
                })
            } else { // if warehouse is provider, show note as instructions to both connected and 3pl provider
                if (this.isWarehouse6PL) {
                    defaultHeaders = defaultHeaders.filter(v => {
                        if (v.text === 'STORABLE UNIT') {
                            v.width = '15%'
                        }

                        if (v.text === 'NOTE') {
							v.text = 'INSTRUCTIONS'
						}
                        
                        return defaultHeaders
                    })
                } else { // if warehouse is own type, do not show note field in products tab
                    defaultHeaders = defaultHeaders.filter(v => {
                        if (v.text === 'STORABLE UNIT') {
                            v.width = '15%'
                        }
                        return v.text !== 'NOTE' 
                    })
                }
            }

            return defaultHeaders
        }
	},
	methods: {
		...mapActions({
			pickproduct: "outbound/pickproduct",
			BatchPickedApi: "outbound/BatchPickedApi",
			fetchSingleOutbound: "outbound/fetchSingleOutbound",
			pickOutboundLocationApi: "outbound/pickOutboundLocationApi",
			pickOutboundStorableApi: "outbound/pickOutboundStorableApi",
			loadProductApi:"outbound/loadProductApi",
			multipleLoadProductApi:"outbound/multipleLoadProductApi",
			setAllOutboundProductsLists: 'outbound/setAllOutboundProductsLists',
			fetchPendingOutbounds: "outbound/fetchPendingOutbounds",
			fetchCompletedOutbounds: "outbound/fetchCompletedOutbounds",
		}),
		...mapMutations({EMPTY_STORABLE_UNIT_lOCATION:"outbound/EMPTY_STORABLE_UNIT_lOCATION"}),
		...globalMethods,
		async pickProduct(item) {
			this.dialogPick = true;
			this.singlePickProductloader = true;
			this.$nextTick(() => {
				this.currentPickedProduct = Object.assign({}, item);
			});

			let { carton_count, total_unit, in_each_carton } = item;
			// let { id } = item.storable_units.length>0
			//   ? item.storable_units[0].label
			//   : "";
			//   let {location_id} =item.storable_units.length>0 && item.storable_units[0].location_id !==null
			//   ? item.storable_units[0].location_id
			//   : "";
			let newItem = [
				{
					carton_count: carton_count !== null ? parseInt(carton_count) : 0,
					total_unit: total_unit !== null ? parseInt(total_unit) : 0,
					in_each_carton,
					label: "",
					location_id: "",
					whole:0,
					pick_from:''
				},
			];

			this.pickProductsDAta = newItem;
			await this.pickOutboundLocation(item);
			await this.pickOutboundStorableUnit(item);
			this.singlePickProductloader=false
		},
		async pickOutboundLocation(item) {
			// console.log("pick product", item);
			let payload = {
				oid: this.linkData.oid,
				wid: this.linkData.wid,
				loc_id: item.id,
			};

			try {
				await this.pickOutboundLocationApi(payload);
			} catch (e) {
				this.notificationError(e);
			}
		},
		async pickOutboundStorableUnit(item) {
			// console.log("pick product", item);
			let payload = {
				oid: this.linkData.oid,
				wid: this.linkData.wid,
				str_id: item.id,
			};

			try {
				await this.pickOutboundStorableApi(payload);
			} catch (e) {
				this.notificationError(e);
			}
		},
		clearSelection() {
			this.selected = [];
			this.selectedValue=[];
			this.locationDataDropdown = [];
			this.StorableUnitDataDropdown = [];
			this.pickMultipleData = []
		},
		close() {
			this.dialog_pick_products = false;
			this.$nextTick(() => {
				// this.editedOrderItems = Object.assign({}, this.defaultOrderItems)
				this.MUltiplePickProductData = [];
				this.selected = [];
				this.selectedValue=[];
				this.locationDataDropdown = [];
				this.StorableUnitDataDropdown = [];
				this.pickMultipleData = []
			});
		},
		closeSinglePick() {
			this.dialogPick = false;
			this.EMPTY_STORABLE_UNIT_lOCATION([])
			this.$nextTick(() => {
				// this.editedOrderItems = Object.assign({}, this.defaultOrderItems)
				this.currentPickedProduct = Object.assign({});
				this.pickProductsDAta =[]
			});
		},
		async createPickProducts() {
			
			this.selectedValue = this.selected.filter(val=>{
				return	val.status !== 'picked' && val.is_in_inventory
			})
			if(this.selectedValue.length==0) return
			this.dialog_pick_products = true;
			this.batchPickProduct_loader=true;
			// console.log(this.selectedValue)
			let buildProductss = this.selectedValue.map((val) => [
				{
					carton_count:
					val.carton_count !== null ? parseInt(val.carton_count) : 0,
					total_unit: val.total_unit !== null ? parseInt(val.total_unit) : 0,
					in_each_carton: val.in_each_carton,
					label: "",
					// val.storable_units.length > 0 && val.storable_units[0].label !==null ? val.storable_units[0].label : "",
					location_id: "",
					whole:0,
					pick_from:''
					// val.storable_units.length>0 && val.storable_units[0].location_id !==null ? val.storable_units[0].location_id:'',
				},
			]);

			this.$nextTick(() => {
				// this.PickProductData = Object.assign({}, this.defaultOrderItems)
				this.PickProductData = this.selectedValue;
				let mydata = buildProductss.map((val, index) => {
				return { val, ...this.PickProductData[index] };
			});

			this.MUltiplePickProductData = mydata;
			// console.log("Batch Picked Data", this.MUltiplePickProductData);
			});
			await this.getAllStorableUnitsMultiple();
			await this.getAllLocationsMultiple();
			this.batchPickProduct_loader=false
		},
		async getAllStorableUnitsMultiple() {
			const poBaseUrl = process.env.VUE_APP_PO_URL;

			try {
				const getAllStorableUnits = await Promise.all(this.selectedValue.map(async (item) => {
					const response = await axios.get(
					`${poBaseUrl}/warehouse/${this.linkData.wid}/outbound/${this.linkData.oid}/get-storable-units/${item.id}`
					);

					return await response;
					})
				);

				getAllStorableUnits.map((val) => {
					if (val !== undefined && val !== "undefined") {
						if (val.status == 200) {
							this.pickMultipleData.push(this.pickFromArray)
							return this.StorableUnitDataDropdown.push(val.data.results);
						} else {
							return this.notificationError(val);
						}
					} else {
						this.notificationError(
							"Something is wrong while getting your Storable Unit. Please try again later."
						);
					}
				});
			} catch (e) {
				this.notificationError(e.error);
			}
		},
		async getAllLocationsMultiple() {
			const poBaseUrl = process.env.VUE_APP_PO_URL;

			try {
				const getAllLocations = await Promise.all(this.selectedValue.map(async (item) => {
					const response = await axios.get(
					`${poBaseUrl}/warehouse/${this.linkData.wid}/outbound/${this.linkData.oid}/get-locations/${item.id}`
					);

					return await response;
					})
				);

				getAllLocations.map((val) => {
					if (val !== undefined && val !== "undefined") {
						if (val.status == 200) {
							return this.locationDataDropdown.push(val.data.results);
						} else {
							return this.notificationError(val);
						}
					} else {
						this.notificationError(
						"Something is wrong while getting your location. Please try again later."
						);
					}
				});
			} catch (e) {
				this.notificationError(e.error);
			}
		},
		loadProductFunction(item){
			if(this.hidewareFloorTab == true){
				let findItem = _.find(this.productsListsData, (e) => e.product_id === item.product_id)
        		if (findItem !== "undefined" && findItem !== undefined) {
					if(item.expected_carton_count > findItem.expected_carton_count  || item.total_unit > findItem.total_unit){
						return this.notificationError("We don't have enough product in the inventory")
					}
				}
			}
			this.loadProductData =item
			this.loadProductDialog= true
		},
		async LoadProduct(){
			let payload ={
				id:this.loadProductData.id,
				wid:this.loadProductData.warehouse_id,
				oid:this.loadProductData.outbound_id
			}
			try{
			await this.loadProductApi(payload)
			this.loadProductDialog= false
			this.notificationMessage('Product Loaded sucessfully')
			let payloadSingleProduct = {
						wid: this.linkData.wid,
						oid: this.linkData.oid,
					};
				await this.fetchSingleOutbound(payloadSingleProduct);
				this.setAllOutboundProductsLists([])
                this.$emit('fetchOutboundProductsAPiFunction','Outbound')
				if(this.getCurrentOutboundTab !== 'undefined' && this.getCurrentOutboundTab !== undefined){
					let storeOutboundTab = this.$store.state.outbound 
					let dataWithPage = { id: this.linkData.wid,page: 1}
					if(this.getCurrentOutboundTab == 0){
            			dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
						await this.fetchPendingOutbounds(dataWithPage);
					}else{
						if(this.getCurrentOutboundTab == 2){
							dataWithPage.page = storeOutboundTab.completedOutboundPagination.current_page
							await this.fetchCompletedOutbounds(dataWithPage)
						}	
					}
				}	
			}catch(e){
				this.notificationError(e)
			}
		},
		multipleLoadProducts(){
		this.selectedLoaded = this.selected.filter(val=>{
			return	val.status !=='loaded'
			})
		var result2 = this.selectedLoaded.filter((dropdown) => {
			return this.productsListsData.some((product_row) => dropdown.product_id === product_row.product_id && ((dropdown.expected_carton_count > 0 && dropdown.expected_carton_count <= product_row.expected_carton_count) || (dropdown.total_unit > 0 && dropdown.total_unit <= product_row.total_unit) ) ) 
			});
			if(result2.length==0) return this.notificationError("We don't have enough product in the inventory")
			result2.filter(val =>{
				if (this.sendataLoaded.includes(val.id)){
                    return
                } else {
                    this.sendataLoaded.push(val.id)
                }
            })
			this.loadProductDialog = true;
			this.multipleLoadDialog=true
		},
		async LoadProductMultiple(){
			let payload ={
				wid:this.linkData.wid,
				oid:this.linkData.oid,
				idss:this.sendataLoaded
			}
			try{
				await this.multipleLoadProductApi(payload)
				this.loadProductDialog= false
				this.multipleLoadDialog=false
				this.selectedLoaded=[]
				this.selected=[]
				this.sendataLoaded=[]
				this.notificationMessage('Product Loaded sucessfully')
				let payloadSingleProduct = {
						wid: this.linkData.wid,
						oid: this.linkData.oid,
					};
				await this.fetchSingleOutbound(payloadSingleProduct);
				this.setAllOutboundProductsLists([])
                this.$emit('fetchOutboundProductsAPiFunction','Outbound')
				if(this.getCurrentOutboundTab !== 'undefined' || this.getCurrentOutboundTab !== undefined) {
					let storeOutboundTab = this.$store.state.outbound 
						let dataWithPage = { id: this.linkData.wid,page: 1}
					if(this.getCurrentOutboundTab == 0){
            			dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
						await this.fetchPendingOutbounds(dataWithPage);
					}
					else{
						if(this.getCurrentOutboundTab == 2){
							dataWithPage.page = storeOutboundTab.completedOutboundPagination.current_page
							await this.fetchCompletedOutbounds(dataWithPage)
						}
					}
				}	
			}catch(e){
				this.notificationError(e)
			}
		},
		loadProductDialogClose(){
			this.loadProductDialog= false,
			this.multipleLoadDialog=false
			this.selected=[]
			this.selectedLoaded=[]
			this.sendataLoaded=[]
		},
		// SelectAllProdsucts(){
		// 	if(this.productsData.outbound_products !==null && this.productsData.outbound_products !=='' && typeof this.productsData.outbound_products !=='undefined')
		// 	this.selected = this.productsData.outbound_products
		// 	this.selectedLoaded = this.selected.filter(val=>{
		// 	return	val.status !=='loaded'
		// 	})
		// 	if(this.selectedLoaded.length==0) return
		// 	this.selectedLoaded.filter(val =>{
        //         if (this.sendataLoaded.includes(val.id)){
        //             return
        //         } else {
        //             this.sendataLoaded.push(val.id)
        //         }
        //     })
		// 	this.loadProductDialog = true;
		// 	this.multipleLoadDialog=true
		// },
		disableClearSelection(status){
			if( this.hidewareFloorTab ===false){
				return ( status == 'cancelled' || status == 'completed' || status == 'rejected' )
			}else{
				return  status =='cancelled'
			}
		},
		fetchOutboundProductsAPiFunction(){
			this.$emit('fetchOutboundProductsAPiFunction','Outbound')
		},
		Warehouse6PL_ProductsOnchange(val){
			if(val !== null && val !== 'undefined' && val !== undefined){
				console.log(val)
			this.$emit('Warehouse6PL_ProductsOnchange',val)
			}
		}
	},
	async mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "inventory");
		let wid = new URL(location.href).searchParams.get("wid");
		let oid = new URL(location.href).searchParams.get("id");
		this.linkData = { oid, wid };
	},
	updated() {},
};
</script>

<style lang="scss">
/* @import '../../../assets/scss/buttons.scss'; */
/* @import '../../../assets/scss/pages_scss/outbound/outboundView.scss'; */
@import "./scss/productSection.scss";
@import "@/assets/scss/pages_scss/dialog/globalDialog.scss";

.btn-whitee {
	background-color: $white !important;
	color: $dark-blue !important;
	border: 1px solid $light-grey !important;
	padding: 10px 16px !important;
	font-size: 14px;
	height: 34px !important;
	text-transform: capitalize;
	letter-spacing: 0;
	box-shadow: none !important;
	border-radius: 4px;
	font-family: "Inter-Regular", sans-serif;
	display: flex;
	justify-content: center;
	align-items: center;
	margin: 0 !important;

	&:disabled {
		color: $dark-blue !important;
	}

	&.delete {
		color: $red !important;
	}
}

.myclass {
	background-color: #EBFAEF !important;
	padding: 7px 0 !important;
	color: #16B442 !important;
	border: none !important;
	border-radius: 4px !important;
	pointer-events: none !important;
	margin-right: 5px !important;
}

.greenColor {
	color: #16B442 !important;
	font-weight: 500 !important;
	font-size: 12px !important;
}
.skuColor{
	color: #4A4A4A !important;
}
</style>