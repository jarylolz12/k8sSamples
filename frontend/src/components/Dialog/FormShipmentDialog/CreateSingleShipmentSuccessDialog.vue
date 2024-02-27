<template>
	<div style="position: relative;">

		<v-dialog
			v-model="show"
			:content-class="`${className} create-shipment-ultimate-wrapper`"
			width="900px"
			persistent
		>
			<v-card id="single-shipment-dialog">
				<div>
					<img src="../../../assets/icons/CircleCheckOutline.svg" width="40px" height="40px"/>
					<p class="success-header-text">Shipment Added for Tracking</p>
					<p>You have successfully added a shipment for tracking. Below are the information related to that shipment:</p>
					<template v-if="Object.keys(shipmentDetails).length > 0">
						<v-row
							no-gutters
							style="border: 1px solid #D8E7F0; border-radius: 5px;"
							:class="createdShipmentType == 'withInValidMBL' || createdShipmentType == 'withoutMBL' ? 'mb-4' : ''"
						>
							<v-col class="pa-3" md="6" v-if="createdShipmentType == 'withInValidMBL'">
								<div class="d-flex invalid-mbl-container">
									<span style="font-weight: 500; size: 10px; color: #6D858F;">MBL number </span><span class="ml-4 d-flex" style="font-weight: 500; size: 14px; color: #F44336;">
										Invalid MBL Number Provided
										<GenericIcon
											color="#F44336"
											iconName="info-icon"
											class="ml-4"
										></GenericIcon>
									</span>
								</div>
							</v-col>
							<v-col class="pa-3" md="6" v-if="createdShipmentType == 'withValidMBL'">
								<span style="font-weight: 500; size: 14px; color: #6D858F;">MBL Number </span><span class="ml-4" style="font-weight: 500; size: 14px; color: #4A4A4A;">{{ shipmentDetails.mbl_num ? shipmentDetails.mbl_num : 'N/A' }}</span>
							</v-col>
							<v-col class="pa-3" md="6">
								<span style="font-weight: 500; size: 14px; color: #6D858F;">Shipping line Booking number </span><span class="ml-4" style="font-weight: 500; size: 14px; color: #4A4A4A;">{{ shipmentDetails.booking_number ? shipmentDetails.booking_number : 'N/A' }}</span>
							</v-col>
							<v-col class="pa-3" md="6" v-if="createdShipmentType == 'withoutMBL'">
								<span style="font-weight: 500; size: 14px; color: #6D858F;">Customer Ref Number </span><span class="ml-4" style="font-weight: 500; size: 14px; color: #4A4A4A;">{{ shipmentDetails.customer_reference_number ? shipmentDetails.customer_reference_number : 'N/A' }}</span>
							</v-col>
						</v-row>
						<v-row
							v-if="createdShipmentType == 'withInValidMBL'"
							no-gutters
							style="border: 1px solid #D8E7F0; border-radius: 5px;"
							class="mt-4 mb-4"
						>
							<v-col class="align-self-center pt-3">
								<p style="text-align: center;">As the MBL Number you provided is invalid, You couldn’t track any shipment information.</p>
							</v-col>
						</v-row>
						<template v-if="createdShipmentType == 'withValidMBL'">
							<p style="color: #4A4A4A; font-weight: 600; size: 14px; line-height: 20px; margin-top: 3%;">Information tracked based on given MBL number</p>
							<v-row
								no-gutters
								style="border: 1px solid #D8E7F0; border-radius: 5px;"
								class="mb-6"
							>
								<v-col class="pa-3" md="6">
									<div class="d-flex">
										<v-col class="pa-0" md="4">
											<span style="font-weight: 500; size: 14px; color: #6D858F;">From & ETD </span>
										</v-col>
										<v-col class="pa-0" md="8">
											<span class="ml-4" style="font-weight: 500; size: 14px; color: #4A4A4A;">{{ shipmentDetails.location_from_name ? shipmentDetails.location_from_name : 'N/A' }}, <span style="color: #0171A1;">{{ getEtdDate(shipmentDetails.etd) }}</span></span>
										</v-col>
									</div>
								</v-col>
								<v-col class="pa-3" md="6">
									<div class="d-flex">
										<v-col class="pa-0" md="5">
											<span style="font-weight: 500; size: 14px; color: #6D858F;">Carrier </span>
										</v-col>
										<v-col class="pa-0" md="7">
											<span class="ml-4" style="font-weight: 500; size: 14px; color: #4A4A4A;">{{ getMBLNumberData.attributes.shipping_line_short_name ? getMBLNumberData.attributes.shipping_line_short_name : 'N/A' }}</span>
										</v-col>
									</div>
								</v-col>
								<v-col class="pa-3" md="6">
									<div class="d-flex">
										<v-col class="pa-0" md="4">
											<span style="font-weight: 500; size: 14px; color: #6D858F;">To & ETA </span>
										</v-col>
										<v-col class="pa-0" md="8">
											<span class="ml-4" style="font-weight: 500; size: 14px; color: #4A4A4A;">{{ shipmentDetails.location_to_name ? shipmentDetails.location_to_name : 'N/A' }}, <span style="color: #0171A1;">{{ getEtaDate(shipmentDetails.eta) }}</span></span>
										</v-col>
									</div>
								</v-col>
								<v-col class="pa-3" md="6">
									<div class="d-flex">
										<v-col class="pa-0" md="5">
											<span style="font-weight: 500; size: 14px; color: #6D858F;">Vessel & Voyage </span>
										</v-col>
										<v-col class="pa-0" md="7">
											<span class="ml-4" style="font-weight: 500; size: 14px; color: #4A4A4A;">{{ getMBLNumberData.attributes.pod_vessel_name ? getMBLNumberData.attributes.pod_vessel_name : 'N/A' }}, {{ getMBLNumberData.attributes.pod_voyage_number ? getMBLNumberData.attributes.pod_voyage_number : 'N/A' }}</span>
										</v-col>
									</div>
								</v-col>
								<v-col class="pa-3" md="6">
									<div class="d-flex">
										<v-col class="pa-0" md="4">
											<span style="font-weight: 500; size: 14px; color: #6D858F;">Mode & Type </span>
										</v-col>
										<v-col class="pa-0" md="8">
											<span class="ml-4" style="font-weight: 500; size: 14px; color: #4A4A4A;">
												<img src="../../../assets/icons/ocean-mode.svg" width="20px" height="20px" v-if="shipmentDetails.mode === 'Ocean'"/> 
												<img src="../../../assets/icons/airplane.svg" width="20px" height="20px" v-if="shipmentDetails.mode === 'Air'"/>
												{{ modeName() }}, {{ typeName() }}</span>
										</v-col>
									</div>
								</v-col>
								<v-col class="pa-3" md="6">
									<div class="d-flex">
										<v-col class="pa-0" md="5">
											<span style="font-weight: 500; size: 14px; color: #6D858F;">Terminal </span>
										</v-col>
										<v-col class="pa-0" md="7">
											<span class="ml-4" style="font-weight: 500; size: 14px; color: #4A4A4A;">
												<!-- {{ shipmentDetails.terminal_real_name ? shipmentDetails.terminal_real_name: "N/A" }} -->
												{{ getMBLNumberData.terminal_name ? getMBLNumberData.terminal_name : 'N/A' }}
											</span>
										</v-col>
									</div>
								</v-col>
								<v-col class="pa-3" md="6">
									<div class="d-flex">
										<v-col class="pa-0" md="4">
											<span style="font-weight: 500; size: 14px; color: #6D858F;">Rail </span>
										</v-col>
										<v-col class="pa-0" md="8">
											<!-- <img class="ml-3" src="../../../assets/icons/rail-arrived.svg" width="20px" height="20px"/> -->
											<span class="ml-4" style="font-weight: 500; size: 14px; color: #4A4A4A;">N/A</span>
										</v-col>
									</div>
								</v-col>
								<v-col class="pa-3" md="6">
									<div class="d-flex">
										<v-col class="pa-0" md="5">
											<span style="font-weight: 500; size: 14px; color: #6D858F;">Incoterms </span>
										</v-col>
										<v-col class="pa-0" md="7">
											<span class="ml-4" style="font-weight: 500; size: 14px; color: #4A4A4A;">N/A</span>
										</v-col>
									</div>
								</v-col>
							</v-row>
						</template>
					</template>
					<template v-else>
						<div class="preloader d-flex w-full justify-center align-center pt-4 pb-4">
							<v-progress-circular :size="40" color="#0171a1" indeterminate>
							</v-progress-circular>
						</div>
					</template>
				</div>
				
				<div class="footer-btn">
					<div class="d-flex footer mb-4">
						<v-btn class="save-btn btn-blue" text>
							<span><a :href="'/shipment/'+createSingleShipmentId" style="color: #FFFFFF; text-decoration: none;">Close & View Shipment</a></span>
						</v-btn>
						<v-btn class="save-btn btn-white" text style="margin-left: 1%;" @click.stop="addMoreInfo()" :disabled="Object.keys(shipmentDetails).length <= 0">
							<span>Add More Info</span>
						</v-btn>
					</div>
					<div class="d-flex">
						<GenericIcon
							color="#819FB2"
							iconName="info-icon"
						></GenericIcon>
						You can add/update information later from shipment details
					</div>
				</div>
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
import _ from "lodash";
import moment from "moment";
import { mapGetters } from "vuex";

export default {
	name: "CreateSingleShipmentSuccessDialog",
	props: [
		"show",
		"createSingleShipmentId",
		"className",
		"createdShipmentDetails",
		"createdShipmentType",
	],
	data: () => ({
		isLoading: false,
		shipmentDetails: {},
	}),
	components: {
		GenericIcon,
	},
	computed: {
		...mapGetters({getMBLNumberData:"booking/getMBLNumberData"}),
	},
	methods: {
		clickOutside() {
			this.$emit("close");
		},
		async fetchCreatedShipmentInfo() {
            return this.$store.dispatch("booking/fetchShipmentDetails", this.createSingleShipmentId);
        },
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
		getSchedules() {
			let schedules = this.shipmentDetails.schedules_group_bookings;
			schedules = Array.isArray(schedules) ? schedules : JSON.parse(schedules);
			return schedules;
		},
		getEtdDate(value) {
			let etd = value;
			return etd ? this.dateFormat(etd) : "N/A";
		},
		getEtaDate(value) {
			let eta = value;
			return eta ? this.dateFormat(eta) : "N/A";
		},
		addMoreInfo() {
			let newshipmentDetails = {};
			if(this.getMBLNumberData !== null && this.createdShipmentType === 'withValidMBL') {
				newshipmentDetails = Object.assign(this.shipmentDetails, { 
					'vessel': this.getMBLNumberData ? this.getMBLNumberData.attributes.pod_vessel_name : '',
					'voyage_number': this.getMBLNumberData ? this.getMBLNumberData.attributes.pod_voyage_number : '',
					'carrier': this.getMBLNumberData ? this.getMBLNumberData.attributes.shipping_line_short_name : ''
				});
			} else {
				newshipmentDetails = this.shipmentDetails;
			}
			this.$emit('shipmentDetails', newshipmentDetails);
			this.$emit('showAddMoreShipmentInfo')
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
	},
	watch: {
		createSingleShipmentId: {
			immediate: true,
			deep: true,
			async handler(newVal, oldVal) {
				if(oldVal !== newVal && newVal != '') {
					this.isLoading = true;
					this.shipmentDetails = await this.fetchCreatedShipmentInfo();
				}
			}
		}
    },
};
</script>

