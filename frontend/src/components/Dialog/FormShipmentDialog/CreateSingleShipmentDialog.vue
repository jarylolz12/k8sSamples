<!-- @format -->

<template>
	<div style="position: relative;">
		
		<v-dialog
			v-model="dialog"
			:content-class="`${className} create-shipment-ultimate-wrapper ${getClassName}` "
			:width="multiShipmentForm ? '' : '800px'"
			persistent
		>
			<v-card id="single-shipment-dialog-id" class="single-shipment-dialog">

				<v-card-title class="headline">
					<slot name="title"></slot>
					<div class="d-flex shipment-dialog-header" :style="multiShipmentForm ? 'left: 21%;' : 'left: 37%;'">
						<v-btn class="btn-white shipment-dialog-header-btn-left" :class="multiShipmentForm ? '' : 'active-btn'" @click="addSingleShipment()">
							<span>Add Single Shipment</span>
						</v-btn>
						<v-btn class="btn-white shipment-dialog-header-btn-right" :class="multiShipmentForm ? 'active-btn' : ''" @click="addMultiShipment()">
							<span>Add In Bulk</span>
						</v-btn>
					</div>
					<button icon dark class="btn-close" @click.stop="closeSingleShipmentDialog()"
					:disabled="this.getCreateShipmentLoading || this.getCreateShipmentWithoutMBLNumber || getCheckingMBLNumber()">
						<v-icon>mdi-close</v-icon>
					</button>
				</v-card-title>

				<v-card-text class="pb-0" v-if="!multiShipmentForm">
					<div
						ref="cardTextWrapper"
						class="d-flex flex-row"
					>
						<div
							class="d-flex flex-column single-shipment-dialog-main-content second-column add-shipment-tracking"
						>
							<v-form>
								<p class="create-shipment-warn d-flex">
									<GenericIcon
										color="#D68A1A"
										iconName="info-icon"
									></GenericIcon>
									<span style="margin: -2px 0px 0px 10px;">You can track a shipment through Shifl by simply sharing the MBL or Booking number.</span>
								</p>

								<div class="d-flex">
									<div class="input-fields">
										<div class="form-label required">
											<span>MBL #</span>
										</div>
										<div :class="mbl_checkbox ? 'readonly-field' : ''">
											<v-text-field
												v-if="createSingleShipmentPayloadData.is_edit_dialog === true"
												:height="40"
												v-model="createSingleShipmentPayloadData.mbl_num"
												type="text"
												dense
												placeholder="Enter MBL number"
												outlined
												hide-details="auto"
												:readonly="mbl_checkbox"
												:disabled="mbl_checkbox"
												@focusout.stop="checkMBLNumber(createSingleShipmentPayloadData.mbl_num)"
											>
											</v-text-field>
											<v-text-field
												v-else
												:height="40"
												v-model="mbl_number"
												type="text"
												dense
												placeholder="Enter MBL number"
												outlined
												hide-details="auto"
												:readonly="mbl_checkbox"
												:disabled="mbl_checkbox"
												@focusout.stop="checkMBLNumber(mbl_number)"
											>
											</v-text-field>
											<span style="color: #F44336; font-size: 10px; line-height: 14px;" v-if="inValidMBLNumber && !existingMBLNumber">Invalid MBL Number</span>
											<span style="color: #F44336; font-size: 10px; line-height: 14px;" v-if="existingMBLNumber && !inValidMBLNumber">There is an existing shipment with the same MBL number</span>
										</div>

										<div class="form-label required mt-4">
											<span>Shipping line Booking number</span>
										</div>
										<v-text-field
											v-if="createSingleShipmentPayloadData.is_edit_dialog === true"
											:height="40"
											v-model="createSingleShipmentPayloadData.booking_num"
											type="text"
											dense
											placeholder="Enter Shipping Line Booking Number"
											outlined
											hide-details="auto"
										>
										</v-text-field>
										<v-text-field
											v-else
											:height="40"
											v-model="booking_number"
											type="text"
											dense
											placeholder="Enter Shipping Line Booking Number"
											outlined
											hide-details="auto"
										>
										</v-text-field>
										
										<div class="form-label required mt-4">
											<span>Customer Ref Number</span>
										</div>
										<v-text-field
											v-if="createSingleShipmentPayloadData.is_edit_dialog === true"
											:height="40"
											v-model="createSingleShipmentPayloadData.customer_reference_number"
											type="text"
											dense
											placeholder="Enter Customer Ref Number"
											outlined
											hide-details="auto"
											class="mb-4"
										>
										</v-text-field>
										<v-text-field
											v-else
											:height="40"
											v-model="customer_reference_number"
											type="text"
											dense
											placeholder="Enter Customer Ref Number"
											outlined
											hide-details="auto"
											class="mb-4"
										>
										</v-text-field>
									</div>

									<div class="mbl-warning">
										<v-card-actions class="mbl-find-wrapper">
											<v-btn
												color="#F0FBFF"
											>
												Can’t find MBL? 
												<v-btn
													icon
													@click="showDropdown = !showDropdown"
												>
													<v-icon>{{ showDropdown ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
												</v-btn>
											</v-btn>
										</v-card-actions>

										<v-expand-transition>
											<v-card
												class="mx-auto"
												max-width="344"
												v-show="showDropdown"
											>
												<div style="border-bottom: 1px solid #D8E7F0;">
													<v-card-text>
														<span class="card-text-header">Can’t Find MBL?</span>
														<div>
															<span class="card-text-body">
																If you cannot find MBL number, please reach out to us at <a href="PO@shifl.com" target="_blank" style="text-decoration: none;">PO@shifl.com</a> Or, Provide booking number and we can help you find the MBL number
															</span>
														</div>
													</v-card-text>
												</div>
												<div class="bottom-checkbox">
													<v-card-text>
														<v-checkbox
															hide-details
															v-model="mbl_checkbox"
															@change="checkMBL()"
															label="Track with booking number only"
														></v-checkbox>
													</v-card-text>
												</div>
												
											</v-card>
										</v-expand-transition>
									</div>
								</div>

							</v-form>
						</div>
					</div>
				</v-card-text>
				
				<v-card-text class="pb-0" v-if="multiShipmentForm">
					<create-multi-shipment
						@addBulkShipmentSuccess="addBulkShipmentSuccess"
						@close="close"
						@reloadShipments="reloadShipments"
					/>
				</v-card-text>
				
				<v-card-actions v-if="!multiShipmentForm">
					<div class="d-flex footer">
						<v-btn class="save-btn btn-blue" text @click.stop="trackShipment()" 
							:disabled="this.getCreateShipmentLoading || this.getCreateShipmentWithoutMBLNumber || getCheckingMBLNumber() || checkMBLError() || (this.createSingleShipmentPayloadData.is_edit_dialog === true ? this.createSingleShipmentPayloadData.mbl_num === '' : this.mbl_number === '' && this.createSingleShipmentPayloadData.is_edit_dialog === true ? this.createSingleShipmentPayloadData.booking_num === '' : this.booking_number === '')">
							<span v-if="this.getCreateShipmentLoading || this.getCreateShipmentWithoutMBLNumber">Shipment Tracking...</span>
							<span v-else-if="getCheckingMBLNumber()">Checking MBL Number...</span>
							<span v-else>Track Shipment</span>
						</v-btn>
						<v-btn class="delete-cancel btn-white edit-shipment-cancel-btn btn-blue" text @click.stop="closeSingleShipmentDialog()"
						:disabled="this.getCreateShipmentLoading || this.getCreateShipmentWithoutMBLNumber || getCheckingMBLNumber()">
							<span>Cancel</span>
						</v-btn>
					</div>
				</v-card-actions>
			</v-card>
		</v-dialog>
		
	</div>
</template>
<style lang="scss">
@import "./scss/createSingleShipmentDialogTracking.scss";
@import "./scss/fields.scss";
</style>
<script>
import GenericIcon from "../../Icons/GenericIcon";
import { mapGetters, mapActions } from "vuex";
import CreateMultiShipment from './CreateMultiShipment.vue';
import globalMethods from '@/utils/globalMethods';

export default {
	name: "CreateSingleShipmentDialog",
	props: [
		"show",
		"createSingleShipmentPayloadData",
		"className",
		"addShipmentDialogModalView",
		"item",
		"addBulkShipmentDialogModalView",
	],
	data: () => ({
		multiShipmentForm: false,
		showDropdown: false,
		isAddMoreInfo: false,
		sidebarItems: [
			{
				icon: "general",
				label: "General Information",
				width: 20,
				selected: true,
				id: "generalInformationSection",
				reference: "generalInformationSection",
				height: 20,
			},
			{
				icon: "po-icon",
				label: "Purchase Orders",
				width: 20,
				selected: false,
				id: "purchaseOrderSection",
				reference: "purchaseOrderSection",
				height: 20,
			},
		],
		mbl_number: '',
		booking_number: '',
		customer_reference_number: '',
		mbl_checkbox: false,
		inValidMBLNumber: false,
		existingMBLNumber: false,
		existingLCLShipment: false,
		existingFCLShipment: false,
		trackInfoData: [],
		containerInfo: '',
		trackingMBLNumber: false,
		existingShipmentId: '',
	}),
	components: {
		GenericIcon,
		CreateMultiShipment,
	},
	computed: {
		...mapGetters([
			"getCreateShipmentLoading",
			"getCreateShipmentWithoutMBLNumber",
		]),
		getClassName() {
			return this.multiShipmentForm ? '' : 'create-single-shipment-dialog';
		},
		dialog: {
            get() {
                return this.show;
            },
            set(value) {
                this.$emit("update:show", value);
            },
        },
	},
	methods: {
		...globalMethods,
		...mapActions([
			"createShipment",
			"createShipmentWithoutMblNumber"
		]),
		addMultiShipment() {
			this.multiShipmentForm = true;
		},
		addSingleShipment() {
			this.multiShipmentForm = false;
		},
		addMoreInfo() {
			this.isAddMoreInfo = true;
		},
		clickOutside() {
			this.$emit("close");
		},
		closeSingleShipmentDialog() {
			this.$emit("close");
		},
		trackShipment() {

			let mbl_num = this.createSingleShipmentPayloadData.is_edit_dialog === true ? this.createSingleShipmentPayloadData.mbl_num : this.mbl_number;
			let booking_num = this.createSingleShipmentPayloadData.is_edit_dialog === true ? this.createSingleShipmentPayloadData.booking_num : this.booking_number;
			let ref_number = this.createSingleShipmentPayloadData.is_edit_dialog === true ? this.createSingleShipmentPayloadData.customer_reference_number : this.customer_reference_number;

			if(mbl_num !== '' || booking_num !== '') {

				let payload = {
					'mbl_num': mbl_num, 
					'booking_num': booking_num, 
					'customer_reference_number': ref_number, 
					'date_id': new Date().getTime(),
					'carrier_id': '',
					'eta': this.trackInfoData.eta ? this.trackInfoData.eta : '',
					'etd': this.trackInfoData.etd ? this.trackInfoData.etd : '',
					'location_from': this.trackInfoData.location_from ? this.trackInfoData.location_from : '',
					'location_to': this.trackInfoData.location_to ? this.trackInfoData.location_to : '',
					'mode': '',
					'type': '',
					'inco_term': '',
					'containers': this.containerInfo ? this.containerInfo : {},
					'vessel': this.trackInfoData.vessel ? this.trackInfoData.vessel : '',
					'voyage_number': this.trackInfoData.voyage_number ? this.trackInfoData.voyage_number : '',
					'terminal': this.trackInfoData.terminal_name ? this.trackInfoData.terminal_name : '',
					'invalid_mbl_number': this.inValidMBLNumber,
					'existing_mbl_number': this.existingMBLNumber,
					'existing_fcl_shipment': this.existingFCLShipment,
					'existing_lcl_shipment': this.existingLCLShipment,
					'existing_shipment_id': this.existingShipmentId,
					'is_edit_dialog': false
				}

				if(this.inValidMBLNumber === true) {
					this.$emit('createSingleShipmentPayload', payload);
				} else if(this.existingMBLNumber === true) {
					this.$emit('createSingleShipmentPayload', payload);
				} else if(this.existingFCLShipment === true) {
					this.$emit('createSingleShipmentPayload', payload);
				} else if(this.existingLCLShipment === true) {
					this.$emit('createSingleShipmentPayload', payload);
				} else {
					if(mbl_num !== '') {
						this.createShipment(payload)
							.then((response) => {
								if(response.data && response.data.is_already_exists && response.data.is_already_exists.is_already_exists === true && response.data.is_already_exists.type === 'LCL') {
									this.notificationError("LCL shipment is already exist");
								} else if(response.data && response.data.is_already_exists && response.data.is_already_exists.is_already_exists === true && response.data.is_already_exists.type === 'FCL') {
									this.notificationError("FCL shipment is already exist");
								} else if(response.data && response.data.is_already_exists && response.data.is_already_exists.is_already_exists === true && response.data.is_already_exists.type === '') {
									this.notificationError("Shipment is already exist");
								} else {
									this.$emit('update:createdShipmentType', 'withValidMBL');
									this.$emit('showSingleShipmentSuccessDialog');
									this.$emit('update:createSingleShipmentId', response.data.shipment_id);
									this.mbl_number = '';
									this.booking_number = '';
									this.customer_reference_number = '';
								}
								this.closeSingleShipmentDialog();
							})
							.catch((e) => {
								console.log(e);
								this.notificationError("SOMETHING WENT WRONG!");
							});
					} else {
						this.createShipmentWithoutMblNumber(payload)
							.then((response) => {
								if(response.data.status === 'ok') {
									this.$emit('update:createdShipmentType', 'withoutMBL');
									this.$emit('showSingleShipmentSuccessDialog');
									this.$emit('update:createSingleShipmentId', response.data.shipment_id);
									this.mbl_number = '';
									this.booking_number = '';
									this.customer_reference_number = '';
								} else {
									this.notificationError("SOMETHING WENT WRONG!");
								}
							})
							.catch((e) => {
								console.log(e);
								this.notificationError("SOMETHING WENT WRONG!");
							});
					}
				}
			}
		},
		checkMBL() {
			this.mbl_number = '';
			this.createSingleShipmentPayloadData.mbl_num = '';
		},
		checkMBLNumber(mbl_number) {
			let mbl_num = mbl_number;
			this.existingMBLNumber = false;
			this.inValidMBLNumber = false;
			this.existingLCLShipment = false;
			this.existingFCLShipment = false;
			this.trackingMBLNumber = true;
			this.existingShipmentId = '';

			if (mbl_num !== "") {
				this.$store
					.dispatch("booking/checkMblNumber", mbl_num)
					.then((r) => {
						if (typeof r.data !== "undefined" && r.status === 200) {
							this.mblNumInfo = r.data;
							if (this.mblNumInfo.is_already_exists.is_already_exists == true) {
								if (this.mblNumInfo.is_already_exists.type && this.mblNumInfo.is_already_exists.type == "LCL") {
									this.existingLCLShipment = true;
								} else if(this.mblNumInfo.is_already_exists.type && this.mblNumInfo.is_already_exists.type == "FCL") {
									this.existingFCLShipment = true;
									this.existingShipmentId = this.mblNumInfo.is_already_exists.shipment_id;
								} else {
									this.existingMBLNumber = true;
									this.existingShipmentId = this.mblNumInfo.is_already_exists.shipment_id;
								}
							} else {
								if(this.mblNumInfo.attributes === undefined) {
									this.inValidMBLNumber = true;
								} else {
									this.trackInfoData = {
										eta: this.mblNumInfo.attributes.pod_eta_at == null
												? this.mblNumInfo.attributes.pod_ata_at
												: this.mblNumInfo.attributes.pod_eta_at,
										etd: this.mblNumInfo.attributes.pol_etd_at == null
												? this.mblNumInfo.attributes.pol_atd_at
												: this.mblNumInfo.attributes.pol_etd_at,
										location_from: this.mblNumInfo.attributes.port_of_lading_name,
										location_to: this.mblNumInfo.attributes.port_of_discharge_name,
										carrier: this.mblNumInfo.attributes.shipping_line_short_name,
										vessel: this.mblNumInfo.attributes.pod_vessel_name,
										voyage_number: this.mblNumInfo.attributes.pod_voyage_number,
										terminal_name: this.mblNumInfo.terminal_name,
									};
									this.containerInfo = this.mblNumInfo.containers;
								}
							}
						} else {
							this.inValidMBLNumber = true;
						}
					})
					.catch((e) => {
						console.log("e", e);
						this.inValidMBLNumber = true;
					});
			}
		},
		getCheckingMBLNumber() {
			return this.$store.getters['booking/getCheckingMblNumber']
		},
		checkMBLError() {
			if(this.trackingMBLNumber === false && (this.createSingleShipmentPayloadData.invalid_mbl_number || this.createSingleShipmentPayloadData.existing_mbl_number || this.createSingleShipmentPayloadData.existing_fcl_shipment || this.createSingleShipmentPayloadData.existing_lcl_shipment)) {
				return true;
			} else {
				return false;
			}
		},
		addBulkShipmentSuccess(counter) {
			this.$emit("update:addBulkShipmentDialogModalView", true);
			this.$emit("update:shipmentCounter", counter);
		},
		close() {
			this.$emit("close");
		},
		reloadShipments() {
			this.$emit("reloadShipments");
		},
	}
};
</script>
