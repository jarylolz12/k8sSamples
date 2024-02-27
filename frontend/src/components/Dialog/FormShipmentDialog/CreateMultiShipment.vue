<!-- @format -->
<template>
	<div>
		<div class="multi-shipment-table-wrapper">
			<v-data-table
				:headers="headers"
				:items="rows"
				mobile-breakpoint="769"
				:page="1"
				item-key="name"
				class="multi-shipment-table"
				hide-default-footer
				ref="my-table"
			>

				<template v-slot:[`item.mbl_num`]="{ item, index }">
					<v-tooltip bottom color="#4A4A4A" max-width="250px" :disabled="(item.mbl_num === '' && item.booking_number === '') || item.is_mbl_error !== '' ? false : true">
						<template v-slot:activator="{ on, attrs }">
							<div 
								class="d-flex multi-shipment-table-row tooltip-has-error" 
								v-on="on"									
								v-bind="attrs"
								:class="item.is_mbl_error ? 'field-error' : ''"
							>
								<v-text-field
									v-model="item.mbl_num"
									ref="mbl_number"
									placeholder="Enter MBL"
									dense
									outlined
									height="25px"
									type="text"
									hide-details="auto"
									@focusout.stop="checkMBLNumber(item, index)"
								></v-text-field>
							</div>
						</template>
						<span><center>{{ MblNumberRequiredMessage(item) }}</center></span>
					</v-tooltip>
				</template>

				<template v-slot:[`item.booking_number`]="{ item }">
					<v-tooltip bottom color="#4A4A4A" max-width="250px" :disabled="item.mbl_num === '' && item.booking_number === '' ? false : true">
						<template v-slot:activator="{ on, attrs }">
							<div 
								class="d-flex multi-shipment-table-row tooltip-has-error" 
								v-on="on"									
								v-bind="attrs"
								:class="item.is_mbl_error ? 'field-error' : ''"
							>
								<v-text-field
									v-model="item.booking_number"
									ref="booking_number"
									placeholder="Enter Booking Number"
									dense
									outlined
									height="25px"
									type="text"
									hide-details="auto"
									@input="checkErrorInData()"
								></v-text-field>
							</div>
						</template>
						<span><center>{{ MblNumberRequiredMessage(item) }}</center></span>
					</v-tooltip>
				</template>

				<template v-slot:[`item.customer_reference`]="{ item }">
					<div class="k-flex k-justify-between">
						<v-text-field
							v-model="item.customer_reference"
							ref="customer_reference"
							placeholder="Enter Customer Reference"
							dense
							outlined
							height="25px"
							type="text"
							hide-details="auto"
							:class="item.mbl_num === '' && item.booking_number === '' || item.is_mbl_error !== '' ? 'readonly-text' : ''"
							:readonly="item.mbl_num === '' && item.booking_number === '' || item.is_mbl_error !== '' ? true : false"
						></v-text-field>
					</div>
				</template>

				<template v-slot:[`item.total_carton`]="{ item }">
					<div class="k-flex k-justify-between">
						<v-text-field
							v-model="item.total_carton"
							ref="total_carton"
							placeholder="0"
							dense
							outlined
							height="25px"
							type="number"
							hide-details="auto"
							value="0"
							:class="item.mbl_num === '' && item.booking_number === '' || item.is_mbl_error !== '' ? 'readonly-text' : ''"
							:readonly="item.mbl_num === '' && item.booking_number === '' || item.is_mbl_error !== '' ? true : false"
						></v-text-field>
					</div>
				</template>

				<template v-slot:[`item.total_volume`]="{ item }">
					<div class="k-flex k-justify-between">
						<v-text-field
							v-model="item.total_volume"
							ref="total_volume"
							placeholder="0.00 CBM"
							dense
							outlined
							height="25px"
							type="number"
							hide-details="auto"
							:class="item.mbl_num === '' && item.booking_number === '' || item.is_mbl_error !== '' ? 'readonly-text' : ''"
							:readonly="item.mbl_num === '' && item.booking_number === '' || item.is_mbl_error !== '' ? true : false"
						></v-text-field>
					</div>
				</template>

				<template v-slot:[`item.total_weight`]="{ item }">
					<div class="k-flex k-justify-between">
						<v-text-field
							v-model="item.total_weight"
							ref="total_weight"
							placeholder="0.00 KG"
							dense
							outlined
							height="25px"
							type="number"
							hide-details="auto"
							value="0"
							:class="item.mbl_num === '' && item.booking_number === '' || item.is_mbl_error !== '' ? 'readonly-text' : ''"
							:readonly="item.mbl_num === '' && item.booking_number === '' || item.is_mbl_error !== '' ? true : false"
						></v-text-field>
					</div>
				</template>

				<template v-slot:[`item.actions`]="{ item, index }">
					<div class="k-flex shipment-remove-row">
						<a href="javascript:void(0)" @click.stop="removeShipmentRow(index)">
							<generic-icon
								color="#F93131"
								:iconName="item.is_mbl_error ? 'trash-product' : 'trash-product-light'"
								v-model="item.ReferenceID"
							></generic-icon>
						</a>
					</div>
				</template>

				<template v-slot:body.append>
					<tr>
						<td>
							<a style="display: flex; flex-direction: row; font-weight: bold; margin-left: 5%;" @click="addShipmentRow()">
								<span style="margin-right: 6.3px;">
									<generic-icon
										color="#0171A1"
										iconName="plus"
									></generic-icon>
								</span>
								<span>Add Shipment</span>
							</a>
						</td>
						<td colspan="6"></td>
					</tr>
				</template>

			</v-data-table>
		</div>
		<v-spacer style="border-top: 2px solid #EBF2F5;">
			<div class="footer-btn" style="padding: 20px 25px;">
				<div class="d-flex footer">
					<v-btn class="save-btn btn-blue" text @click.stop="createBulkShipment()" :disabled="createShipmentDisabled || getCreateBulkShipmentLoading || getCheckingMblNumber">
						<span v-if="getCreateBulkShipmentLoading">Shipments Adding...</span>
						<span v-else-if="getCheckingMblNumber">Checking MBL Number...</span>
						<span v-else>Add Shipments</span>
					</v-btn>
					<v-btn class="save-btn btn-white" text style="margin-left: 1%;" :disabled="getCreateBulkShipmentLoading" @click="close()">
						<span>Cancel</span>
					</v-btn>
					<div class="d-flex" style="position: absolute; left: 62%; margin-top: 0.7%;">
						<p style="margin-bottom: 7px;" class="d-flex">
						<generic-icon
							color="#0171A1"
							iconName="info-icon"
							style="margin-top: 2px; margin-right: 6px;"
						></generic-icon>
						If you can not find MBL number, please reach out to us at <a href="mailto: PO@shifl.com" target="_blank" style="underline: none; text-decoration: auto;"> PO@shifl.com</a></p>
					</div>
				</div>
			</div>
		</v-spacer>
	</div>
</template>

<style scoped>
@import "./scss/createMultiShipmentDialogTracking.scss";
</style>
<script>
import GenericIcon from "../../Icons/GenericIcon";
import headers from "./Datas/tableHeaders";
import { mapActions, mapGetters } from "vuex";

export default {
	name: "CreateMultiShipment",
	data: () => ({
		headers: headers.multiShipmentRow.data,
		props: [
			'addShipmentDialogModalView',
			'refNumber',
		],
		rows: [{
            mbl_num: '',
            booking_number: '',
            customer_reference: '',
            total_carton: 0,
            total_volume: 0,
            total_weight: 0,
			is_mbl_error: '',
			date_id: new Date().getTime(),
			trackInfoData: '',
			containerInfo: '',
          },
        ],
		mblTrackFail: false,
		mblTracked: false,
		mblNumInfo: false,
		shipmentExistId: '',
		checkTrackingNumber: false,
		createShipmentDisabled: true,
		shiflShipmentRef: '',
	}),
	components: {
		GenericIcon
	},
	computed: {
		...mapGetters([
			"getCreateBulkShipmentLoading",
		]),
		getCheckingMblNumber() {
			return this.$store.getters["booking/getCheckingMblNumber"];
		},
	},
	methods: {
		...mapActions([
			"createBulkShipments",
		]),
		addShipmentRow() {
			this.rows.push({
				mbl_num: '',
				booking_number: '',
				customer_reference: '',
				total_carton: 0,
				total_volume: 0,
				total_weight: 0,
				is_mbl_error: '',
				date_id: new Date().getTime(),
				trackInfoData: '',
				containerInfo: '',
			});
			this.createShipmentDisabled = true;
		},
		removeShipmentRow(index) {
			this.rows.splice(index, 1);
			this.checkErrorInData('remove_row');
		},
		MblNumberRequiredMessage(item) {
			if(item.mbl_num === '') {
				return 'MBL or Booking Number - at least one is required';
			} else if(item.is_mbl_error) {
				return item.is_mbl_error;
			} else {
				return false;
			}
		},
		checkMBLNumber(item, key) {
			let mbl_num = item.mbl_num;
			let booking_num = item.booking_num;
			if (mbl_num !== "") {
				this.mblTrackFail = false;
				this.mblTracked = false;
				this.rows[key].is_mbl_error = '';
				this.$store
					.dispatch("booking/checkMblNumber", mbl_num)
					.then((r) => {
						if (typeof r.data !== "undefined" && r.status === 200) {
							this.mblNumInfo = r.data;
							this.mblTracked = true;
							this.mblTrackFail = false;
							if (this.mblNumInfo.is_already_exists.is_already_exists == true) {
								if (this.mblNumInfo.is_already_exists.type && this.mblNumInfo.is_already_exists.type == "LCL") {
									this.rows[key].is_mbl_error = 'LCL Shipment is already exist';
								} else if(this.mblNumInfo.is_already_exists.type && this.mblNumInfo.is_already_exists.type == "FCL") {
									this.shipmentExistId = this.mblNumInfo.is_already_exists.shipment_id;
									this.rows[key].is_mbl_error = 'FCL Shipment is already exist';
								} else {
									this.rows[key].is_mbl_error = 'Shipment already exist';
								}
							} else {
								if(this.mblNumInfo.attributes === undefined) {
									this.rows[key].is_mbl_error = 'Please provide a valid MBL number';
								} else {
									this.rows[key].trackInfoData = {
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
									this.rows[key].containerInfo = this.mblNumInfo.containers;
								}
							}
						} else {
							this.mblTrackFail = true;
							this.rows[key].is_mbl_error = 'Please provide a valid MBL number';
						}
						this.checkTrackingNumber = false;
						this.checkErrorInData();
					})
					.catch((e) => {
						console.log("e", e);
						this.mblTrackFail = true;
						this.checkTrackingNumber = false;
						this.rows[key].is_mbl_error = 'Please provide a valid MBL number';
						this.checkErrorInData();
					});
			} else if(booking_num !== '') {
				this.rows[key].is_mbl_error = '';
			} else {
				this.rows[key].is_mbl_error = 'MBL or Booking Number - at least one is required';
			}
		},
		createBulkShipment() {
			let payload = {
				'data': this.rows
			}
			this.createBulkShipments(payload)
				.then((response) => {
					if(response.data && response.data.is_already_exists && response.data.is_already_exists.is_already_exists === false) {
						this.mblTrackFail = true;
					} else {
						this.$emit("close");
						this.shiflShipmentRef = response.data.shipment ? response.data.shipment.shifl_ref : '';
						this.$emit("addBulkShipmentSuccess", response.data.shipments_ref);
						this.$emit("reloadShipments");
					}
				})
				.catch((e) => {
					console.log(e);
				});
		},
		checkErrorInData(type = '') {
			if(this.rows.length > 0) {
				
				let checkError = this.rows.filter(function(currentArr) {
					return currentArr.is_mbl_error !== ''
				})
				let checkBooking = this.rows.filter(function(currentArr) {
					return currentArr.booking_number !== '' && currentArr.mbl_num === ''
				})
				let checkMBL = this.rows.filter(function(currentArr) {
					return currentArr.mbl_num !== ''
				})
				let checkRecord = this.rows.filter(function(currentArr) {
					return currentArr.mbl_num === '' && currentArr.booking_number === ''
				})

				let valueArr = this.rows.map(function(item) { 
					return item.mbl_num 
				});

				let currentRow = this.rows;
				let isDuplicate = valueArr.some(function(item, idx){ 
					if(type === 'remove_row' && currentRow[idx].is_mbl_error === 'MBL number already filled') {
						currentRow[idx].is_mbl_error = '';
					}
					if(valueArr.indexOf(item) != idx && currentRow[idx].mbl_num !== '' && currentRow[idx].is_mbl_error !== 'MBL number already filled') {
						currentRow[idx].is_mbl_error = 'MBL number already filled';
						return valueArr.indexOf(item) != idx 
					}
				});

				if(isDuplicate === true) {
					this.createShipmentDisabled = true;
				}
				if(checkRecord.length > 0) {
					this.createShipmentDisabled = true;
				} 
				if(checkBooking.length > 0) {
					this.createShipmentDisabled = false;
				} 
				if(checkMBL.length > 0) {
					if(checkError.length > 0) {
						this.createShipmentDisabled = true;
					} else {
						this.createShipmentDisabled = false;
					}
				}

			} else {
				this.createShipmentDisabled = true;
			}
		},
		close() {
			this.$emit('close');
		}
	},
};
</script>
