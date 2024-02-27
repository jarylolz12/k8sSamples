<!-- @format -->

<template>
	<div class="shipment-info-general-wrapper">
		<v-row class="shipment-info-general-header-wrapper">
			<v-col :cols="6">
				<div class="info-title">{{ "General Information" }}</div>
			</v-col>
			<v-col :cols="6">
				<div v-if="1 == 2" class="d-flex justify-end">
					<a class="edit-info-btn">
						{{ "Edit Info" }}
					</a>
				</div>
			</v-col>
		</v-row>

		<v-row class="shipment-info-general-content-wrapper">
			<v-col :cols="`${isMobile ? 12 : 6}`" class="shipment-details-section">
				<div class="shipment-info-general-label">
					<div>MBL #</div>
					<div 
						class="original-width" 
						:class="getShipmentDetails.mbl_num || getShipmentDetails.is_shipment_tracking_create == 0 ? '' : 'edited-fields'" 
						v-if="!editMblField || getShipmentDetails.is_shipment_tracking_create == 0" 
						@click="getShipmentDetails.mbl_num || getShipmentDetails.is_shipment_tracking_create == 0 ? '' : editMblFieldClick(true)"
					>
						<span>{{
							getShipmentDetails.mbl_num ? getShipmentDetails.mbl_num : "N/A"
						}}</span>
					</div>
					
					<div class="original-width edit-mbl-field d-flex" v-if="editMblField && getShipmentDetails.is_shipment_tracking_create == 1">
						<v-text-field
							v-model="getShipmentDetails.mbl_num"
							class="mbl-input-field"
							outlined
						></v-text-field>
						<template v-if="!isMblCalling">
							<div style="margin-right: 2%; margin-left: 4%; margin-top: 1.5%; cursor: pointer;">
								<button @click="openMBLWarningDialog()"><img src="../../assets/icons/check-blue.svg" /></button>
							</div>
							<div style="cursor: pointer; margin-top: 1.5%;">
								<button @click="editMblFieldClick(false)"><img src="../../assets/icons/close-red.svg" /></button>
							</div>
						</template>
						<template v-else>
							<div style="margin-right: 2%; margin-left: 4%; margin-top: 0.5%; cursor: pointer;">
								<v-progress-circular :size="20" color="#0171a1" indeterminate></v-progress-circular>
							</div>
						</template>
					</div>
				</div>
				<div class="shipment-info-general-label">
					<div>From & ETD</div>
					<div class="original-width">
						<span
							>{{
								getShipmentDetails.location_from_name
									? getShipmentDetails.location_from_name
									: "N/A"
							}},
						</span>
						<span style="color: #0171A1;">{{
							getEtdDate(getShipmentDetails.etd)
						}}</span>
					</div>
				</div>
				<div class="shipment-info-general-label">
					<div>To & ETA</div>
					<div class="original-width">
						<span
							>{{
								getShipmentDetails.location_to_name
									? getShipmentDetails.location_to_name
									: "N/A"
							}},
						</span>
						<span style="color: #0171A1;">{{
							getEtaDate(getShipmentDetails.eta)
						}}</span>
					</div>
				</div>
				<div class="shipment-info-general-label">
					<div>Mode & Type</div>
					<div class="original-width">
						<span class="d-flex">
							<mode-icon v-if="mode" :iconName="mode.toLowerCase()" />
							{{ modeName() }}, <span class="ml-1 text-uppercase">{{ typeName() }}</span>
						</span>
					</div>
				</div>
				<div class="shipment-info-general-label">
					<div>Terminal</div>
					<div class="original-width">
						<span>{{
							getShipmentDetails.terminal_real_name
								? getShipmentDetails.terminal_real_name
								: "N/A"
						}}</span>
					</div>
				</div>
			</v-col>
			<v-col :cols="`${isMobile ? 12 : 6}`">
				<div class="shipment-info-general-label" id="booking-number">
					<div>Booking #</div>
					<div 
						class="original-width" 
						:class="getShipmentDetails.booking_number ? '' : 'edited-fields'"
						v-if="!editBookingField" 
						@click="getShipmentDetails.booking_number ? '' : editBookingFieldClick(true)"
					>
						{{
							getShipmentDetails.booking_number
								? getShipmentDetails.booking_number
								: "N/A"
						}}
					</div>
					<div class="original-width edit-mbl-field d-flex" v-if="editBookingField">
						<v-text-field
							v-model="getShipmentDetails.booking_number"
							class="mbl-input-field"
							outlined
							height="5"
						></v-text-field>
						<template v-if="!getUpdateShipmentFieldsLoading">
							<div style="margin-right: 2%; margin-left: 4%; margin-top: 1.5%; cursor: pointer;">
								<button @click="checkEditedField('booking_num')"><img src="../../assets/icons/check-blue.svg" /></button>
							</div>
							<div style="cursor: pointer; margin-top: 1.5%;">
								<button @click="editBookingFieldClick(false)"><img src="../../assets/icons/close-red.svg" /></button>
							</div>
						</template>
						<template v-else>
							<div style="margin-right: 2%; margin-left: 4%; margin-top: 0.5%; cursor: pointer;">
								<v-progress-circular :size="20" color="#0171a1" indeterminate></v-progress-circular>
							</div>
						</template>
					</div>
				</div>
				<div class="shipment-info-general-label">
					<div>Custom Ref#</div>
					<div 
						class="original-width edited-fields"
						v-if="!editCustomerRefField" 
						@click="editCustomerRefFieldClick(true)"
					>
						<span>{{
							getShipmentDetails.customer_reference_number
								? getShipmentDetails.customer_reference_number
								: "N/A"
						}}</span>
					</div>
					<div class="original-width edit-mbl-field d-flex" v-if="editCustomerRefField">
						<v-text-field
							v-model="getShipmentDetails.customer_reference_number"
							class="mbl-input-field"
							outlined
							height="5"
						></v-text-field>
						<template v-if="!getUpdateShipmentFieldsLoading">
							<div style="margin-right: 2%; margin-left: 4%; margin-top: 1.5%; cursor: pointer;">
								<button @click="checkEditedField('customer_ref')"><img src="../../assets/icons/check-blue.svg" /></button>
							</div>
							<div style="cursor: pointer; margin-top: 1.5%;">
								<button @click="editCustomerRefFieldClick(false)"><img src="../../assets/icons/close-red.svg" /></button>
							</div>
						</template>
						<template v-else>
							<div style="margin-right: 2%; margin-left: 4%; margin-top: 0.5%; cursor: pointer;">
								<v-progress-circular :size="20" color="#0171a1" indeterminate></v-progress-circular>
							</div>
						</template>
					</div>
				</div>
				<div class="shipment-info-general-label">
					<div>Carrier</div>
					<div class="original-width">
						<span v-if="getShipmentDetails.is_tracking_shipment === 1">
								{{getMilestonesAttributes && getMilestonesAttributes.shipping_line_short_name !==
										"undefined" &&
									getMilestonesAttributes.shipping_line_short_name !== "" && getMilestonesAttributes.shipping_line_short_name !== null
										? getMilestonesAttributes.shipping_line_short_name
										: "N/A"
								}}
							</span>
						<span v-if="getShipmentDetails.is_tracking_shipment === 0">
							{{ getShipmentDetails.carrier !== "undefined" &&
								getShipmentDetails.carrier !== "" &&
								getShipmentDetails.carrier !== null
									? typeof getShipmentDetails.carrier === "String"
										? getShipmentDetails.carrier
										: getShipmentDetails.carrier.name
									: "N/A"
							}}
						</span>
					</div>
				</div>
				<div class="shipment-info-general-label">
					<div>Vessel & Voyage</div>
					<div class="original-width">
						<span>{{ getVessel() }}, {{ getVoyage() }}</span>
					</div>
				</div>
			</v-col>
		</v-row>

		<ConfirmDialog :dialogData.sync="MBLSubmittedDialog">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="../../assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Confirm new MBL</h2>
			</template>

			<template v-slot:dialog_content>
				<p>
					You are trying to add a different MBL number. This will update all the related shipment information. Do you confirm this change?
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" text @click="checkEditedField('mbl_num')">
					<span>Yes, Confirm</span>
				</v-btn>
				<v-btn class="btn-white" text @click="closeMBLDialog()">
					No, Edit MBL
				</v-btn>
			</template>
		</ConfirmDialog>

	</div>
</template>

<style lang="scss">
@import "../../assets/scss/pages_scss/shipment/shipmentInfoGeneral.scss";
</style>

<script>
import moment from "moment";
import ModeIcon from "../Icons/ModeIcon";
import _ from "lodash";
import { mapActions, mapGetters } from "vuex";
import globalMethods from '@/utils/globalMethods';
import ConfirmDialog from "@/components/Dialog/GlobalDialog/ConfirmDialog.vue";

export default {
	props: ["isMobile"],
	components: {
		ModeIcon,
		ConfirmDialog
	},
	computed: {
		...mapGetters([
			"getShipmentDetails",
			"getCarriers",
			"getMilestonesAttributes",
			"getMilestonesLoading",
			"getUpdateShipmentFieldsLoading",
		]),
		getCheckingMblNumber() {
			return this.$store.getters["booking/getCheckingMblNumber"];
		},
	},
	mounted() {
		this.fetchCarriers();
	},
	methods: {
		...globalMethods,
		...mapActions(["fetchCarriers", "updateShipmentFields"]),
		ucFirst(str) {
			let pieces = str.split(" ");
			for (let i = 0; i < pieces.length; i++) {
				let j = pieces[i].charAt(0).toUpperCase();
				pieces[i] = j + pieces[i].substr(1);
			}
			return pieces.join(" ");
		},
		dateFormat(dateStr) {
			return dateStr ? moment(dateStr).format("MMM DD, YYYY") : "";
		},
		modeName() {
			let schedules = this.getSchedules();

			let check_confirmed_schedule = _.find(
				schedules,
				(e) => e.is_confirmed === 1
			);
			if (typeof check_confirmed_schedule !== "undefined") {
				this.mode = check_confirmed_schedule.mode
					? this.ucFirst(check_confirmed_schedule.mode.toLowerCase())
					: "N/A";
			} else {
				let findScheduleIndex = -1;
				if (schedules.length > 0) {
					findScheduleIndex = _.findIndex(schedules);
					if (findScheduleIndex !== -1) {
						this.mode = schedules[findScheduleIndex].mode
							? this.ucFirst(schedules[findScheduleIndex].mode.toLowerCase())
							: "N/A";
					}
				}
			}
			return this.mode;
		},
		typeName() {
			let schedules = this.getSchedules();

			let check_confirmed_schedule = _.find(
				schedules,
				(e) => e.is_confirmed === 1
			);
			if (typeof check_confirmed_schedule !== "undefined") {
				this.type = check_confirmed_schedule.type
					? this.ucFirst(check_confirmed_schedule.type.toLowerCase())
					: "N/A";
			} else {
				let findScheduleIndex = -1;
				if (schedules.length > 0) {
					findScheduleIndex = _.findIndex(schedules);
					if (findScheduleIndex !== -1) {
						this.type = schedules[findScheduleIndex].type
							? this.ucFirst(schedules[findScheduleIndex].type.toLowerCase())
							: "N/A";
					}
				}
			}
			return this.type;
		},
		getVessel() {
			this.vessel =
				typeof this.getShipmentDetails.vessel !== "undefined" &&
				this.getShipmentDetails.vessel !== null &&
				this.getShipmentDetails.vessel !== ""
					? this.getShipmentDetails.vessel
					: "";

			if (this.getShipmentDetails.is_tracking_shipment === 1) {
				let t = setInterval(() => {
					if (!this.getMilestonesLoading) {
						if (this.getMilestonesAttributes !== null) {
							//check milestone attribute
							let vessel_name =
								typeof this.getMilestonesAttributes.pod_vessel_name !==
									"undefined" &&
								this.getMilestonesAttributes.pod_vessel_name !== null
									? this.getMilestonesAttributes.pod_vessel_name
									: "";
							this.vessel = this.vessel === "" ? vessel_name : "";
						}
						clearInterval(t);
					}
				}, 300);
			}
			return this.vessel ? this.vessel : "N/A";
		},
		getVoyage() {
			this.voyage =
				typeof this.getShipmentDetails.voyage_number !== "undefined" &&
				this.getShipmentDetails.voyage_number !== null &&
				this.getShipmentDetails.voyage_number !== ""
					? this.getShipmentDetails.voyage_number
					: "";
			return this.voyage ? this.voyage : "N/A";
		},

		getSchedules() {
			let schedules = this.getShipmentDetails.schedules_group_bookings;
			schedules = Array.isArray(schedules) ? schedules : JSON.parse(schedules);
			return schedules;
		},
		getEtdDate() {
			let etd = null;
			let schedules = this.getSchedules();
			let findScheduleIndex = -1;
			if (schedules.length > 0) {
				findScheduleIndex = _.findIndex(schedules);
				if (findScheduleIndex !== -1) {
					etd = this.ucFirst(schedules[findScheduleIndex].etd.toLowerCase());
				}
			}
			return etd ? this.dateFormat(etd) : "N/A";
		},
		getEtaDate() {
			let eta = null;
			let schedules = this.getSchedules();
			let findScheduleIndex = -1;
			if (schedules.length > 0) {
				findScheduleIndex = _.findIndex(schedules);
				if (findScheduleIndex !== -1) {
					eta = this.ucFirst(schedules[findScheduleIndex].eta.toLowerCase());
				}
			}
			return eta ? this.dateFormat(eta) : "N/A";
		},
		checkEditedField(editedField) {
			if(editedField === 'mbl_num') {
				this.MBLSubmittedDialog = false;
				let mbl_num = this.getShipmentDetails.mbl_num;
				if(mbl_num === null || mbl_num === '') {
					this.notificationErrorCustom('Please fill the MBL number');
				} else {
					this.isMblCalling = true;
					this.$store
						.dispatch("booking/checkMblNumber", mbl_num)
						.then((r) => {
							if (typeof r.data !== "undefined") {
								this.mblNumInfo = r.data;
								this.mblTracked = true;
								this.mblTrackFail = false;
								if (this.mblNumInfo.is_already_exists.is_already_exists == true) {
									this.isMblCalling = false;
									if (this.mblNumInfo.is_already_exists.type && this.mblNumInfo.is_already_exists.type == "LCL") {
										this.notificationErrorCustom('LCL Shipment is already exist');
										this.editMblField = false;
										this.getShipmentDetails.mbl_num = this.old_mbl_num;
									} else if(this.mblNumInfo.is_already_exists.type && this.mblNumInfo.is_already_exists.type == "FCL") {
										this.shipmentExistId = this.mblNumInfo.is_already_exists.shipment_id;
										this.notificationErrorCustom('FCL Shipment is already exist');
										this.editMblField = false;
										this.getShipmentDetails.mbl_num = this.old_mbl_num;
									} else {
										this.notificationErrorCustom('Shipment already exist');
										this.editMblField = false;
										this.getShipmentDetails.mbl_num = this.old_mbl_num;
									}
								} else {
									if(this.mblNumInfo.attributes === undefined) {
										this.isMblCalling = false;
										this.notificationErrorCustom('Please provide a valid MBL number');
										this.editMblField = false;
										this.getShipmentDetails.mbl_num = this.old_mbl_num;
									} else {
										this.trackInfoData = {
											eta: this.mblNumInfo.attributes.pod_eta_at == null
													? this.mblNumInfo.attributes.pod_ata_at
													: this.mblNumInfo.attributes.pod_eta_at,
											etd: this.mblNumInfo.attributes.pol_etd_at == null
													? this.mblNumInfo.attributes.pol_atd_at
													: this.mblNumInfo.attributes.pol_etd_at
												,
											location_from: this.mblNumInfo.attributes.port_of_lading_name,
											location_to: this.mblNumInfo.attributes.port_of_discharge_name,
											carrier: this.mblNumInfo.attributes.shipping_line_short_name,
											vessel: this.mblNumInfo.attributes.pod_vessel_name,
											voyage_number: this.mblNumInfo.attributes.pod_voyage_number,
											terminal_name: this.mblNumInfo.terminal_name,
										};
										this.containerInfo = this.mblNumInfo.containers;
										this.updateFields('mbl_num');
									}
								}
							} else {
								this.mblTrackFail = true;
								this.notificationErrorCustom('Please provide a valid MBL number');
								this.editMblField = false;
								this.isMblCalling = false;
								this.getShipmentDetails.mbl_num = this.old_mbl_num;
							}
							this.checkTrackingNumber = false;
						})
						.catch((e) => {
							console.log("e", e);
							this.mblTrackFail = true;
							this.notificationErrorCustom('Please provide a valid MBL number');
							this.editMblField = false;
							this.getShipmentDetails.mbl_num = this.old_mbl_num;
							this.checkTrackingNumber = false;
							this.isMblCalling = false;
						});
				}
			} else if(editedField === 'booking_num') {
				if(this.getShipmentDetails.booking_number === '' || this.getShipmentDetails.booking_number === null) {
					this.notificationErrorCustom('Please fill the Booking number');
				} else {
					this.updateFields('booking_num');
				}
			} else {
				this.updateFields('customer_ref');
			}
		},
		updateFields(field) {
			let payloadObject = {'field': field, 'shipmentId': this.getShipmentDetails.id, 'mbl_num': this.getShipmentDetails.mbl_num, 'booking_num': this.getShipmentDetails.booking_number, 'customer_ref': this.getShipmentDetails.customer_reference_number, 'date_id': new Date().getTime()}
			this.updateShipmentFields(payloadObject)
				.then((response) => {
					this.editMblField = false;
					this.editBookingField = false;
					this.editCustomerRefField = false;
					this.notificationMessageCustomSuccess(response.data.message);
					this.isMblCalling = false;
					setTimeout(() => {
						location.reload();
					}, 2000);
				})
				.catch((e) => {
					console.log(e);
					// this.editMblField = false;
					// this.editBookingField = false;
					// this.editCustomerRefField = false;
					this.notificationErrorCustom('SOMETHING WENT WRONG!');
					this.isMblCalling = false;
				})
		},
		editMblFieldClick(val) {
			if(val === true) {
				this.old_mbl_num = this.getShipmentDetails.mbl_num;
				if(this.old_mbl_num === '' || this.old_mbl_num === null) {
					this.editMblField = true;
				}
			} else {
				this.getShipmentDetails.mbl_num = this.old_mbl_num;
				this.editMblField = false;
			}
		},
		editBookingFieldClick(val) {
			if(val === true) {
				this.old_booking_num = this.getShipmentDetails.booking_number;
				if(this.old_booking_num === '' || this.old_booking_num === null) {
					this.editBookingField = true;
				}
			} else {
				this.getShipmentDetails.booking_number = this.old_booking_num;
				this.editBookingField = false;
			}
		},
		editCustomerRefFieldClick(val) {
			if(val === true) {
				this.old_customer_ref = this.getShipmentDetails.customer_reference_number;
				this.editCustomerRefField = true;
			} else {
				this.getShipmentDetails.customer_reference_number = this.old_customer_ref;
				this.editCustomerRefField = false;
			}
		},
		openMBLWarningDialog(){
			this.MBLSubmittedDialog = true;
		},
		closeMBLDialog() {
			this.MBLSubmittedDialog = false;
		},
	},
	data: () => ({
		vessel: "",
		voyage: "",
		carrier: "",
		mode: "",
		type: "",
		editMblField: false,
		editBookingField: false,
		mbl_number: '',
		booking_num: '',
		is_mbl_error: '',
		trackInfoData: '',
		containerInfo: '',
		checkTrackingNumber: false,
		isMblCalling: false,
		old_mbl_num: '',
		old_booking_num: '',
		old_customer_ref: '',
		editCustomerRefField: false,
		MBLSubmittedDialog: false,
	}),
};
</script>
