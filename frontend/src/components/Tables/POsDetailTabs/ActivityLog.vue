<!-- @format -->

<template>
	<div class="po-details-wrapper-table history">
		<v-data-table
			:headers="headers"
			:items="poActivityLogItems"
			class="desktop-podetails-product elevation-0"
			v-bind:class="{
				'no-data-table': poActivityLogItems.length === 0,
			}"
			hide-default-footer
			mobile-breakpoint="1023"
			v-if="!isMobile"
			:items-per-page="500"
		>
			<template v-slot:[`item.updated_at`]="{ item }">
				<span>
					{{ item.updated_at !== null ? formatDate(item.updated_at) : "--" }}
				</span>
			</template>

			<template v-slot:[`item.update_type`]="{ item }">
				<span class="text-capitalize">
					{{ item.update_type }}
				</span>
			</template>

			<template v-slot:[`item.action_name`]="{ item }">
				<span class="text-capitalize">
					{{ item.action_name }}
				</span>
			</template>

			<template v-slot:[`item.descriptionData`]="{ item }">
				<span v-html="formatDescriptionData(item, item.update_type)"></span>
			</template>

			<template v-slot:no-data>
				<div class="loading-wrapper pt-5" v-if="getPoActivityLogLoading">
					<v-progress-circular :size="40" color="#0171a1" indeterminate>
					</v-progress-circular>
				</div>

				<div
					class="no-data-wrapper"
					v-if="!getPoActivityLogLoading && poActivityLogItems.length == 0"
				>
					<div class="no-data-heading">
						<p>No data available in activity log</p>
					</div>
				</div>
			</template>
		</v-data-table>
	</div>
</template>

<script>
import { mapGetters } from "vuex";
import inventoryGlobalMethods from "../../../utils/inventoryMethods/inventoryGlobalMethods";

export default {
	name: "ActivityLog",
	props: ["isMobile", "from", "vendorName", "buyerName"],
	data: () => ({
		headers: [
			{
				text: "Updated at",
				align: "start",
				sortable: false,
				value: "updated_at",
				fixed: true,
				width: "18%",
			},
			{
				text: "Updated By",
				align: "start",
				sortable: false,
				value: "update_type",
				fixed: true,
				width: "10%",
			},
			{
				text: "Update Type",
				align: "start",
				sortable: false,
				value: "action_name",
				fixed: true,
				width: "25%",
			},
			{
				text: "Description",
				align: "start",
				sortable: false,
				value: "descriptionData",
				fixed: true,
				width: "47%",
			},
		],
		vendorSign: "",
		buyerSign: "",
	}),
	methods: {
		...inventoryGlobalMethods,
		formatDate(date) {
			return this.formatTimeAndDateTogether(date);
		},
		replaceString(data) {
			if (data !== null) {
				if (data === "so_created") {
					data = "SO_Created";
				} else if (data === "po_created") {
					data = "PO_Created";
				}

				return data.replace(/_+/g, " ");
			}
		},
		formatDescriptionData(item, updateType) {
			let returnVal = "";
			if (item.descriptionData) {
				const excludeFields = ["required_deposit_type", "signature_details"];
				let required_deposit_type = null;
				item.descriptionData.filter(function(row) {
					if (row.field == "required_deposit_type") {
						required_deposit_type = row.new_value;
					}
				});
				item.descriptionData.forEach((element) => {
					if (
						element.field != null &&
						element.new_value != null &&
						excludeFields.includes(element.field)
					) {
						returnVal +=
							(returnVal != "" ? " | " : "") +
							'<span class="text-capitalize">' +
							this.replaceString(element.field) +
							"</span>" +
							": " +
							this.checkAndFormatField(
								element,
								updateType,
								required_deposit_type
							);
					}
				});
			}
			return returnVal;
		},
		checkAndFormatField(item, updateType, required_deposit_type) {
			const dateFields = [
				"cargo_ready_date",
				"must_arrive_by",
				"committed_cargo_ready_date",
			];
			if (dateFields.includes(item.field)) {
				return this.formatDateOnly(item.new_value);
			}
			if (item.field == "signature_details") {
				let signature_details = item?.new_value
					? JSON.parse(item.new_value)
					: "";

				signature_details.map((sign) => {
					if (updateType == "Vendor") {
						this.vendorSign =
							sign.vendor_signature + ", " + sign.vendor_signature_date;
					} else {
						this.buyerSign =
							sign.buyer_signature + ", " + sign.buyer_signature_date;
					}
				});

				return updateType == "Vendor" ? this.vendorSign : this.buyerSign;
			}
			if (item.field == "production_overall_status") {
				return item.productionOverallStatusName;
			}
			if (item.field == "required_deposit_value") {
				return required_deposit_type == "in-percentage"
					? item.new_value + "%"
					: "$" + item.new_value;
			}
			return item.new_value;
		},
	},
	computed: {
		...mapGetters({
			getPoActivityLog: "poDetails/getPoActivityLog",
			getPoActivityLogLoading: "poDetails/getPoActivityLogLoading",
		}),
		poActivityLogItems() {
			let items = [];

			if (
				typeof this.getPoActivityLog !== "undefined" &&
				this.getPoActivityLog !== null
			) {
				if (
					typeof this.getPoActivityLog.data !== "undefined" &&
					this.getPoActivityLog.data !== null
				) {
					items = this.getPoActivityLog.data;
				}
			}

			return items;
		},
	},
	async mounted() {},
	updated() {},
};
</script>

<style lang="scss">
@import "@/assets/scss/pages_scss/po/poHistory.scss";
</style>
