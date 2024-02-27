<template>
	<div>
		<v-dialog
			v-model="dialogSinglePIck"
			max-width="1064px"
			content-class="create-outbound-dialog single-product-pick"
			:retain-focus="false"
			persistent
			v-resize="onResize">

			<v-card class="my-0 py-0">
				<v-form ref="form" v-model="valid" action="#" @submit.prevent="">
					<v-card-title>
						<span class="headline"> Pick Products</span>
						<v-spacer></v-spacer>
						<v-btn :class="isMobile ? 'justify-end':''" icon dark class="btn-close" @click="close">
							<v-icon>mdi-close</v-icon>
						</v-btn>
					</v-card-title>

					<p class="textColorGray" :class="isMobile? 'px-3 ml-1 py-2':'pa-6 pb-4'">
						You have to pick only 1 SKU. Please mention the reference of 
						storable unit you pick from.
					</p>

					<div :class="isMobile ? 'bgColor':'mx-6 mb-6 bgColor'">
						<v-row v-if="!isMobile" class="bgColor mx-1">
							<v-col cols="3" sm="3" md="3" lg="3" :class="isMobile ? 'mr-0 pr-0':''">
								<div>
									<h5 class="font-weight-medium" v-if="PickProductItems">
										SKU-{{
											PickProductItems.sku !== null &&
											PickProductItems.sku !== ""
											? PickProductItems.sku
											: "--"
										}}
									</h5>

									<p class="textColorGray"  v-if="PickProductItems">
										{{
											PickProductItems.product_name !== null &&
											PickProductItems.product_name !== ""
											? PickProductItems.product_name
											: "--"
										}}
									</p>
								</div>
							</v-col>

							<v-col cols="9" sm="9" md="9" lg="9">
								<div class="d-flex" v-if="PickProductItems" :class="isMobile ?'mx-1':'mx-2'">
									<div :class="isMobile ? '': 'mywidth'">
										<h5 :class="isMobile ? '': 'text-end'">Carton to Pick</h5>
										<p class="textColorGray" :class="isMobile ? '': 'text-end'">
											{{
												PickProductItems.carton_count !== null &&
												PickProductItems.carton_count !== ""
												? PickProductItems.carton_count
												: "--"
											}}
										</p>
									</div>

									<div :class="isMobile ? '': 'mywidth'" v-if="PickProductItems">
										<h5 class="text-end">In Each</h5>
										<p class="text-end textColorGray">
											{{
												PickProductItems.in_each_carton !== null &&
												PickProductItems.in_each_carton !== ""
												? PickProductItems.in_each_carton
												: "--"
											}}
										</p>
									</div>

									<div :class="isMobile ? '': 'mywidth'" v-if="PickProductItems">
										<h5 class="text-end">Unit to Pick</h5>
										<p class="text-end textColorGray">
											{{
												PickProductItems.total_unit !== null &&
												PickProductItems.total_unit !== ""
												? PickProductItems.total_unit
												: "--"
											}}
										</p>
									</div>

									<div :class="isMobile ? '': 'mywidth'" v-if="PickProductItems">
										<h5 class="text-end">Carton Picked</h5>
										<p class="text-end" :class="getColorCarton(PickProductItems.carton_count)">
											{{totalCartonPicked !==null && totalCartonPicked!=='' ?  totalCartonPicked:"--" }}
										</p>
									</div>

									<div :class="isMobile ? '': 'mywidth'" v-if="PickProductItems">
										<h5 class="text-end">Unit Picked</h5>
										<p class="text-end " :class="getColor(PickProductItems.total_unit )">
											{{totalUnitPicked !==null && totalUnitPicked !=='' ?totalUnitPicked:'--'}}
										</p>
									</div>
								</div>
							</v-col>
						</v-row>

						<v-row no-gutters v-if="isMobile" class="bgColor mx-3  px-2 pt-3" style="borderBottom:7px solid white">
							<v-col cols="6" sm="6" md="3" lg="3" class="mr-0 pr-0 mb-0 pb-0">
								<div>
									<h5 class="font-weight-medium" v-if="PickProductItems">
										SKU-{{
											PickProductItems.sku !== null &&
											PickProductItems.sku !== ""
											? PickProductItems.sku
											: "--"
										}}
									</h5>

									<p class="textColorGray"  v-if="PickProductItems">
										{{
											PickProductItems.product_name !== null &&
											PickProductItems.product_name !== ""
											? PickProductItems.product_name
											: "--"
										}}
									</p>
								</div>
							</v-col>

							<v-col v-if="PickProductItems" class="" cols="6" sm="6" md="9" lg="9">
								<div :class="isMobile ? '': 'mywidth'">
									<h5 class="text-end">Carton to Pick</h5>
									<p class="textColorGray text-end">
										{{
											PickProductItems.carton_count !== null &&
											PickProductItems.carton_count !== ""
											? PickProductItems.carton_count
											: "--"
										}}
									</p>
								</div>
							</v-col>

							<v-col cols="6" sm="6">
								<div :class="isMobile ? '': 'mywidth'" v-if="PickProductItems">
									<h5 class="">In Each</h5>
									<p class="textColorGray">
										{{
											PickProductItems.in_each_carton !== null &&
											PickProductItems.in_each_carton !== ""
											? PickProductItems.in_each_carton
											: "--"
										}}
									</p>
								</div>
							</v-col>

							<v-col cols="6" sm="6">
								<div :class="isMobile ? '': 'mywidth'" v-if="PickProductItems">
									<h5 class="text-end">Unit to Pick</h5>
									<p class="text-end textColorGray">
										{{
											PickProductItems.total_unit !== null &&
											PickProductItems.total_unit !== ""
											? PickProductItems.total_unit
											: "--"
										}}
									</p>
								</div>
							</v-col>

							<v-col cols="6" sm="6">
								<div :class="isMobile ? '': 'mywidth'" v-if="PickProductItems">
									<h5 class="">Carton Picked</h5>
									<p class="" :class="getColorCarton(PickProductItems.carton_count)">
										{{totalCartonPicked !==null && totalCartonPicked!=='' ?  totalCartonPicked:"--" }}
									</p>
								</div>
							</v-col>

							<v-col cols="6" sm="6">
								<div :class="isMobile ? '': 'mywidth'" v-if="PickProductItems">
									<h5 class="text-end">Unit Picked</h5>
									<p class="text-end " :class="getColor(PickProductItems.total_unit )">
										{{totalUnitPicked !==null && totalUnitPicked !=='' ?totalUnitPicked:'--'}}
									</p>
								</div>
							</v-col>
						</v-row>

						<v-row v-if="!isMobile" no-gutters class=" bgColor my-2 mb-0" :class="isMobile ? 'mx-1':'mx-4'">
							<!-- <v-col class="pl-2 py-3 white" cols="3" sm="3" md="3" lg="3">
								<h5 class="my-0">Storable Unit</h5>
							</v-col>
							<v-col cols="2" class="py-3 white" :class="isMobile ?'pr-1':'px-2'">
								<h5 class="text-start my-0">Location</h5>
							</v-col> -->
							<v-col class="pl-3 py-2 bgColor-header" cols="2" sm="2" md="2" lg="2">
								<h5 class="my-0">Pick From</h5>
							</v-col>
							<v-col cols="3" class="py-2 bgColor-header" :class="isMobile ?'pr-1':'px-2'">
								<h5 class="text-start my-0">Reference</h5>
							</v-col>
							<v-col cols="5">
								<v-row no-gutters>
									<v-col cols="6" sm="6" md="3" lg="3" class="py-2 bgColor-header">
										<h5 class="text-start my-0">Whole</h5>
									</v-col>
									<v-col cols="3" sm="3" md="6" lg="6" class="py-2 bgColor-header px-2 d-flex justify-end">
										<h5 class="text-end my-0">Carton</h5>
									</v-col>
									<v-col cols="3" class="py-2 bgColor-header px-2 d-flex justify-end">
										<h5 class="text-end my-0">In Each</h5>
									</v-col>
								</v-row>
							</v-col>
							<v-col cols="2" class="py-2 bgColor-header d-flex justify-end" :class="isMobile ?'px-1':'px-3'">
								<h5 class="text-end my-0">Unit</h5>
							</v-col>
						</v-row>

						<div v-if="!isMobile">
							<v-row
								no-gutters
								:class="isMobile ? 'mx-1':'mx-3'"
								class="py-0 my-0 px-1 bgColor"
								v-for="(item, index) in outboundProducts"
								:key="index"
								:style="ErrorShowFunction(outboundProducts[index].label,index) ==true || isStorableSelected(item,index) ? 'height: 70px;' : 'height: 44px;'">

								<v-col cols="2" sm="2" md="2" lg="2" class="py-0 my-0 px-0 mx-0" style="height:40px" >
									<!-- <v-autocomplete
										v-model.number="outboundProducts[index].label"
										height="40px"
										color="#002F44"
										width="200px"
										type="text"
										dense
										:items="getpickOutboundStorableData"
										:item-text="getStorabetext"
										item-value="label"
										:rules="[(v) => !!v || 'label is Required']"
										background-color="white"
										class="text-fields custom"
										placeholder="Enter storable unit’s label"
										outlined
										:return-object="true"
										hide-details="auto"
										@input="storableValueOnChangeFunc($event,index)"
										>
										<template v-slot:no-data>
											<div class="option-items loading" 
												v-if="singlePickProductloader"
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
												v-if="!singlePickProductloader"
												style="min-height: 40px; padding: 12px; font-size: 14px;">
												<div class="sku-item">
													No available data
												</div>
											</div>
										</template>
									</v-autocomplete> -->
									
									<!-- @input.native="outboundProducts[index].label=$event.srcElement.value -->

									<v-select
										:items="pickFromArray"
										class="text-fields custom customs-pick-product pick-from"
										item-text="name"
										item-value="value"
										placeholder="Pick From"
										outlined
										append-icon="mdi-chevron-down"
										dense
										height="40px"
										width="200px"
										:rules="[(v) => !!v || 'Input is Required']"
										v-model="outboundProducts[index].pick_from"
										background-color="white"
										hide-details="auto"
										@change="setWholeValuetoZero(outboundProducts[index].pick_from,outboundProducts[index].whole,index)"
										:menu-props="{ bottom: true, offsetY: true }">
									</v-select>
								</v-col>

								<v-col cols="3" class="py-0 my-0" :class="isMobile ?'pr-1':'px-0 mx-0 my-0 py-0'" style="height:40px">
									<span v-if="outboundProducts[index].pick_from == 'location'">
										<v-select
											:items="getpickOutboundLocationData"
											class="text-fields custom customs-pick-product customs-pick-product-border-remove"
											:item-text="getLocationText"
											item-value="id"
											placeholder="Enter location"
											outlined
											append-icon="mdi-chevron-down"
											dense
											height="40px"
											width="200px"
											:rules="[(v) => !!v || 'Location is Required']"
											v-model="outboundProducts[index].location_id"
											background-color="white"
											hide-details="auto"
											:menu-props="{ bottom: true, offsetY: true }">
											<template v-slot:no-data>
												<div class="option-items loading" 
													v-if="singlePickProductloader"
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
													v-if="!singlePickProductloader"
													style="min-height: 40px; padding: 12px; font-size: 14px;">
													<div class="sku-item">
														No available data
													</div>
												</div>
											</template>
										</v-select>
									</span>

									<span v-else-if="outboundProducts[index].pick_from == 'storable_unit'">
										<v-autocomplete
											v-model.number="outboundProducts[index].label"
											height="40px"
											color="#002F44"
											width="200px"
											type="text"
											dense
											:items="getpickOutboundStorableData"
											:item-text="getStorabetext"
											append-icon="mdi-chevron-down"
											item-value="label"
											:rules="[(v) => !!v || 'label is Required']"
											background-color="white"
											class="text-fields custom customs-pick-product customs-pick-product-border-remove"
											placeholder="Enter storable unit’s label"
											outlined
											:return-object="true"
											hide-details="auto"
											@input="storableValueOnChangeFunc($event,index)">
											<template v-slot:no-data>
												<div class="option-items loading" 
													v-if="singlePickProductloader"
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
													v-if="!singlePickProductloader"
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
											append-icon="mdi-chevron-down"
											outlined
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

								<v-col cols="5" style="height:40px" class="py-0 my-0 px-0 mx-0">
									<v-row no-gutters class="py-0 my-0 px-0 mx-0">
										<v-col cols="6" sm="6" md="3" lg="3" class="px-0 mx-0 my-0 py-0">
											<div :class=" outboundProducts[index].pick_from !== 'floor' ? 'checkbox-border pl-2':'checkbox-border checkbox-border-floor pl-2'" >
												<v-checkbox hide-details="auto" :on-icon="'mdi-check'" :off-icon="''" color="#0084bd"  :class="getColourDisabled(disableCheckbox(outboundProducts[index].label,outboundProducts[index].pick_from,index))" @change="addWholeValues(outboundProducts[index].label,index)" :disabled="disableCheckbox(outboundProducts[index].label,outboundProducts[index].pick_from,index)" v-bind:false-value="0" v-bind:true-value="1"   v-model="outboundProducts[index].whole" label="whole"></v-checkbox>
											</div>
										</v-col>

										<v-col cols="3" sm="3" md="6" lg="6" class="py-0 my-0 px-0">
											<v-text-field
												v-if="PickProductItems"
												v-on:input="fillCartonUnit(index, $event)"
												height="40px"
												color="#002F44"
												width="200px"
												type="number"
												dense
												:disabled="getValue(outboundProducts[index].whole,PickProductItems.carton_count)"
												:background-color="outboundProducts[index].whole ===1 || PickProductItems.carton_count ==null ? '#EBF2F5':'white'"
												class="text-fields custom customs-pick-product customs-pick-product-border-remove"
												reverse
												placeholder="0"
												:min="minValue"
												:max="PickProductItems.carton_count !==null ?PickProductItems.carton_count:0"
												:rules="[RemainingCartonCheck(totalCartonPicked,PickProductItems.carton_count),cartonRules.required]"
												v-model.number="outboundProducts[index].carton_count"
												outlined
												hide-details="auto"
												@keydown="restrictValues($event)"
												@wheel="$event.target.blur()">
										</v-text-field>

										<span class="red--text text-subtitle-2 ml-1" v-if="PickProductItems">
											<!-- {{ RemainingCartonCheck(totalCartonPicked,PickProductItems.carton_count) }} -->
										</span>
										</v-col>

										<v-col cols="3" class="py-0 my-0 px-0 mx-0">
											<v-text-field
											height="40px"
											color="#002F44"
											width="150px"
											type="number"
											dense
											background-color="#EBF2F5"
											class="text-fields custom customs-pick-product customs-pick-product-border-remove"
											placeholder="0"
											style="border-radius:none"
											reverse
											outlined
											disabled
											v-model="outboundProducts[index].in_each_carton"
											hide-details="auto">
										</v-text-field>
										</v-col>
									</v-row>
								</v-col>

								<v-col cols="2" style="height:40px" class="py-0 my-0 px-0 mx-0" :class="isMobile ?'px-1':'pl-0 pr-0'" >
									<div class="d-flex">
										<v-text-field
											v-if="PickProductItems"
											v-model.number="outboundProducts[index].total_unit"
											height="40px"
											color="#002F44"
											width="150px"
											type="number"
											dense
											class="text-fields custom customs-pick-product customs-pick-product-border-remove"
											placeholder="0"
											reverse
											outlined
											:disabled="getValue_Units(outboundProducts[index].whole,PickProductItems.total_unit)"
											:background-color="outboundProducts[index].whole ===1 || PickProductItems.total_unit==null  ? '#EBF2F5':'white'"
											:min="minValue"
											:max="PickProductItems.total_unit !==null ? PickProductItems.total_unit:0"
											:rules="[RemainingUnitsCheck(totalUnitPicked,PickProductItems.total_unit),UnitRules.required]"
											hide-details="auto"
											@keydown="restrictValues($event)"
											@wheel="$event.target.blur()">
										</v-text-field>

										<v-icon v-if="outboundProducts.length>1" @click="removeRow(item)" right color="red">
											mdi-close
										</v-icon>
									</div>

									<!-- <span class="red--text text-subtitle-2 ml-1" v-if="PickProductItems"> -->
										<!-- {{ RemainingUnitsCheck(totalUnitPicked,PickProductItems.total_unit) }} -->
									<!-- </span> -->
								</v-col>

								<v-col class="py-0 my-0 px-0 mx-0"  v-if="ErrorShowFunction(outboundProducts[index].label,index) ==true" cols="12" style="height:40px">
									<div class=" my-1 ml-1">
										<span style="font-size:12px; color:#6D858f">{{ErrorShowFunctionShow(outboundProducts[index].label,index)}}</span>	
									</div>
								</v-col>

								<v-col class="py-0 my-0 px-0 mx-0" v-if="isStorableSelected(item,index)"  cols="12" style="height:40px">
									<div class=" my-1 ml-1">
										<span style="font-size:12px; color:#6D858f">{{isStorableSelected(item,index)}}</span>
									</div>		
								</v-col>
							</v-row>
						</div>

						<div v-if="isMobile">
							<v-row
								no-gutters
								class="pt-2 pb-3 my-0 px-1 mx-3 bgColor mb-2"
								:class="outboundProducts.length>1 ? 'borderBottomClass':''"
								v-for="(item, index) in outboundProducts"
								:key="index">

								<v-col cols="6" sm="6" md="6" lg="6" class="py-1 my-0 px-1">
									<h5>Pick From</h5>
									<!-- <v-autocomplete
										v-model.number="outboundProducts[index].label"
										height="40px"
										color="#002F44"
										width="200px"
										type="text"
										dense
										:items="getpickOutboundStorableData"
										:item-text="getStorabetext"
										item-value="label"
										:rules="[(v) => !!v || 'label is Required']"
										background-color="white"
										class="text-fields custom"
										placeholder="Enter storable unit’s label"
										outlined
										:return-object="true"
										hide-details="auto"
										@input="storableValueOnChangeFunc($event,index)"
										>
										<template v-slot:no-data>
											<div class="option-items loading" 
												v-if="singlePickProductloader"
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
												v-if="!singlePickProductloader"
												style="min-height: 40px; padding: 12px; font-size: 14px;">
												<div class="sku-item">
													No available data
												</div>
											</div>
										</template>
									</v-autocomplete> -->
									<!-- @input.native="outboundProducts[index].label=$event.srcElement.value -->

									<v-select
										:items="pickFromArray"
										class="text-fields custom customs-pick-product"
										item-text="name"
										item-value="value"
										placeholder="Pick From"
										outlined
										append-icon="mdi-chevron-down"
										dense
										height="40px"
										width="200px"
										:rules="[(v) => !!v || 'Input is Required']"
										v-model="outboundProducts[index].pick_from"
										background-color="white"
										hide-details="auto"
										@change="setWholeValuetoZero(outboundProducts[index].pick_from,outboundProducts[index].whole,index)"
										:menu-props="{ bottom: true, offsetY: true }">
									</v-select>
								</v-col>
								
								<v-col cols="6" class="py-1 my-0">
									<h5 class="text-end">Refrence 
										<v-icon size="20" v-if="outboundProducts.length>1" @click="removeRow(item)"  right color="red">
											mdi-close
										</v-icon>
									</h5>

									<span v-if="outboundProducts[index].pick_from == 'storable_unit'">
										<v-autocomplete
											v-model.number="outboundProducts[index].label"
											height="40px"
											color="#002F44"
											width="200px"
											type="text"
											dense
											:items="getpickOutboundStorableData"
											:item-text="getStorabetext"
											item-value="label"
											:rules="[(v) => !!v || 'label is Required']"
											background-color="white"
											class="text-fields custom"
											placeholder="Enter storable unit’s label"
											outlined
											:return-object="true"
											hide-details="auto"
											@input="storableValueOnChangeFunc($event,index)">
											<template v-slot:no-data>
												<div class="option-items loading" 
													v-if="singlePickProductloader"
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
													v-if="!singlePickProductloader"
													style="min-height: 40px; padding: 12px; font-size: 14px;">
													<div class="sku-item">
														No available data
													</div>
												</div>
											</template>
										</v-autocomplete>
									</span>

									<span v-else-if="outboundProducts[index].pick_from == 'location'">
										<v-select
											:items="getpickOutboundLocationData"
											class="text-fields custom"
											:item-text="getLocationText"
											item-value="id"
											placeholder="Enter location"
											outlined
											dense
											height="40px"
											width="200px"
											:rules="[(v) => !!v || 'Location is Required']"
											v-model="outboundProducts[index].location_id"
											background-color="white"
											hide-details="auto"
											:menu-props="{ bottom: true, offsetY: true }">
											<template v-slot:no-data>
												<div class="option-items loading" 
													v-if="singlePickProductloader"
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
													v-if="!singlePickProductloader"
													style="min-height: 40px; padding: 12px; font-size: 14px;">
													<div class="sku-item">
														No available data
													</div>
												</div>
											</template>	
										</v-select>
									</span>

									<span v-else>
										<v-select
											class="text-fields custom customs-pick-product customs-pick-product-border-remove"
											item-value="id"
											placeholder="--"
											append-icon="mdi-chevron-down"
											outlined
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

								<v-col cols="6" sm="6" md="6" lg="6" class="my-0 pt-1 px-1">
									<h5>Whole</h5>
									<div class="checkbox-border pl-2">
										<v-checkbox hide-details="auto" :on-icon="'mdi-check'" :off-icon="''" color="#6D858F"  :class="getColourDisabled(disableCheckbox(outboundProducts[index].label,outboundProducts[index].pick_from,index))" @change="addWholeValues(outboundProducts[index].label,index)" :disabled="disableCheckbox(outboundProducts[index].label,outboundProducts[index].pick_from,index)" v-bind:false-value="0" v-bind:true-value="1"   v-model="outboundProducts[index].whole" label="whole"></v-checkbox>
									</div>
								</v-col>

								<v-col cols="6" sm="6" md="6" lg="6" class="pt-1 my-0">
									<h5 class="text-end">Carton</h5>
									<v-text-field
										v-if="PickProductItems"
										height="40px"
										color="#002F44"
										width="200px"
										type="number"
										dense
										:disabled="getValue(outboundProducts[index].whole,PickProductItems.carton_count)"
										:background-color="outboundProducts[index].whole ===1 || PickProductItems.carton_count ==null ? '#EBF2F5':'white'"
										class="text-fields custom"
										reverse
										placeholder="0"
										:min="minValue"
										:max="PickProductItems.carton_count !==null ?PickProductItems.carton_count:0"
										:rules="[RemainingCartonCheck(totalCartonPicked,PickProductItems.carton_count),cartonRules.required]"
										v-model.number="outboundProducts[index].carton_count"
										outlined
										hide-details="auto"
										@keydown="restrictValues($event)"
										@wheel="$event.target.blur()">
									</v-text-field>

									<span class="red--text text-subtitle-2 ml-1" v-if="PickProductItems">
										<!-- {{ RemainingCartonCheck(totalCartonPicked,PickProductItems.carton_count) }} -->
									</span>
								</v-col>

								<v-col cols="6" class="py-0 my-0 px-1">
									<h5>In Each Carton</h5>
									<v-text-field
										height="40px"
										color="#002F44"
										width="150px"
										type="number"
										dense
										background-color="#EBF2F5"
										class="text-fields custom"
										placeholder="0"
										reverse
										outlined
										disabled
										v-model="outboundProducts[index].in_each_carton"
										hide-details="auto">
									</v-text-field>
								</v-col>

								<v-col cols="6" class="py-0 my-0 ">
									<h5 class="text-end">Units</h5>
									<div class="d-flex">
										<v-text-field
											v-if="PickProductItems"
											v-model.number="outboundProducts[index].total_unit"
											height="40px"
											color="#002F44"
											width="150px"
											type="number"
											dense
											class="text-fields custom"
											placeholder="0"
											reverse
											outlined
											:disabled="getValue_Units(outboundProducts[index].whole,PickProductItems.total_unit)"
											:background-color="outboundProducts[index].whole ===1 || PickProductItems.total_unit==null  ? '#EBF2F5':'white'"
											:min="minValue"
											:max="PickProductItems.total_unit !==null ? PickProductItems.total_unit:0"
											:rules="[RemainingUnitsCheck(totalUnitPicked,PickProductItems.total_unit),UnitRules.required]"
											hide-details="auto"
											@keydown="restrictValues($event)"
											@wheel="$event.target.blur()">
										</v-text-field>
									</div>

									<!-- <span class="red--text text-subtitle-2 ml-1" v-if="PickProductItems"> -->
										<!-- {{ RemainingUnitsCheck(totalUnitPicked,PickProductItems.total_unit) }} -->
									<!-- </span> -->
								</v-col>

								<v-col v-if="ErrorShowFunction(outboundProducts[index].label,index) ==true" cols="12" style="height:40px">
									<div class=" my-2 ml-1">
										<span style="font-size:12px; color:#6D858f">{{ErrorShowFunctionShow(outboundProducts[index].label,index)}}</span>	
									</div>
								</v-col>

								<v-col v-if="isStorableSelected(item,index)"  cols="12" style="height:40px">
									<div class=" my-2 ml-1">
										<span style="font-size:12px; color:#6D858f">{{isStorableSelected(item,index)}}</span>
									</div>		
								</v-col>
							</v-row>
						</div>

						<v-row class="mx-3 pb-2 pl-1" no-gutters>
							<v-col cols="2" sm="2" md="2" lg="2" class="my-0 px-0 mx-0" style="height:42px;background:white;" >
								<!-- <div class="add-row-wrapper pb-4 bgColor" :class="isMobile ? 'mx-2 pl-1':' mx-3'"> -->
								<v-btn 
									v-if="PickProductItems" 
									:disabled="totalUnitPicked >= PickProductItems.total_unit && totalCartonPicked>=PickProductItems.carton_count" 
									@click="addRow" 
									
									style="border:1px solid #EBF2F5 !important;height:42px !important;border-radius:0 !important;"
									block
									
									class="btn-white add-btn justify-start">
									+ Add Line
								</v-btn>
							<!-- </div> -->
							</v-col>
						</v-row>						
					</div>
				</v-form>

				<!-- <v-divider></v-divider> -->

				<v-card-actions :class="isMobile ?'single-pick-button-attach':'' ">
					<v-btn
						v-if="PickProductItems"
						class="text-capitalize btn-blue"
						:disabled="disabledValueCheck(totalUnitPicked,totalCartonPicked)"
						@click="pickProductApi">
						{{ !getPickProductLoading ? 'Confirm Pick' : 'Confirming...' }}
					</v-btn>

					<v-btn class="btn-white" @click="close">Cancel</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import globalMethods from "../../../../utils/globalMethods";
import _ from 'lodash'
export default {
	props: [
		"dialogCreateSingle",
		"currentPickedProduct",
		"pickProductsDAta",
		"sortableData",
		"singlePickProductloader",
		"singleOutbound_status",
		"isWarehouse6PL",
		"isWarehouseConnected",
		"productsData"
	],
	data: () => ({
		minValue:0,
		dialog_pick_products: false,
		valid: true,
		LocationDropdown: [],
		linkData: {
			wid: "",
			oid: "",
		},
		outboundProducts: [
			{
				label: "",
				location_id: "",
				carton_count: "",
				total_unit: "",
				in_each_carton: "",
				whole:0,
				pick_from:'',
			},
		],
		cartonRules: {
		required:(v) => parseFloat(v) >= 0 || "Carton should be minimum zero"
		},
		UnitRules:{
		required:(v) => parseFloat(v) >= 0 || "Units should be minimum zero"
		},
		isMobile: false,
		pickFromArray:[{name:'Storable Unit',value:'storable_unit'},{name:'Floor',value:'floor'}]
		
	}),
	computed: {
		...mapGetters({
			getPickProductLoading: "outbound/getPickProductLoading",
			getpickOutboundLocationLoading:"outbound/getpickOutboundLocationLoading",
			getpickOutboundLocationData:"outbound/getpickOutboundLocationData",
			getpickOutboundStorableData:"outbound/getpickOutboundStorableData",
			getCurrentOutboundTab: "outbound/getCurrentOutboundTab"
		}),
		dialogSinglePIck: {
			get() {
				return this.dialogCreateSingle;
			},
			set(value) {
				this.$emit("update:dialogCreateSingle", value);
			},
		},
		PickProductItems: {
			get() {
				return this.currentPickedProduct;
			},
			set(value) {
				this.$emit("update:currentPickedProduct", value);
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
		pickeddd: {
			get() {
				return this.pickProductsDAta;
			},
			set(value) {
				this.$emit("update:pickProductsDAta", value);
			},
		},  
		totalCartonPicked: function(){
			return this.outboundProducts.reduce(function(prev, index) {
				if (prev ==='' || index.carton_count ==='') return prev
				return parseInt(prev) + parseInt(index.carton_count) 
			}, 0);
		},
		totalUnitPicked: function(){
			return this.outboundProducts.reduce(function(prev, index) {
				if (prev ==='' || index.total_unit ==='') return prev
				return parseInt(prev) + parseInt(index.total_unit) 
			}, 0);
		},
	},
	watch:{
		// outboundProducts:{
		// 	deep: true,
		// 	handler(){
		// 		this.outboundProducts.map((val,index) =>{
		// 			if(this.outboundProducts[index].whole ===1){
		// 			this.outboundProducts[index].carton_count = this.getpickOutboundStorableData[index].storable_unit_products.carton_count !==null ? this.getpickOutboundStorableData[index].storable_unit_products.carton_count:0 ;
		// 			this.outboundProducts[index].total_unit =this.getpickOutboundStorableData[index].storable_unit_products.total_unit !==null ? this.getpickOutboundStorableData[index].storable_unit_products.total_unit:0;
		// 			}
					
		// 		})
		// 	}
			
		// }
	},
	methods: {
		...mapActions({
			pickproduct: "outbound/pickproduct",
			fetchSingleOutbound: "outbound/fetchSingleOutbound",
			fetchPendingOutbounds: "outbound/fetchPendingOutbounds",
			fetchFloorOutbounds: "outbound/fetchFloorOutbounds",
			fetchPendingShippingServiceProvider:'outbound/fetchPendingShippingServiceProvider',
			setAllOutboundProductsLists: 'outbound/setAllOutboundProductsLists',
		}),
		...globalMethods,
		getColor(colrCheck) {
			if (this.totalUnitPicked == 0) return  'RedError'
			else if (this.totalUnitPicked < parseInt(colrCheck)) return 'OrangeWarning'
			else if (this.totalUnitPicked == parseInt(colrCheck)) return 'GreenSucess'
		},
		getColorCarton(colrCheckCarton) {
			if (this.totalCartonPicked == 0) return  'RedError'
			else if (this.totalCartonPicked < parseInt(colrCheckCarton)) return 'OrangeWarning'
			else if (this.totalCartonPicked == parseInt(colrCheckCarton)) return 'GreenSucess'
		},
		onResize() {
			if (window.innerWidth < 1024) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
		},
		close() {
			this.outboundProducts = [
				{
					label: "",
					location_id: "",
					carton_count: "",
					total_unit: "",
					in_each_carton: "",
					whole:0,
					pick_from:''
				},
			]
			this.$emit("closeSinglePick");
			this.$refs.form.resetValidation()
		},
		async pickProductApi() {
			if (typeof this.PickProductItems !== "undefined") {
				// let pickeddd =	[this.pickeddd] 
				// pickeddd.map((val,index)=>{
				// 	if(typeof pickeddd[index].label =='object'){
				// 		pickeddd[index].label = pickeddd[index].label.label
				// 	}	
				// })
				var deepCopy = _.cloneDeep(this.pickeddd);
				deepCopy.map((val,index) =>{
					if(typeof deepCopy[index].label =='object' ){
						deepCopy[index].label = deepCopy[index].label.label
					}
				} )
				deepCopy.filter((val,index) =>{
					if(deepCopy[index].pick_from =='floor' ){
						return  delete val.location_id && delete val.label
					}

				} )
				let newdata = {
					outbound_product_id: this.PickProductItems.id,
					data: deepCopy,
					oid: this.linkData.oid,
					wid: this.linkData.wid
				};

				try {
					await this.pickproduct(newdata);
					this.$emit("closeSinglePick");
					this.notificationMessage("Product picked sucessfully");
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
				} catch (e) {
					this.notificationError(e);
				}
			}
		},
		addRow() {
			let getItem = this.pickeddd;
				getItem.push({
				label: "",
				location_id: "",
				carton_count: 0,
				total_unit: 0,
				in_each_carton: this.PickProductItems.in_each_carton !==null && this.PickProductItems.in_each_carton !=='' ? this.PickProductItems.in_each_carton :0,
				whole:0,
				pick_from:''
			});
		},
		removeRow(item) {
			let getIndex = this.outboundProducts.indexOf(item);
			this.outboundProducts.splice(getIndex, 1);
		},
		RemainingCartonCheck(input_value,actual_remaining_Carton) {
			if (input_value!==null && input_value!=='' && actual_remaining_Carton !==null && actual_remaining_Carton !=='') {
				if (input_value > actual_remaining_Carton) return false
				// else if( input_value < actual_remaining_Carton) return false
				return true
			} else {
				return true
			}
		},
		RemainingUnitsCheck(inputvalue,actual_remaining_Unit) {
			if (inputvalue!==null && inputvalue!=='' && actual_remaining_Unit !==null && actual_remaining_Unit !=='') {
				if (inputvalue > actual_remaining_Unit) return false
				// else if(inputvalue < actual_remaining_Unit) return false
				return true
			} else{
				return true
			}
		},
		getLocationText(item){
			if (item.shelf !==null && item.row !==null && item.rack !==null) {
				return `${item.row}${item.rack}${item.shelf}`
			} else {
				return  '--'
			}
		},
		getStorabetext(item) {
			if (item.label!==null && item.label !=='') {
				return item.label
			} else {
				return '--'
			}
		},
		getValue(item,value) {
			if (item===1 || value==null ) {
				return  true
			} else {
				return  false
			}
		},
		getValue_Units(item,value) {
			if (item===1 || value==null ) {
				return  true
			} else {
				return  false
			}
		},
		addWholeValues(item,indexBox) {
			if (item !== 'undefined' && item !== null && typeof item!=='undefined' && item !=='' &&  typeof item !=='string' &&  item.storable_unit_products !=='undefined') {
				if (item.storable_unit_products[0].carton_count !=='undefined') {
					let CARTONS = this.PickProductItems.carton_count !==null ? this.PickProductItems.carton_count:0
					let UNITS = this.PickProductItems.total_unit !==null ? this.PickProductItems.total_unit:0
					if (item.storable_unit_products[0].carton_count >CARTONS || item.storable_unit_products[0].total_unit >UNITS  ) {
						return
					} else {
						this.outboundProducts.find((val,index) =>{
							if (this.outboundProducts[index].whole ===1 && index ===indexBox  ) {
								this.outboundProducts[index].carton_count = item.storable_unit_products[0].carton_count !==null ? item.storable_unit_products[0].carton_count:0 ;
								this.outboundProducts[index].total_unit =item.storable_unit_products[0].total_unit !==null ? item.storable_unit_products[0].total_unit:0;
							} else {
								this.outboundProducts
							}	
						})
					}
				}
			}
		},
		disableCheckbox(item,pickFrom,indexTextField) {
			if (pickFrom == 'floor') {
				return true
			} else {
				if (item !== 'undefined' && item !== null && item !=='' &&  typeof item!=='undefined' && typeof item !=='string' &&  item.storable_unit_products !=='undefined') {
					if (item.storable_unit_products[0].carton_count !=='undefined') {
						let CARTONS = this.PickProductItems.carton_count !==null ? this.PickProductItems.carton_count:0
						let UNITS = this.PickProductItems.total_unit !==null ? this.PickProductItems.total_unit:0
						let product__id =[]
						product__id=item.storable_unit_products.filter(val=> {
							if(val.product_id == this.PickProductItems.product_id) {
								return val
							}
						})
						if (product__id.length>0  && (product__id[0].carton_count >CARTONS || product__id[0].total_unit >UNITS || item.storable_unit_products.length>1 )) {
							this.outboundProducts[indexTextField].whole = 0;
							return true
						} else {
							return false
						}
					}
				} else if (item =='' && typeof item =='string') {
					return true
				}
			}		
		},
		getColourDisabled(disabledValue) {
			if (disabledValue) return "Card__container_forCheckbox" 	
			return "Card__container_forCheckboxChecked"			
		},
		disabledValueCheck(unit_params,carton_params) {
			if (unit_params > this.PickProductItems.total_unit || carton_params > this.PickProductItems.carton_count )return true
			else if (unit_params < this.PickProductItems.total_unit || carton_params < this.PickProductItems.carton_count )return true
			else if (!this.valid) return true
			else if (this.getPickProductLoading) return true
			else if (this.pickeddd.length==0) return true
		},
		restrictValues(e) {
            if (e.key=='-' && e.keyCode==189 || e.key=='+' && e.keyCode==187 ) {
                e.preventDefault()
            }
        },
		fillCartonUnit(indexTextField, value) {
            // let findItem = _.find(this.PickProductItems, (e) => e.id === item.outbound_product_id)

            // if (findItem !== undefined) {
            //     item.total_unit = value * findItem.in_each_carton
            // }
			this.outboundProducts[indexTextField].total_unit = value * this.outboundProducts[indexTextField].in_each_carton
        },
		/* eslint-disable */
		storableValueOnChangeFunc(event,indexTextField) {
				this.getpickOutboundLocationData.find(value => {
					if (value.id ==event.location_id) {
					this.outboundProducts.map((val) => {
						// console.log(val)
						this.outboundProducts[indexTextField].label = event;
						this.outboundProducts[indexTextField].location_id =value.id
					})
				}
			})
			// this.ErrorShowFunction(event,indexTextField)
			// console.log("evnet",event)			
		},
		/* eslint-enable */
		ErrorShowFunction(event,indexTextField){
			if (this.outboundProducts !=='undefined' && this.outboundProducts.length>0) {
				if (this.outboundProducts[indexTextField].carton_count > event.total_cartons || this.outboundProducts[indexTextField].total_unit > event.total_units ){
					return true
				} else {
					return false
				}
			}
		},
		ErrorShowFunctionShow(event,indexTextField){
			if (this.outboundProducts !=='undefined' && this.outboundProducts.length>0) {
				if (this.outboundProducts[indexTextField].carton_count > event.total_cartons || this.outboundProducts[indexTextField].total_unit > event.total_units ){
					return "The storable unit doesn't have enough units or carton"
				} else {
					return ''
				}
			}
		},
		isStorableSelected(item, index) {
	  		if (item.label !== "" && item.whole !== 0) {
				let findSelectedOption = _.findIndex(
		  			this.outboundProducts,
		  			(e) =>
						(e.label == item.label &&
						e.whole == item.whole)
					);
				if (findSelectedOption !== index) {
		  			return "This storable unit has already been selected/duplicated";
				}
	  		}
		},
		setWholeValuetoZero(pick_from,whole,indexTextField){
			if (pick_from == 'floor' && whole == 1){
				this.outboundProducts[indexTextField].whole = 0;
			}
		}
	},
	async mounted() {
		let wid = new URL(location.href).searchParams.get("wid");
		let oid = new URL(location.href).searchParams.get("id");
		this.linkData = { oid, wid };

		if (this.outboundProducts !== this.pickeddd) {
			this.outboundProducts = this.pickeddd;
		}
	},
	updated() {
		if (this.outboundProducts !== this.pickeddd) {
			this.outboundProducts = this.pickeddd;
		}
	},
};
</script>

<style lang="scss">
@import "@/assets/scss/pages_scss/inventory/outbound/outboundDialog.scss";

.create-outbound-dialog.single-product-pick {
	.v-card {
		.text-subtitle-2 {
			font-family: "Inter-Medium", sans-serif !important;
		}
	}

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
		font-size: 14px !important;
	}

	.bgColor h5 {
		font-weight: 500 !important;
		font-family: "Inter-Medium", sans-serif;
		color: #6d858f !important;
		font-size: 14px !important;
	}

	.GreenSucess {
		// font-weight: 600;
		font-family: "Inter-Medium", sans-serif;
		color: #16b442 !important;
	}

	.OrangeWarning {
		// font-weight: 600;
		font-family: "Inter-Medium", sans-serif;
		color: #D68A1A !important;
	}

	.RedError {
		//  font-weight: 600;
		font-family: "Inter-Medium", sans-serif;
		color: #F93131 !important;
	}

	.single-pick-button-attach {
		position: sticky !important;
	}

	.checkbox-border {
		border: 1px solid #EBF2F5;
		border-radius: 0;
		border-left: none ;
		background-color: white;
		height: 45px;
		display: flex;
		align-items: center;

		.mdi-checkbox-blank-outline::before {
			content: "\F14FC";
			color: #EBF2F5;
		}
		.v-input {
			&.v-input--checkbox {
				margin-top: 0;
				padding-top: 0;
			}
		}
		.v-label {
			font-size: 14px;
			color: #4A4A4A !important;
			text-transform: capitalize;
			font-family: 'Inter-Regular', sans-serif;
		}
	}
	.checkbox-border-floor {
		background-color: #FFF9F0;
		// border-left: 1px solid #EBF2F5 !important;

		.v-input {
			.v-input__control{
				background:#FFF9F0 !important; 

				.v-icon {
					&.material-icons {
						&::before {
							background-color: #FFF9F0 !important;
							border-radius: 4px;
						}
					}
				}	
			}
		}
		.v-label {
			background:#FFF9F0; 
			color: #819FB2 !important;
		}
	}
	.Card__container_forCheckbox {
		// .v-input--selection-controls__input{
		// 	background-color: #FFF9F0;
		// 	border-radius: 5px !important;
		// 	padding: 2px; 
		// 	border: 1px solid #b4cfe0;
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
					opacity: .5;
					background-color: #B4CFE0 !important;
					border-radius: 4px;
				}
			}
		}	
	}
	.Card__container_forCheckboxChecked{
		// .v-input--selection-controls__input{
		// 	border-radius: 5px !important; 
		// 	padding: 8px;
		// 	border: 2px solid #0084bd;
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

	.borderBottomClass{
		border-bottom: 1px solid rgba(0, 0, 0, 0.12) !important;
	}

	.customs-pick-product.v-text-field>.v-input__control{
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
					border-left: none !important;
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
}
</style>