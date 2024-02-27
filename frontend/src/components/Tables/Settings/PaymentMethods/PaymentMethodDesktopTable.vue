<!-- @format -->

<template>
	<div>
		<v-data-table
			:headers="headers"
			:items="items"
			sort-by="calories"
			class="methods-table elevation-1"
			hide-default-footer
			:page.sync="page"
			:items-per-page="itemsPerPage"
			:search="search"
			@page-count="pageCount = $event"
			mobile-breakpoint="769"
			v-if="activeTab == 2"
			:class="items !== null && items.length > 0 ? '' : 'no-data-table'"
		>
			<template v-slot:[`item.card_number`]="{ item }">
				<div class="card-number-wrapper">
					<div class="card-type">
						<img
							:src="getImgUrl(item.card_type)"
							v-bind:alt="item.card_type"
							width="56px"
							height="40px"
						/>
					</div>

					<div>
						<p class="mb-0">
							{{ ccNumberFormat(item.card_number, "************") }}
						</p>
						<p class="name mb-0">
							{{ item.first_name }},
							{{ item.expiration }}
						</p>
					</div>
				</div>
			</template>

			<template v-slot:[`item.default`]="{ item }">
				<div>
					<p class="mb-0">{{ item.default === 1 ? "Default" : "-" }}</p>
				</div>
			</template>

			<template v-slot:[`item.actions`]="{ item }">
				<div class="manage-action-buttons">
					<v-btn class="btn-white icons" @click="editPaymentMethod(item)">
						<img src="../../../../assets/icons/edit-blue.svg" alt="" />
					</v-btn>

					<v-btn class="btn-white icons" @click="deletePaymentMethod(item)">
						<img src="../../../../assets/icons/delete-blue.svg" alt="" />
					</v-btn>
				</div>
			</template>

			<template v-slot:no-data>
				<div class="loading-wrapper mt-4" v-if="getcardsLoading">
					<v-progress-circular :size="40" color="#0171a1" indeterminate>
					</v-progress-circular>
				</div>

				<div
					class="no-data-wrapper pa-6"
					v-if="getcards.length == 0 && !getcardsLoading"
				>
					<div class="no-data-heading">
						<img
							src="../../../../assets/icons/payment-icon.svg"
							width="40px"
							height="42px"
							alt=""
						/>
						<h3>No Payment Method</h3>
						<p>
							You haven’t added any payment method.
							<br />Please add one to pay for the invoices.
						</p>
						<v-btn
							color="primary"
							dark
							class="btn-white payment-btn"
							@click="openPaymentMethodDialog"
						>
							Add Payment Method
						</v-btn>
					</div>
				</div>
			</template>
		</v-data-table>
	</div>
</template>

<script>
import { mapGetters } from "vuex";
import globalMethods from "../../../../utils/globalMethods";

export default {
	name: "PaymentMethodDesktopTable",
	props: ["isMobile"],
	components: {},
	mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "settings/manage-payment-methods");
	},
	data: () => ({
		page: 1,
		pageCount: 0,
		itemsPerPage: 35,
		search: "",
		headers: [
			{
				text: "Method",
				align: "start",
				sortable: false,
				value: "card_number",
				width: "40%",
				fixed: true,
			},
			{
				text: "Date Added",
				align: "start",
				sortable: false,
				value: "date_added",
				width: "20%",
				fixed: true,
			},
			{
				text: "Last Used",
				align: "start",
				sortable: false,
				value: "last_used",
				width: "20%",
				fixed: true,
			},
			{
				text: "Type",
				align: "start",
				sortable: false,
				value: "default",
				width: "5%",
				fixed: true,
			},
			{
				text: "",
				align: "end",
				sortable: false,
				value: "actions",
				width: "15%",
				fixed: true,
			},
		],
		activeTab: 2,
		cardDetail: "",
	}),
	computed: {
		...mapGetters({
			getcardsLoading: "getcardsLoading",
			getcards: "getcards",
		}),
		paymentMethods() {
			let data = this.getcards.filter(
				(payment) => payment.tab === this.tabs[this.activeTab]
			);
			return data;
		},
		items() {
			if (!(Array.isArray(this.getcards) && this.getcards.length >= 1)) {
				return [];
			}

			return this.getcards.map((item) => {
				return {
					card_id: item.id,
					card_number: item.number_masked,
					first_name: item.first_name,
					last_name: item.last_name,
					expiration: item.expiration_month + "/" + item.expiration_year,
					cvc: "",
					coutry: "",
					date_added: item.created_at,
					last_used: item.updated_at,
					default: item.is_default,
					card_type: item.type.toLowerCase(),
					tab: "Payment Methods",
				};
			});
		},
	},
	watch: {},
	methods: {
		...globalMethods,
		getCurrentTab(id) {
			console.log(id);
		},
		onTabChange() {
			this.page = 1;
		},
		openPaymentMethodDialog() {
			this.$emit("openPaymentMethodDialog");
		},
		editPaymentMethod(payment) {
			this.$emit("editPaymentMethod", payment);
		},
		close() {
			this.$emit("close");
		},
		deletePaymentMethod(item) {
			this.$emit("deletePaymentMethod", item);
		},
		closeDelete() {
			this.$emit("closeDelete");
		},
		save() {
			if (this.editedIndex > -1) {
				Object.assign(this.desserts[this.editedIndex], this.editedItem);
			} else {
				this.desserts.push(this.editedItem);
			}
			this.close();
		},
		handleManagePayment() {
			this.$router.push(`billing/manage-payment-methods`);
		},
		managePayment(item) {
			console.log(item);
		},
		getImgUrl(pic) {
			if (pic !== "undefined" && pic !== null) {
				return require(`../../../../assets/icons/${pic}.svg`);
			} else {
				return require("../../../../assets/icons/default-product-icon.svg");
			}
		},
	},
};
</script>

<style lang="scss">
@import "../../../../assets/scss/pages_scss/settings/managePayment.scss";
@import "../../../../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../../../../assets/scss/buttons.scss";
</style>
