<!-- @format -->

<template>
	<div
		:class="`${isMobile ? 'shipment-info-mobile' : ''} shipment-info top-info`"
	>
		<v-container class="cont-fluid-wrapper">
			<shipment-general-information
				:details="getDetails"
				:isMobile="isMobile"
			/>
			<div
				class="row shipment-info-wrapper"
				v-if="getDetails.is_tracking_shipment == 0 && 1 == 2"
			>
				<div class="col-xs-12 col-sm-12 col-md-6">
					<div
						class="row"
						v-if="
							getDetails.customer !== 'undefined' &&
								getDetails.customer !== null &&
								getDetails.customer.company_name !== 'undefined'
						"
					>
						<h3 class="building-name">
							{{ getDetails.customer.company_name }}
						</h3>
					</div>
					<div
						class="row"
						v-if="
							getDetails.location_from_name !== null || getDetails.etd !== null
						"
					>
						<span class="heading shipment-info-content">{{
							`FROM & ETD`
						}}</span>

						<p class="shipment-info-content">
							<span
								class="heading-place"
								v-if="
									getDetails.location_from_name !== 'undefined' &&
										getDetails.location_from_name !== '' &&
										getDetails.location_from_name !== null
								"
							>
								{{
									getDetails.shipment_status == "Pending Approval"
										? "N/A"
										: getDetails.location_from_name
								}},
							</span>

							<span class="heading-date" v-if="shipment_etd !== null">
								{{
									getDetails.shipment_status == "Pending Approval"
										? "N/A"
										: shipment_etd
								}}
							</span>
						</p>
					</div>
					<div
						class="row"
						v-if="
							getDetails.location_to_name !== null || getDetails.eta !== null
						"
					>
						<span class="heading shipment-info-content">{{ `TO & ETA` }}</span>

						<p class="shipment-info-content">
							<span
								class="heading-place"
								v-if="
									getDetails.location_to_name !== 'undefined' &&
										getDetails.location_to_name !== '' &&
										getDetails.location_to_name !== null
								"
							>
								{{
									getDetails.shipment_status == "Pending Approval"
										? "N/A"
										: getDetails.location_to_name
								}},
							</span>

							<span class="heading-date" v-if="shipment_eta !== null">
								{{
									getDetails.shipment_status == "Pending Approval"
										? "N/A"
										: shipment_eta
								}}
							</span>
						</p>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-6">
					<div
						class="row"
						v-if="
							getDetails.carrier !== 'undefined' &&
								getDetails.carrier !== null &&
								getDetails.carrier !== '' &&
								getDetails.shipment_status !== 'Pending Approval'
						"
					>
						<span class="heading shipment-info-content">Carrier</span>
						<p class="heading-place shipment-info-content">
							{{
								typeof getDetails.carrier === "String"
									? getDetails.carrier
									: getDetails.carrier.name
							}}
						</p>
					</div>
					<div
						class="row"
						v-if="
							getDetails.vessel !== 'undefined' && getDetails.vessel !== null
						"
					>
						<span class="heading shipment-info-content">Vessel</span>

						<p class="heading-place shipment-info-content">
							{{ getDetails.vessel }}
						</p>
					</div>

					<div
						class="row"
						v-if="
							getDetails.mbl_num !== 'undefined' && getDetails.mbl_num !== null
						"
					>
						<span class="heading shipment-info-content">MBL#</span>

						<p class="heading-place shipment-info-content">
							{{ getDetails.mbl_num }}
						</p>
					</div>

					<!-- <div class="row" v-if="getDetails.type !== 'undefined' && getDetails.type !== null && getDetails.type !== 'null'">
                        <span class="heading shipment-info-content">Shipment Type</span>

                        <p class="heading-place shipment-info-content">
                            {{ getDetails.type }}
                        </p>
                    </div> -->
				</div>
			</div>
			<div
				class="row shipment-info-wrapper"
				v-if="getDetails.is_tracking_shipment == 1"
				:class="
					getFilteredShipmentDetails.external_shipment_tracking === 1
						? ''
						: 'k-pt-0'
				"
			>
				<div class="preloader" v-if="getMilestonesLoading">
					<v-progress-circular :size="40" color="#0171a1" indeterminate>
					</v-progress-circular>

					<p>Fetching tracking details...</p>
				</div>

				<div
					v-if="
						!getMilestonesLoading && getMilestonesAttributes !== null && 1 == 2
					"
					class="col-xs-12 col-sm-12 col-md-6"
				>
					<div
						class="row"
						v-if="
							getDetails.customer !== 'undefined' &&
								getDetails.customer !== null &&
								getDetails.customer.company_name !== 'undefined'
						"
					>
						<h3 class="building-name">
							{{ getDetails.customer.company_name }}
						</h3>
					</div>

					<div
						class="row"
						v-if="getMilestonesAttributes.port_of_lading_name !== null"
					>
						<span class="heading shipment-info-content">{{
							`FROM & ETD`
						}}</span>

						<p class="shipment-info-content">
							<span
								class="heading-place"
								v-if="
									getMilestonesAttributes.port_of_lading_name !== 'undefined' &&
										getMilestonesAttributes.port_of_lading_name !== '' &&
										getMilestonesAttributes.port_of_lading_name !== null
								"
							>
								{{ getMilestonesAttributes.port_of_lading_name }},
							</span>

							<span
								class="heading-date"
								v-if="getMilestonesAttributes.pol_etd_at !== null"
							>
								{{ trackingDateConvert(getMilestonesAttributes.pol_etd_at) }}
							</span>

							<span
								class="heading-date"
								v-if="getMilestonesAttributes.pol_etd_at === null"
							>
								<!-- if pol_etd_at is null, show pol_atd_at -->
								{{ trackingDateConvert(getMilestonesAttributes.pol_atd_at) }}
							</span>
						</p>
					</div>

					<div
						class="row"
						v-if="getMilestonesAttributes.port_of_discharge_name !== null"
					>
						<span class="heading shipment-info-content">{{ `TO & ETA` }}</span>

						<p class="shipment-info-content">
							<span
								class="heading-place"
								v-if="
									getMilestonesAttributes.port_of_discharge_name !==
										'undefined' &&
										getMilestonesAttributes.port_of_discharge_name !== '' &&
										getMilestonesAttributes.port_of_discharge_name !== null
								"
							>
								{{ getMilestonesAttributes.port_of_discharge_name }},
							</span>

							<span
								class="heading-date"
								v-if="getMilestonesAttributes.pod_eta_at !== null"
							>
								{{ trackingDateConvert(getMilestonesAttributes.pod_eta_at) }}
							</span>

							<span
								class="heading-date"
								v-if="
									getMilestonesAttributes.pod_eta_at === null &&
										getMilestonesAttributes.pod_ata_at !== null
								"
							>
								<!-- if pod_eta_at is null, show pod_ata_at -->
								{{ trackingDateConvert(getMilestonesAttributes.pod_ata_at) }}
							</span>
						</p>
					</div>
				</div>

				<div
					v-if="
						!getMilestonesLoading && getMilestonesAttributes !== null && 1 == 2
					"
					class="col-xs-12 col-sm-12 col-md-6"
				>
					<div
						class="row"
						v-if="
							getMilestonesAttributes.shipping_line_short_name !==
								'undefined' &&
								getMilestonesAttributes.shipping_line_short_name !== null &&
								getMilestonesAttributes.shipping_line_short_name !== ''
						"
					>
						<span class="heading shipment-info-content">Carrier</span>

						<p class="heading-place shipment-info-content">
							{{ getMilestonesAttributes.shipping_line_short_name }}
						</p>
					</div>

					<div
						class="row"
						v-if="
							getMilestonesAttributes.pod_vessel_name !== 'undefined' &&
								getMilestonesAttributes.pod_vessel_name !== null
						"
					>
						<span class="heading shipment-info-content">Vessel</span>

						<p class="heading-place shipment-info-content">
							{{ getMilestonesAttributes.pod_vessel_name }}
						</p>
					</div>

					<div
						class="row"
						v-if="
							getDetails.mbl_num !== 'undefined' && getDetails.mbl_num !== null
						"
					>
						<span class="heading shipment-info-content">MBL#</span>

						<p class="heading-place shipment-info-content">
							{{ getDetails.mbl_num }}
						</p>
					</div>
				</div>
			</div>

			<div
				class="row shipment-info-wrapper k-pt-0"
				v-if="
					getDetails.is_tracking_shipment == 1 &&
						!getMilestonesLoading &&
						getMilestonesAttributes == null &&
						1 == 2
				"
			>
				<div class="col-xs-12 col-sm-12 col-md-6">
					<div
						class="row"
						v-if="
							getDetails.customer !== 'undefined' &&
								getDetails.customer !== null &&
								getDetails.customer.company_name !== 'undefined'
						"
					>
						<h3 class="building-name">
							{{ getDetails.customer.company_name }}
						</h3>
					</div>
					<div
						class="row"
						v-if="
							getDetails.location_from_name !== null || getDetails.etd !== null
						"
					>
						<span class="heading shipment-info-content">{{
							`FROM & ETD`
						}}</span>

						<p class="shipment-info-content">
							<span
								class="heading-place"
								v-if="
									getDetails.location_from_name !== 'undefined' &&
										getDetails.location_from_name !== '' &&
										getDetails.location_from_name !== null
								"
							>
								{{
									typeof getDetails.location_from_name !== "undefined"
										? getDetails.location_from_name
										: "N/A"
								}},
							</span>

							<span class="heading-date" v-if="shipment_etd !== null">
								{{
									shipment_etd === "" || shipment_etd === null
										? "N/A"
										: shipment_etd
								}}
							</span>
						</p>
					</div>

					<div
						class="row"
						v-if="
							getDetails.location_to_name !== null || getDetails.eta !== null
						"
					>
						<span class="heading shipment-info-content">{{ `TO & ETA` }}</span>

						<p class="shipment-info-content">
							<span
								class="heading-place"
								v-if="
									getDetails.location_to_name !== 'undefined' &&
										getDetails.location_to_name !== '' &&
										getDetails.location_to_name !== null
								"
							>
								{{
									typeof getDetails.location_to_name !== "undefined"
										? getDetails.location_to_name
										: "N/A"
								}},
							</span>

							<span class="heading-date" v-if="shipment_eta !== null">
								{{
									shipment_eta === "" || shipment_eta === null
										? "N/A"
										: shipment_eta
								}}
							</span>
						</p>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-6">
					<div
						class="row"
						v-if="
							getDetails.carrier !== 'undefined' &&
								getDetails.carrier !== null &&
								getDetails.carrier !== ''
						"
					>
						<span class="heading shipment-info-content">Carrier</span>

						<p class="heading-place shipment-info-content">
							{{
								typeof getDetails.carrier === "String"
									? getDetails.carrier
									: getDetails.carrier.name
							}}
						</p>
					</div>

					<div
						class="row"
						v-if="
							getDetails.vessel !== 'undefined' && getDetails.vessel !== null
						"
					>
						<span class="heading shipment-info-content">Vessel</span>

						<p class="heading-place shipment-info-content">
							{{ getDetails.vessel }}
						</p>
					</div>

					<div
						class="row"
						v-if="
							getDetails.mbl_num !== 'undefined' && getDetails.mbl_num !== null
						"
					>
						<span class="heading shipment-info-content">MBL#</span>

						<p class="heading-place shipment-info-content">
							{{ getDetails.mbl_num }}
						</p>
					</div>

					<!-- <div class="row" v-if="getDetails.type !== 'undefined' && getDetails.type !== null && getDetails.type !== 'null'">
                        <span class="heading shipment-info-content">Shipment Type</span>

                        <p class="heading-place shipment-info-content">
                            {{ getDetails.type }}
                        </p>
                    </div> -->
				</div>
			</div>
		</v-container>
		{{ /* missing suppliers */ }}
		<v-container
			v-if="
				getDetails.suppliers == 'undefined' ||
					getDetails.shipment_suppliers == null ||
					(getDetails.shipment_suppliers.length === 0 &&
						!getMilestonesLoading &&
						getMilestonesAttributes == null)
			"
			fluid
			class="k-p-0 k-border-t-1 k-border-l-0 k-border-r-0 k-border-b-0 k-border-white-blue no-resource-wrapper"
		>
			<div class="d-flex k-p-16 missing-title k-pl-20 k-pr-20">
				Suppliers
			</div>
			<div class="no-details-row k-pb-24 k-pt-12">
				<generic-icon iconName="supplier" color="#0171A1" />
				<div class="no-details-row-header">
					{{ "No Supplier Info" }}
				</div>
				<div class="no-details-row-body">
					<!-- {{ "When you add POs to the shipment, supplier info will show." }} -->
					{{
						"We couldn’t track any supplier. When you add POs to the shipment, supplier list will be automatically populated."
					}}
				</div>
			</div>
		</v-container>
		{{ /* missing containers */ }}
		<v-container
			v-if="
				getDetails.containers == 'undefined' ||
					getDetails.containers == null ||
					(getDetails.containers.length === 0 &&
						getDetails.is_tracking_shipment == 1 &&
						!getMilestonesLoading &&
						getMilestonesAttributes == null)
			"
			fluid
			class="k-p-0 k-border-t-1 k-border-l-0 k-border-r-0 k-border-b-0 k-border-white-blue no-resource-wrapper"
		>
			<div
				class="d-flex justify-space-between k-p-16 k-pr-20 missing-title k-pl-20"
			>
				<div>Containers</div>
				<div class="d-flex justify-end">
					<a class="add-container-info-btn">
						{{ "Add Container Info" }}
					</a>
				</div>
			</div>
			<div class="no-details-row k-pb-24 k-pt-24">
				<generic-icon iconName="container" color="#0171A1" />
				<div class="no-details-row-header">
					{{ "No Container info" }}
				</div>
				<div class="no-details-row-body">
					{{
						"We couldn’t track any container information. Please edit the shipment and add information related to container."
					}}
				</div>
			</div>
		</v-container>
		<!-- SUPPLIERS -->
		<ShipmentSupplier
			:getDetails="getDetails"
			:isMobile="isMobile"
			:shipment_status="shipment_status"
		/>

		<!-- CONTAINERS -->
		<MileStoneContainers :getDetails="getDetails" :isMobile="isMobile" />

		<ShipmentSchedulingOptions
			:getDetails="getDetails"
			:isMobile="isMobile"
			v-if="displaySchedulingSection"
		/>

		<v-container
			v-if="
				getDetails.is_tracking_shipment == 1 &&
					!getMilestonesLoading &&
					getMilestonesAttributes == null &&
					(getDetails.containers == 'undefined' ||
						getDetails.containers == null ||
						(getDetails.containers.length === 0 && 1 == 2))
			"
			fluid
			class="k-p-0 k-border-t-1 k-border-l-0 k-border-r-0 k-border-b-0 k-border-white-blue"
		>
			<div class="no-details-row k-pb-20">
				<custom-icon
					width="36"
					height="36"
					iconName="warning"
					color="#D68A1A"
				/>
				<div class="no-details-row-header">
					Missing Information
				</div>
				<div class="no-details-row-body">
					We can’t track the information for some reason. Can you make sure the
					MBL# is correct?
				</div>
			</div>
		</v-container>
	</div>
</template>

<script>
import { mapGetters } from "vuex";
import MileStoneContainers from "@/components/MileStoneContainers.vue";
import ShipmentSupplier from "@/components/ShipmentSupplier.vue";
import ShipmentSchedulingOptions from "@/components/ShipmentSchedulingOptions";
import moment from "moment";
import CustomIcon from "@/components/Icons/CustomIcon";
import GenericIcon from "@/components/Icons/GenericIcon";
import ShipmentGeneralInformation from "@/components/ShipmentComponents/ShipmentGeneralInformation";
import "../assets/scss/utilities.scss";
// import { mapActions } from "vuex";

export default {
	props: [
		"getDetails",
		"isMobile",
		"shipment_status",
		"getFilteredShipmentDetails",
	],
	components: {
		MileStoneContainers,
		ShipmentSupplier,
		ShipmentSchedulingOptions,
		CustomIcon,
		ShipmentGeneralInformation,
		GenericIcon,
	},
	methods: {
		trackingDateConvert(date) {
			return date !== null ? moment.utc(date).format("MMM DD, YYYY") : null;
		},
	},
	data: () => ({
		shipment_eta: null,
		shipment_etd: null,
		incoterms: [],
		mbl_num: null,
		displaySchedulingSection: true,
	}),
	async mounted() {
		this.shipment_id = this.$route.params.id;
		let userDetails = JSON.parse(localStorage.getItem("shifl.user_details"));
		this.displaySchedulingSection =
			this.getDetails.customer_id === userDetails.default_customer.id;

		if (this.getDetails.is_tracking_shipment === 0) {
			this.shipment_etd =
				this.getDetails.etd !== null
					? moment.utc(this.getDetails.etd).format("MMM DD, YYYY")
					: null;
			this.shipment_eta =
				this.getDetails.eta !== null
					? moment.utc(this.getDetails.eta).format("MMM DD, YYYY")
					: null;
		}
		//set current page
		this.$store.dispatch("page/setPage", "shipments");
	},
	updated() {},
	created() {},
	computed: {
		...mapGetters([
			"getMilestonesAttributes",
			"getMilestonesLoading",
			"getShipmentDetailsLoading",
			"getScheduleOptions",
		]),
	},
};
</script>

<style lang="scss">
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap");
/* @import '../assets/css/shipments_styles/shipmentInfo.css'; */
@import "../assets/scss/pages_scss/shipment/shipmentInfo.scss";
</style>
