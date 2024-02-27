<template>
	<div>
		<v-dialog
			v-model="dialog"
			max-width="1064px"
			content-class="create-outbound-dialog single-product-pick"
			:retain-focus="false"
			persistent
			v-resize="onResize"
			scrollable>
			<v-card class="">				
				<v-card-title>
					<span class="headline"> Pick Products </span>
					<v-spacer></v-spacer>
					<v-btn icon dark class="btn-close" @click="close">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>

				<v-card-text>
					<v-form ref="form" v-model="valid" action="#" @submit.prevent="">
						<p class="pa-4 pt-6 textColorGray">
							You have to pick the following {{ MultipleProductArray.length }} SKUs. 
							Please mention the reference of every storable unit you pick from.
						</p>

						<!-- <p class="px-4 pb-6 textColorGray" v-if="MultipleProductArray.length < productsData.outbound_products.length">
							<span class="font-medium" style="color:#F93131;">NOTE:</span> Some products are not included as they are not from inventory.						
						</p> -->

						<div class="mx-4 bgColor" v-for="(itemsMUlt, indexx) in MultipleProductArray" :key="indexx">
							<v-row class="mx-3 pt-2">
								<v-col cols="4">
									<div>
										<h5 class="font-weight-medium">
											SKU-{{
												itemsMUlt.sku !== null && itemsMUlt.sku !== ""
												? itemsMUlt.sku
												: "--"
											}}
										</h5>

										<p class="textColorGray">
											{{
												itemsMUlt.product_name !== null &&
												itemsMUlt.product_name !== ""
												? itemsMUlt.product_name
												: "--"
											}}
										</p>
									</div>
								</v-col>

								<v-col cols="8">
									<div class="mx-2 d-flex">
										<div class="mywidth">
											<h5 class="text-end">Carton to Pick</h5>
											<p class="text-end textColorGray">
												{{
													itemsMUlt.carton_count !== null &&
													itemsMUlt.carton_count !== ""
													? itemsMUlt.carton_count
													: "--"
												}}
											</p>
										</div>
										<div class="mywidth">
											<h5 class="text-end">In Each</h5>
											<p class="text-end textColorGray">
												{{
													itemsMUlt.in_each_carton !== null &&
													itemsMUlt.in_each_carton !== ""
													? itemsMUlt.in_each_carton
													: "--"
												}}
											</p>
										</div>
										<div class="mywidth">
											<h5 class="text-end">Unit to Pick</h5>
											<p class="text-end textColorGray">
												{{
													itemsMUlt.total_unit !== null &&
													itemsMUlt.total_unit !== ""
													? itemsMUlt.total_unit
													: "--"
												}}
											</p>
										</div>
										<div class="mywidth">
											<h5 class="text-end">Carton Picked</h5>
											<p class="text-end"
												:class="
													getColorCarton(
														itemsMUlt.carton_count,
														cartonPickedTotal[indexx]
													)
												"
												>
												<!-- {{
													itemsMUlt.carton_count !== null &&
													itemsMUlt.carton_count !== ""
													? itemsMUlt.carton_count
													: "--"
												}} -->{{
													cartonPickedTotal[indexx] !== null &&
													cartonPickedTotal[indexx] !== ""
													? cartonPickedTotal[indexx]
													: "--"
												}}
											</p>
										</div>
										<div class="mywidth">
											<h5 class="text-end">Unit Picked</h5>
											<p class="text-end"
												:class="
													getColorUnit(
													itemsMUlt.total_unit,
													UnitPickedTotal[indexx]
													)
												"
												>
												<!-- {{
													itemsMUlt.total_unit !== null &&
													itemsMUlt.total_unit !== ""
													? itemsMUlt.total_unit
													: "--"
												}} -->
												{{
													UnitPickedTotal[indexx] !== null &&
													UnitPickedTotal[indexx] !== ""
													? UnitPickedTotal[indexx]
													: "--"
												}}
											</p>
										</div>
									</div>
								</v-col>
							</v-row>

							<v-row no-gutters class="mx-5 my-2 mb-0">
								<v-col class="pl-2 py-3 bgColor-header" cols="2">
									<h5 class="my-0">Pick From</h5>
								</v-col>
								
								<v-col cols="3" class="px-2 py-3 bgColor-header">
									<h5 class="text-start my-0">Reference</h5>
								</v-col>

								<v-col cols="5">
									<v-row no-gutters>
										<v-col cols="6" sm="6" md="3" lg="3" class="py-3 bgColor-header px-2">
											<h5 class="text-start my-0">Whole</h5>
										</v-col>
										<v-col cols="3" sm="3" md="6" lg="6" class="py-3 bgColor-header px-2 d-flex justify-end">
											<h5 class="text-end my-0">Carton</h5>
										</v-col>
										<v-col cols="3" class="py-3 bgColor-header d-flex justify-end">
											<h5 class="text-end my-0">In Each</h5>
										</v-col>
									</v-row>
								</v-col>
								<v-col cols="2" class="pl-1 pr-2 py-3 bgColor-header d-flex justify-end">
									<h5 class="text-end my-0">Unit</h5>
								</v-col>
							</v-row>

							<v-row no-gutters class="mx-5 py-0 my-0"
								v-for="(first, index) in MultipleProductArray[indexx].val"								
								:style="ErrorShowFunction(MultipleProductArray[indexx].val[index].label,indexx,index) ==true || isStorableSelected(first,indexx,index) ? 'height: 70px;' : 'height: 44px;'"
								:key="index">
								
								<v-col cols="2" class="mx-0 px-0 my-0 py-0" style="height:45px">
									<!-- <v-autocomplete
										height="40px"
										:items="StorableArray[indexx]"
										:item-text="getStorabetext"
										item-value="label"
										color="#002F44"
										width="200px"
										type="text"
										dense
										background-color="white"
										:return-object="true"
										class="text-fields custom"
										placeholder="Enter storable unit’s label"
										v-model.number="MultipleProductArray[indexx].val[index].label"
										outlined
										:rules="[(v) => !!v || 'label is Required']"
										@input="storableValueOnChangeFunc($event,index,indexx)"
										hide-details="auto">
										<template v-slot:no-data>
											<div class="option-items loading" 
												v-if="batchPickProduct_loader"
												style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
												<div class="sku-item">
													<v-progress-circular
														:size="40"
														color="#0171a1"
														indeterminate>
													</v-progress-circular>
												</div>
											</div>

											<div class="option-items no-data" 
												v-if="!batchPickProduct_loader"
												style="min-height: 40px; padding: 12px; font-size: 14px;">
												<div class="sku-item">
													No available data
												</div>
											</div>
										</template>	
									</v-autocomplete> -->
									
									<!-- @input.native="MultipleProductArray[indexx].val[index].label =$event.srcElement.value" -->
									<v-select
										:items="pickFromArray[indexx]"
										class="text-fields custom customs-pick-product pick-from"
										item-text="name"
										item-value="value"
										placeholder="Pick From"
										outlined
										dense
										append-icon="mdi-chevron-down"
										height="40px"
										v-model="
											MultipleProductArray[indexx].val[index].pick_from
										"
										width="200px"
										:rules="[(v) => !!v || 'Input is Required']"
										background-color="white"
										hide-details="auto"
										@change="setWholeValuetoZero(MultipleProductArray[indexx].val[index].pick_from,MultipleProductArray[indexx].val[index].whole,index)"
										:menu-props="{ bottom: true, offsetY: true }">
											<template v-slot:no-data>
												<div class="option-items loading" 
													v-if="batchPickProduct_loader"
													style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
													<div class="sku-item">
														<v-progress-circular
															:size="40"
															color="#0171a1"
															indeterminate>
														</v-progress-circular>
													</div>
												</div>

												<div class="option-items no-data" 
													v-if="!batchPickProduct_loader"
													style="min-height: 40px; padding: 12px; font-size: 14px;">
													<div class="sku-item">
														No available data
													</div>
												</div>
											</template>	
									</v-select>
								</v-col>

								<v-col cols="3" class="mx-0 px-0 my-0 py-0" style="height:45px">
									<span v-if="MultipleProductArray[indexx].val[index].pick_from == 'location'">
										<v-select
											:items="LocationArray[indexx]"
											class="text-fields custom customs-pick-product customs-pick-product-border-remove"
											:item-text="getLocationText"
											item-value="id"
											placeholder="Enter location"
											outlined
											append-icon="mdi-chevron-down"
											dense
											height="40px"
											v-model.number="
												MultipleProductArray[indexx].val[index].location_id
											"
											width="200px"
											:rules="[(v) => !!v || 'Location is Required']"
											background-color="white"
											hide-details="auto"
											:menu-props="{ bottom: true, offsetY: true }">
											<template v-slot:no-data>
												<div class="option-items loading" 
													v-if="batchPickProduct_loader"
													style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
													<div class="sku-item">
														<v-progress-circular
															:size="40"
															color="#0171a1"
															indeterminate>
														</v-progress-circular>
													</div>
												</div>

												<div class="option-items no-data" 
													v-if="!batchPickProduct_loader"
													style="min-height: 40px; padding: 12px; font-size: 14px;">
													<div class="sku-item">
														No available data
													</div>
												</div>
											</template>											
										</v-select>
									</span>
									<span v-else-if="MultipleProductArray[indexx].val[index].pick_from == 'storable_unit'">
										<v-autocomplete
											height="40px"
											:items="StorableArray[indexx]"
											:item-text="getStorabetext"
											item-value="label"
											color="#002F44"
											width="200px"
											type="text"
											append-icon="mdi-chevron-down"
											dense
											background-color="white"
											:return-object="true"
											class="text-fields custom customs-pick-product customs-pick-product-border-remove"
											placeholder="Enter storable unit’s label"
											v-model.number="MultipleProductArray[indexx].val[index].label"
											outlined
											:rules="[(v) => !!v || 'label is Required']"
											@input="storableValueOnChangeFunc($event,index,indexx)"
											hide-details="auto">
											<template v-slot:no-data>
												<div class="option-items loading" 
													v-if="batchPickProduct_loader"
													style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
													<div class="sku-item">
														<v-progress-circular
															:size="40"
															color="#0171a1"
															indeterminate>
														</v-progress-circular>
													</div>
												</div>

												<div class="option-items no-data" 
													v-if="!batchPickProduct_loader"
													style="min-height: 40px; padding: 12px; font-size: 14px;">
													<div class="sku-item">
														No available data
													</div>
												</div>
											</template>	
										</v-autocomplete>
									</span>

									<span v-else>
										<v-select
											class="text-fields custom customs-pick-product customs-pick-product-border-remove"
											item-value="id"
											placeholder="--"
											outlined
											append-icon="mdi-chevron-down"
											dense
											disabled
											height="40px"
											width="200px"
											background-color="#EBF2F5"
											hide-details="auto"
											:menu-props="{ bottom: true, offsetY: true }">
										</v-select>
									</span>
								</v-col>

								<v-col cols="5" class="mx-0 px-0 my-0 py-0" style="height:45px">
									<v-row no-gutters>
										<v-col cols="6" sm="6" md="3" lg="3" :class="isMobile ?'':''">
											<div :class="MultipleProductArray[indexx].val[index].pick_from !== 'floor' ? 'checkbox-border':' checkbox-border checkbox-border-floor'">
												<v-checkbox hide-details="auto" :on-icon="'mdi-check'" :off-icon="''" class="pl-2" :class="getColourDisabled(disableCheckbox(MultipleProductArray[indexx].val[index].label,itemsMUlt.carton_count,itemsMUlt.total_unit,itemsMUlt,MultipleProductArray[indexx].val[index].pick_from,index,indexx))"  @change="addWholeValues(MultipleProductArray[indexx].val[index].label,index)" :disabled="disableCheckbox(MultipleProductArray[indexx].val[index].label,itemsMUlt.carton_count,itemsMUlt.total_unit,itemsMUlt,MultipleProductArray[indexx].val[index].pick_from,index,indexx)"  v-bind:false-value="0" v-bind:true-value="1" light color="#0084bd" v-model="MultipleProductArray[indexx].val[index].whole" label="whole"></v-checkbox>
											</div>
										</v-col>

										<v-col cols="3" sm="3" md="6" lg="6" class="mx-0 px-0 my-0 py-0">
											<v-text-field
												v-on:input="fillCartonUnit(MultipleProductArray[indexx].val,index, $event)"
												height="40px"
												color="#002F44"
												width="200px"
												type="number"
												dense
												:background-color="MultipleProductArray[indexx].val[index].whole ==1 || itemsMUlt.carton_count ==null ? '#EBF2F5': 'white'"
												class="text-fields custom customs-pick-product customs-pick-product-border-remove"
												reverse
												:min="minValue"
												:max="itemsMUlt.carton_count !==null ? itemsMUlt.carton_count:0"
												:rules="[CHeckValuesOfTotalCarton(cartonPickedTotal[indexx],itemsMUlt.carton_count),cartonRules.required]"
												:disabled="wholeValue(MultipleProductArray[indexx].val[index].whole,itemsMUlt.carton_count)"
												placeholder="0"
												outlined
												v-model.number="
													MultipleProductArray[indexx].val[index].carton_count
												"
												hide-details="auto"
												@keydown="restrictValues($event)"
												@wheel="$event.target.blur()">
											</v-text-field>

											<!-- <span class="red--text text-subtitle-2 ml-1">
												{{
													CHeckValuesOfTotalCarton(
														cartonPickedTotal[indexx],
														itemsMUlt.carton_count
													)
												}}
											</span> -->
										</v-col>

										<v-col cols="3" class="mx-0 px-0 my-0 py-0">
											<v-text-field
												height="40px"
												color="#002F44"
												width="200px"
												type="number"
												dense
												background-color="#EBF2F5"
												class="text-fields custom customs-pick-product py-0 my-0 customs-pick-product-border-remove"
												placeholder="0"
												reverse
												outlined
												disabled
												v-model="
													MultipleProductArray[indexx].val[index].in_each_carton
												"
												hide-details="auto">
											</v-text-field>
										</v-col>
									</v-row>								
								</v-col>

								<v-col cols="2" class="mx-0 px-0 my-0 py-0" style="height:45px">
									<div class="d-flex">
										<v-text-field
											height="40px"
											color="#002F44"
											width="150px"
											type="number"
											dense
											:background-color="MultipleProductArray[indexx].val[index].whole ==1 || itemsMUlt.total_unit ==null ? '#EBF2F5': 'white'"
											class="text-fields custom customs-pick-product "
											placeholder="0"
											v-model.number="
											MultipleProductArray[indexx].val[index].total_unit
											"
											reverse
											:min="minValue"
											:max="itemsMUlt.total_unit !==null ? itemsMUlt.total_unit:0"
											:rules="[CHeckValuesOfTotalUnit(UnitPickedTotal[indexx],itemsMUlt.total_unit),UnitRules.required]"
											:disabled="wholeValue_Unit(MultipleProductArray[indexx].val[index].whole,itemsMUlt.total_unit)"
											outlined
											hide-details="auto"
											@keydown="restrictValues($event)"
											@wheel="$event.target.blur()">
										</v-text-field>

										<v-icon v-if="MultipleProductArray[indexx].val.length>1" @click="removeRow(indexx, index)" right color="red">
											mdi-close
										</v-icon>
									</div>

									<!-- <span class="red--text text-subtitle-2 ml-1">
										{{
											CHeckValuesOfTotalUnit(
												UnitPickedTotal[indexx],
												itemsMUlt.total_unit
											)
										}}
									</span> -->
								</v-col>

								<v-col v-if="ErrorShowFunction(MultipleProductArray[indexx].val[index].label,indexx,index) ==true" cols="12">
									<div class="ml-1">
										<span style="font-size:12px; color:#6D858f">{{ErrorShowFunctionShow(MultipleProductArray[indexx].val[index].label,indexx,index)}}</span>	
									</div>
								</v-col>

								<v-col v-if="isStorableSelected(first,indexx,index)"  cols="12" style="height:40px">
									<div class=" my-2 ml-1">
										<span style="font-size:12px; color:#6D858f">{{isStorableSelected(first,indexx,index)}}</span>
									</div>		
								</v-col>
							</v-row>

							<v-row class="mx-4 pb-2 pl-1 my-0" no-gutters>
								<v-col cols="2" sm="2" md="2" lg="2" class="my-0 px-0 mx-0" style="height:42px;background:white;">
									<v-btn
										block
										height="40px"
										style="border:1px solid #EBF2F5 !important;height:42px !important;border-radius:0 !important;"
										:disabled="
										UnitPickedTotal[indexx] >= itemsMUlt.total_unit &&
										cartonPickedTotal[indexx] >= itemsMUlt.carton_count
										"
										@click="addRow(indexx)"
										small
										class="btn-white add-btn justify-start">
										+ Add Line
									</v-btn>
								</v-col>
							</v-row>							

							<v-row no-gutters class="my-2">
								<v-col class="py-3 white" cols="12"> </v-col>
							</v-row>
						</div>
					</v-form>
				</v-card-text>				

				<v-card-actions>
					<v-btn
						class="text-capitalize btn-blue"
						:disabled="disableCheck(getBatchProductLoading)"
						@click="BatchpickProductApi">						
						{{ !getBatchProductLoading ? 'Confirm Pick' : 'Confirming...' }}
					</v-btn>
					<v-btn @click="close" class="btn-white">Cancel</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import globalMethods from "../../../../utils/globalMethods";
import _ from 'lodash'

export default {
	props: [
		"dialogCreatepick",
		"MUltiplePickProductData",
		"sortableData",
		"locationDataDropdown",
		"StorableUnitDataDropdown",
		"batchPickProduct_loader",
		"singleOutbound_status",
		"isWarehouse6PL",
		"isWarehouseConnected",
		"productsData",
		"pickMultipleData"
	],
	data: () => ({
		minValue: 0,
		dialog_pick_products: false,
		valid: true,
		linkData: {
			wid: "",
			oid: "",
		},
		LocationDropdown: [],
		outboundProducts: [
			{
			label: "",
			location_id: "",
			carton_count: 0,
			total_unit: 0,
			pick_from:'',
			},
		],
		cartonRules: {
			required:(v) => parseFloat(v) >= 0 || "Carton should be minimum zero",
		},
		UnitRules: { required:(v) => parseFloat(v) >= 0 || "Units should be minimum zero"},
		StorableUnitArray: [],
		isMobile: false,
		
	}),
	watch:{
		// MultipleProductArray:{
		// 	deep: true,
		// 	handler(){
		// 		this.MultipleProductArray.map((val,indexx) =>{
		// 			this.MultipleProductArray[indexx].val.map((val,index)=>{
		// 				if(this.MultipleProductArray[indexx].val[index].whole ===1){
		// 			this.MultipleProductArray[indexx].val[index].carton_count = this.MultipleProductArray[indexx].carton_count !==null ? this.MultipleProductArray[indexx].carton_count :0;
		// 			this.MultipleProductArray[indexx].val[index].total_unit =this.MultipleProductArray[indexx].total_unit !==null ? this.MultipleProductArray[indexx].total_unit :0
		// 			}
		// 			})
					
		// 		})
					
		// 	}
			
		// }
	},
	computed: {
		...mapGetters({
			getBatchProductLoading: "outbound/getBatchProductLoading",
			getpickOutboundLocationLoading: "outbound/getpickOutboundLocationLoading",
			getpickOutboundLocationData: "outbound/getpickOutboundLocationData",
			getCurrentOutboundTab: "outbound/getCurrentOutboundTab"
		}),
		dialog: {
			get() {
				return this.dialogCreatepick;
			},
			set(value) {
				this.$emit("update:dialogCreatepick", value);
			},
		},
		MultipleProductArray: {
			get() {
				return this.MUltiplePickProductData;
			},
			set(value) {
				this.$emit("update:MUltiplePickProductData", value);
			},
		},
		StorableUnit: {
			get() {
				return this.sortableData;
			},
			set(value) {
				this.$emit("update:sortableData", value);
			},
		},
		LocationArray: {
			get() {
				return this.locationDataDropdown;
			},
			set(value) {
				this.$emit("update:locationDataDropdown", value);
			},
		},
		StorableArray: {
			get() {
				return this.StorableUnitDataDropdown;
			},
			set(value) {
				this.$emit("update:StorableUnitDataDropdown", value);
			},
		},
		pickFromArray:{
			get(){
				return this.pickMultipleData
			},
			set(value){
				this.$emit("update:pickMultipleData",value)
			}
		},
		cartonPickedTotal: function () {
			return this.MultipleProductArray.map((val, indexx) => {
				return this.MultipleProductArray[indexx].val.reduce((prev, item) => {
				if (prev ==='' || item.carton_count ==='') return prev
					return parseInt(prev) + parseInt(item.carton_count);
				}, 0);
			});
		},
		UnitPickedTotal: function () {
			return this.MultipleProductArray.map((val, indexx) => {
				return this.MultipleProductArray[indexx].val.reduce((prev, item) => {
				if (prev ==='' || item.total_unit ==='') return prev
					return parseInt(prev) + parseInt(item.total_unit);
				}, 0);
			});
		},
	},
	methods: {
		...mapActions({
			BatchPickedApi: "outbound/BatchPickedApi",
			fetchSingleOutbound: "outbound/fetchSingleOutbound",
			fetchPendingOutbounds: "outbound/fetchPendingOutbounds",
			fetchFloorOutbounds: "outbound/fetchFloorOutbounds",
			fetchPendingShippingServiceProvider:'outbound/fetchPendingShippingServiceProvider',
			setAllOutboundProductsLists: 'outbound/setAllOutboundProductsLists',
		}),
		...globalMethods,
		onResize() {
			if (window.innerWidth < 1024) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
		},
		close() {
			this.$emit("close");
			this.$refs.form.resetValidation();
		},
		async BatchpickProductApi() {
			if (!this.$refs.form.validate()) return;

			// only get data,, that user add or remove from skuss
			let filter_SKU = this.MultipleProductArray.filter((val, index) => {
				if (this.MultipleProductArray[index].val.length > 0) {
				return val.val;
				}
			});
			// let filter_SKU_copy = filter_SKU
			// filter_SKU_copy.map((val,index)=>{
			// 	filter_SKU_copy[index].val.map((value,indexx)=>{
			// 		if(typeof filter_SKU_copy[index].val[indexx].label =='object')
			// 	filter_SKU_copy[index].val[indexx].label=filter_SKU_copy[index].val[indexx].label.label
			// 	})
			// })
			var deepCopy = _.cloneDeep(filter_SKU);
			deepCopy.map((val,index)=>{
				deepCopy[index].val.map((value,indexx)=>{
					if(typeof deepCopy[index].val[indexx].label =='object')
				deepCopy[index].val[indexx].label=deepCopy[index].val[indexx].label.label
				})
			})
			deepCopy.map((val,index)=>{
				deepCopy[index].val.filter((value,indexx)=>{
					if(deepCopy[index].val[indexx].pick_from == 'floor')
				 		delete deepCopy[index].val[indexx].label && delete deepCopy[index].val[indexx].location_id
				})
			})

			// payload for sending data
			let PayloadDATA = deepCopy.map((val) => ({
				outbound_product_id: val.id,
				data: val.val,
				wid: this.linkData.wid,
				oid: this.linkData.oid,
			}));

			// arrange payload for api
			let payload = {
				wid: this.linkData.wid,
				oid: this.linkData.oid,
				data: PayloadDATA,
			};
			try {
				await this.BatchPickedApi(payload);
				this.$emit("close");
				this.notificationMessage("Products picked sucessfully");
				let payloadSingleProduct = {
					wid: this.linkData.wid,
					oid: this.linkData.oid,
				};
				await this.fetchSingleOutbound(payloadSingleProduct);
				if(this.getCurrentOutboundTab !=='undefined' && this.getCurrentOutboundTab !== undefined){
					let storeOutboundTab = this.$store.state.outbound 
					let dataWithPage = { id: this.linkData.wid,page: 1}
					if(!this.isWarehouse6PL){
						if(this.getCurrentOutboundTab == 0){
							dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
							await this.fetchPendingOutbounds(dataWithPage);
						}else {
							if(this.getCurrentOutboundTab == 1){
								dataWithPage.page = storeOutboundTab.floorOutboundPagination.current_page
								await this.fetchFloorOutbounds(dataWithPage);
							}
						}
					}else{
						if(this.getCurrentOutboundTab == 0){
							dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
							await this.fetchPendingOutbounds(dataWithPage);
						}else if(this.getCurrentOutboundTab == 1){
							dataWithPage.page = storeOutboundTab.pendingShippingProviderOutboundPagination.current_page
							await this.fetchPendingShippingServiceProvider(dataWithPage)
						}
						else {
							if(this.getCurrentOutboundTab == 2){
								dataWithPage.page = storeOutboundTab.floorOutboundPagination.current_page
								await this.fetchFloorOutbounds(dataWithPage);
							}
						}
					}
					if(typeof this.productsData !=='undefined' && this.productsData !== 'undefined' && this.productsData !== undefined){
						if(this.isWarehouse6PL ==true && !this.isWarehouseConnected &&  this.productsData.warehouse_customer_id !=='' ){
							this.setAllOutboundProductsLists([])
							let val = {
					  			id:this.productsData.warehouse_customer_id
					  		}
							this.$emit('Warehouse6PL_ProductsOnchange',val)
				  		}else{
							this.setAllOutboundProductsLists([])
							this.$emit('fetchOutboundProductsAPiFunction','Outbound')
				  		}
					}
				}
			} catch (e) {
				this.notificationError(e);
			}
		},
		addRow(indexx) {
			this.MultipleProductArray[indexx].val.push({
				label: "",
				location_id: "",
				carton_count: 0,
				total_unit: 0,
				in_each_carton: this.MultipleProductArray[indexx].val.in_each_carton !==null && this.MultipleProductArray[indexx].in_each_carton ? this.MultipleProductArray[indexx].in_each_carton :0,
				whole:0,
				pick_from:''
			});
		},
		removeRow(indexx, index) {
			this.MultipleProductArray[indexx].val.splice(index, 1);
		},
		getColorCarton(actual_carton, total_Carton_Param) {
			if (total_Carton_Param == 0) return "RedError";
			else if (total_Carton_Param < parseInt(actual_carton))
				return "OrangeWarning";
			else if (total_Carton_Param == parseInt(actual_carton))
				return "GreenSucess";
		},
		getColorUnit(actual_unit, total_unitParam) {
			if (total_unitParam == 0) return "RedError";
			else if (total_unitParam < parseInt(actual_unit)) return "OrangeWarning";
			else if (total_unitParam == parseInt(actual_unit)) return "GreenSucess";
		},
		CHeckValuesOfTotalUnit(input_value, actual_remaining_value) {
			if (input_value !== null && 
				input_value !== "" && 
				actual_remaining_value !== null && 
				actual_remaining_value !== "" ) {
				if (input_value > actual_remaining_value) return false
				// else if (input_value < actual_remaining_value) return false
				return true
			} else {
				return true
			}
		},
		CHeckValuesOfTotalCarton(inputValue, actual_remaining_Carton) {
			if (inputValue !== null &&
				inputValue !== "" &&
				actual_remaining_Carton !== null &&
				actual_remaining_Carton !== "") {
				if (inputValue > actual_remaining_Carton) return false
				// else if (inputValue < actual_remaining_Carton) return false
				return true
			} else {
				return true
			}
		},
		getLocationText(item) {
			if (item.shelf !== null && item.row !== null && item.rack !== null) {
				return `${item.row}${item.rack}${item.shelf}`;
			} else {
				return "--";
			}
		},
		getStorabetext(item) {
			if (item.label !== null && item.label !== "") {
				return item.label;
			} else {
				return "--";
			}
		},
		wholeValue(item,value) {
			if (item === 1 || value == null) {
				return true
			} else {
				return false
			}
		},
		wholeValue_Unit(item,value) {
			if (item === 1 || value == null) {
				return true
			} else {
				return false
			}
		},
		addWholeValues(item,indexBox) {
			if (item !== 'undefined' && item !== null && item !=='' &&  typeof item !=='string' && item.storable_unit_products !=='undefined') {
				if (item.storable_unit_products[0].carton_count !=='undefined') {
					this.MultipleProductArray.map((val,indexx) => {
						this.MultipleProductArray[indexx].val.find((val,index) => {
							if (this.MultipleProductArray[indexx].val[index].whole ===1 && index ===indexBox) {
								this.MultipleProductArray[indexx].val[index].carton_count = item.storable_unit_products[0].carton_count !==null ? item.storable_unit_products[0].carton_count:0 ;
								this.MultipleProductArray[indexx].val[index].total_unit =item.storable_unit_products[0].total_unit !==null ? item.storable_unit_products[0].total_unit:0;
								// console.log(indexx, " indexx ", this.MultipleProductArray[indexx].val[index].total_unit, " index ",indexBox )
							} else {
								this.MultipleProductArray
							}
						})	
					})		
				}
			}
		},
		disableCheckbox(item,carton_params,units_params,itemsMUlt,pick,selectFieldIndex,indexy) {
			if (pick == 'floor') {
				return true
			} else {
				if (item !== 'undefined' && item !== null && item !=='' &&  typeof item !=='string' &&  item.storable_unit_products !=='undefined') {
					if (item.storable_unit_products[0].carton_count !=='undefined') {
						let CARTONS = carton_params !==null ? carton_params:0
						let UNITS = units_params !==null ? units_params:0
						let product__id =[]
						product__id = item.storable_unit_products.filter(val => {
							if (val.product_id==itemsMUlt.product_id) {
								return val
							}
						})
						if (product__id.length>0 &&( product__id[0].carton_count >CARTONS || product__id[0].total_unit >UNITS || item.storable_unit_products.length>1)) {
							this.MultipleProductArray.map((val,indexx) => {
								this.MultipleProductArray[indexx].val.find((values,index) => {
									if (index ==selectFieldIndex && indexx ==indexy){
										this.MultipleProductArray[indexx].val[index].whole= 0
									} else {
										this.MultipleProductArray	
									}						
								})
							})
							
							return true
						} else {
							return false
						}
					}
				} else if (typeof item =='string' && item =='' ) {
					return true
				}
			}		
		},
		disableCheck(loading) {
			var confirm =false
			if (loading) return confirm = true
			else if (!this.valid) return confirm = true
			this.MultipleProductArray.map((val,indexx) => {
				if (this.UnitPickedTotal[indexx] >this.MultipleProductArray[indexx].total_unit || this.UnitPickedTotal[indexx] < this.MultipleProductArray[indexx].total_unit ){
					confirm = true
				} else if (this.cartonPickedTotal[indexx] >this.MultipleProductArray[indexx].carton_count || this.cartonPickedTotal[indexx] < this.MultipleProductArray[indexx].carton_count ){
					confirm = true
				}
			})
			return confirm
		},
		getColourDisabled(disabledValue) {
			if (disabledValue) return "Card__container_forCheckbox" 
			return "Card__container_forCheckboxChecked"	 
		},
		storableValueOnChangeFunc(event,selectFieldIndex,indexy) {
			this.LocationArray[indexy].find(value => {
				if (value.id == event.location_id) {
					this.MultipleProductArray.map((val,indexx) => {
						this.MultipleProductArray[indexx].val.find((values,index) => {
							if (index ==selectFieldIndex && indexx ==indexy) {
								this.MultipleProductArray[indexx].val[index].label=event
								this.MultipleProductArray[indexx].val[index].location_id=value.id
							} else {
								this.MultipleProductArray	
							}						
						})
					})	
				}
			})
		},
		ErrorShowFunction(event,indexx,index){
			if (this.MultipleProductArray !=='undefined' && this.MultipleProductArray.length>0) {
				if (this.MultipleProductArray[indexx].val[index].carton_count > event.total_cartons || this.MultipleProductArray[indexx].val[index].total_unit > event.total_units ) {
					return true
				} else {
					return false
				}
			}
		},
		ErrorShowFunctionShow(event,indexx,index){
			if (this.MultipleProductArray !=='undefined' && this.MultipleProductArray.length>0) {
				if (this.MultipleProductArray[indexx].val[index].carton_count > event.total_cartons || this.MultipleProductArray[indexx].val[index].total_unit > event.total_units ) {
					return "The storable unit doesn't have enough units or carton"
				} else {
					return ''
				}
			}
		},
		restrictValues(e) {
            if (e.key=='-' && e.keyCode==189 || e.key=='+' && e.keyCode==187 ) {
                e.preventDefault()
            }
        },
		fillCartonUnit(val,index,value){
			val[index].total_unit = value * val[index].in_each_carton
		},
		isStorableSelected(label,indexx, index) {			
			if (this.MultipleProductArray !=='undefined' && this.MultipleProductArray !== undefined  && this.MultipleProductArray.length > 0){
				if (label.label !== '' && label.whole !== 0){
					let findSelectedOption = _.findIndex(
		  				this.MultipleProductArray[indexx].val,
		  				(e) =>
							(e.label == label.label &&
							e.whole == label.whole)
						);
					if (findSelectedOption !== index) {
		  				return "This storable unit has already been selected/duplicated";
					}
				}	  			
			}
		},
		setWholeValuetoZero(pick_from,whole,indexBox){
			this.MultipleProductArray.map((val,indexx) => {
				this.MultipleProductArray[indexx].val.find((val,index) => {
					if (this.MultipleProductArray[indexx].val[index].pick_from == 'floor' && this.MultipleProductArray[indexx].val[index].whole ===1 && index ===indexBox){
						this.MultipleProductArray[indexx].val[index].whole = 0 ;
					} else {
						this.MultipleProductArray
					}
				})	
			})	
		}
	},
	mounted() {
		let wid = new URL(location.href).searchParams.get("wid");
		let oid = new URL(location.href).searchParams.get("id");
		this.linkData = { oid, wid };

		if (this.outboundProducts !== this.MultipleProductArray) {
			this.outboundProducts = this.MultipleProductArray;
		}
	},
	updated() {
		if (this.outboundProducts !== this.MultipleProductArray) {
			this.outboundProducts = this.MultipleProductArray;
		}
	},
};
</script>

<style lang="scss" scoped>
@import "@/assets/scss/pages_scss/inventory/outbound/outboundDialog.scss";

.mywidth {
	width: 298px !important;
}
.bgColor {
	background-color: #F0FBFF !important;
}

.bgColor-header {
	background-color: #EBF2F5 !important;
	height: 38px !important;
	display: flex;
    align-items: center;
}
.textColorGray {
	color: #4a4a4a !important;
	font-size: 16px !important;
}
.bgColor h5 {
	font-weight: 500 !important;
	color: #6d858f !important;
	font-size: 14px !important;
}
.fontBOld {
	font-weight: 600;
	color: #16b442 !important;
}
.bgWhite {
	background-color: #fff !important;
}
.GreenSucess {
	font-weight: 600;
	color: #16b442 !important;
}
.OrangeWarning {
	font-weight: 600;
	color: #d68a1a !important;
}
.RedError {
	font-weight: 600;
	color: #f93131 !important;
}
.Card__container_forCheckbox {
    .v-input--selection-controls__input {
		background-color: rgb(221, 221, 221);
		border-radius: 5px !important;
		padding: 8px; 
		border: 3px solid #0084bd;
    }

	.v-icon {
		&.material-icons {
			&::before {
				content: '' !important;
				background-image: url('/checkbox-empty-icon-1.svg') !important;
				background-position: center !important;
				background-repeat: no-repeat !important;
				background-size: cover !important;
				width: 18px !important;
				height: 18px !important;
				opacity: .5;
				background-color: #B4CFE0 !important;
				border-radius: 4px;
			}
		}
	}	
}
.Card__container_forCheckboxChecked {
	// .v-input--selection-controls__input{
	// 	border-radius: 5px !important; 
	// 	padding: 8px;
	// 	border: 3px solid #0084bd;
    // }

	.v-icon {
		&.material-icons {
			&::before {
				content: '' !important;
				background-image: url('/checkbox-empty-icon-1.svg') !important;
				background-position: center !important;
				background-repeat: no-repeat !important;
				background-size: cover !important;
				width: 18px !important;
				height: 18px !important;
			}
		}
		
		&.mdi-check {
			width: 28px !important;
			// height: 26px !important;
			padding: 3px !important;

			&::before {
				content: '' !important;
				background-image: url('/checkbox-filled-icon-1.svg') !important;
				background-position: center !important;
				background-repeat: no-repeat !important;
				background-size: cover !important;
				width: 18px !important;
				height: 18px !important;
			}
		}
	}	
}
.customs-pick-product.v-text-field>.v-input__control {
	border-radius: 0;
}

.customs-pick-product.v-input {
	.v-input__control {
		.v-input__slot {
			font-size: 14px;

			fieldset {                            
				border: 1px solid #EBF2F5 !important;
				border-left: none !important;
			}
		}
	}

	&.pick-from {
		.v-input__control {
			.v-input__slot {
				fieldset {                            
					border-left: 1px solid #EBF2F5 !important;
				}
			}
		}
	}
	
	&.v-input--is-disabled {
		.v-input__control {
			.v-input__slot {
				fieldset {
					background-color: #FFF9F0 !important;
				}
			}
		}
	}
}

.customs-pick-product-border-remove.v-input {
	.v-input__control {
		background-color: transparent !important;

		.v-input__slot {
			font-size: 14px;

			fieldset {                            
				border: 1px solid #EBF2F5 !important;
			}
		}
	}
}
</style>